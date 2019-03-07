<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class MatrixCommand
 * @package App\Command
 */
class MatrixCommand extends Command
{
    protected static $defaultName = 'app:matrix';

    const ARGUMENT_X = 'x';
    const ARGUMENT_Y = 'y';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Creates a matrix by provided arguments. And then rotates it')
            ->addArgument(self::ARGUMENT_X, InputArgument::REQUIRED, 'Argument description')
            ->addArgument(self::ARGUMENT_Y, InputArgument::REQUIRED, 'Argument description');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $x = $input->getArgument(self::ARGUMENT_X);
        $y = $input->getArgument(self::ARGUMENT_Y);

        $this->validateData($x, $y);

        // Convert arguments to integer
        $x = (int)$x;
        $y = (int)$y;

        $rows = $this->buildRows($x, $y);

        // Writes matrix
        $io->table(
            [], $rows
        );

        $io->writeln(['Rotated data']);

        // Writes rotated matrix
        $io->table(
            [], $this->rotateRows($rows)
        );
    }

    /**
     * Checks commend's arguments
     *
     * @param $x
     * @param $y
     */
    private function validateData($x, $y)
    {
        $this->isArgumentValid($x);
        $this->isArgumentValid($y);
    }

    /**
     * Checks if value is integer
     * @param $value
     * @return bool
     *
     * @throws \LogicException
     */
    private function isArgumentValid($value): bool
    {
        if (!filter_var($value, FILTER_VALIDATE_INT)) {
            throw new \LogicException('One of provided arguments is not valid.');
        }

        if ($value > 1) {
            return true;
        }

        throw new \LogicException('One of provided arguments less than 2');
    }

    /**
     * Builds rows data
     *
     * @param int $x
     * @param int $y
     * @return array
     */
    private function buildRows(int $x, int $y): array
    {
        $rows = [];
        for ($i = 1; $x >= $i; $i++) {
            $rows[] = $this->buildRow($y);
        }

        return $rows;
    }

    /**
     * Builds row data
     *
     * @param int $y
     * @return array
     */
    private function buildRow(int $y): array
    {
        $rowData = [];
        for ($i = 1; $y >= $i; $i++) {
            $rowData[] = random_int(0, 1000);
        }

        return $rowData;
    }

    /**
     * Rotates matrix on 90%
     *
     * @param array $oldRows
     *
     * @return array
     */
    private function rotateRows(array $oldRows): array
    {
        $rows = [];
        $rowsAmount = \count($oldRows);
        $tdAmount = \count($oldRows[0]);
        for ($i = 0; $i < $rowsAmount; $i++) {
            $row = [];
            for ($j = $tdAmount - 1; $j >= 0; $j--) {
                $row[] = $oldRows[$j][$i];
            }

            $rows[] = $row;
        }

        return $rows;
    }
}
