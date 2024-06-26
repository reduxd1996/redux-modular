<?php

namespace Redux\Modular\Console\Commands;

use Illuminate\Console\Command;
use Redux\Modular\Console\Generators\FileGenerator;

class CreateImportCommand extends Command
{
    protected $signature = 'redux:make-import
    {module      : The name of the module}
    {import      : The name of the import}';
    protected $description = 'Create new module import';

    public function handle()
    {
        $module_path = config('redux-modular.modulePath');

        $module = ucfirst($this->argument('module'));

        $import = ucfirst($this->argument('import'));
        if (empty($module)) {
            $this->components->error("module is required");
            return;
        }

        $generator = new FileGenerator;

        $modulesPathFolder = rtrim($module_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $moduleFolder = $modulesPathFolder . $module;

        $importPathFolder =  $moduleFolder . "/App/Imports/";

        $GeneratePath = "{$importPathFolder}{$import}.php";

        if (is_dir($moduleFolder)) {
            if (!is_dir($importPathFolder)) {
                if (!mkdir($importPathFolder, 0755, true)) {
                    throw new \Exception("Failed to create directory: Imports Folder");
                    exit;
                }
            }

            if (file_exists($GeneratePath)) {
                $this->components->error("Import [{$GeneratePath}] already exists.");
            } else {
                $generator->createFile($module, $GeneratePath, $import, 'imports');
                if (file_exists($GeneratePath)) {
                    $this->components->info("Created Import [{$GeneratePath}] successfully.");
                }
            }
        } else {
            $this->components->error("Module [{$module}] doesn't exists.");
        }
    }
}
