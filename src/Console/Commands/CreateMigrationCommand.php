<?php

namespace Redux\Modular\Console\Commands;

use Illuminate\Console\Command;
use Redux\Modular\Console\Generators\MigrationsGenerator;

class CreateMigrationCommand extends Command
{
    protected $signature = 'redux:make-migration
    {module     : The name of the module}
    {migration  : The name of the migration}';
    protected $description = 'Create new module migration';

    public function handle()
    {
        $module_path = config('redux-modular.modulePath');

        $module = ucfirst($this->argument('module'));
        $migration = ucfirst($this->argument('migration'));
        if (empty($module)) {
            $this->error("module is required");
            return;
        }

        $migrationGenerator = new MigrationsGenerator;

        $modulesPathFolder = rtrim($module_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $moduleFolder = $modulesPathFolder . $module;
        $migration_name = $this->migrationTableName($migration);
        $GeneratePath = $moduleFolder . "/Database/Migrations/{$migration_name['file_name']}.php";
        if (is_dir($moduleFolder)) {
            if (file_exists($GeneratePath)) {
                $this->error("Migration {$migration_name['file_name']} in {$module} already exists");
            } else {
                $migrationGenerator->createMigrationFile($GeneratePath, $migration_name['table_name']);
                if (file_exists($GeneratePath)) {
                    $this->info("Created Migration {$migration_name['file_name']} in {$module} module");
                }
            }
        } else {
            $this->error("Module {$module} doesn't exists");
        }
    }


    private function migrationTableName($name)
    {
        $migration_table_name = preg_replace('/(?<!^)([A-Z])/', '_$1', $name);
        return [
            'file_name' => strtolower(date('Y_m_d_hmi') . '_create_' . $this->pluralizeTableName($migration_table_name) . '_table'),
            'table_name' => strtolower($this->pluralizeTableName($migration_table_name)),
        ];
    }

    private function pluralizeTableName($table_name){
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

        // Default case: add 's'
        return $table_name . 's';
    }
}
