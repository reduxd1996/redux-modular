# Redux-modular
Laravel modular Architecture

This repository provides a set of artisan commands to generate modules, controllers, requests, resources, and models in a Laravel application.

# Introduction
This package simplifies the process of creating structured modules in a Laravel application, ensuring consistency and adherence to best practices.

# Requirements

- PHP >= 8.x
- Laravel >= 8.x

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

# Create Controllers

To create a controller, use the following command:

```
php artisan redux:make-controller <Module> <ControllerName>
```
To generate multiple controllers, you can separate the controllers with a comma. For example: 

```
php artisan redux:make-controller <Module> TestController,TestNewController
```

For API you can also use "--api" or "--resource" or both

```
php artisan redux:make-controller <Module> TestController,TestNewController --api --resource
```

# Create Models

To create a model, use the following command:

```
php artisan redux:make-model <Module> <Model>
```

To generate multiple model, you can separate the model with a comma. For example: 

```
php artisan redux:make-model <Module> User,Contact
```

To generate migrations for your models, you can use "--m" flags:

```
php artisan redux:make-model <Module> User,Contact --m
```

## License

This project is licensed under the MIT License. See the [MIT license](LICENSE.md) file for details.
