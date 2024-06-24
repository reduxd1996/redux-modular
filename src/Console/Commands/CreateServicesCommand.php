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
            $this->components->error("module is required");
            return;
        }

        $generator = new FileGenerator;

        $modulesPathFolder = rtrim($module_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $moduleFolder = $modulesPathFolder . $module;

        $servicesPathFolder =  $moduleFolder . "/App/Services/";

        $GeneratePath = "{$servicesPathFolder}{$service}.php";

        if (is_dir($moduleFolder)) {
            if (!is_dir($servicesPathFolder)) {
                if (!mkdir($servicesPathFolder, 0755, true)) {
                    throw new \Exception("Failed to create directory: Services Folder");
                    exit;
                }
            }

            if (file_exists($GeneratePath)) {
                $this->components->error("Service [{$GeneratePath}] already exists.");
            } else {
                $generator->createFile($module, $GeneratePath, $service,'normalservice');
                if (file_exists($GeneratePath)) {
                    $this->components->info("Created Service [{$GeneratePath}] successfully.");
                }
            }
        } else {
            $this->components->error("Module [{$module}] doesn't exists.");
        }
    }

}
