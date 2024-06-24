<?php

namespace Redux\Modular\Console\Commands;

use Illuminate\Console\Command;
use Redux\Modular\Console\Generators\FileGenerator;

class CreateTraitsCommand extends Command
{
    protected $signature = 'redux:make-trait
    {module     : The name of the module}
    {trait      : The name of the trait}';
    protected $description = 'Create new module trait';

    public function handle()
    {
        $module_path = config('redux-modular.modulePath');

        $module = ucfirst($this->argument('module'));

        $trait = ucfirst($this->argument('trait'));

        if (empty($module)) {
            $this->error("module is required");
            return;
        }

        $generator = new FileGenerator;

        $modulesPathFolder = rtrim($module_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $moduleFolder = $modulesPathFolder . $module;

        $traitsPathFolder =  $moduleFolder . "/App/Traits/";

        $GeneratePath = "{$traitsPathFolder}{$trait}.php";

        if (!is_dir($traitsPathFolder)) {
            if (!mkdir($traitsPathFolder, 0755, true)) {
                throw new \Exception("Failed to create directory: Traits Folder");
                exit;
            }
        }

        if (is_dir($moduleFolder)) {
            if (file_exists($GeneratePath)) {
                $this->error("Trait {$trait} in {$module} already exists");
            } else {
                $generator->createFile($module, $GeneratePath, $trait, 'traits');
                if (file_exists($GeneratePath)) {
                    $this->info("Created Trait {$trait} in {$module} module");
                }
            }
        } else {
            $this->error("Module {$module} doesn't exists");
        }
    }
}
