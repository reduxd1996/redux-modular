<?php

namespace Redux\Modular\Console\Commands;

use Illuminate\Console\Command;
use Redux\Modular\Console\Generators\FileGenerator;

class CreateInterfacesCommand extends Command
{
    protected $signature = 'redux:make-interface
    {module     : The name of the module}
    {interface  : The name of the interface}';
    protected $description = 'Create new module interface';

    public function handle()
    {
        $module_path = config('redux-modular.modulePath');

        $module = ucfirst($this->argument('module'));

        $interface = ucfirst($this->argument('interface'));

        if (empty($module)) {
            $this->components->error("--module= is required");
            return;
        }

        $generator = new FileGenerator;

        $modulesPathFolder = rtrim($module_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $moduleFolder = $modulesPathFolder . $module;

        $interfacesPathFolder =  $moduleFolder . "/App/Interfaces/";

        $GeneratePath = "{$interfacesPathFolder}{$interface}.php";

        if (is_dir($moduleFolder)) {
            if (!is_dir($interfacesPathFolder)) {
                if (!mkdir($interfacesPathFolder, 0755, true)) {
                    throw new \Exception("Failed to create directory: Interfaces Folder");
                    exit;
                }
            }

            if (file_exists($GeneratePath)) {
                $this->components->error("Interface [{$GeneratePath}] already exists.");
            } else {
                $generator->createFile($module, $GeneratePath, $interface, 'interfaces');
                if (file_exists($GeneratePath)) {
                    $this->components->info("Created Interface [{$GeneratePath}] successfully.");
                }
            }
        } else {
            $this->components->error("Module [{$module}] doesn't exists.");
        }
    }
}
