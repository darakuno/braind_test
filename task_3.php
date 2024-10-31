<?php

function enter_figure() {
    $params = explode(" ", trim(readline()));
    $x = (int)$params[0];
    $y = (int)$params[1];

    $image = [];
    for ($i = 0; $i < $y; $i++) {
        $row = array_map('intval', explode(" ", trim(fgets(STDIN))));
        $image[] = $row;
    }

    return [$x, $y, $image];
}
  
function calculate_min_max($x, $y, $image) {
    $min_x = PHP_INT_MAX;
    $min_y = PHP_INT_MAX;
    $max_x = -PHP_INT_MAX;
    $max_y = -PHP_INT_MAX;

    for ($i = 0; $i < $y; $i++) {
        for ($j = 0; $j < $x; $j++) {
            if ($i == 0 or $i == $y-1 or $j == 0 or $j == $x-1) {
                if ($image[$i][$j] == 1) {
                    return false; //проверка на отступ, если его нет, то неудача
                }
            }
            if ($image[$i][$j] == 1) { //Ищем мин и макс положения единиц в капче
                $min_x = min($min_x, $j);
                $max_x = max($max_x, $j);
                $min_y = min($min_y, $i);
                $max_y = max($max_y, $i);
            }
        }
    }

    if ($min_x == PHP_INT_MAX || $min_y == PHP_INT_MAX) { // Проверка, существует ли фигура из единиц
        return false;
    }
    return [$min_x, $min_y, $max_x, $max_y];
}

function is_square($x, $y, $image) {
    list($min_x, $min_y, $max_x, $max_y) = calculate_min_max($x, $y, $image);
    if ($min_x === false) {
        return false; // Неудача при проверке границ
    }

    $width = $max_x - $min_x + 1; // вычисленная широта и высота найденной фигуры из единиц
    $height = $max_y - $min_y + 1;

    if ($width != $height) { // Если не равны, это не квадрат, а что-то произвольное
        return false; 
    }

    for ($i = $min_y; $i <= $max_y; $i++) { // Проверка, что все пиксели внутри найденного квадрата равны 1
        for ($j = $min_x; $j <= $max_x; $j++) {
            if ($image[$i][$j] != 1) {
                return false; 
            }
        }
    }

    return true;
}

function is_circle($x, $y, $image) {
    list($min_x, $min_y, $max_x, $max_y) = calculate_min_max($x, $y, $image);
    if ($min_x === false) {
        return false; // Неудача при проверке границ
    }

    $center_x = ($min_x + $max_x) / 2;
    $center_y = ($min_y + $max_y) / 2;
    $radius_x = ($max_x - min($min_x, $center_x)); // Полуось по X
    $radius_y = ($max_y - min($min_y, $center_y)); // Полуось по Y
    $tolerance = 0.5; // Допустимая погрешность для радиуса
    
    foreach ($image as $i => $row) { // Проверка каждой точки с единицей
        foreach ($row as $j => $value) {
            if ($value == 1) {
                // Вычисляем расстояние от точки до центра
                $distance_x = abs($j - $center_x) / $radius_x;
                $distance_y = abs($i - $center_y) / $radius_y;

                // Неудача, если точка не находится в пределах допустимой погрешности для круга
                if ($distance_x * $distance_x + $distance_y * $distance_y > 1 + $tolerance) {
                    return false; 
                }
            }
        }
    }
    return true; 
}

// function is_triangle($x, $y, $image) {
//     list($min_x, $min_y, $max_x, $max_y) = calculate_min_max($x, $y, $image);
//     if ($min_x === false) {
//         return false; // Неудача при проверке границ
//     }

//     // Сбор всех точек (единиц) в массив
//     $points = [];
//     for ($i = $min_y; $i <= $max_y; $i++) {
//         for ($j = $min_x; $j <= $max_x; $j++) {
//             if ($image[$i][$j] == 1) {
//                 $points[] = [$j, $i]; // Сохраняем координаты точки
//             }
//         }
//     }

//     // Проверка на количество точек (должно быть 3 для треугольника)
//     if (count($points) < 3) {
//         return false;
//     }

//     // Вычисляем длины сторон
//     $side_lengths = [];
//     for ($i = 0; $i < count($points); $i++) {
//         for ($j = $i + 1; $j < count($points); $j++) {
//             $length = sqrt(pow($points[$j][0] - $points[$i][0], 2) + pow($points[$j][1] - $points[$i][1], 2));
//             $side_lengths[] = $length;
//         }
//     }

//     // Находим максимальную и минимальную длины сторон
//     $max_side = max($side_lengths);
//     $min_side = min($side_lengths);

//     // Вычисляем углы треугольника
//     // Для этого используем формулу косинуса
//     function calculate_angle($a, $b, $c) {
//         return acos(($b * $b + $c * $c - $a * $a) / (2 * $b * $c)) * (180 / pi()); // Угол в градусах
//     }

//     // Углы треугольника
//     $angles = [];
//     for ($i = 0; $i < count($side_lengths); $i++) {
//         if ($i == 0) {
//             $angles[] = calculate_angle($side_lengths[0], $side_lengths[1], $side_lengths[2]);
//         } elseif ($i == 1) {
//             $angles[] = calculate_angle($side_lengths[1], $side_lengths[0], $side_lengths[2]);
//         } else {
//             $angles[] = calculate_angle($side_lengths[2], $side_lengths[0], $side_lengths[1]);
//         }
//     }

//     if (min($angles) < 10) {
//         return false; 
//     }
//     return true; 
// }



function main() {
    list($width, $height, $image) = enter_figure();

    if (is_square($width, $height, $image))
        echo "square";
    elseif (is_circle($width, $height, $image))
        echo "circle";
    // elseif (is_triangle($width, $height, $image))
    //     echo "triangle";
    else
        echo "неизвестная фигура";

    
}

main();