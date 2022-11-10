<?php
final class ClassFinder
{
    private static $classes  = [];
    public static self|null $instance = null;

    private function __construct()
    {
        $composer = $GLOBALS['composer'];
        self::$classes  = [];

        if (!empty($composer)) {
            self::$classes  = array_keys($composer->getClassMap());
        }
    }

    public static function getInstance() : self
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getClasses()
    {
        $allClasses = [];

        if (!empty(self::$classes)) {
            foreach (self::$classes as $class) {
                $allClasses[] = '\\' . $class;
            }
        }

        return $allClasses;
    }

    public function getClassesByNamespace($namespace)
    {
        if (0 !== strpos($namespace, '\\')) {
            $namespace = '\\' . $namespace;
        }

        $termUpper = strtoupper($namespace);
        return array_filter($this->getClasses(), function($class) use ($termUpper) {
            $className = strtoupper($class);
            if (
                0 === strpos($className, $termUpper) and
                false === strpos($className, strtoupper('Abstract')) and
                false === strpos($className, strtoupper('Interface'))
            ){
                return $class;
            }
            return false;
        });
    }

    public function getClassesWithTerm($term)
    {
        $termUpper = strtoupper($term);
        return array_filter($this->getClasses(), function($class) use ($termUpper) {
            $className = strtoupper($class);
            if (
                false !== strpos($className, $termUpper) and
                false === strpos($className, strtoupper('Abstract')) and
                false === strpos($className, strtoupper('Interface'))
            ){
                return $class;
            }
            return false;
        });
    }
}
?>