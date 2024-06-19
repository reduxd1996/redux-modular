<?php

namespace Redux\Modular\Console\Commands;

use Illuminate\Console\Command;
use Redux\Modular\Console\Generators\MigrationsGenerator;
use Redux\Modular\Console\Generators\ModelsGenerator;

class CreateModelCommand extends Command
{
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
            $this->error("module is required");
            return;
        }

        $generator = new ModelsGenerator;

        $modulesPathFolder = rtrim($module_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $moduleFolder = $modulesPathFolder . $module;

        if (is_dir($moduleFolder)) {
            if ($this->isMultipleController($model)) {
                $models = explode(',', $model);
                foreach ($models as $value) {
                    $GenerateModelsPath = $moduleFolder . "/App/Models/{$value}.php";
                    if (file_exists($GenerateModelsPath)) {
                        $this->error("Model {$value} in {$module} module already exists");
                    } else {
                        $generator->createModelFile($module, $GenerateModelsPath, $value);
                        if (file_exists($GenerateModelsPath)) {
                            $this->info("Created Model {$value} in {$module} module");
                            if($haveMigration){
                                $migrationGenerator = new MigrationsGenerator;
                                $migration_name = $this->migrationTableName($value);
                                $GenerateMigrationPath = $moduleFolder . "/Database/Migrations/{$migration_name['file_name']}.php";
                                $migrationGenerator->createMigrationFile($GenerateMigrationPath, $migration_name['table_name']);
                                $this->info("Create Migration {$migration_name['file_name']} in {$module} module");
                            }
                        }
                    }
                }
            } else {
                $GeneratePath = $moduleFolder . "/App/Models/{$model}.php";
                if (file_exists($GeneratePath)) {
                    $this->error("Model {$model} in {$module} module already exists");
                } else {
                    $generator->createModelFile($module, $GeneratePath, $model);
                    if (file_exists($GeneratePath)) {
                        $this->info("Created Model {$model} in {$module} module");
                        if ($haveMigration) {
                            $migrationGenerator = new MigrationsGenerator;
                            $migration_name = $this->migrationTableName($model);
                            $GenerateMigrationPath = $moduleFolder . "/Database/Migrations/{$migration_name['file_name']}.php";
                            $migrationGenerator->createMigrationFile($GenerateMigrationPath, $migration_name['table_name']);
                        }
                    }
                }
            }
        } else {
            $this->error("Module {$module} doesn't exists");
        }
    }

    private function migrationTableName($name)
    {
        // Add a space before each uppercase letter, except the first letter if the string starts with an uppercase letter
        $migration_table_name = preg_replace('/(?<!^)([A-Z])/', '_$1', $name);
        return [
            'file_name' => strtolower(date('Y_m_d_hmi') . '_create_' . $this->pluralizeTableName($migration_table_name) . '_table'),
            'table_name' => strtolower($this->pluralizeTableName($migration_table_name)),
        ];
    }

    private function pluralizeTableName($table_name)
    {
        $last_letter = substr($table_name, -1);
        $last_two_letters = substr($table_name, -2);

        // Check for nouns ending in -s, -ss, -sh, -ch, -x, or -z
        if (in_array($last_two_letters, ['ss', 'sh', 'ch']) || in_array($last_letter, ['s', 'x', 'z'])) {
            return $table_name . 'es';
        }

        // Check for nouns ending in -y preceded by a consonant
        if ($last_letter === 'y' && !in_array(substr($table_name, -2, 1), ['a', 'e', 'i', 'o', 'u'])) {
            return substr($table_name, 0, -1) . 'ies';
        }

        // Check for nouns ending in -o (exceptions not handled for simplicity)
        if ($last_letter === 'o') {
            return $table_name . 'es';
        }

        return $table_name . 's';
    }

    private function isMultipleController($string, $char = ',')
    {
        return preg_match('/' . preg_quote($char, '/') . '/', $string) === 1;
    }

}
