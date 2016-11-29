<?php

namespace Octo\Http;

use b8\Http\Response as BaseResponse;
use Octo\Event;

class Response extends BaseResponse
{
    public function header($key, $value)
    {
        $this->setHeader($key, $value);
        return $this;
    }

    public function headers(array $headers)
    {
        foreach ($headers as $key => $value) {
            $this->header($key, $value);
        }

        return $this;
    }

    public function success($message)
    {
        $_SESSION['GlobalMessage']['success'] = $message;
    }

    public function error($message)
    {
        $_SESSION['GlobalMessage']['error'] = $message;
    }

    public function info($message)
    {
        $_SESSION['GlobalMessage']['info'] = $message;
    }

    public function type($contentType)
    {
        return $this->header('Content-Type', $contentType);
    }

    public function download($name, $content)
    {
        $this->header('Content-Disposition', 'attachment; filename="' . $name . '"');
        $this->setContent($content);
        return $this;
    }

    public function flush()
    {
        Event::trigger('Response.Flush', $this);
        return parent::flush();
    }
}