<?php

namespace Chicoco;

class Application
{
    private static $instance;

    private $controller = '';
    private $action = '';
    private $uriParts = '';
    private $path ='';
    private $config;
    private $pathParams = array();
    private $alias = '';

    public function __construct()
    {
        try {
            $this->loadConfig();

            $this->uriParts = parse_url($_SERVER['REQUEST_URI']);
            $this->path = explode('/', $this->uriParts['path']);

            if (isset($this->path[1]) && $this->path[1] != '') {
                $this->controller = $this->path[1];
            } else {
                $this->controller = 'Index';
            }

            if (isset($this->path[2]) && $this->path[2] != '') {
                $this->action = $this->path[2];
            } else {
                $this->action = 'Index';
            }
        } catch (\Exception $e) {
            header("HTTP/1.0 500 Error found");
            include('layout/500.phtml');
            exit();
        }
    }

    public static function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function checkAlias()
    {
        if (isset($this->config['Aliases'])) {
            $aliases = $this->config['Aliases'];
            $key = $this->uriParts['path'];

            foreach ($aliases as $k => $v) {
                $patern = '$^'.$k.'$';
                $r = preg_match($patern, $key);
                if ($r === 1) {
                    $this->alias = $k;
                    list($this->controller, $this->action) = explode("/", $v);
                    $this->addToGlobal('ALIAS', $k);
                    break;
                }
            }
        }
    }

    private function parcePathParams()
    {
        $path = $this->uriParts['path'];

        if ($this->alias != '') {
            $path = substr_replace($path, '', 0, strlen($this->alias));
        } else {
            $path = substr_replace($path, '', 0, strlen('/'.$this->controller.'/'.$this->action));
        }

        $path = preg_replace('+^/+', '', $path);
        $pathParts = explode('/', $path);
        $pathParams = array();

        if (count($pathParts) >= 1) {
            for ($i = 0; $i < count($pathParts); $i++) {
                if ($pathParts[$i] == '') {
                    continue;
                }

                if (isset($pathParts[$i + 1])) {
                    $pathParams[$pathParts[$i]] = $pathParts[$i+1];
                    $i++;
                } else {
                    $pathParams[$pathParts[$i]] = null;
                }
            }
        }
        $this->pathParams = $pathParams;
    }

    public function run()
    {
        try {
            $this->checkAlias();

            $controller = $this->controller.'Controller';
            $c = new $controller;

            if (!($c instanceof Controller)) {
                throw new \Exception('Unable to load class '.$this->controller);
            }

            $c->setController($this->controller);
            $c->setAction($this->action);

            $this->parcePathParams();

            $c->setPathParams($this->pathParams);
            $c->init();

            if (!method_exists($c, $this->action.'Action')) {
                throw new \Exception('Unable to execute the action "'.$this->action.'"');
            }

            $this->addToGlobal('CONTROLLER', $this->controller);
            $this->addToGlobal('ACTION', $this->action);
            $this->addToGlobal('PATH_PARAMS', $this->pathParams);

            $c->{$this->action.'Action'}();
        } catch (\Exception $e) {
            header("HTTP/1.0 500 Error found");
            include('layout/500.phtml');
            return false;
        }
    }

    private function loadConfig()
    {
        $this->config = array();
        if (!($config = @parse_ini_file('conf/Application.ini', true))) {
            throw new \Exception("unable to load configuration file");
        }

        if (is_array($config)) {
            // Get the general conf
            if (is_array($config['General'])) {
                foreach ($config['General'] as $k => $v) {
                    $this->config[$k] = $v;
                }
            }

            // Get the conf for the current env
            if (isset($this->config['applicaction.env']) && is_array($config[$this->config['applicaction.env']])) {
                foreach ($config[$this->config['applicaction.env']] as $k => $v) {
                    $this->config[$k] = $v;
                }
            }

            // Get the common conf
            if (isset($config['Common']) && is_array($config['Common'])) {
                foreach ($config['Common'] as $k => $v) {
                    $this->config[$k] = $v;
                }
            }

            // Get the aliases conf
            if (isset($config['Aliases']) && is_array($config['Aliases'])) {
                $this->config['Aliases'] = array();
                foreach ($config['Aliases'] as $k => $v) {
                    $this->config['Aliases'][$k] = $v;
                }
            }
            $this->addToGlobal('CONF', $this->config);
        }
    }

    public function getConfig()
    {
        return $this->config;
    }

    private function addToGlobal($key, $value)
    {
        global $_CHICOCO;

        if (!isset($_CHICOCO)) {
            $_CHICOCO = array();
        }

        $_CHICOCO[$key] = $value;
    }
}
