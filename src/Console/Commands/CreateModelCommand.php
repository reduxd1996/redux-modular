<?php

namespace Redux\Modular\Console\Commands;

use Illuminate\Console\Command;
use Redux\Modular\Console\Generators\MigrationsGenerator;
use Redux\Modular\Console\Generators\ModelsGenerator;
use Redux\Modular\Traits\Migration;
class CreateModelCommand extends Command
{
    use Migration;

    protected $signature = 'redux:make-model
    {module     : The name of the module}
    {model      : The name of the model}
    {--m        : Include the migration}'
    ;
    protected $description = 'Create new module model';

    public function handle()
    {
        $module_path = config('redux-modular.modulePath');

        $module = ucfirst($this->argument('module'));
        $haveMigration = ucfirst($this->option('m'));
        $model = ucfirst($this->argument('model'));

        if(empty($module)){
            $this->components->error("module is required");
            return;
        }

        $generator = new ModelsGenerator;

        $modulesPathFolder = rtrim($module_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $moduleFolder = $modulesPathFolder . $module;

        if (is_dir($moduleFolder)) {
            if ($this->isMultipleModels($model)) {
                $models = explode(',', $model);
                foreach ($models as $value) {
                    $GenerateModelsPath = $moduleFolder . "/App/Models/{$value}.php";
                    if (file_exists($GenerateModelsPath)) {
                        $this->components->error("Model [{$GenerateModelsPath}] already exists");
                    } else {
                        $generator->createModelFile($module, $GenerateModelsPath, $value);
                        if (file_exists($GenerateModelsPath)) {
                            $this->components->info("Created Model [{$GenerateModelsPath}] successfully.");
                            if($haveMigration){
                                $migrationGenerator = new MigrationsGenerator;
                                $migration_name = $this->migrationTableName('create_'.$value.'_table');
                                $GenerateMigrationPath = $moduleFolder . "/Database/Migrations/{$migration_name['file_name']}.php";
                                $migrationGenerator->createMigrationFile($GenerateMigrationPath, $migration_name['table_name'], $migration_name['stub']);
                                $this->components->info("Create Migration [{$migration_name['file_name']}] successfully.");
                            }
                        }
                    }
                }
            } else {
                $GeneratePath = $moduleFolder . "/App/Models/{$model}.php";
                if (file_exists($GeneratePath)) {
                    $this->components->error("Model [{$GeneratePath}] already exists");
                } else {
                    $generator->createModelFile($module, $GeneratePath, $model);
                    if (file_exists($GeneratePath)) {
                        $this->components->info("Created Model [{$GeneratePath}] successfully.");
                        if ($haveMigration) {
                            $migrationGenerator = new MigrationsGenerator;
                            $migration_name = $this->migrationTableName('create_' . $model . '_table');
                            $GenerateMigrationPath = $moduleFolder . "/Database/Migrations/{$migration_name['file_name']}.php";
                            $migrationGenerator->createMigrationFile($GenerateMigrationPath, $migration_name['table_name'], $migration_name['stub']);
                            $this->components->info("Create Migration [{$migration_name['file_name']}] successfully.");
                        }
                    }
                }
            }
        } else {
            $this->components->error("Module [{$module}] doesn't exists.");
        }
    }

    private function isMultipleModels($string, $char = ',')
    {
        return preg_match('/' . preg_quote($char, '/') . '/', $string) === 1;
    }

}
