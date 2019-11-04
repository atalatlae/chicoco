<?php

namespace Chicoco\Http;

use Chicoco\Http\Exceptions\InternalErrorException;

class HtmlResponse extends Response
{
    protected $data;
    protected $template;
    protected $layout;

    public function __construct($template, $layout = '', int $statusCode = 200, array $headers = [])
    {
        $this->template = $template;
        $this->layout = $layout;
        $this->data = [];

        parent::__construct('', $statusCode, $headers);
    }

    public function setVar($name, $value)
    {
        if ($name != '') {
            $this->data[$name] = $value;
        }
    }

    public function addScript(string $script)
    {
        $this->scripts[] = $script;
    }

    public function getScripts()
    {
        return $this->scripts;
    }

    public function addStyle(string $style)
    {
        $this->styles[] = $style;
    }

    public function getStyles()
    {
        return $this->styles;
    }

    private function render()
    {
        if ($this->template == '') {
            throw new InternalErrorException('No template present');
        }

        foreach ($this->data as $k => $v) {
            ${$k}  = $v;
        }

        ob_start();
        include($this->template);
        $content = ob_get_contents();
        ob_end_clean();

        $this->content = $content;

        if ($this->layout) {
            ob_start();
            include($this->layout);
            $layoutContent = ob_get_contents();
            ob_end_clean();

            $this->content = $layoutContent;
        }
    }

    public function sendContent()
    {
        $this->render();
        echo $this->content;
    }
}
