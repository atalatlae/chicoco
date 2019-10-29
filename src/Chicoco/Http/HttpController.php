<?php

namespace Chicoco\Http;

use Chicoco\Core\Interfaces\Request;

class HttpController
{
    protected $action;
    protected $log;

    private $data = array();

    public function __construct(Request $req = null, Log $log = null)
    {
        $this->action = 'index';
        $this->request = $req;
        $this->log = $log;

        $this->init();
    }

    protected function init()
    {
    }

    public function run()
    {
        $this->{$this->action}();
    }

    protected function index()
    {
        echo "Index Action";
    }
}