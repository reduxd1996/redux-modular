<?php

namespace Redux\Modular\Console\Commands;

use Illuminate\Console\Command;
use Redux\Modular\Console\Generators\FileGenerator;

class CreateRulesCommand extends Command
{
    protected $signature = 'redux:make-rule
    {module    : The name of the module}
    {rule         : The name of the rule}';
    protected $description = 'Create new module rule';

    public function handle()
    {
        $module_path = config('redux-modular.modulePath');

        $module = ucfirst($this->argument('module'));

        $rule = ucfirst($this->argument('rule'));

        if (empty($module)) {
            $this->components->error("module is required");
            return;
        }

        $generator = new FileGenerator;

        $modulesPathFolder = rtrim($module_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        $moduleFolder = $modulesPathFolder . $module;

        $rulesPathFolder =  $moduleFolder . "/App/Rules/";

        $GeneratePath = "{$rulesPathFolder}{$rule}.php";

        if (is_dir($moduleFolder)) {
            if (!is_dir($rulesPathFolder)) {
                if (!mkdir($rulesPathFolder, 0755, true)) {
                    throw new \Exception("Failed to create directory: Rules Folder");
                    exit;
                }
            }
            if (file_exists($GeneratePath)) {
                $this->components->error("Rule [{$GeneratePath}] already exists.");
            } else {
                $generator->createFile($module, $GeneratePath, $rule, 'rules');
                if (file_exists($GeneratePath)) {
                    $this->components->info("Created Rule [{$GeneratePath}] successfully.");
                }
            }
        } else {
            $this->components->error("Module [{$module}] doesn't exists.");
        }
    }
}
