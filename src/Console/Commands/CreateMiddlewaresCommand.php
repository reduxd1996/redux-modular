<?php

namespace Redux\Modular\Console\Commands;

use Illuminate\Console\Command;
use Redux\Modular\Console\Generators\FileGenerator;

class CreateMiddlewaresCommand extends Command
{
    protected $signature = 'redux:make-middleware
    {module     : The name of the module}
    {middleware : The name of the middleware}';
    protected $description = 'Create new module middleware';

    public function handle()
    {
        $module_path = config('redux-modular.modulePath');

        $module = ucfirst($this->argumente('module'));

        $middleware = ucfirst($this->argument('middleware'));
        if (empty($module)) {
            $this->error("module is required");
            return;
        }

        $generator = new FileGenerator;

        $modulesPathFolder = rtrim($module_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $moduleFolder = $modulesPathFolder . $module;

        $GeneratePath = $moduleFolder . "/App/Http/Middlewares/{$middleware}.php";
        if (is_dir($moduleFolder)) {
            if (file_exists($GeneratePath)) {
                $this->error("Middleware {$middleware} in {$module} already exists");
            } else {
                $generator->createFile($module, $GeneratePath, $middleware, 'middlewares');
                if (file_exists($GeneratePath)) {
                    $this->info("Created middleware {$middleware} in {$module} module");
                }
            }
        } else {
            $this->error("Module {$module} doesn't exists");
        }
    }
}
