<?php

namespace Chicoco;

class Controller
{
    protected $controller;
    protected $action;
    protected $defaultLayout = 'default';
    protected $pathParams = array();
    protected $scripts;
    protected $log;

    private $data = array();

    public function __construct()
    {
        $this->log = new Log();
    }

    public function __call($name, $params)
    {
        return false;
    }

    public function init()
    {
    }

    public function setController($controller)
    {
        $this->controller = $controller;
        return $this;
    }

    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    public function setPathParams(array $params)
    {
        $this->pathParams = $params;
        return $this;
    }

    public function getPathParams($name = '', $type = 'string')
    {
        if ($name == '') {
            return $this->pathParams;
        } else {
            if (array_key_exists($name, $this->pathParams)) {
                return $this->sanitizeVar($this->pathParams[$name], $type);
            } else {
                return null;
            }
        }
    }

    public function render($layout = '', $fileName = '')
    {

        if ($layout != '' && is_file('layout/'.$layout.'.phtml')) {
            $this->defaultLayout = $layout;
        }

        $viewDir = preg_replace('/(.*)Controller$/', '${1}', $this->controller);

        if ($fileName != '') {
            if (!is_file('view/'.$viewDir.'/'.$fileName.'.phtml')) {
                $fileName = $this->action;
            }
        } else {
            $fileName = $this->action;
        }

        // Put the variables visible to the included file
        if (is_array($this->data)) {
            foreach ($this->data as $k => $v) {
                ${$k}  = $v;
            }
        }

        ob_start();
        include('view/'.$viewDir.'/'.$fileName.'.phtml');
        $content = ob_get_contents();
        ob_end_clean();

        include('layout/'.$this->defaultLayout.'.phtml');
        exit();
    }

    public function redirect($path = '')
    {
        if ($path != '') {
            header('Location: '.$path);
            exit();
        }
    }

    public function setViewVar($name, $value)
    {
        if ($name != '') {
            $this->data[$name] = $value;
        }
    }

    public function setViewScript($script)
    {
        if ($script != '') {
            $this->scripts[] = $script;
            $this->setViewVar('viewScripts', $this->scripts);
        }
    }

    public function logInfo($message = '')
    {
        return $this->log->info($message, $this->controller, $this->action);
    }

    public function logWarning($message = '')
    {
        return $this->log->warning($message, $this->controller, $this->action);
    }

    public function logError($message = '')
    {
        return $this->log->error($message, $this->controller, $this->action);
    }

    public function getUrlVar($name = '', $type = '')
    {
        return $this->getVar($name, $type, 'get');
    }

    public function getPostVar($name = '', $type = '')
    {
        return $this->getVar($name, $type, 'post');
    }

    public function getRequestVar($name = '', $type = '')
    {
        return $this->getVar($name, $type, 'request');
    }

    public function getFileVar($name = '')
    {
        return $this->getVar($name, '', 'file');
    }

    private function getVar($name = '', $type = '', $from = 'get')
    {
        switch ($from) {
            case 'get':
                if (isset($_GET[$name])) {
                    return $this->sanitizeVar($_GET[$name], $type);
                }
                break;
            case 'post':
                if (isset($_POST[$name])) {
                    return $this->sanitizeVar($_POST[$name], $type);
                }
                break;
            case 'request':
                if (isset($_REQUEST[$name])) {
                    return $this->sanitizeVar($_REQUEST[$name], $type);
                }
                break;
            case 'file':
                if (isset($_FILES[$name])) {
                    return $_FILES[$name];
                }
                break;
            default:
                return null;
                break;
        }
        return null;
    }

    private function sanitizeVar($var = null, $type = "")
    {
        $filters = array(
            "string" => FILTER_SANITIZE_STRING,
            "email"  => FILTER_SANITIZE_EMAIL,
            "float"  => FILTER_SANITIZE_NUMBER_FLOAT,
            "int"    => FILTER_SANITIZE_NUMBER_INT,
            "url"    => FILTER_SANITIZE_URL
        );

        if (isset($filters[$type])) {
            return filter_var($var, $filters[$type]);
        } else {
            return $var;
        }
    }

    protected function dumpVar($var)
    {
        $s = '<pre>'
        .var_export($var, true)
        .'</pre>';
        return $s;
    }
}
