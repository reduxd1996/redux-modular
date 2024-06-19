# Redux-modular
Laravel modular Architecture

This repository provides a set of artisan commands to generate modules, controllers, requests, resources, and models in a Laravel application.

# Introduction
This package simplifies the process of creating structured modules in a Laravel application, ensuring consistency and adherence to best practices.

# Requirements
- PHP >= 7.4
- Laravel >= 8.x

# Installation
To install this package, run the following command in your Laravel project directory:

composer require redux/modular

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

You need to provide the name of the module and the controller.
