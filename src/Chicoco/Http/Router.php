<?php

namespace Chicoco\Http;

use Chicoco\Core\Interfaces\Handler;
use Chicoco\Core\Interfaces\Request;
use Chicoco\Core\Interfaces\Controller;
use Chicoco\Http\Exceptions\NotFoundException;
use Chicoco\Http\Exceptions\NotImplementedException;
use Chicoco\Http\Exceptions\InternalErrorException;
use Chicoco\Http\Exceptions\BadRequestException;

class Router implements Handler
{
    private $request;
    private $actions;
    private $basePath;

    public function __construct(Request $r, $basePath = '') {
        $this->request = $r;
        $this->actions = [];
        $this->basePath = $basePath;
    }

    public function get($path, $action) {
        $this->add('get', $path, $action);
    }

    public function post($path, $action) {
        $this->add('post', $path, $action);
    }

    public function delete($path, $action) {
        $this->add('delete', $path, $action);
    }

    public function put($path, $action) {
        $this->add('put', $path, $action);
    }

    public function add($method, $path, $action) {
        $method = strtoupper($method);
        $this->actions[$method][$path] = $action;
    }

    public function execute()
    {
        $path = str_replace($this->basePath, '/', $this->request->getPath());
        $method = $this->request->getMethod();

        try {
            if (!isset($this->actions[$method])) {
                throw new NotImplementedException('');
            }

            if (!isset($this->actions[$method][$path])) {
                throw new NotFoundException($path);
            }

            $action = $this->actions[$method][$path];

            call_user_func($action, $this->request);
            return;
        }
        catch (BadRequestException $e) {
            $message = $e->getMessage();
            header('HTTP/1.0 400 '.$message);
        }
        catch (NotFoundException $e) {
            $message = $e->getMessage();
            header('HTTP/1.0 404 '.$message);
        }
        catch (NotImplementedException $e) {
            $message = $e->getMessage();
            header('HTTP/1.0 501 '.$message);
        }
        catch (InternalErrorException $e) {
            $message = $e->getMessage();
            header('HTTP/1.0 500 '.$message);
        }

        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error', 'message' => $message]
        );
    }
}

