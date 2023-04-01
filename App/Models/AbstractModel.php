<?php
namespace App\Models;

use initializeLoader;
use PDOStatement;

abstract class AbstractModel implements initializeLoader
{
    /**
     * sql query string execute
     *
     * @var string
     */
    protected string $query = "";

    /**
     * all params query
     *
     * @var array
     */
    protected array $params = [];

    /**
     * Entity class result
     *
     * @var string
     */
    protected string $entityClass = '';

    protected \PDO $db;

    public function initialize()
    {
        $this->db = db();
    }

    public function execute(bool $fetchEntity = true): PDOStatement
    {
        $stmp = $this->db->prepare($this->query);
        if (!empty($this->entityClass) && $fetchEntity) {
            $stmp->setFetchMode(\PDO::FETCH_CLASS, $this->entityClass);
        } else {
            $stmp->setFetchMode(\PDO::FETCH_ASSOC);
        }
        foreach ($this->params as $key => $param) {
            $type = gettype($param);
            if ($type == "boolean") {
                $stmp->bindValue($key, $param, \PDO::PARAM_BOOL);
            } else 
            if ($type == "NULL" or $type == "unknown type") {
                $stmp->bindValue($key, $param, \PDO::PARAM_NULL);
            } else 
            if ($type == "integer") {
                $stmp->bindValue($key, $param, \PDO::PARAM_INT);
            } else {
                $stmp->bindValue($key, $param);
            }
        }
        $stmp->execute();
        return $stmp;
    }

    public function executeAssoc(): PDOStatement
    {
        return $this->execute(false);
    }

    public function setQuery(string $sql): self
    {
        $this->query = $sql;
        return $this;
    }

    public function setParams(array $params): self
    {
        $this->params = $params;
        return $this;
    }

    /**
     * set database
     *
     * @param \PDO $db
     * @return self
     */
    public function setDatasource(\PDO $db): self
    {
        $this->db = $db;
        return $this;
    }

    public function getLastInsertId(): int
    {
        return intval($this->db->lastInsertId());
    }

    public function fetch()
    {
        return $this->execute()->fetch();
    }

    public function fetchAll(): array
    {
        return $this->execute()->fetchAll();
    }
}
?>