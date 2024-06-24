<?php

namespace Redux\Modular\Console\Commands;

use Illuminate\Console\Command;
use Redux\Modular\Console\Generators\FileGenerator;

class CreateRepositoryCommand extends Command
{
    protected $signature = 'redux:make-repository
    {module      : The name of the module}
    {repository  : The name of the repository}';
    protected $description = 'Create new module repository';

    public function handle()
    {
        $module_path = config('redux-modular.modulePath');

        $module = ucfirst($this->argument('module'));

        $repository = ucfirst($this->argument('repository'));
        if (empty($module)) {
            $this->components->error("module is required");
            return;
        }

        $generator = new FileGenerator;

        $modulesPathFolder = rtrim($module_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $moduleFolder = $modulesPathFolder . $module;

        $repositoryPathFolder =  $moduleFolder . "/App/Repositories/";

        $GeneratePath = "{$repositoryPathFolder}{$repository}.php";

        if (is_dir($moduleFolder)) {
            if (!is_dir($repositoryPathFolder)) {
                if (!mkdir($repositoryPathFolder, 0755, true)) {
                    throw new \Exception("Failed to create directory: Repositories Folder");
                    exit;
                }
            }

            if (file_exists($GeneratePath)) {
                $this->components->error("Repository [{$GeneratePath}] already exists.");
            } else {
                $generator->createFile($module, $GeneratePath, $repository, 'repositories');
                if (file_exists($GeneratePath)) {
                    $this->components->info("Created [{$GeneratePath}] successfully.");
                }
            }
        } else {
            $this->components->error("Module [{$module}] doesn't exists.");
        }
    }
}
