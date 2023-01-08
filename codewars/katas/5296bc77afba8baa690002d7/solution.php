<?php

class PuzzleCell {
    public static array $allPossibleValues = [1, 2, 3, 4, 5, 6, 7, 8, 9];

    public int $value;

    /**
     * @var int[]
     */
    public array $possibleValues;

    public function __construct($value)
    {
        $this->value = $value;

        $this->possibleValues = [];
    }
}


class Puzzle {
    /**
     * @var PuzzleCell[][]
     */
    public array $puzzle;

    public function __construct(array $twoDimensionalArray)
    {
        $this->puzzle = array_map(
            function ($puzzleRow) {
                return array_map(
                    fn($puzzleCellValue) => new PuzzleCell($puzzleCellValue),
                    $puzzleRow,
                );
            },
            $twoDimensionalArray,
        );

        $this->refreshPossibleValuesForAllCells();
    }

    public function __toString()
    {
        $puzzleAsString = '';

        foreach ($this->puzzle as $puzzleRow) {
            foreach ($puzzleRow as $cell) {
                $puzzleAsString .= "$cell->value [" . join(',', $cell->possibleValues) . "]\t\t\t";
            }
            $puzzleAsString .= "\n";
        }

        return $puzzleAsString;
    }

    private function getPossibleValuesInARow(int $targetRowIndex): array
    {
        $possibleValues = PuzzleCell::$allPossibleValues;

        foreach ($this->puzzle[$targetRowIndex] as $cell) {
            $possibleValues = array_filter(
                $possibleValues,
                fn($possibleValue) => $possibleValue !== $cell->value,
            );
        }

        return $possibleValues;
    }

    private function getPossibleValuesInAColumn(int $targetColumnIndex): array
    {
        $possibleValues = PuzzleCell::$allPossibleValues;

        foreach ($this->puzzle as $row) {
            $possibleValues = array_filter(
                $possibleValues,
                fn($possibleValue) => $possibleValue !== $row[$targetColumnIndex]->value,
            );
        }

        return $possibleValues;
    }

    private function getPossibleValuesInASquare(int $rowIndex, int $columnIndex): array
    {
        $possibleValues = PuzzleCell::$allPossibleValues;

        $squareRowIndices = static::getSquareRangeForIndex($rowIndex);
        $squareColumnIndices = static::getSquareRangeForIndex($columnIndex);

        foreach ($this->puzzle as $rowIndex => $puzzleRow) {
            if (!in_array($rowIndex, $squareRowIndices)) {
                continue;
            }
            foreach ($puzzleRow as $columnIndex => $cell) {
                if (!in_array($columnIndex, $squareColumnIndices)) {
                    continue;
                }
                $possibleValues = array_filter(
                    $possibleValues,
                    fn($possibleValue) => $possibleValue !== $cell->value,
                );
            }
        }

        return $possibleValues;
    }

    private static function getSquareRangeForIndex(int $index): array
    {
        if (in_array($index, [0, 1, 2])) {
            return [0, 1, 2];
        } elseif (in_array($index, [3, 4, 5])) {
            return [3, 4, 5];
        } else {
            return [6, 7, 8];
        }
    }

    private function refreshPossibleValuesForAllCells()
    {
        foreach ($this->puzzle as $rowIndex => $puzzleRow) {
            foreach ($puzzleRow as $columnIndex => $cell) {
                if ($cell->value) {
                    continue;
                }
                $rowPossibleValues = $this->getPossibleValuesInARow($rowIndex);
                $columnPossibleValues =  $this->getPossibleValuesInAColumn($columnIndex);
                $squarePossibleValues = $this->getPossibleValuesInASquare($rowIndex, $columnIndex);
                $possibleValues = array_intersect(
                    $rowPossibleValues,
                    $columnPossibleValues,
                    $squarePossibleValues,
                );
                $cell->possibleValues = $possibleValues;
            }
        }
    }

    public function toArray(): array
    {
        return array_map(
            function ($puzzleRow) {
                return array_map(
                    fn($puzzleCell) => $puzzleCell->value,
                    $puzzleRow,
                );
            },
            $this->puzzle,
        );
    }

    function solve(): void
    {
        do {
            $wasACellFilled = false;
            foreach ($this->puzzle as $puzzleRow) {
                foreach ($puzzleRow as $cell) {
                    if ($cell->value === 0 && count($cell->possibleValues) === 1) {
                        $cell->value = array_values($cell->possibleValues)[0];
                        $cell->possibleValues = [];
                        $wasACellFilled = true;
                    }
                }
            }
            $this->refreshPossibleValuesForAllCells();
        } while ($wasACellFilled);
    }
}

function sudoku(array $puzzle): array
{
    $puzzleObject = new Puzzle($puzzle);
    $puzzleObject->solve();

    return $puzzleObject->toArray();
}
