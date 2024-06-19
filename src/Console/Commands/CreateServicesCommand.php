<?php

namespace Redux\Modular\Console\Commands;

use Illuminate\Console\Command;
use Redux\Modular\Console\Generators\FileGenerator;

class CreateServicesCommand extends Command
{
    protected $signature = 'redux:make-service
    {module      : The name of the module}
    {service     : The name of the service}';
    protected $description = 'Create new module service';

    public function handle()
    {
        $module_path = config('redux-modular.modulePath');

        $module = ucfirst($this->argument('module'));

        $service = ucfirst($this->argument('service'));
        if (empty($module)) {
            $this->error("module is required");
            return;
        }

        $generator = new FileGenerator;

        $modulesPathFolder = rtrim($module_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $moduleFolder = $modulesPathFolder . $module;

        $GeneratePath = $moduleFolder . "/App/Services/{$service}.php";

        if (is_dir($moduleFolder)) {
            if (file_exists($GeneratePath)) {
                $this->error("Service {$service} in {$module} already exists");
            } else {
                $generator->createFile($module, $GeneratePath, $service,'normalservice');
                if (file_exists($GeneratePath)) {
                    $this->info("Created Service {$service} in {$module} module");
                }
            }
        } else {
            $this->error("Module {$module} doesn't exists");
        }
    }

}
