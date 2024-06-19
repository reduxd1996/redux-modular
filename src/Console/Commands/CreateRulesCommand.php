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
            $this->error("module is required");
            return;
        }

        $generator = new FileGenerator;

        $modulesPathFolder = rtrim($module_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $moduleFolder = $modulesPathFolder . $module;

        $GeneratePath = $moduleFolder . "/App/Rules/{$rule}.php";

        if (is_dir($moduleFolder)) {
            if (file_exists($GeneratePath)) {
                $this->error("Rule {$rule} in {$module} already exists");
            } else {
                $generator->createFile($module, $GeneratePath, $rule, 'rules');
                if (file_exists($GeneratePath)) {
                    $this->info("Created Rule {$rule} in {$module} module");
                }
            }
        } else {
            $this->error("Module {$module} doesn't exists");
        }
    }
}
