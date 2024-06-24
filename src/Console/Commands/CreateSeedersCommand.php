<?php

namespace Redux\Modular\Console\Commands;

use Illuminate\Console\Command;
use Redux\Modular\Console\Generators\FileGenerator;

class CreateSeedersCommand extends Command
{
    protected $signature = 'redux:make-seeder
    {module      : The name of the module}
    {seeder         : The name of the seeder}';
    protected $description = 'Create new module seeder';

    public function handle()
    {
        $module_path = config('redux-modular.modulePath');

        $module = ucfirst($this->argument('module'));

        $seeder = ucfirst($this->argument('seeder'));

        if (empty($module)) {
            $this->components->error("module is required");
            return;
        }

        $generator = new FileGenerator;

        $modulesPathFolder = rtrim($module_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $moduleFolder = $modulesPathFolder . $module;


        $GeneratePath = $moduleFolder . "/Database/Seeders/{$seeder}.php";

        if (is_dir($moduleFolder)) {
            if (file_exists($GeneratePath)) {
                $this->components->error("Seeder [{$GeneratePath}] already exists.");
            } else {
                $generator->createFile($module, $GeneratePath, $seeder, 'seeders');
                if (file_exists($GeneratePath)) {
                    $this->components->info("Created Seeder [{$GeneratePath}] successfully.");
                }
            }
        } else {
            $this->components->error("Module [{$module}] doesn't exists");
        }
    }

}
