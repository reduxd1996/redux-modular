<?php

namespace Redux\Modular\Console\Commands;

use Illuminate\Console\Command;
use Redux\Modular\Console\Generators\FileGenerator;

class CreatePoliciesCommand extends Command
{
    protected $signature = 'redux:make-policy
    {module      : The name of the module}
    {policy      : The name of the policy}';
    protected $description = 'Create new module policy';

    public function handle()
    {
        $module_path = config('redux-modular.modulePath');

        $module = ucfirst($this->argument('module'));

        $policy = ucfirst($this->argument('policy'));
        if (empty($module)) {
            $this->error("module is required");
            return;
        }

        $generator = new FileGenerator;

        $modulesPathFolder = rtrim($module_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $moduleFolder = $modulesPathFolder . $module;

        $policiesPathFolder =  $moduleFolder . "/App/Policies/";

        $GeneratePath = "{$policiesPathFolder}{$policy}.php";

        if (is_dir($moduleFolder)) {
            if (!is_dir($policiesPathFolder)) {
                if (!mkdir($policiesPathFolder, 0755, true)) {
                    throw new \Exception("Failed to create directory: Policies Folder");
                    exit;
                }
            }

            if (file_exists($GeneratePath)) {
                $this->components->error("Policy [{$GeneratePath}] already exists.");
            } else {
                $generator->createFile($module, $GeneratePath, $policy, 'normalpolicy');
                if (file_exists($GeneratePath)) {
                    $this->components->info("Created Policy [{$GeneratePath}] successfully.");
                }
            }
        } else {
            $this->components->error("Module [{$module}] doesn't exists.");
        }
    }
}
