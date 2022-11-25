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
                $params[$column] = $entity->{$column} ?? null;
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
            $stmp->execute($params);
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
            $stmp->execute($params);
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
        $stmp->execute($this->query['params'] ?? []);
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
        $stmp->execute($this->query['params'] ?? []);
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
        $stmp->execute($this->query['params'] ?? []);
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
        $query[] = !empty($this->query['offset']) ? "OFFSET ".$this->query['offset'] : "";
        $query[] = !empty($this->query['limit']) ? "LIMIT ".$this->query['limit'] : "";
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
        if(!empty($params['limit']))
        {
            $this->limit($params['limit']);
        }
        if(!empty($params['offset']))
        {
            $this->offset($params['offset']);
        }
        $this->withParams($params);
        $results = $this->fetchAll();
        $total = $this->count();
        return compact('results', 'total');
    }
}
?>