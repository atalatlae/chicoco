<?php

namespace Chicoco\Core;

use Error;
use Exception;

class Conf
{
  protected $config;

  public function __construct($file = 'conf/Application.ini') {
    $this->config = array();

    if (!is_file($file)) {
        throw new Exception('file not found');
    }

    if (!($config = parse_ini_file($file, true, INI_SCANNER_TYPED))) {
        throw new Exception("unable to load configuration file");
    }

    if (is_array($config)) {
        // Get the general conf
        if (is_array($config['General'])) {
            foreach ($config['General'] as $k => $v) {
                $this->config[$k] = $v;
            }
        }

        // Get the common conf
        if (isset($config['Common']) && is_array($config['Common'])) {
            foreach ($config['Common'] as $k => $v) {
                $this->config[$k] = $v;
            }
        }

        // Get the conf for the current env
        if (isset($this->config['application.env']) && is_array($config[$this->config['application.env']])) {
            foreach ($config[$this->config['application.env']] as $k => $v) {
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
        }
        $this->putEnv();
    }

  public function getConf($key) {
    if (isset($key) && isset($this->config[$key])) {
      return $this->config[$key];
    }
    return null;
  }

  public function getAll() {
    return $this->config;
  }

  private function putEnv() {
    foreach ($this->config as $k => $v) {
        if (is_array($v)) {
            $v = json_encode($v);
        }
        putenv(
            sprintf('%s=%s', $k, $v)
        );
    }
  }
}

