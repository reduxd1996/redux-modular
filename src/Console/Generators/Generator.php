<?php

namespace Redux\Modular\Console\Generators;

use Illuminate\Support\Facades\File;

class Generator
{
    protected $modulePath;

    public function __construct()
    {
        $this->modulePath = config('redux-modular.modulePath');
    }

    protected function createStub(string $path, string $stub, array $replace = [])
    {
        $stub = strtolower($stub);

        $stub_file = File::get(__DIR__ . "/../../stubs/{$stub}.stub");

        $controllerContent = $this->replacePlaceholders($stub_file, $replace);

        File::put($path, $controllerContent);
        return $stub;
    }

    protected function replacePlaceholders($stub, array $replacements)
    {
        foreach ($replacements as $placeholder => $value) {
            $stub = str_replace($placeholder, $value, $stub);
        }
        return $stub;
    }
}
