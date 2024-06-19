<?php

namespace Redux\Modular\Console\Generators;

class FileGenerator extends Generator
{
    public function createFile(string $module, string $path, string $name, string $stub, array $addOnReplace = [])
    {
        $parentModulePath = $this->modulePath;
        $namespace_module = ucfirst($parentModulePath);
        $replace = [
            '{{namespace}}' => "{$namespace_module}\\$module\\",
            '{{class}}' => $name,
        ];
        if(!empty($addOnReplace)){
            $replace = array_merge($replace, $addOnReplace);
        }

        $this->createStub($path, $stub, $replace);
    }
}
