<?php

namespace App\Responses\HtmlExtensions;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use App\Models\Entities\EntityInterface;

/**
 * Extension that render html form.
 */
class FormHelper implements ExtensionInterface
{
    /**
     * Instance of the current template.
     * @var Template
     */
    public $template;

    /**
     * entity object patch into form
     *
     * @var EntityInterface|null
     */
    protected ?EntityInterface $entity = null;

    /**
     * Create new Form instance.
     */
    public function __construct()
    {

    }

    /**
     * Register extension function.
     * @param Engine $engine
     * @return null
     */
    public function register(Engine $engine)
    {
        $engine->registerFunction('form', [$this, 'getFormInstance']);
    }

    public function getFormInstance()
    {
        return $this;
    }

    /**
     * open a form
     *
     * @param array $attributes : method, class, id, accept-charset, autocomplete, name, enctype, novalidate, target v.v          
     * @return string
     */
    public function open(array $attributes): string
    {
        $html = [];
        $html[] = '<form';
        $html[] = $this->renderAttr($attributes);
        $html[] = '>';
        //$html[] = PHP_EOL;
        if (!empty($attributes['method']) and strtolower($attributes['method']) == 'post') {
            $html[] = input_csrf_token();
        }
        return implode(" ", $html);
    }

    /**
     * close form
     *         
     * @return string
     */
    public function close(): string
    {
        return '</form>';
    }

    /**
     * render html form input
     *
     * @param string $field
     * @param string $type
     * @param array $attributes
     * @return string
     */
    public function input(string $field, string $type = 'text',array $attributes = []): string
    {
        if (empty($attributes['id'])) {
            $attributes['id'] = 'input-'.$type.'-'.$field;
        }
        $html = [];
        $html[] = '<fieldset>';
        //$html[] = PHP_EOL;
        $html[] = '<label for="'.$attributes['id'].'">'.($attributes['label'] ?? '').'</label>';
        //$html[] = PHP_EOL;
        $html[] = '<input';
        $html[] = 'name="'.$field.'"';
        $html[] = 'type="'.$type.'"';
        $html[] = $this->renderAttr($attributes);
        if($this->entityExistsField($field)) {
            $html[] = 'value="'.$this->entity->{$field}.'"';
        }
        $html[] = '/>';
        //$html[] = PHP_EOL;
        $html[] = '</fieldset>';
        return implode(" ", $html);
    }

    /**
     * render html form textarea
     *
     * @param string $field
     * @param array $attributes
     * @return string
     */
    public function textarea(string $field, array $attributes = []): string
    {
        if (empty($attributes['id'])) {
            $attributes['id'] = 'textarea-'.$field;
        }
        $html = [];
        $html[] = '<fieldset>';
        //$html[] = PHP_EOL;
        $html[] = '<label for="'.$attributes['id'].'">'.($attributes['label'] ?? '').'</label>';
        //$html[] = PHP_EOL;
        $html[] = '<textarea';
        $html[] = 'name="'.$field.'"';
        $html[] = $this->renderAttr($attributes);
        if($this->entityExistsField($field)) {
            $html[] = trim($this->entity->{$field});
        }
        $html[] = '</textarea>';
        //$html[] = PHP_EOL;
        $html[] = '</fieldset>';
        return implode(" ", $html);
    }

    /**
     * render html form select
     *
     * @param string $field
     * @param array $attributes
     * @param array $options
     * @return string
     */
    public function select(string $field, array $attributes = [], array $options = []): string
    {
        if (empty($attributes['id'])) {
            $attributes['id'] = 'select-'.$field;
        }
        $html = [];
        $html[] = '<fieldset>';
        //$html[] = PHP_EOL;
        $html[] = '<label for="'.$attributes['id'].'">'.($attributes['label'] ?? '').'</label>';
        //$html[] = PHP_EOL;
        $html[] = '<select';
        $html[] = 'name="'.$field.'"';
        $html[] = $this->renderAttr($attributes);
        $html[] = '>';
        //$html[] = PHP_EOL;
        foreach($options as $opt) {
            $html[] = '<option value="'.$opt['value'].'"';
            if ($this->entityExistsField($field) and $this->entity->{$field} == $opt['value']) {
                $html[] = 'selected=""';
            }
            $html[] = '>'.$opt['label'].'</option>';
        }
        $html[] = '</select>';
        //$html[] = PHP_EOL;
        $html[] = '</fieldset>';
        return implode(" ", $html);
    }

    /**
     * render html form button
     *
     * @param string $label
     * @param array $attributes
     * @return string
     */
    public function button(string $label = 'Submit', array $attributes = []): string
    {
        $html = [];
        $html[] = '<fieldset><button';
        $html[] =  !empty($attributes['type']) ? 'type="'.$attributes['type'].'"' : 'type="submit"';
        $html[] = $this->renderAttr($attributes);
        $html[] = '>'.$label.'</button></fieldset>';
        return implode(" ", $html);
    }

