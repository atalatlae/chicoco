<?php

namespace Chicoco;

class Dao extends DataBase
{
    protected $db;
    protected $msgResult = "";
    protected $sql;
    protected $params;
    protected $result;

    public function __construct()
    {
        $this->db = $this->getInstance();
    }

    public function inTransaction()
    {
        return $this->db->inTransaction();
    }

    public function begin()
    {
        $this->db->beginTransaction();
    }

    public function commit()
    {
        $this->db->commit();
    }

    public function rollback()
    {
        $this->db->rollback();
    }

    public function setSql($sql)
    {
        $this->sql = $sql;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getMsgResult()
    {
        return $this->msgResult;
    }

    public function doSelect()
    {
        try {
            $stmt = $this->db->prepare($this->sql);
            $this->setParams($stmt);
            $query = $stmt->execute();

            if ($query !== true) {
                $error = $stmt->errorInfo();
                throw new \Exception('Dao: '.var_export($error, true));
            }

            $this->result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            $this->msgResult = $e->getMessage();
            $this->result = false;
        }
    }

    public function doInsert()
    {
        return $this->doWrite();
    }

    public function doUpdate()
    {
        return $this->doWrite();
    }

    public function addParam($key = '', $value = '', $type = PDO::PARAM_STR)
    {
        // TODO: Fix here when the value == 0 and 0 is a valid value !!!
        // if ($key != '' && $value != '' && $type != '')
        {
            $this->params[] = array(
                'key'   => $key,
                'value' => $value,
                'type'  => $type
            );
            return true;
        }
        return false;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function clearParams()
    {
        $this->params = array();
        return true;
    }

    /*** ***/

    protected function setParams($stmt)
    {
        if (is_array($this->params) && count($this->params) > 0) {
            foreach ($this->params as $p) {
                if (!isset($p['type'])) {
                    $p['type'] = \PDO::PARAM_STR;
                }

                $stmt->bindParam($p['key'], $p['value'], $p['type']);
            }
        }
    }

    private function doWrite()
    {
        try {
            $stmt = $this->db->prepare($this->sql);
            $this->setParams($stmt);
            $query = $stmt->execute();

            if ($query !== true) {
                $error = $stmt->errorInfo();
                throw new \Exception('Dao: '.var_export($error, true));
            }
            return true;
        } catch (\Exception $e) {
            $this->msgResult = $e->getMessage();
            return false;
        }
    }
}
