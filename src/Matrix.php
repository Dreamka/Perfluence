<?php

namespace Perfluence;

class Matrix
{
    protected $matrix;
    public function __construct($matrix)
    {
        $this->setMatrix($matrix);
    }

    public function setMatrix($matrix) {
        $this->matrix = $matrix;
    }

    protected function swap(&$matrix, $row1, $row2, $col) {
        for ($i = 0; $i < $col; $i++)
        {
            $temp = $matrix[$row1][$i];
            $matrix[$row1][$i] = $matrix[$row2][$i];
            $matrix[$row2][$i] = $temp;
        }
    }

    public function getRank() {
        $R = count($this->matrix);
        $C = count($this->matrix[0]) - 1;
        $rank = $C;

        $row = 0;
        while ($row < $rank) {
            if ($this->matrix[$row][$row]) {
                foreach ( $this->matrix as $col => $el ) {
                    if ($col != $row) {
                        $mult = $this->matrix[$col][$row] / $this->matrix[$row][$row];
                        foreach ( $this->matrix[0] as $i => $matrix_element ) {
                            $this->matrix[$col][$i] -= $mult * $this->matrix[$row][$i];
                        }
                    }
                }
            } else {
                $reduce = true;
                $i = $row + 1;
                while ($i < $R) {
                    if ($this->matrix[$i][$row]) {
                        $this->swap($this->matrix, $row, $i, $rank);
                        $reduce = false;
                        break ;
                    }
                    $i++;
                }

                if ($reduce) {
                    $rank--;
                    foreach ($this->matrix as $indx => $r) {
                        $this->matrix[$indx][$row] = $this->matrix[$indx][$rank];
                    }
                }

                $row--;
            }

            $row++;
        }
        return $rank;
    }
}