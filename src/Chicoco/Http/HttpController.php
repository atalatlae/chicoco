<?php

namespace Chicoco\Http;

use Chicoco\Core\Interfaces\Request;
use Chicoco\Core\Log;

class HttpController
{
    protected $action;
    protected $log;

    private $data = array();

    public function __construct(Request $req = null, Log $log = null)
    {
        $this->action = 'index';
        $this->request = $req;
        $this->log = $log ?? new Log();

        $this->init();
    }

    protected function init()
    {
    }

    public function run()
    {
        $this->{$this->action}();
    }

    public function index()
    {
        echo "Index Action";
        $this->log->info('Info: Index Action');
        $this->log->warning('Warn: Index Action');
        $this->log->error('Err: Index Action');
    }
}
