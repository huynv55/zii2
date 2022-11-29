<?php
namespace App\Models\Behavior;

use App\Entities\EntityInterface;
use App\Exceptions\Validations\ValidationException;
use Rakit\Validation\Validator;
use Rakit\Validation\Validation;

/**
 * define logic validate before saveEntity
 */
trait ValidateEntity
{
    public function saveEntity(EntityInterface &$entity)
    {
        if ($this->validated($entity))
        {
            parent::saveEntity($entity);
        }
        else
        {
            throw new ValidationException("Entity is not validate");
        }
    }

    /**
     * check validate entity
     *
     * @return boolean
     */
    public function validated(EntityInterface &$entity) : bool
    {
        $data = $entity->toArray();
        $validator = new Validator($this->messages);
        $this->validation = $validator->validate($data, $this->validate);
        return $this->validation->passes();
    }
    
    public function getValidateErrors()
    {
        return $this->validation->errors();
    }
}
?>