    /**
     * render html form checkbox
     *
     * @param string $field
     * @param array $attributes
     * @param array $options
     * @return string
     */
    public function checkbox(string $field, array $attributes = [], array $options = []): string
    {
        if (empty($attributes['id'])) {
            $attributes['id'] = 'input-checkbox-'.$field;
        }
        $html = [];
        if (!empty($options)) {
            foreach ($options as $index => $opt) {
                $attributes['id'] = 'input-checkbox-'.$field.'-'.$index;
                $html[] = '<fieldset>';
                //$html[] = PHP_EOL;
                $html[] = '<label for="'.$attributes['id'].'">'.($opt['label'] ?? '').'</label>';
                //$html[] = PHP_EOL;
                
                $html[] = '<input';
                $html[] = 'name="'.$field.'"';
                $html[] = 'value="'.$opt['value'].'"';
                $html[] = 'type="checkbox"';
                $html[] = $this->renderAttr($attributes);
                if($this->entityExistsField($field) and $this->entity->{$field} == $opt['value']) {
                    $html[] = 'checked="checked"';
                }
                $html[] = '/>';
                //$html[] = PHP_EOL;
                $html[] = '</fieldset>';
                //$html[] = PHP_EOL;
            }
        } else {
            $html[] = '<fieldset>';
            //$html[] = PHP_EOL;
            $html[] = '<label for="'.$attributes['id'].'">'.($attributes['label'] ?? '').'</label>';
            //$html[] = PHP_EOL;
            
            $html[] = '<input';
            $html[] = 'name="'.$field.'"';
            $html[] = 'type="checkbox"';
            $html[] = $this->renderAttr($attributes);
            if($this->entityExistsField($field) and is_bool($this->entity->{$field})) {
                $html[] = $this->entity->{$field} ? 'checked="checked"' : '';
            }
            $html[] = '/>';
            //$html[] = PHP_EOL;
            $html[] = '</fieldset>';
        }
        
        return implode(" ", $html);
    }

    /**
     * render html form radio
     *
     * @param string $field
     * @param array $attributes
     * @param array $options
     * @return string
     */
    public function radio(string $field, array $attributes = [], array $options = []): string
    {
        if (empty($attributes['id'])) {
            $attributes['id'] = 'input-radio-'.$field;
        }
        $html = [];
        if (!empty($options)) {
            foreach ($options as $index => $opt) {
                $attributes['id'] = 'input-radio-'.$field.'-'.$index;
                $html[] = '<fieldset>';
                //$html[] = PHP_EOL;
                $html[] = '<label for="'.$attributes['id'].'">'.($opt['label'] ?? '').'</label>';
                //$html[] = PHP_EOL;
                
                $html[] = '<input';
                $html[] = 'name="'.$field.'"';
                $html[] = 'value="'.$opt['value'].'"';
                $html[] = 'type="radio"';
                $html[] = $this->renderAttr($attributes);
                if($this->entityExistsField($field) and $this->entity->{$field} == $opt['value']) {
                    $html[] = 'checked="checked"';
                }
                $html[] = '/>';
                //$html[] = PHP_EOL;
                $html[] = '</fieldset>';
                //$html[] = PHP_EOL;
            }
        } else {
            $html[] = '<fieldset>';
            //$html[] = PHP_EOL;
            $html[] = '<label for="'.$attributes['id'].'">'.($attributes['label'] ?? '').'</label>';
            //$html[] = PHP_EOL;
            
            $html[] = '<input';
            $html[] = 'name="'.$field.'"';
            $html[] = 'type="radio"';
            $html[] = $this->renderAttr($attributes);
            if($this->entityExistsField($field) and is_bool($this->entity->{$field})) {
                $html[] = $this->entity->{$field} ? 'checked="checked"' : '';
            }
            $html[] = '/>';
            //$html[] = PHP_EOL;
            $html[] = '</fieldset>';
        }
        
        return implode(" ", $html);
    }

    public function setEntity(EntityInterface $entity)
    {
        $this->entity = $entity;
    }

    public function entityExistsField(string $field): bool
    {
        return (
            !empty($this->entity)
            and 
            property_exists($this->entity, $field)
            and 
            !is_null($this->entity->{$field})
        );
    }

    public function renderAttr(array $attributes)
    {
        $attr = [];
        foreach ($attributes as $key => $value) {
            if (
                $key != 'value'
                and
                $key != 'label'
                and 
                $key != 'type'
                and
                $key != 'name'
            ) {
                $attr[] = $key.'="'.$value.'"';
            }
        }
        return implode(" ", $attr);
    }
}
