<?php
ini_set('display_errors', true);

chdir(dirname(__DIR__ . '/../../../...'));
require_once 'vendor/ZendFramework/library/Zend/Loader/AutoloaderFactory.php';
Zend\Loader\AutoloaderFactory::factory(array('Zend\Loader\StandardAutoloader' => array()));

$appConfig = include 'config/application.config.php';

$moduleManager    = new Zend\Module\Manager($appConfig['modules']);
$listenerOptions  = new Zend\Module\Listener\ListenerOptions($appConfig['module_listener_options']);
$defaultListeners = new Zend\Module\Listener\DefaultListenerAggregate($listenerOptions);

$defaultListeners->getConfigListener()->addConfigGlobPath('config/autoload/*.config.php');
$moduleManager->events()->attachAggregate($defaultListeners);
$moduleManager->loadModules();

// Create application, bootstrap, and run
$bootstrap   = new Zend\Mvc\Bootstrap($defaultListeners->getConfigListener()->getMergedConfig());
$application = new Zend\Mvc\Application;
$bootstrap->bootstrap($application);
$locator = $application->getLocator();

$cli = new \Symfony\Component\Console\Application(
    'DoctrineModule Command Line Interface',
    \DoctrineModule\Version::VERSION
);
$cli->setCatchExceptions(true);

$helpers = array();

if (class_exists('Doctrine\DBAL\Migrations\Version')) {
    $em = $locator->get('doctrine_em');
    $db = $em->getConnection();

    $helpers['em'] = new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em);
    $helpers['db'] = new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($db);
    $helpers['dialog'] = new \Symfony\Component\Console\Helper\DialogHelper();

    $commands = array(
        // Migrations Commands
        new \Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand(),
        new \Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand(),
        new \Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand(),
        new \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand(),
        new \Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand(),
        new \Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand()
    );

    $cli->addCommands($commands);
    /**
     * @var Doctrine\DBAL\Migrations\Tools\Console\Command\AbstractCommand $command
     * @var DoctrineMigrationsModule\Configuration $configuration
     */
    $configuration = $locator->get('dbal_migrations_config');
    foreach($commands AS $command) {
        $command->setMigrationConfiguration($configuration);
    }
}

$helperSet = isset($helperSet) ? $helperSet : new \Symfony\Component\Console\Helper\HelperSet();
foreach ($helpers as $name => $helper) {
    $helperSet->set($helper, $name);
}
$cli->setHelperSet($helperSet);

$cli->run();