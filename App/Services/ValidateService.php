<?php
namespace App\Services;
use function filter_var;

class ValidateService extends AbstractService
{
    protected \PDO $db;

    public function initialize()
    {
        parent::initialize();
        $this->db = db();
    }

    public function length(string $value, int $min = 0, int $max = 0): bool
    {
        if (empty($value))
        {
            return false;
        }
        return (
            strlen($value) >= $min
            and
            strlen($value) <= $max
        );
    }

    /**
     * validate email value
     *
     * @param $value
     * @return boolean
     */
    public function email($value): bool
    {
        if (empty($value))
        {
            return false;
        }
        if(filter_var($value, FILTER_VALIDATE_EMAIL) !== FALSE) return TRUE;
        return FALSE;
    }

    /**
     * validate boolean value
     *
     * @param $value
     * @return boolean
     */
    public function boolean($value): bool
    {
        if(filter_var($value, FILTER_VALIDATE_BOOLEAN) !== NULL) return TRUE;
        return FALSE;
    }

    /**
     * validate domain value
     *
     * @param $value
     * @return boolean
     */
    public function domain($value): bool
    {
        if (empty($value))
        {
            return false;
        }
        if(filter_var($value, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) !== FALSE) return TRUE;
        return FALSE;
    }

    /**
     * validate ip value
     *
     * @param $value
     * @param FILTER_FLAG_IPV4|FILTER_FLAG_IPV6|FILTER_FLAG_NO_PRIV_RANGE|FILTER_FLAG_NO_RES_RANGE|FILTER_FLAG_GLOBAL_RANGE $flag
     * @return boolean
     */
    public function ip($value, $flag = FILTER_FLAG_IPV4): bool
    {
        if (empty($value))
        {
            return false;
        }
        if(filter_var($value, FILTER_VALIDATE_IP, $flag) !== FALSE) return TRUE;
        return FALSE;
    }

    /**
     * validate address MAC value
     *
     * @param $value
     * @return boolean
     */
    public function addressMAC($value): bool
    {
        if (empty($value))
        {
            return false;
        }
        if(filter_var($value, FILTER_VALIDATE_MAC) !== FALSE) return TRUE;
        return FALSE;
    }

    /**
     * validate url value
     *
     * @param $value
     * @param FILTER_FLAG_PATH_REQUIRED|FILTER_FLAG_QUERY_REQUIRED $flag
     * @return boolean
     */
    public function url($value, $flag = FILTER_FLAG_PATH_REQUIRED): bool
    {
        if (empty($value))
        {
            return false;
        }
        if(filter_var($value, FILTER_VALIDATE_URL, $flag) !== FALSE) return TRUE;
        return FALSE;
    }

    /**
     * validate id value exist by table
     *
     * @param integer $id
     * @param string $table
     * @return boolean
     */
    public function exists(int $id, string $table): bool
    {
        $query = "SELECT `id` FROM `".$table."` WHERE `id` = :id";
        $stmp = $this->db->prepare($query);
        $stmp->setFetchMode(\PDO::FETCH_ASSOC);
        $stmp->bindParam('id', $id, \PDO::PARAM_INT);
        if($stmp->execute())
        {
            $result = $stmp->fetch();
            return !empty($result);
        }
        return false;
    }

    /**
     * validate exist value by field and by table
     *
     * @param mixed $value
     * @param string $field
     * @param string $table
     * @return boolean
     */
    public function existsField(mixed $value, string $field, string $table): bool
    {
        $query = "SELECT `".$field."` FROM `".$table."` WHERE `".$field."` = ? LIMIT 1";
        $stmp = $this->db->prepare($query);
        $stmp->setFetchMode(\PDO::FETCH_ASSOC);
        if($stmp->execute([$value]))
        {
            $result = $stmp->fetch();
            return !empty($result);
        }
        return false;
    }

    /**
     * validate unique value by field and by table
     *
     * @param mixed $value
     * @param string $field
     * @param string $table
     * @param integer $id
     * @return boolean
     */
    public function unique(mixed $value, string $field, string $table, int $id = 0): bool
    {
        $query = "SELECT `id` FROM `".$table."` WHERE `".$field."` = ? LIMIT 1";
        $stmp = $this->db->prepare($query);
        $stmp->setFetchMode(\PDO::FETCH_ASSOC);
        if($stmp->execute([$value]))
        {
            $result = $stmp->fetch();
            return (empty($result) or $result['id'] === $id);
        }
        return false;
    }
}
?>