<?php

namespace Chicoco;

class Mail
{
    private $_from;
    private $_to;
    private $_subject;
    private $_content;
    private $_data;
    private $_layout;

    private $_message;

    public function __construct($from = '', $to = '', $subject = '', $content = '', array $data, $layout = 'mail') {
        $this->_from = $from;
        $this->_to = $to;
        $this->_subject = $subject;
        $this->_content = $content;
        $this->_data = $data;
        $this->_layout = $layout;

        $this->_render();
    }

    public function sendHtml() {
        $headers = "From: ".$this->_from."\r\n"
        ."MIME-Version: 1.0\r\n"
        ."Content-Type: text/html; charset=utf-8\r\n";

        return $this->_send($headers);
    }

    private function _send($headers = '') {
        return mail($this->_to, $this->_subject, $this->_message, $headers);
    }

    private function _render() {
        if ($this->_layout != '' && !is_file('layout/'.$this->_layout.'.phtml')) {
            return false;
        }
        else if ($this->_layout == '') {
            $this->_layout = 'mail';
        }

        if ($this->_content != '' && !is_file('view/'.$this->_content)) {
            return false;
        }

        // Put the variables visible to the included file
        if (is_array($this->_data)) {
            foreach($this->_data as $k => $v) {
                ${$k}  = $v;
            }
        }

        if ($this->_content != '') {
            ob_start();
            include('view/'.$this->_content);
            $content = ob_get_contents();
            ob_end_clean();
        }
        else {
            $content = '';
        }

        ob_start();
        include ('layout/'.$this->_layout.'.phtml');
        $this->_message = ob_get_contents();
        ob_end_clean();

        return true;
    }
}
