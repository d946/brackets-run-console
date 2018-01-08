<?php

namespace D946;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class VerifyCommand extends Command
{
    private $io;

    protected function configure()
    {
        $this->setName('verify')
            ->setDescription('Verify file')
            ->addArgument('file', InputArgument::REQUIRED, 'Path to file for verify');;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('file');
        if (!is_readable($file)) {
            $this->io->error('Файл не найден');
            return;
        }
        try {
            $brackets = new \D946\Brackets();
            $expression = file_get_contents($file);
            $brackets->load($expression);
            if ($brackets->verify()) {
                $this->io->success('Содежимое файла не нарушает правила');
            } else {
                $this->io->warning('Содежимое файла не соответствует заданым правилам');
            }
        } catch (\Exception $e) {
            $this->io->error('Исключение : ' . $e->getMessage());
        }
    }
}
