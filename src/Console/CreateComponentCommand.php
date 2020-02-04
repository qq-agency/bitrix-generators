<?php

namespace QQ\Bitrix\Generators\Console;

use QQ\Bitrix\Generators\Entity\SimpleComponent;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Filesystem\Filesystem;

class CreateComponentCommand extends Command
{
    protected static $defaultName = 'create:component';

    protected function configure()
    {
        $this
            ->setDescription('Creates a new bitrix component.')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Module name with partner prefix (eg. qq.component)'
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
        $component = new SimpleComponent($input->getArgument('name'), $input->getOption('lang'));
        $path = $input->getArgument('path');

        $componentPath = $path.DIRECTORY_SEPARATOR.
            $component->getVendorCode().DIRECTORY_SEPARATOR.
            $component->getDefaultName();

        $filesystem = new Filesystem();
        if (is_dir($componentPath)) {
            $questionHelper = $this->getHelper('question');
            $shouldOverwrite = new ConfirmationQuestion(
                'Directory '.$componentPath.' already exist. Continue? [yes or no]',
                false,
                '/yes/i'
            );

            if (!$questionHelper->ask($input, $output, $shouldOverwrite)) {
                $output->writeln('<error>Directory '.$componentPath.' exists. Exit.</error>');
                return 0;
            }

            $filesystem->remove($componentPath);
            $output->writeln('<info>Directory '.$componentPath.' cleared</info>');
        }

        $output->writeln(
            [
                '<info>Start to create component '.$component->getFullName().'</info>',
            ]
        );

        if (!is_dir($path) || !is_writable($path)) {
            throw new RuntimeException($path.' is not directory or is not writable');
        }

        $component->compile($componentPath);

        $output->writeLn('<info>Component '.$component->getFullName().' created!</info>');

        return 0;
    }
}
