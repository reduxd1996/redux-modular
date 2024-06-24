<?php

namespace Redux\Modular\Console\Commands;

use Illuminate\Console\Command;
use Redux\Modular\Console\Generators\MigrationsGenerator;
use Redux\Modular\Traits\Migration;
class CreateMigrationCommand extends Command
{

    use Migration;

    protected $signature = 'redux:make-migration
    {module     : The name of the module}
    {migration  : The name of the migration}
    {--table=   : The name of the table}';
    protected $description = 'Create new module migration';

    public function handle()
    {
        $module_path = config('redux-modular.modulePath');

        $module = ucfirst($this->argument('module'));
        $migration = ucfirst($this->argument('migration'));
        $table = $this->option('table');

        if (empty($module)) {
            $this->components->error("module is required");
            return;
        }

        $migrationGenerator = new MigrationsGenerator;

        $modulesPathFolder = rtrim($module_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $moduleFolder = $modulesPathFolder . $module;
        $migration_name = $this->migrationTableName($migration,$table);

        $GeneratePath = $moduleFolder . "/Database/Migrations/{$migration_name['file_name']}.php";
        if (is_dir($moduleFolder)) {
            if (file_exists($GeneratePath)) {
                $this->components->error("Migration [{$migration_name['file_name']}] already exists.");
            } else {
                $migrationGenerator->createMigrationFile($GeneratePath, $migration_name['table_name'], $migration_name['stub']);
                if (file_exists($GeneratePath)) {
                    $this->components->info("Created Migration [{$migration_name['file_name']}] successfully.");
                }
            }
        } else {
            $this->components->error("Module [{$module}] doesn't exists.");
        }
    }

}
