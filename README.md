# Redux-modular
Laravel modular Architecture

This repository offers a collection of Artisan commands designed to generate modules, controllers, requests, resources, models, and more within a Laravel application.

# Introduction
This package simplifies the process of creating structured modules in a Laravel application, ensuring consistency and adherence to best practices.

# Requirements

- PHP >= 8.1
- Laravel >= 9.x

# Installation

First add the following to your Composer autoload configuration in composer.json:

```
"Modules\\": "Modules/"
```

It should look like this:

```
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "Modules\\": "Modules/"
    }
}
```

Then, run:

```
composer dump-autoload
```

Then install the following command in your Laravel project directory:

```
composer require redux/modular
```

# Create Modules

To create a module, use the following command:

```
php artisan redux:make-module <Module>
```

This command will generate the necessary folders and files for your module.

You can now generate repository, trait, interface, or service or combined when you generate module with "--width" flag, using the following command: 

```
php artisan redux:make-module <Module> --with=trait
```

or with multiple files 

```
php artisan redux:make-module <Module> --with=trait,service,interface
```

It will generate standardized name like ModuleNameInterface, ModuleNameService etc...

# Create Controllers

To create a controller, use the following command:

```
php artisan redux:make-controller <Module> <ControllerName>
```
To generate multiple controllers, you can separate the controllers with a comma. For example: 

```
php artisan redux:make-controller <Module> <ControllerName>,<OtherControllerName>
```

For an API controller, you can use the --api flag, the --resource flag, or both.

```
php artisan redux:make-controller <Module> <ControllerName>,<OtherControllerName> --api --resource
```

# Create Models

To create a model, use the following command:

```
php artisan redux:make-model <Module> <Model>
```

To generate multiple model, you can separate the model with a comma. For example: 

```
php artisan redux:make-model <Module> <Model>,<OtherModel>
```

To generate migrations for your models, you can use "--m" flags:

```
php artisan redux:make-model <Module> <Model>,<OtherModel> --m
```

# Create Requests

To create a new request within a module, use the following command:

```
php artisan redux:make-request <Module> <RequestName>
```

# Create Resources

To create a new resource within a module, use the following command:

```
php artisan redux:make-resource <Module> <ResourceName>
```

# Create Services

To create a new service within a module, use the following command:

```
php artisan redux:make-service <Module> <ServiceName>
```

# Create Repositories

To create a new repository within a module, use the following command:

```
php artisan redux:make-repository <Module> <RepositoryName>
```

# Create Interface

To create a new interface within a module, use the following command:

```
php artisan redux:make-interface <Module> <InteraceName>
```

# Create Traits

To create a new trait within a module, use the following command:

```
php artisan redux:make-trait <Module> <TraitName>
```

<!-- # Create Tests

To create a new feature tests within a module, use the following command:

```
php artisan redux:make-test <Module> <TestName>
```

If you want to create a new unit tests within a module, use the following command:

```
php artisan redux:make-test <Module> <TestName> --unit
``` -->

## License

This project is licensed under the MIT License. See the [MIT license](LICENSE) file for details.
