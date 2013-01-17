## Welcome to the DoctrineMigrationsModule for Zend Framework 2!

DoctrineMigrationsModule add migration commands to DoctrineModule Cli!

Version: 1.0.0

## Istallation

### Composer

1. Add `"murganikolay/doctrine-migrations-module": "1.0.0"` to your `composer.json` file and run php composer.phar update.
2. Add DoctrineMigrationsModule to your `config/application.config.php` file under the modules key.

If you use default `minimum-stability` ( default `minimum-stability: stable`) you need add modify root composer.json
and add `"doctrine/migrations": "v1.0-ALPHA1"` to `require` section.

### Manual

Not support!

### Configuration

Change you Application config like this:

    return array(
        ...
        'doctrine' => array(
            'migrations' => array(
                'migrations_table' => 'migrations',
                'migrations_namespace' => 'Application',
                'migrations_directory' => 'data/migrations',
            ),
        ),
        ...
    );
