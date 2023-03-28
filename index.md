Symfony : triggering SQL Filter on events from Symfony Demo Application
=======================================================================

The "Symfony Demo Application" is a reference application created to show how
to develop applications following the [Symfony Best Practices][1].

You can also learn about these practices in [the official Symfony Book][5].

_This extension aims to be a POC on how to trigger SQL Filters when a condition is met.
Users might have a country. Idem for posts, I added a simple property for country.
If the connected user have a defined country, the filter aims to restrict visible posts on this one._

Differences from the original symfony/demo
------------------------------------------

- User Locale Listener ([Symfony 6 Events and Event Listeners](https://symfony.com/doc/current/event_dispatcher.html#creating-an-event-subscriber))
- User Locale SQL Filter ([Doctrine ORM 2.14.1 SQL Filters](https://www.doctrine-project.org/projects/doctrine-orm/en/2.14/reference/filters.html))
- Config php-way ([T. Votruba : 10 Cool Features You Get after switching from YAML to PHP Configs](https://tomasvotruba.com/blog/2020/07/16/10-cool-features-you-get-after-switching-from-yaml-to-php-configs/))
- Simplify Config Transformer ([Simplify Converts Symfony XML/YAML configs to PHP](https://github.com/symplify/config-transformer))
- Note for later : [T. Votruba : Don't Ever use Symfony Listeners](https://tomasvotruba.com/blog/2019/05/16/don-t-ever-use-listeners)
- See also : [PHP.net : Reading Attributes with the Reflection API](https://www.php.net/manual/en/language.attributes.reflection.php)
- See also : [PHP.watch : Attributes in PHP 8](https://php.watch/articles/php-attributes#reflection)
- See also : [SymfonyCasts : Symfony 5 > Go Pro with Doctrine Queries > Filters](https://symfonycasts.com/screencast/doctrine-queries/filters)
- User data fixtures adapted to the context : french_{user/admin}, english_{user/admin}....

**Visit the blog connected as the french user, you'll see only french posts.  
Visit the blog connected as the english user, you'll see only english posts.  
Visit the blog anonymously, or connected as an admin, or connected as a user whom language is unknown,
and you'll see all posts.**

Requirements
------------

* PHP 8.1.0 or higher;
* PDO-SQLite PHP extension enabled;
* and the [usual Symfony application requirements][2].

Installation
------------

[Download Composer][6] and use the `composer` binary installed
on your computer to run these commands:

```bash
# clone the code repository and install its dependencies
$ git clone https://github.com/atierant/demo-event-subscriber.git my_project
$ cd my_project/
$ composer install
$ npm install
```

Usage
-----

There's no need to configure anything before running the application. There are
2 different ways of running this application depending on your needs:

**Option 1.** [Download Symfony CLI][4] and run this command:

```bash
$ cd my_project/
$ symfony serve
```

Then access the application in your browser at the given URL (<https://localhost:8000> by default).

**Option 2.** Use a web server like Nginx or Apache to run the application
(read the documentation about [configuring a web server for Symfony][3]).

On your local machine, you can run this command to use the built-in PHP web server:

```bash
$ cd my_project/
$ php -S localhost:8000 -t public/
```

Tests
-----

Execute this command to run tests:

```bash
$ cd my_project/
$ ./bin/phpunit
```

[1]: https://symfony.com/doc/current/best_practices.html
[2]: https://symfony.com/doc/current/setup.html#technical-requirements
[3]: https://symfony.com/doc/current/setup/web_server_configuration.html
[4]: https://symfony.com/download
[5]: https://symfony.com/book
[6]: https://getcomposer.org/
