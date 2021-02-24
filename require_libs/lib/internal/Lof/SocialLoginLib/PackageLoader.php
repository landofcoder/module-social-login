<?php
namespace Lof;
class PackageLoader
{
    public $rootDir;

    public function __construct($dir) {
        $this->rootDir = $dir;
    }

    public function getComposerFile($dir)
    {
        if(file_exists($dir."/composer.json")){
            return json_decode(file_get_contents($dir."/composer.json"), 1);
        }
        return false;
    }

    protected function _getSubDirs() {
        if($this->rootDir){
            $directories = glob($this->rootDir . '/*' , GLOB_ONLYDIR);
            return $directories;
        }
        return [];
    }

    public function load()
    {
        $dirs = $this->_getSubDirs();
        foreach($dirs as $dir) {
            $composer = $this->getComposerFile($dir);
            if($composer)
                $this->loadPSR($composer,$dir);
        }
    }

    public function loadPSR($composer, $dir)
    {
        $psr4 = false;
        if(isset($composer['autoload']['psr-4'])){
            $psr4 = true;
            $namespaces = $composer['autoload']['psr-4'];
        }
        if(isset($composer['autoload']['psr-0'])){
            $namespaces = $composer['autoload']['psr-0'];
        }
        $classmap = [];
        if(isset($composer['autoload']['classmap'])){
            $classmap = $composer['autoload']['classmap'];
        }
        $classlib = [];
        if(isset($composer['autoload']['classlib'])){
            $classlib = $composer['autoload']['classlib'];
        }
        if($classlib){
            $namespace = "";
            foreach ($classlib as $class_lib_name){
                $classpaths = "lib/";
                spl_autoload_register(function ($class_name) use ($namespace, $classpaths,$dir, $psr4, $class_lib_name) {
                    if($class_name == $class_lib_name){
                        $filename = preg_replace("#\\\\#", "/", $class_name).".php";
                        $fullpath = $dir."/".$classpaths."$filename";
                        if (file_exists($fullpath)) {
                            include_once $fullpath;
                        }
                    }
                });
            }
        }

        // Foreach namespace specified in the composer, load the given classes
        foreach ($namespaces as $namespace => $classpaths) {
            if (!is_array($classpaths)) {
                $classpaths = array($classpaths);
            }
            spl_autoload_register(function ($classname) use ($namespace, $classpaths,$dir, $psr4,$classmap) {  
                // Check if the namespace matches the class we are looking for
                if (preg_match("#^".preg_quote($namespace)."#", $classname)) {
                    // Remove the namespace from the file path since it's psr4
                    if ($psr4) {
                        $classname = str_replace($namespace, "", $classname);
                    }

                    $filename = preg_replace("#\\\\#", "/", $classname).".php";
                    
                    foreach ($classpaths as $classpath) {
                        
                        if(substr($classpath, -1) === '/'){
                            $fullpath = $dir."/".$classpath."$filename";
                        } else {
                            $fullpath = $dir."/".$classpath."/$filename";
                        }
                        
                        
                        if(!empty($classmap)){
                            foreach($classmap as $map){
                                $fullpath = str_replace($map.'_',$map.'/',$fullpath);
                            }
                        }

                        if (file_exists($fullpath)) {
                            include_once $fullpath;
                        }
                    }
                }
            });
        }
    }
}

$packageLoader = new PackageLoader(__DIR__.'/Lib');
$packageLoader->load();