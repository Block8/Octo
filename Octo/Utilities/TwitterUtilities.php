<?php
namespace Octo\Utilities;

use Twitter;
use Octo\System\Model\Setting;
use Octo\Twitter\Model\Tweet;
use Octo\Store;

class TwitterUtilities
{

    /**
     * Get the stream of tweets for the search term and cache to the database
     */
    public static function getStream()
    {
        $tweetStore = Store::get('Tweet');

        $consumerKey = Setting::get('twitter', 'consumer_key');
        $consumerSecret = Setting::get('twitter', 'consumer_secret');
        $accessToken = Setting::get('twitter', 'access_token');
        $accessTokenSecret = Setting::get('twitter', 'access_token_secret');
        $streamSearch = Setting::get('twitter', 'stream_search');

        $twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

        if (self::canCallAPI()) {
            self::updateLastAPICall();

            $stream = $twitter->search($streamSearch);

            foreach ($stream as $s) {
                if (!$tweetStore->getByTwitterIdForScope($s->id, 'stream')) {
                    $t = new Tweet;
                    $t->setTwitterId($s->id);
                    $t->setText($s->text);
                    $t->setHtmlText(Twitter::clickable($s));
                    $t->setScreenname($s->user->screen_name);
                    $t->setPosted($s->created_at);
                    $t->setCreatedDate(new \DateTime());
                    $t->setScope('stream');
                    $tweetStore->insert($t);
                }
            }
        }
    }

    /**
     * Get tweets for the currently authenticated twitter user
     */
    public static function getUser()
    {
        $tweetStore = Store::get('Tweet');

        $consumerKey = Setting::get('twitter', 'consumer_key');
        $consumerSecret = Setting::get('twitter', 'consumer_secret');
        $accessToken = Setting::get('twitter', 'access_token');
        $accessTokenSecret = Setting::get('twitter', 'access_token_secret');

        $twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

        if (self::canCallAPI()) {
            self::updateLastAPICall();

            $statuses = $twitter->load(Twitter::ME);
            foreach ($statuses as $s) {
                if (!$tweetStore->getByTwitterIdForScope($s->id, 'user')) {
                    $t = new Tweet;
                    $t->setTwitterId($s->id);
                    $t->setText($s->text);
                    $t->setHtmlText(Twitter::clickable($s));
                    $t->setScreenname($s->user->screen_name);
                    $t->setPosted($s->created_at);
                    $t->setCreatedDate(new \DateTime());
                    $t->setScope('user');
                    $tweetStore->saveByInsert($t);
                }
            }
        }
    }

    /**
     * Update the last Twitter API call to now
     */
    public static function updateLastAPICall()
    {
        $now = new \DateTime();
        Setting::set('twitter', 'last_api_call', $now->format('Y-m-d H:i:s'));
    }

    /**
     * Checks whether the API can be called
     *
     * Returns false if the API was called less than a minute ago (which gets over 15 requests in
     * 15 minute on Twitter API rate limiting)
     *
     * @return bool
     */
    public static function canCallAPI()
    {
        $lastCall = Setting::get('twitter', 'last_api_call');

        if ($lastCall == null) {
            return true;
        } else {
            $lastCall = new \DateTime($lastCall);
        }

        $now = new \DateTime();
        $interval = $lastCall->diff($now);

        // Are we at least one minute ago?
        if ($interval->i >= 1) {
            return true;
        } else {
            return false;
        }
    }
}
