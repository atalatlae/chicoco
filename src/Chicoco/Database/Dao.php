<?php

namespace Chicoco\DataBase;

use PDO;
use Exception;
use Chicoco\Core\Log;
use Chicoco\DataBase\Exceptions\DuplicatedEntityException;
use Chicoco\DataBase\Exceptions\DBIntegrityException;
use Chicoco\DataBase\Exceptions\DaoException;

class Dao
{
    protected $db;
    protected $msgResult = '';
    protected $sql;
    protected $stmt;
    protected $params;
    protected $error;
    protected $errorCode;
    protected $log;

    protected $transactionEnabled = true;

    public function __construct(Database $db)
    {
        $this->transactionEnabled = true;
        $this->db = $db;
        $this->log = new Log();
    }

    public function inTransaction()
    {
        return $this->db->inTransaction();
    }

    public function begin()
    {
        if ($this->transactionEnabled) {
            $this->db->beginTransaction();
        }
    }

    public function commit()
    {
        if ($this->transactionEnabled) {
            $this->db->commit();
        }
    }

    public function rollback()
    {
        if ($this->transactionEnabled) {
            $this->db->rollback();
        }
    }

    public function enableTransaction()
    {
        $this->transactionEnabled = true;
    }

    public function disableTransaction()
    {
        $this->transactionEnabled = false;
    }

    public function getLastId()
    {
        return $this->db->lastInsertId();
    }

    public function setSql($sql)
    {
        $this->sql = $sql;
    }

    public function getResult($fetchAs = 'assoc', $class = null)
    {
        switch ($fetchAs) {
            case 'class':
                $result = $this->stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $class);
                break;
            case 'numeric':
                $result = $this->stmt->fetchAll(PDO::FETCH_NUM);
                break;
            case 'assoc':
            default:
                $result = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
                break;
        }
        return $result;
    }

    public function getRow($fetchAs = 'assoc', $class = null)
    {
        switch ($fetchAs) {
            case 'class':
                $result = $this->stmt->fetch(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $class);
                break;
            case 'numeric':
                $result = $this->stmt->fetch(PDO::FETCH_NUM);
                break;
            case 'assoc':
            default:
                $result = $this->stmt->fetch(PDO::FETCH_ASSOC);
                break;
        }
        return $result;
    }

    public function getMsgResult()
    {
        return $this->msgResult;
    }

    public function doSelect()
    {
        $this->stmt = $this->db->prepare($this->sql);
        $this->setParams($this->stmt);
        $query = $this->stmt->execute();

        if ($query !== true) {
            $this->error = $this->stmt->errorInfo();
            $this->errorCode = $this->stmt->errorCode();
            $this->errorMessage = $this->error[2];

            $this->log->error($this->errorMessage);

            throw new DaoException($this->errorMessage);
        }

        return true;
    }

    public function doInsert()
    {
        return $this->doWrite();
    }

    public function doUpdate()
    {
        return $this->doWrite();
    }

    public function doDelete()
    {
        return $this->doWrite();
    }

    public function addParam($key = '', $value = '', $type = PDO::PARAM_STR)
    {
        $this->params[] = array(
            'key'   => $key,
            'value' => $value,
            'type'  => $type
        );
    }

    public function addParams(...$params)
    {
        foreach ($params as $p) {
            if (count($p) != 2 && count($p) != 3) {
                throw new Exception('wrong input param');
            }

            $this->addParam($p[0], $p[1], $p[2] ?? null);
        }
    }

    public function clearParams()
    {
        $this->params = array();
        return true;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getErrorCode()
    {
        if (isset($this->error[1])) {
            return $this->error[1];
        }
        return null;
    }

    /*** ***/

    protected function setParams($stmt)
    {
        if (is_array($this->params) && count($this->params) > 0) {
            foreach ($this->params as $p) {
                if (!isset($p['type'])) {
                    $p['type'] = PDO::PARAM_STR;
                }

                $stmt->bindParam($p['key'], $p['value'], $p['type']);
            }
        }
    }

    protected function handleError()
    {
        switch ($this->getErrorCode()) {
            case 1062:
                throw new DuplicatedEntityException('duplicated entry');
                break;
            case 1451:
                throw new DBIntegrityException('fk constraint fails');
                break;
            case 1452:
                throw new DBIntegrityException('invalid references');
                break;
            default:
                throw new DaoException('default exception');
                break;
        }
    }

    private function doWrite()
    {
        try {
            $stmt = $this->db->prepare($this->sql);
            $this->setParams($stmt);
            $query = $stmt->execute();

            if ($query !== true) {
                $this->error = $stmt->errorInfo();
                throw new Exception('Dao: ' . var_export($this->error, true));
            }
            return true;
        } catch (Exception $e) {
            $this->msgResult = $e->getMessage();
            $this->log->error($e->getMessage());
            return false;
        }
    }

    public function getTotalRows()
    {
        $this->setSql('SELECT FOUND_ROWS() as total');
        $this->clearParams();
        $this->doSelect();
        $result = $this->getResult();

        if ($result === false) {
            return -1;
        }

        return $result[0]['total'];
    }
}
