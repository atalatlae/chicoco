<?php

namespace Chicoco\Http;

class ApiResponse
{
    public $status;
    public $content;
    public $responseCode;
    public $contentType;

    public function __construct($ch, $content)
    {
        $this->status = 'error';

        if ($content == null) {
            $this->content = curl_error($ch);
        }

        $this->content = $content;
        $this->responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $this->contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $this->setStatus();
    }

    public function setStatus() {
        $base = (int)($this->responseCode / 100)*100;

        switch ($base) {
            case 100:
                $status = 'informational';
                break;
            case 200:
                $status = 'success';
                break;
            case 300:
                $status = 'redirection';
                break;
            case 400:
                $status = 'client error';
                break;
            case 500:
                $status = 'server error';
                break;
            default:
                $status = 'unknown';
                break;
        }

        $this->status = $status;
    }
}
