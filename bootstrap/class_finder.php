<?php
final class ClassFinder
{
    public static self|null $instance = null;

    private function __construct()
    {
        
    }

    public static function getInstance() : self
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getListFileInDir($dir, &$results = array()) {
        if(empty($dir)) {
            return $results;
        }
        $files = scandir($dir);
        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (is_file($path)) {
                $results[] = $path;
            } else if ($value != "." && $value != "..") {
                $this->getListFileInDir($path, $results);
                //$results[] = $path;
            }
        }
        return $results;
    }

    public function getListClassInDir($dir, &$results = array()) {
        if(empty($dir)) {
            return $results;
        }
        $files = scandir($dir);
        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (is_file($path)) {
                $results[] = pathinfo($path, PATHINFO_FILENAME);
            } else if ($value != "." && $value != "..") {
                $this->getListClassInDir($path, $results);
                //$results[] = $path;
            }
        }
        return $results;
    }
}
?>