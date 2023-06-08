<?php
namespace App\Models\Entities;

abstract class AbstractEntity implements EntityInterface
{
    public function fromArray(array $data): EntityInterface
    {
        $vars = get_object_vars($this);
        foreach($vars as $property => $value) {
            if($property != 'id') {
                $this->{$property} = $data[$property] ?? null;
            } else {
                $this->{'id'} = $data['id'] ?? 0;
            }
        }
        return $this;
    }

    public function toArray(): array
    {
        $data = [];
        $vars = get_object_vars($this);
        foreach($vars as $property => $value) {
            $data[$property] = $value;
        }
        return $data;
    }

    /**
     * validate entity data
     *
     * @return array message validatetion
     * @return array empty if validation pass
     */
    public function validate(): array
    {
        return [];
    }

    public function primaryKey(): string
    {
        return 'id';
    }
}
?>