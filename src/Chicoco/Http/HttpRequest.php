<?php

namespace Chicoco\Http;;

use Chicoco\Core\Interfaces\Request;
use Chicoco\Core\Sanitizer;

class HttpRequest implements Request
{
    private $parameters = [];
    private $method;
    private $pathParams = [];
    private $ip;

    public function __construct() {
        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);
        $this->ip = null;
        $this->path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->contentType = $_SERVER['CONTENT_TYPE']??null;

        switch ($this->method) {
            case 'POST':
                $this->parameters = $_POST;
                break;
            case 'GET':
                $this->parameters = $_GET;
                break;
            case 'DELETE':
                parse_str(file_get_contents("php://input"), $_DELETE);
                $this->parameters = $_DELETE;
                break;
            default:
                $this->parameters = $_REQUEST;
                break;
        }

        $this->getRemoteIP();
    }

    public function getMethod() {
        return $this->method;
    }


    public function getPath() {
        return $this->path;
    }

    public function getParam($name, $type = 'string', $default = null) {
        if (!empty($this->parameters[$name])) {
            return Sanitizer::clear($this->parameters[$name], $type);
        }
        if (!empty($this->pathParams[$name])) {
            return Sanitizer::clear($this->pathParams[$name], $type);
        }

        return $default;
    }

    public function getParamAsObject(Object $object) {
        $has = get_object_vars($object);

        $validators = [];
        $className = get_class($object);

        if (defined($className."::dtoValidators")) {
            $validators = $object::dtoValidators;
        }

        foreach ($has as $attr => $oldValue) {
            $validator = $validators[$attr] ?? 'string';

            switch ($validator) {
                case 'date':
                    $param = $this->getParam($attr);
                    $object->$attr = NULL;

                    if (!empty($param)) {
                        $object->$attr = strftime('%Y-%m-%d %H:%M', strtotime($param));
                    }
                    break;
                default:
                    $object->$attr = $this->getParam($attr, $validator);
            }
        }
    }

    public function is($method = null)  {
        $method = strtoupper($method);
        return ($this->method == $method);
    }

    public function getIP() {
        return $this->ip;
    }

    private function getRemoteIP() {
        $keys = [
            'HTTP_CF_CONNECTING_IP',
            'HTTP_X_FORWARDED_FOR',
            'REMOTE_ADDR'
        ];

        foreach ($keys as $k) {
            if (getenv($k)) {
                $this->ip =  getenv($k);
                return;
            }
        }
    }

    public function __call($name, $args)
    {
        $type = 'string';
        if (isset($args[0])) {
            $type = $args[0];
        }

        return $this->getParam($name, $type);
    }

    public function __get($name) {
        return $this->getParam($name);
    }
}
