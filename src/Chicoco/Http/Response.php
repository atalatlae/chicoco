<?php

namespace Chicoco\Http;

class Response
{
    protected $content;
    protected $statusCode;
    protected $statusText;
    protected $headers;
    protected $version;

    protected $codeNames = [
        200 => 'OK',
        301 => 'Moved Permanently',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
    ];

    public function __construct($content = '', int $statusCode = 200, array $headers = [])
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->version = $_SERVER['SERVER_PROTOCOL'];

        $this->statusText = ($this->codeNames[$statusCode]) ?? 'unknown';
    }

    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();
    }

    protected function sendHeaders()
    {
        header($this->version . ' ' . $this->statusCode . ' ' . $this->statusText, true, $this->statusCode);

        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value, true, $this->statusCode);
        }
    }

    protected function sendContent()
    {
        echo $this->content;
    }
}
