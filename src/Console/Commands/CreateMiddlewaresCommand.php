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

        $module = ucfirst($this->argument('module'));

        $middleware = ucfirst($this->argument('middleware'));
        if (empty($module)) {
            $this->components->error("module is required");
            return;
        }

        $generator = new FileGenerator;

        $modulesPathFolder = rtrim($module_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $moduleFolder = $modulesPathFolder . $module;

        $middlewaresPathFolder =  $moduleFolder . "/App/Http/Middlewares/";

        $GeneratePath = "{$middlewaresPathFolder}{$middleware}.php";

        if (is_dir($moduleFolder)) {
            if (!is_dir($middlewaresPathFolder)) {
                if (!mkdir($middlewaresPathFolder, 0755, true)) {
                    throw new \Exception("Failed to create directory: Middlewares Folder");
                    exit;
                }
            }

            if (file_exists($GeneratePath)) {
                $this->components->error("Middleware [{$GeneratePath}] already exists.");
            } else {
                $generator->createFile($module, $GeneratePath, $middleware, 'middlewares');
                if (file_exists($GeneratePath)) {
                    $this->components->info("Created middleware [{$GeneratePath}] successfully.");
                }
            }
        } else {
            $this->components->error("Module [{$module}] doesn't exists.");
        }
    }
}
