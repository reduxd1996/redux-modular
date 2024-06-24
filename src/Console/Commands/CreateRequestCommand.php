<?php

namespace Redux\Modular\Console\Commands;

use Illuminate\Console\Command;
use Redux\Modular\Console\Generators\FileGenerator;

class CreateRequestCommand extends Command
{
    protected $signature = 'redux:make-request
    {module      : The name of the module}
    {request     : The name of the request}';
    protected $description = 'Create new module request';

    public function handle()
    {
        $module_path = config('redux-modular.modulePath');

        $module = ucfirst($this->argument('module'));

        $request = ucfirst($this->argument('request'));
        if (empty($module)) {
            $this->components->error("module is required");
            return;
        }

        $generator = new FileGenerator;

        $modulesPathFolder = rtrim($module_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $moduleFolder = $modulesPathFolder . $module;

        $GeneratePath = $moduleFolder . "/App/Http/Requests/{$request}.php";

        if (is_dir($moduleFolder)) {
            if (file_exists($GeneratePath)) {
                $this->components->error("Request [{$GeneratePath}] already exists.");
            } else {
                $generator->createFile($module, $GeneratePath, $request, 'normalrequest');
                if (file_exists($GeneratePath)) {
                    $this->components->info("Created Request [{$GeneratePath}] successfully.");
                }
            }
        } else {
            $this->components->error("Module [{$module}] doesn't exists.");
        }
    }
}
