<?php

namespace QQ\Bitrix\Generators\Console;

use QQ\Bitrix\Generators\Entity\Template;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Filesystem\Filesystem;

class CreateTemplateCommand extends Command
{
    protected static $defaultName = 'create:template';

    protected function configure()
    {
        $this
            ->setDescription('Creates a new bitrix template.')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Template name (eg. general)'
            )
            ->addArgument(
                'path',
                InputArgument::OPTIONAL,
                'Path to put module. Default is current directory',
                getcwd()
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $template = new Template($input->getArgument('name'));
        $path = $input->getArgument('path');

        $templatePath = $path.DIRECTORY_SEPARATOR.$template->getDefaultName();

        $filesystem = new Filesystem();
        if (is_dir($templatePath)) {
            $questionHelper = $this->getHelper('question');
            $shouldOverwrite = new ConfirmationQuestion(
                'Directory '.$templatePath.' already exist. Continue? [yes or no]',
                false,
                '/yes/i'
            );

            if (!$questionHelper->ask($input, $output, $shouldOverwrite)) {
                $output->writeln('<error>Directory '.$templatePath.' exists. Exit.</error>');
                return 0;
            }

            $filesystem->remove($templatePath);
            $output->writeln('<info>Directory '.$templatePath.' cleared</info>');
        }

        $output->writeln(
            [
                '<info>Start to create template '.$template->getDefaultName().'</info>',
            ]
        );

        if (!is_dir($path) || !is_writable($path)) {
            throw new RuntimeException($path.' is not directory or is not writable');
        }

        $template->compile($templatePath);

        $output->writeLn('<info>Template '.$template->getDefaultName().' created!</info>');

        return 0;
    }
}
