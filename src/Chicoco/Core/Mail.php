<?php

namespace Chicoco\Core;

class Mail
{
    private $from;
    private $to;
    private $subject;
    private $content;
    private $data;
    private $layout;
    private $message;

    public function __construct($from, $to, $subject, $content, array $data, $layout = 'mail')
    {
        $this->from = $from;
        $this->to = $to;
        $this->subject = $subject;
        $this->content = $content;
        $this->data = $data;
        $this->layout = $layout;

        $this->render();
    }

    public function sendHtml()
    {
        $headers = "From: " . $this->from . "\r\n"
        . "MIME-Version: 1.0\r\n"
        . "Content-Type: text/html; charset=utf-8\r\n";

        return $this->send($headers);
    }

    private function send($headers = '')
    {
        return mail($this->to, $this->subject, $this->message, $headers);
    }

    private function render()
    {
        if ($this->layout != '' && !is_file('layout/' . $this->layout . '.phtml')) {
            return false;
        } elseif ($this->layout == '') {
            $this->layout = 'mail';
        }

        if ($this->content != '' && !is_file('view/' . $this->content)) {
            return false;
        }

        // Put the variables visible to the included file
        if (is_array($this->data)) {
            foreach ($this->data as $k => $v) {
                ${$k}  = $v;
            }
        }

        if ($this->content != '') {
            ob_start();
            include('view/' . $this->content);
            $content = ob_get_contents();
            ob_end_clean();
        } else {
            $content = '';
        }

        ob_start();
        include('layout/' . $this->layout . '.phtml');
        $this->message = ob_get_contents();
        ob_end_clean();

        return true;
    }
}
