<?php

namespace Chicoco\Http;

use Chicoco\Core\Interfaces\Handler;
use Chicoco\Core\Interfaces\Request;
use Chicoco\Http\Exceptions\HttpException;
use Chicoco\Http\Exceptions\NotFoundException;
use Chicoco\Http\Exceptions\NotImplementedException;
use Chicoco\Http\Exceptions\InternalErrorException;
use Chicoco\Http\Exceptions\BadRequestException;

class Router implements Handler
{
    private $request;
    private $actions;

    public function __construct(Request $r)
    {
        $this->request = $r;
        $this->actions = [];
    }

    public function get($path, $action)
    {
        $this->add('get', $path, $action);
    }

    public function post($path, $action)
    {
        $this->add('post', $path, $action);
    }

    public function delete($path, $action)
    {
        $this->add('delete', $path, $action);
    }

    public function put($path, $action)
    {
        $this->add('put', $path, $action);
    }

    public function add($method, $path, $action)
    {
        $method = strtoupper($method);
        $this->actions[$method][$path] = $action;
    }

    public function execute()
    {
        $path = $this->request->path;
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

        } catch (BadRequestException $e) {
            $response = new Response($e->getMessage(), 400);
        } catch (NotFoundException $e) {
            $response = new Response($e->getMessage(), 404);
        } catch (NotImplementedException $e) {
            $response = new Response($e->getMessage(), 501);
        } catch (InternalErrorException | HttpException $e) {
            $response = new Response($e->getMessage(), 500);
        }

        $response->send();
    }
}
