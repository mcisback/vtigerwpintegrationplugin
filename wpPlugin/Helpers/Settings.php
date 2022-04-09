<?php
namespace Mcisback\WpPlugin\Helpers;

class Settings {
    protected static $instance = null;
    protected array $settings = [];

    // Get Singleton Instance
    public static function gI(
        string $filePath = '',
        array $settings = []
    ) {
      if (static::$instance == null) {
        static::$instance = new Settings(
            $filePath,
            $settings
        );
      }
   
      return static::$instance;
    }

    public function __construct(
        string $filePath = '',
        array $settings = []
    ) {
        $this->filePath = $filePath;

        if( file_exists($filePath) ) {
            $this->loadFromFile();
        } else {        
            $this->settings = $settings;
        }
    }

    public function setFilePath(string $filePath) {
        $this->filePath = $filePath;

        return $this;
    }

    public function setAll(array $settings) {
        $this->settings = $settings;

        return $this;
    }

    public function setSome(array $settings) {
        foreach ($settings as $key => $value) {
            $this->settings[$key] = $value;
        }

        return $this;
    }

    public function get(string $key = null) {
        if($key !== null) {
            return $this->settings[$key];
        }

        return $this->settings;
    }

    public function set(string $key, string $value) {
        $this->settings[$key] = $value;

        return $this;
    }

    public function loadFromFile() {
        $this->settings = json_decode(
            file_get_contents($this->filePath),
            true
        );

        return $this;
    }

    public function saveToFile() {
        /* $class_name = get_called_class();

        $reflection_class = new \ReflectionClass($class_name);

        $ajaxActionNamespace = $reflection_class->getNamespaceName();
        $ajaxActionNamespace .= '\\Admin\\Ajax\\Actions'; */

        return file_put_contents(
            $this->filePath,
            json_encode( $this->settings )
        );
    }
}