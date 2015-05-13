<?php
// $phalconDevTools = '/usr/bin/phalcon';
// if (!is_file($phalconDevTools)) die('Missing Phalcon DevTools');

$projectPath = __DIR__ . '/../../';
echo `cd $projectPath && phalcon all-models --relations`;

$dbTablePath = './DbTable/';

foreach (getAllFiles($dbTablePath) as $filename) {
    $modelName = pathinfo($filename, PATHINFO_FILENAME);
    $newModelName = $modelName . 'DbTable';
    $newFilename = $newModelName . '.php';
    
    if (!is_numeric(stripos($filename, "DbTable.php"))) {
        $file = file_get_contents($filename);
        $file = str_replace("class {$modelName} extends", "class {$newModelName} extends", $file);
        $file = updateInitializeFunction($file);
        
        if (file_put_contents($dbTablePath . $newFilename, $file)) {
            unlink($filename);
            
            createModelFile($modelName, $newModelName);
        }
    } else {
        $modelName = str_replace(array("DbTable", ".php"), array("", ""), $modelName);
        $newModelName = $modelName . 'DbTable';
        createModelFile($modelName, $newModelName);
    }
}

function getAllFiles($dir, $extension = null) {
    $root = scandir($dir);
    $result = array();
    
    foreach ($root as $value) {
        if ($value === '.' || $value === '..') {
            continue;
        }
        if ($value === 'Rename.php') {
            continue;
        }
        if (is_file("$dir/$value")) {
            $result[] = "$dir/$value";
            continue;
        }
        
        foreach (find_all_files("$dir/$value") as $value) {
            $result[] = $value;
        }
    }
    return $result;
}

function updateInitializeFunction($file) {
    $template = '    /**
     * Initialize method for model.
     */
    public function initialize()
    {
    }

}';
    
    if (!stripos($file, 'initialize()')) {
        $file = str_replace('}', $template, $file);
    }
    
    return $file;
}

function createModelFile($modelName, $dbtableModel) {
    $template = "<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class {$modelName} extends {$dbtableModel}
{
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
    }

    public function beforeValidationOnCreate()
    {
    }
    
    public function beforeValidationOnUpdate()
    {
    }

    public function validation()
    {
        \$this->validate(new Uniqueness(array(
            'field'   => 'key',
            'message' => 'Sorry, your key was registered'
        )));

        if (\$this->validationHasFailed() == true) {
            return false;
        }
    }
}";
    
    $modelFilename = $modelName . '.php';
    if (!is_file($modelFilename)) file_put_contents($modelFilename, $template);
}
