<?php

namespace Chicoco\Http;

class ApiClient
{
    protected $baseUrl;
    protected $ch;

    public function __construct()
    {
        $this->ch = curl_init();

        if (empty($this->ch)) {
            return false;
        }

        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
    }

    public function setUrl(string $url)
    {
        if (isset($this->ch)) {
            curl_setopt($this->ch, CURLOPT_URL, $url);
            return true;
        }
        return false;
    }

    public function post($data)
    {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);

        $r = $this->exec();
        return new ApiResponse($this->ch, $r);
    }

    public function put($data)
    {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);

        $r = $this->exec();
        return new ApiResponse($this->ch, $r);
    }

    public function get($data = null)
    {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'GET');

        if ($data) {
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        }

        $r = $this->exec();

        return new ApiResponse($this->ch, $r);
    }

    public function del()
    {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

        $r =  $this->exec();

        return new ApiResponse($this->ch, $r);
    }

    public function setHeader(array $headers)
    {
        return curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
    }

    protected function exec()
    {
        $obj = curl_exec($this->ch);

        if (!$obj) {
            return null;
        }

        return $obj;
    }
}
