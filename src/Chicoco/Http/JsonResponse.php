<?php

namespace Chicoco\Http;

class JsonResponse extends Response
{
    public function __construct(Array $content, int $statusCode = 200, Array $headers = [])
    {
        $jsonContent = json_encode($content);

        $headers['Content-Type'] = 'application/json'; 

        parent::__construct($jsonContent, $statusCode, $headers);
    }
}
