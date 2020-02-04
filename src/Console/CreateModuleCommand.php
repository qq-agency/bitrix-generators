<?php

namespace QQ\Bitrix\Generators\Console;

use QQ\Bitrix\Generators\Entity\Module;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Filesystem\Filesystem;

class CreateModuleCommand extends Command
{
    protected static $defaultName = 'create:module';

    protected function configure()
    {
        $this
            ->setDescription('Creates a new bitrix d7 module.')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Module name with partner prefix (eg. qq.module)'
            )
            ->addArgument(
                'path',
                InputArgument::OPTIONAL,
                'Path to put module. Default is current directory',
                __DIR__
            )
            ->addOption(
                'lang',
                null,
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'Space separated module language (eg. ru en)',
                ['ru']
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $module = new Module($input->getArgument('name'), $input->getOption('lang'));
        $path = $input->getArgument('path');

        $modulePath = $path.DIRECTORY_SEPARATOR.$module->getDefaultName();

        $filesystem = new Filesystem();
        if (is_dir($modulePath)) {
            $questionHelper = $this->getHelper('question');
            $shouldOverwrite = new ConfirmationQuestion(
                'Directory '.$modulePath.' already exist. Continue? [yes or no]',
                false,
                '/yes/i'
            );

            if (!$questionHelper->ask($input, $output, $shouldOverwrite)) {
                $output->writeln('<error>Directory '.$modulePath.' exists. Exit.</error>');
                return 0;
            }

            $filesystem->remove($modulePath);
            $output->writeln('<info>Directory '.$modulePath.' cleared</info>');
        }

        $output->writeln(
            [
                '<info>Start to create module '.$module->getDefaultName().'</info>',
            ]
        );

        if (!is_dir($path) || !is_writable($path)) {
            throw new RuntimeException($path.' is not directory or is not writable');
        }

        $module->compile($modulePath);

        $output->writeLn('<info>Module '.$module->getDefaultName().' created!</info>');

        return 0;
    }
}
