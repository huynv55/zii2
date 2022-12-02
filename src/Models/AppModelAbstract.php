<?php
namespace App\Models;

use App\Entities\EntityInterface;
use App\Requests\RequestInterface;
use PDOStatement;
abstract class AppModelAbstract implements ModelInterface
{
    protected \PDO $db;
    protected string $entityClass;
    protected string $tableName;
    protected array $query;
    protected array $columns = [];

    public function find() : self
    {
        $this->query = [];
        return $this;
    }

    /**
     * select list fields results
     *
     * @param array $select
     * @return self
     */
    public function select(array $select) : self
    {
        $this->query['select'] = $select;
        return $this;
    }

    public function where(array $where) : self
    {
        $this->query['where'] = $where;
        return $this;
    }

    public function order(string $order) : self
    {
        $this->query['order'] = $order;
        return $this;
    }

    public function group(string $group) : self
    {
        $this->query['group'] = $group;
        return $this;
    }

    public function offset(int $offset) : self
    {
        $this->query['offset'] = $offset;
        return $this;
    }

    public function limit(int $limit) : self
    {
        $this->query['limit'] = $limit;
        return $this;
    }

    public function saveEntity(EntityInterface &$entity)
    {
        $columns = $this->getColmuns();
        $params = [];
        foreach ($columns as $key => $column) {
            if($column != $entity->primaryKey())
            {
                if (gettype($entity->{$column}) == "boolean") {
                    $params[$column] = $entity->{$column} ? 1 : 0;
                } else 
                if (gettype($entity->{$column}) == "NULL" or gettype($entity->{$column}) == "unknown type") {
                    $params[$column] = null;
                } else {
                    $params[$column] = $entity->{$column} ?? null;
                }
            }
        }
        $primaryValue = $entity->{$entity->primaryKey()} ?? 0;
        $query = [];
        $query[] = !empty($primaryValue) ? "UPDATE" : "INSERT INTO";
        $query[] = "`".$this->tableName."`";

        // update entity
        if(!empty($primaryValue)) 
        {
            $params['updated_at'] = date('Y-m-d H:i:s');
            $query[] = "SET";
            $fields = [];
            foreach ($columns as $key => $column) {
                if($column != $entity->primaryKey())
                {
                    $fields[] = "`".$column."` = :".$column;
                }
            }
            $query[] = implode(", ", $fields);
            $query[] = "WHERE `".$entity->primaryKey()."` = ".$primaryValue;
            $stmp = $this->db->prepare(implode(" ", $query));
            $stmp = $this->bindParams($stmp, $params);
            $stmp->execute();
        }
        else 
        
        // insert entity
        {
            $params['created_at'] = date('Y-m-d H:i:s');
            $params['updated_at'] = date('Y-m-d H:i:s');
            $fields1 = [];
            $fields2 = [];
            foreach ($columns as $key => $column) {
                if($column != $entity->primaryKey())
                {
                    $fields1[] = "`".$column."`";
                    $fields2[] = ":".$column;
                }
            }
            $query[] = "(".implode(", ", $fields1).")";
            $query[] = "VALUES";
            $query[] = "(".implode(", ", $fields2).")";
            $stmp = $this->db->prepare(implode(" ", $query));
            $stmp = $this->bindParams($stmp, $params);
            $stmp->execute();
            $entity->{$entity->primaryKey()} = $this->db->lastInsertId();
        }
    }

    public function deleteEntity(EntityInterface &$entity)
    {
        $primaryValue = $entity->{$entity->primaryKey()};
        $query = [];
        $query[] = "DELETE FROM";
        $query[] = "`".$this->tableName."`";
        $query[] = "WHERE `".$entity->primaryKey()."` = ".$primaryValue;
        $stmp = $this->db->prepare(implode(" ", $query));
        $stmp->execute();
    }

