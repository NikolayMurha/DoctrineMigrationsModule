<?php

namespace DoctrineMigrationsModule;

use
    \Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand,
    \Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand,
    \Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand,
    \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand,
    \Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand,
    \Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand,
    \Zend\EventManager\EventInterface;
use Doctrine\ORM\EntityManager;


class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function onBootstrap(EventInterface $e)
    {
        $application = $e->getTarget();
        $events = $application->getEventManager()->getSharedManager();
        $configuration = $application->getServiceManager()->get('doctrine.migrations.configuration');
        // Attach to helper set event and load the entity manager helper.
        $events->attach('doctrine', 'loadCli.post', function (EventInterface $e) {
            $sm = $e->getParam('ServiceManager');

            /* @var $cli \Symfony\Component\Console\Application */
            $cli = $e->getTarget();
            $commands = array(
                new DiffCommand(),
                new ExecuteCommand(),
                new GenerateCommand(),
                new MigrateCommand(),
                new StatusCommand(),
                new VersionCommand(),
            );

            $configuration = $sm->get('doctrine.migrations.configuration');
            foreach($commands AS $command) {
                $command->setMigrationConfiguration($configuration);
            }
            $cli->addCommands($commands);

            /** @var EntityManager $em */
            $em = $sm->get('doctrine.entitymanager.orm_default');
            $hs = $cli->getHelperSet();
            $hs->set(new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em), 'em');
            $hs->set(new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()), 'db');
            $hs->set(new \Symfony\Component\Console\Helper\DialogHelper(), 'dialog');
        });
    }
}
