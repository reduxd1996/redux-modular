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
            $this->error("--module= is required");
            return;
        }

        $generator = new FileGenerator;

        $modulesPathFolder = rtrim($module_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $moduleFolder = $modulesPathFolder . $module;


        $GeneratePath = $moduleFolder . "/App/Interfaces/{$interface}.php";

        if (is_dir($moduleFolder)) {
            if (file_exists($GeneratePath)) {
                $this->error("Interface {$interface} in {$module} already exists");
            } else {
                $generator->createFile($module, $GeneratePath, $interface, 'interfaces');
                if (file_exists($GeneratePath)) {
                    $this->info("Created Interface {$interface} in {$module} module");
                }
            }
        } else {
            $this->error("Module {$module} doesn't exists");
        }
    }
}