    public function exec() : false|PDOStatement
    {
        $q = $this->buildQuery();
        $stmp = $this->db->prepare($q);
        $stmp->setFetchMode(\PDO::FETCH_ASSOC);
        $stmp = $this->bindParams($stmp, $this->query['params'] ?? []);
        $stmp->execute();
        return $stmp;
    }

    public function bindParams(PDOStatement $stmp, $params) : PDOStatement
    {
        foreach ($params as $key => $param) {
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
        return $stmp;
    }

    public function count() : ?int
    {
        $this->select(['COUNT(*) as `count_results`']);
        $this->limit(1);
        $this->offset(0);
        $stmp = $this->exec();
        $result = $stmp->fetch();
        return $result['count_results'] ?? null;
    }

    /**
     * Fetch one result from database table
     *
     * @return EntityInterface|bool
     */
    public function fetch() : EntityInterface|bool
    {
        $this->limit(1);
        $q = $this->buildQuery();
        $stmp = $this->db->prepare($q);
        $stmp->setFetchMode(\PDO::FETCH_CLASS, $this->entityClass);
        $stmp = $this->bindParams($stmp, $this->query['params'] ?? []);
        $stmp->execute();
        return $stmp->fetch();
    }

    /**
     * fetch all result from database table
     *
     * @return EntityInterface[]
     */
    public function fetchAll() : array
    {
        $q = $this->buildQuery();
        $stmp = $this->db->prepare($q);
        $stmp->setFetchMode(\PDO::FETCH_CLASS, $this->entityClass);
        $stmp = $this->bindParams($stmp, $this->query['params'] ?? []);
        $stmp->execute();
        return $stmp->fetchAll();
    }

    public function buildQuery() : string
    {
        $query = [];
        $query[] = "SELECT";
        $query[] = !empty($this->query['select']) ? implode(",", $this->query['select']) : "*";
        $query[] = "FROM";
        $query[] = "`".$this->tableName."`";
        $query[] = "WHERE";
        $query[] = !empty($this->query['where']) ? implode(" AND ", $this->query['where']) : "1";
        $query[] = !empty($this->query['group']) ? "GROUP BY ". $this->query['group'] : "";
        $query[] = !empty($this->query['order']) ? "ORDER BY ".$this->query['order'] : "";
        if (!empty($this->query['limit'])) {
            $query[] = "LIMIT ".$this->query['limit'];
            if (!empty($this->query['offset'])) {
                $query[] = "OFFSET ".$this->query['offset'];
            }
        }
        return implode(" ", $query);
    }

    public function withParams(array $params) : self
    {
        $this->query['params'] = $params;
        return $this;
    }

    public function getColmuns()
    {
        if(!empty($this->columns))
        {
            return $this->columns;
        }
        $q = "SHOW COLUMNS FROM `".$this->tableName."`";
        $stmp = $this->db->prepare($q);
        $stmp->setFetchMode(\PDO::FETCH_ASSOC);
        $stmp->execute();
        $results = $stmp->fetchAll();
        $fields = [];
        foreach ($results as $key => $value) {
            $fields[] = $value['Field'];
        }
        $this->columns = $fields;
        return $fields;
    }

    public function paginate(RequestInterface $request)
    {
        $params = $request->getQueryString();
        $limit = 10;
        $offset = 0;
        $page = 1;
        if(!empty($params['limit']))
        {
            $limit = intval($params['limit']);
        }
        if(!empty($params['offset']))
        {
            $offset = intval($params['offset']);
        }
        if(isset($params['page']) && intval($params['page']) > 0) {
            $page = intval($params['page']);
            $offset = ($page - 1) * $limit;
        } else {
            $page = intval($offset / $limit) + 1;
        }
        $this->limit($limit);
        $this->offset($offset);
        $results = $this->fetchAll();
        $total = $this->count();
        $max_page = ( $total % $limit > 0 ) ? intval($total / $limit) + 1 : intval($total / $limit);
        return compact('results', 'total', 'max_page', 'page');
    }
}
?>