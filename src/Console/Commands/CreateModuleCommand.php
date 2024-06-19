<?php

namespace Redux\Modular\Console\Commands;

use Illuminate\Console\Command;
use Redux\Modular\Console\Generators\ModuleGenerator;
use Redux\Modular\Console\Generators\FileGenerator;

class CreateModuleCommand extends Command
{
    protected $signature = 'redux:make-module {module : The name of the module}';
    protected $description = 'Create new module';

    public function handle()
    {
        $module_path = config('redux-modular.modulePath');

        $module = ucfirst($this->argument('module'));

        if (empty($module)) {
            $this->error("module is required");
            return;
        }

        $generator      = new ModuleGenerator;

        $modulesPathFolder = rtrim($module_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $moduleFolder = $modulesPathFolder . $module;

        // Check if the module folder exists
        if (!is_dir($modulesPathFolder)) {
            // Create the module folder
            if (!mkdir($modulesPathFolder, 0755, true)) {
                throw new \Exception("Failed to create directory: $modulesPathFolder");
                exit;
            }
        }

        if (is_dir($module_path)) {
            if (is_dir($module_path)) {
                if (!is_dir($moduleFolder)) {
                    $generate_module_folders = $generator->createModuleFolder($module_path, $module);

                    if ($generate_module_folders) {
                        $this->info("Generated {$module} module scaffolding");
                    } else {
                        $this->error("Error on creating {$module} module scaffolding");
                    }
                } else {
                    $this->error("Module {$module} already exists");
                }
            }
        }
    }
}
