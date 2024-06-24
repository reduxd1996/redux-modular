<?php

namespace Redux\Modular\Console\Commands;

use Illuminate\Console\Command;
use Redux\Modular\Console\Generators\FileGenerator;

class CreateExportCommand extends Command
{
    protected $signature = 'redux:make-export
    {module      : The name of the module}
    {export      : The name of the export}';
    protected $description = 'Create new module export';

    public function handle()
    {
        $module_path = config('redux-modular.modulePath');

        $module = ucfirst($this->argument('module'));

        $export = ucfirst($this->argument('export'));
        if (empty($module)) {
            $this->error("module is required");
            return;
        }

        $generator = new FileGenerator;

        $modulesPathFolder = rtrim($module_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $moduleFolder = $modulesPathFolder . $module;

        $exportsPathFolder =  $moduleFolder . "/App/Exports/";

        $GeneratePath = "{$exportsPathFolder}{$export}.php";


        if (!is_dir($exportsPathFolder)) {
            if (!mkdir($exportsPathFolder, 0755, true)) {
                throw new \Exception("Failed to create directory: Exports Folder");
                exit;
            }
        }

        if (is_dir($moduleFolder)) {
            if (file_exists($GeneratePath)) {
                $this->error("Export {$export} in {$module} already exists");
            } else {
                $generator->createFile($module, $GeneratePath, $export, 'exports');
                if (file_exists($GeneratePath)) {
                    $this->info("Created Export {$export} in {$module} module");
                }
            }
        } else {
            $this->error("Module {$module} doesn't exists");
        }
    }
}
