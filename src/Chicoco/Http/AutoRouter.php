<?php

namespace Chicoco\Http;

use Chicoco\Core\Interfaces\Handler;
use Chicoco\Core\Interfaces\Request;
use Chicoco\Http\Exceptions\NotFoundException;
use Chicoco\Http\Exceptions\NotImplementedException;
use Chicoco\Http\Exceptions\InternalErrorException;
use Chicoco\Http\Exceptions\BadRequestException;

class AutoRouter implements Handler
{
    protected $request;
    protected $controller;
    protected $action;
    protected $path;
    protected $classes;

    public function __construct(Request $r)
    {
        $this->request = $r;
        $this->controller = 'Index';
        $this->action = 'Index';
        $this->path = $r->path;

        $pathParts = explode('/', $r->path);

        if (!empty($pathParts[1])) {
            $this->controller = $pathParts[1];
        }

        if (!empty($pathParts[2])) {
            $this->action = $pathParts[2];
        }
    }

    public function register(array $classes)
    {
        $this->classes = $classes;
    }

    public function execute()
    {
        try {
            if (empty($this->classes[$this->controller])) {
                throw new NotFoundException($this->path);
            }

            $className = $this->classes[$this->controller];
            $action = $this->action;

            // Instance the registered class
            $c = new $className($this->request);

            // method not exists: NotImplementedException
            if (!method_exists($c, $action)) {
                throw new NotFoundException($this->path);
            }

            $c->{$action}();

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
