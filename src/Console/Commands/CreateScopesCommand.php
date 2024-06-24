<?php

namespace Redux\Modular\Console\Commands;

use Illuminate\Console\Command;
use Redux\Modular\Console\Generators\FileGenerator;

class CreateScopesCommand extends Command
{
    protected $signature = 'redux:make-scope
    {module      : The name of the module}
    {scope       : The name of the scope}';
    protected $description = 'Create new module scope';

    public function handle()
    {
        $module_path = config('redux-modular.modulePath');

        $module = ucfirst($this->argument('module'));

        $scope = ucfirst($this->argument('scope'));

        if (empty($module)) {
            $this->components->error("module is required");
            return;
        }

        $generator = new FileGenerator;

        $modulesPathFolder = rtrim($module_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $moduleFolder = $modulesPathFolder . $module;

        $scopesPathFolder =  $moduleFolder . "/App/Models/Scopes/";

        $GeneratePath = "{$scopesPathFolder}{$scope}.php";

        if (is_dir($moduleFolder)) {
            if (!is_dir($scopesPathFolder)) {
                if (!mkdir($scopesPathFolder, 0755, true)) {
                    throw new \Exception("Failed to create directory: Scopes Folder");
                    exit;
                }
            }

            if (file_exists($GeneratePath)) {
                $this->components->error("Scope [{$GeneratePath}] already exists.");
            } else {
                $generator->createFile($module, $GeneratePath, $scope, 'scopes');
                if (file_exists($GeneratePath)) {
                    $this->components->info("Created Scope [{$GeneratePath}] Successfully.");
                }
            }
        } else {
            $this->components->error("Module [{$module}] doesn't exists");
        }
    }
}
