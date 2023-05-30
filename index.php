<?php
require_once "./vendor/autoload.php";

$matrix = [
    [4.1, 1.3, 4.5, 10.4],
    [34.3, 12.6, 11.0, 5],
    [2.34, 5.0, 1.1, 12.0],
];

$matrix_1 = [
    [2, 2, 3],
    [4, 4, 6],
    [6, 6, 9]
]; // rank - 1

$matrix_2 = [
    [-1, 1, -1, -2, 0],
    [2, 2, 6, 0, -4],
    [4, 3, 11, 1, -7]
]; // rank - 2

$matrix_3 = [
    [1, -2, 1],
    [-2, 1, 1],
    [1, 1, 2]
]; // rank - 3

$Matrix = new \Perfluence\Matrix($matrix);
echo "matrix getRank() => " . $Matrix->getRank() . "\n\n";

$Matrix->setMatrix($matrix_1);
echo "matrix_1 getRank() => " . $Matrix->getRank() . "\n\n";

$Matrix->setMatrix($matrix_2);
echo "matrix_2 getRank() => " . $Matrix->getRank() . "\n\n";

$Matrix->setMatrix($matrix_3);
echo "matrix_3 getRank() => " . $Matrix->getRank() . "\n\n";

echo "===============================\n\n";

$Session = new \Perfluence\Session();
$Session->getData("2023-05-25");