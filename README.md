# The Showroom project used with Symfony
========================

What's inside?
--------------

The Symfony project used to manage some shows and caregories. In this, you will find:

  * An AppBundle you can use to develop your solution;

  * Twig as the template engine;

  * Doctrine ORM/DBAL;

  * Annotations enabled for everything.

It comes pre-configured with the following bundles:

  * **FrameworkBundle** - The core Symfony framework bundle

  * [**SensioFrameworkExtraBundle**][6] - Adds several enhancements, including
    template and routing annotation capability

  * [**DoctrineBundle**][7] - Adds support for the Doctrine ORM

  * [**TwigBundle**][8] - Adds support for the Twig templating engine

  * [**SecurityBundle**][9] - Adds security by integrating Symfony's security
    component

  * [**MonologBundle**][11] - Adds support for Monolog, a logging library

  * **WebProfilerBundle** (in dev/test env) - Adds profiling functionality and
    the web debug toolbar

  * **SensioDistributionBundle** (in dev/test env) - Adds functionality for
    configuring and working with Symfony distributions

  * [**SensioGeneratorBundle**][13] (in dev env) - Adds code generation
    capabilities

  * [**WebServerBundle**][14] (in dev env) - Adds commands for running applications
    using the PHP built-in web server

  * **DebugBundle** (in dev/test env) - Adds Debug and VarDumper component
    integration

All libraries and bundles included are released under the MIT or BSD license.

## How to implement the application in your system

You'll just have to follow the following commands : 
* First, fork the project and clone it to your local depository.
* When your project is on your system, use the command : composer install (or php composer.phar install if you are using windows).
* the composer will install all bundles necessary to the project and ask you your environment for the database etc. (with it's command  buildParameters.
* When this command is over, create the database with the command : bin/console doctrine:database:create (or php bin/console doctrine:database:create).
* The database is created ! Now you just have to show the difference between the database schema and the schema of your code (using (php) bin/console doctrine:migrations:diff).
* When the command is cleared, you will have a class that will be created with the difference of schema, take the numbers of the class and transmit them with the command (php) bin/console doctrine:migration:migrate (numbers).
* It's alright, the application is ready to use.

Enjoy!

[6]:  https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/index.html
[7]:  https://symfony.com/doc/3.4/doctrine.html
[8]:  https://symfony.com/doc/3.4/templating.html
[9]:  https://symfony.com/doc/3.4/security.html
[11]: https://symfony.com/doc/3.4/logging.html
[13]: https://symfony.com/doc/current/bundles/SensioGeneratorBundle/index.html
[14]: https://symfony.com/doc/current/setup/built_in_web_server.html
[15]: https://symfony.com/doc/current/setup.html
"# dim2018" 
