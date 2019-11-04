<?php

namespace Chicoco\Http;

class JsonResponse extends Response
{
    public function __construct(array $content, int $statusCode = 200, array $headers = [])
    {
        $jsonContent = json_encode($content);

        $headers['Content-Type'] = 'application/json';

        parent::__construct($jsonContent, $statusCode, $headers);
    }
}
