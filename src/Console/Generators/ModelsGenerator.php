<?php

namespace Redux\Modular\Console\Generators;

use Illuminate\Support\Facades\File;

class ModelsGenerator extends Generator
{
    public function createModelFile($module, $path, $name)
    {
        $parentModulePath = $this->modulePath;
        $namespace_module = ucfirst($parentModulePath);
        $replace = [
            '{{namespace}}' => "{$namespace_module}\\$module\\",
            '{{class}}' => $name,
        ];

        $this->createStub($path, 'Models', $replace);
    }
}
