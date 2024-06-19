<?php

namespace Redux\Modular\Console\Generators;

class ControllersGenerator extends Generator
{
    public function createControllerFile($module, $controller, $path, $isResource, $isApi)
    {
        $namespace_module = ucfirst($this->modulePath);
        $replace = [
            '{{namespace}}' => "{$namespace_module}\\$module\\",
            '{{name}}' => $controller,
        ];

        $stub_type = 'normalcontroller';

        if($isApi && $isResource){
            $stub_type = 'apiresourcecontroller';
        }

        if(!$isApi && $isResource){
            $stub_type = 'resourcecontroller';
        }

        $this->createStub($path, $stub_type, $replace);

    }
}
