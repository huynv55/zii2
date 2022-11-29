<?php
namespace App\Models\Behavior;

use App\Entities\EntityInterface;
use App\Models\AppModelAbstract;

/**
 * define logic soft deleted use deleted_at
 */
trait SoftDelete
{
    public function where(array $where) : AppModelAbstract
    {
        if(in_array('deleted_at', $this->getColmuns()))
        {
            $where[] = '`deleted_at` IS NULL'; 
        }
        $this->query['where'] = $where;
        return $this;
    }

    public function forceWhere() : AppModelAbstract
    {
        $where = json_decode(json_encode($this->query['where']), true);
        if(in_array('deleted_at', $this->getColmuns()))
        {
            if(in_array('`deleted_at` IS NULL', $where))
            {
                array_splice($where, array_search('`deleted_at` IS NULL', $where), 1);
            } 
        }
        $this->query['where'] = $where;
        return $this;
    }

    public function deleteEntity(EntityInterface &$entity)
    {
        if(in_array('deleted_at', $this->getColmuns()))
        {
            $entity->deleted_at = date('Y-m-d H:i:s');
            $this->saveEntity($entity);
        }
        else
        {
            $this->deleteForceEntity($entity);
        }
    }

    public function restoreEntity(EntityInterface &$entity)
    {
        if(in_array('deleted_at', $this->getColmuns()))
        {
            if (!empty($entity->deleted_at))
            {
                $entity->deleted_at = null;
                $this->saveEntity($entity);
            }
        }
    }

    public function deleteForceEntity(EntityInterface &$entity)
    {
        $primaryValue = $entity->{$entity->primaryKey()};
        $query = [];
        $query[] = "DELETE FROM";
        $query[] = "`".$this->tableName."`";
        $query[] = "WHERE `".$entity->primaryKey()."` = ".$primaryValue;
        $stmp = $this->db->prepare(implode(" ", $query));
        $stmp->execute();
    }
}
?>