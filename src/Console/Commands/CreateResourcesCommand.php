<?php

namespace Redux\Modular\Console\Commands;

use Illuminate\Console\Command;
use Redux\Modular\Console\Generators\FileGenerator;

class CreateResourcesCommand extends Command
{
    protected $signature = 'redux:make-resource
    {module      : The name of the module}
    {resource    : The name of the resource}';
    protected $description = 'Create new module resource';

    public function handle()
    {
        $module_path = config('redux-modular.modulePath');

        $module = ucfirst($this->argument('module'));

        $resource = ucfirst($this->argument('resource'));
        if (empty($module)) {
            $this->components->error("module is required");
            return;
        }

        $generator = new FileGenerator;

        $modulesPathFolder = rtrim($module_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $moduleFolder = $modulesPathFolder . $module;

        $GeneratePath = $moduleFolder . "/App/Http/Resources/{$resource}.php";
        if (is_dir($moduleFolder)) {
            if (file_exists($GeneratePath)) {
                $this->components->error("Resource [{$GeneratePath}] already exists.");
            } else {
                $generator->createFile($module, $GeneratePath, $resource, 'normalresource');
                if (file_exists($GeneratePath)) {
                    $this->components->info("Created Resource [{$GeneratePath}] successfully.");
                }
            }
        } else {
            $this->components->error("Module [{$module}] doesn't exists.");
        }
    }
}
