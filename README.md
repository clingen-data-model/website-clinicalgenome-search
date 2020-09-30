# ClinGen Website Search (v4.X)(User Experience - Knowledge-base)
The aim of the project is to integrate ClinGen's internal data services and public external data services around a common user experience to ensure clinically relevant knowledge about genes and variants is available for use in precision medicine and research.


## Framework - Laravel
Laravel is a free, open-source PHP web framework intended for the development of web applications following the model–view–controller architectural pattern and based on Symfony.  https://laravel.com/

### Server Requirements
The Laravel framework has a few system requirements:

- PHP >= 7.1.8
- BCMath PHP Extension
- Ctype PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

### Laravel Version 5.8
Currently this project is using Laravel 5.8.  Keep that in mind if you are reading documentation and using new packages.

### Installation and Configuration
Laravel provides a few options when installing.  You can start right from this repo or you can install fresh. Read more at https://laravel.com/docs/6.0#installing-laravel  When you get started make sure don't forget to run ```composer install``` This is what goes and grabs all of the dependencies you need.  Also, after you have things running make sure you compile the assets.

If you don't start from the repo and simply want to start clean then remember this website is running Laravel 5.8.  If you want to grab 5.8 then consider the following command ```composer create-project laravel/laravel="5.8.*" PROJECT_NAME_HERE```

#### Composer - A Dependency Manager for PHP
Laravel utilizes Composer to manage its dependencies. So, before using Laravel, make sure you have Composer installed on your machine.

#### Public Directory
After installing, you should configure your web server's document / web root to be the public directory. The index.php in this directory serves as the front controller for all HTTP requests entering your application.

#### Configuration Files
All of the configuration files for the framework are stored in the config directory.

#### Environment Configuration .ENV
Laravel utilizes the DotEnv PHP library by Vance Lucas. The root directory the application contains a .env.example file so you can see where you see the defaults.  Make sure you create your own .env and don't commit it... right now it's excluded and lets keep it that way.

#### Directory Permissions
After installing, you may need to configure some permissions. Directories within the storage and the bootstrap/cache directories should be writable by your web server or Laravel will not run.

#### Application Key
The next thing you should do after installing Laravel is set your application key to a random string. If you installed Laravel via Composer or the Laravel installer, this key has already been set for you by the php artisan key:generate command.

#### Additional Configuration
Yu may wish to review the config/app.php file and its documentation. It contains several options such as timezone and locale that you may wish to change according to your application.

### Helpful Reading
Architecture Concepts
- Request Lifecycle [https://laravel.com/docs/6.0/lifecycle]
- Service Container [https://laravel.com/docs/6.0/container]
- Service Providers [https://laravel.com/docs/6.0/providers]
- Facades [https://laravel.com/docs/6.0/facades]
- Contracts [https://laravel.com/docs/6.0/contracts]
- Routing [https://laravel.com/docs/6.0/routing]
- Templates [https://laravel.com/docs/6.0/blade]
- Compiling Assets [https://laravel.com/docs/6.0/mix]

### Common Composer Commands
Just a few that you may want know if you are new... lots of others so you may want to go do some reading.

#### install
First creates a composer.lock if not exists composer.lock file. composer lock file contains composer.json all package with version. then install those packages.
```composer install```

#### update
updates all outdated commands
```composer update```

#### update specific package
```composer update vendor-name/package-name```

### Load External
- php artisan update:Genenames
- php artisan decipher:query
- php artisan exac:query
- php artisan update:map