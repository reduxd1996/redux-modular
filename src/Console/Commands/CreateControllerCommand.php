<?php

namespace Redux\Modular\Console\Commands;

use Illuminate\Console\Command;
use Redux\Modular\Console\Generators\ControllersGenerator;

class CreateControllerCommand extends Command
{
    protected $signature = 'redux:make-controller
    {module     : The name of the module}
    {controller     : The name of the controller}
    {--resource     : Resource Controller}
    {--api          : API Controller}';

    protected $description = 'Create new module controller';

    public function handle()
    {
        $module_path = config('redux-modular.modulePath');

        $module = ucfirst($this->argument('module'));
        $controller = $this->argument('controller');
        $isResource = $this->option('resource');
        $isApi = $this->option('api');

        if (empty($module)) {
            $this->components->error("module= is required");
            return;
        }

        $generator = new ControllersGenerator;

        $modulesPathFolder = rtrim($module_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $moduleFolder = $modulesPathFolder . $module;

        if (is_dir($moduleFolder)) {
            if($this->isMultipleController($controller)){
                $controllers = explode(',', $controller);
                foreach($controllers as $controller){
                    $generatePath = $moduleFolder . "/App/Http/Controllers/{$controller}.php";
                    if (file_exists($generatePath)) {
                        $this->components->error("Controller [{$generatePath}] already exists.");
                    } else {
                        $generator->createControllerFile($module, $controller, $generatePath, $isResource, $isApi);
                        if (file_exists($generatePath)) {
                            $this->components->info("Create Controller [{$generatePath}] successfully.");
                        }
                    }
                }
            } else {
                $generatePath = $moduleFolder . "/App/Http/Controllers/{$controller}.php";
                if (file_exists($generatePath)) {
                    $this->components->error("Controller [{$generatePath}] already exists.");
                } else {
                    $generator->createControllerFile($module, $controller, $generatePath, $isResource, $isApi);
                    if (file_exists($generatePath)) {
                        $this->components->info("Create Controller [{$generatePath}] successfully.");
                    }
                }
            }
        } else {
            $this->components->error("Module [{$module}] doesn't exists.");
        }
    }

    private function isMultipleController($string, $char = ',')
    {
        return preg_match('/' . preg_quote($char, '/') . '/', $string) === 1;
    }
}
