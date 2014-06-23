<?php

namespace Octo\Spider\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Octo\Store;
use b8\Config;
use Octo\Spider\Model\SpiderDeadLink;

/**
 * Crawl the site and find dead links
 *
 * Class ScanDeadLinks
 *
 */

class ScanDeadLinks extends Command
{
    protected $scanQueue = [];
    protected $deadLinks = [];
    protected $pageStore;
    protected $deadLinkStore;
    protected $oldDeadLinks;
    protected $siteRoot;
    protected $stdOut;
    protected $maximumDepth = 5;
    protected $blacklist = [];

    protected function configure()
    {
        $this
            ->setName('spider:crawl')
            ->setDescription('Crawl the site and identify dead links within its pages');
    }

    /* command entrypoint */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->siteRoot = Config::getInstance()->get('site.url')."/";
        $this->blacklist = Config::getInstance()->get('site.spider.blacklist', []);
        $this->stdOut = $output;
        $this->pageStore = Store::get('Page');
        $this->deadLinkStore = Store::get('SpiderDeadLink');

        // Recursively add pages to the scan queue starting from the homepage.
        $parent = $this->pageStore->getHomepage();
        $this->pushPages($parent);

        foreach ($this->scanQueue as $uri) {
            $this->crawlPage($uri, $uri, 0);
        }

        $this->oldDeadLinks = $this->deadLinkStore->getAll();
        $newDeadLinks = array();

        foreach ($this->deadLinks as $deadLink) {
            $newDeadLinks[] = $this->renewModel($deadLink);
        }

        $this->deadLinkStore->truncate();
        foreach ($newDeadLinks as $newDeadLink) {
            $this->deadLinkStore->save($newDeadLink, true);
        }

        return 0;
    }

    /* returns a new deadlink model, or an updated one should it already exist */
    protected function renewModel($deadLink)
    {
        foreach ($this->oldDeadLinks as $oldDeadLink) {
            if ($oldDeadLink->url == $deadLink['url']) {
                $newDeadLink = new SpiderDeadLink();
                $newDeadLink->url = $deadLink['url'];
                $newDeadLink->parent_url = $deadLink['parent_url'];
                $newDeadLink->http_response_code = $deadLink['http_response_code'];
                $newDeadLink->first_scan_epoch = $oldDeadLink->first_scan_epoch;
                $newDeadLink->last_scan_epoch = time();

                return $newDeadLink;
            }
        }
        $newDeadLink = new SpiderDeadLink();
        $newDeadLink->url = $deadLink['url'];
        $newDeadLink->parent_url = $deadLink['parent_url'];
        $newDeadLink->http_response_code = $deadLink['http_response_code'];
        $newDeadLink->first_scan_epoch = time();
        $newDeadLink->last_scan_epoch = time();

        return $newDeadLink;
    }

    /* seeds the scan queue with Octo CMS content */
    protected function pushPages($page)
    {
        if ($page->hasChildren()) {
            $pageChildren = $this->pageStore->getByParentId($page->id, ['order' => [['position', 'ASC']]]);
            foreach ($pageChildren as $child) {
                $this->scanQueue[] = $this->rel2abs($child->uri, $this->siteRoot);
                $this->pushPages($child);
            }
        }
    }

    /* crawls a URL, adds new links to the scan queue, records any dead links. */
    protected function crawlPage($baseUrl, $parentUrl, $depth)
    {
        try {
            $html = $this->getData($baseUrl);
            if (parse_url($baseUrl, PHP_URL_HOST) == parse_url($this->siteRoot, PHP_URL_HOST)) {
                $doc = new \DOMDocument();
                libxml_use_internal_errors(true);
                @$doc->loadHTML($html);
                $ahref = $doc->getElementsByTagName('a');
                if ($depth < $this->maximumDepth) {
                    foreach ($ahref as $link) {
                        $href = (string)$link->getAttribute('href');

                        if (substr($href, 0, 7) == 'mailto:') {
                            continue;
                        }

                        if (substr($href, 0, 4) == 'tel:') {
                            continue;
                        }

                        if (substr($href, 0, 4) == 'sms:') {
                            continue;
                        }

                        $linkUrl = $this->rel2abs($href, $baseUrl);

                        // Check the resulting link against our URL blacklist:
                        $badLink = false;
                        foreach ($this->blacklist as $blockedUrl) {
                            if (substr($linkUrl, 0, strlen($blockedUrl)) == $blockedUrl) {
                                $badLink = true;
                            }
                        }

                        if ($badLink) {
                            $this->deadLinks[] = [
                                'url' => $linkUrl,
                                'parent_url' => $baseUrl,
                                'http_response_code' => 9001,
                            ];

                            continue;
                        }

                        if (!in_array($linkUrl, $this->scanQueue)) {
                            $this->scanQueue[] = $linkUrl;
                            $this->crawlPage($linkUrl, $baseUrl, ($depth + 1));
                        }
                    }
                } else {
                    $this->stdOut->writeln("<error>Maximum crawl depth reached</error>");
                }
                $this->stdOut->writeln("PASS: ".$baseUrl." linked from ".$parentUrl);
            }
        } catch (\Exception $ex) {
            $this->stdOut->writeln("<error>".$baseUrl." (".$ex->getCode().") linked from ".$parentUrl."</error>");
            $this->deadLinks[] = array('url'=>$baseUrl, 'parent_url'=>$parentUrl, 'http_response_code'=>$ex->getCode());
        }
    }

    /* gets the data from a URL */
    protected function getData($url)
    {
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curlHandle, CURLOPT_CONNECTTIMEOUT, 10);
        $data = curl_exec($curlHandle);
        $httpCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
        curl_close($curlHandle);

        if ($httpCode >= 400 || $httpCode == 0) {
            throw new \Exception("HTTP Error", $httpCode);
        }

        return $data;
    }

    /* convert relative links found on a page to absolute urls we can crawl */
    protected function rel2abs($rel, $base)
    {
        if (parse_url($rel, PHP_URL_SCHEME) != '') {
            return strtok($rel, '#');
        }

        if (!isset($rel[0])) {
            return $this->siteRoot;
        }

        if ($rel[0]=='#' || $rel[0]=='?') {
            return strtok($base.$rel, '#');
        }

        $url = parse_url($base);
        $path = preg_replace('#/[^/]*$#', '', $url['path']);

        if ($rel[0] == '/') {
            $path = '';
        }

        if (parse_url($base, PHP_URL_PORT) != '') {
            $abs = $url['host'] . ':' .parse_url($base, PHP_URL_PORT)."$path/$rel";
        } else {
            $abs = $url['host'] . "$path/$rel";
        }

        $re = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
        for ($n=1; $n>0; $abs=preg_replace($re, '/', $abs, -1, $n)) {
        }

        if ($url['scheme'] == 'http' || $url['scheme'] == 'https') {
            return strtok($url['scheme'].'://'.$abs, '#');
        } else {
            return $this->siteRoot;
        }
    }
}
