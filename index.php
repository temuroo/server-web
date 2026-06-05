<?php
    // Последняя цифра номера студенческого билета.
    $variant = 0;

    // Уравнение, которое нужно решить.
    $equation = 'X * 9 = 56';

    // Форматируем число: если оно целое, убираем лишние нули.
    function formatNumber(float $number): string
    {
        if ($number == (int)$number) {
            return (string)(int)$number;
        }

        return rtrim(rtrim(number_format($number, 6, '.', ''), '0'), '.');
    }

    // Решаем уравнение с одной неизвестной X.
    function solveEquation(string $equation): array
    {
        // Убираем пробелы и приводим уравнение к верхнему регистру.
        $cleanEquation = str_replace([' ', ';'], '', strtoupper($equation));

        // Разбираем уравнение на левый операнд, оператор, правый операнд и результат.
        preg_match('/^([0-9.]+|X)([+\-*\/])([0-9.]+|X)=([0-9.]+)$/', $cleanEquation, $matches);

        // Если уравнение не подходит под шаблон, возвращаем ошибку.
        if (empty($matches)) {
            return [
                'error' => 'Уравнение записано некорректно.'
            ];
        }

        // Сохраняем части уравнения в отдельные переменные.
        $leftOperand = $matches[1];
        $operator = $matches[2];
        $rightOperand = $matches[3];
        $result = (float)$matches[4];

        // Проверяем, где находится неизвестная X.
        $isXLeft = $leftOperand === 'X';
        $isXRight = $rightOperand === 'X';

        // Если X нет в уравнении, возвращаем ошибку.
        if (!$isXLeft && !$isXRight) {
            return [
                'error' => 'В уравнении не найдена переменная X.'
            ];
        }

        // Если X указана два раза, возвращаем ошибку.
        if ($isXLeft && $isXRight) {
            return [
                'error' => 'В уравнении должно быть только одно значение X.'
            ];
        }

        // Преобразуем известные числа в числовой тип.
        $leftNumber = $isXLeft ? null : (float)$leftOperand;
        $rightNumber = $isXRight ? null : (float)$rightOperand;

        // Выбираем формулу решения в зависимости от оператора.
        switch ($operator) {
            case '+':
                $x = $isXLeft ? $result - $rightNumber : $result - $leftNumber;
                break;

            case '-':
                $x = $isXLeft ? $result + $rightNumber : $leftNumber - $result;
                break;

            case '*':
                $x = $isXLeft ? $result / $rightNumber : $result / $leftNumber;
                break;

            case '/':
                $x = $isXLeft ? $result * $rightNumber : $leftNumber / $result;
                break;

            default:
                return [
                    'error' => 'Неизвестный оператор.'
                ];
        }

        // Список названий операторов.
        $operatorNames = [
            '+' => 'сложение',
            '-' => 'вычитание',
            '*' => 'умножение',
            '/' => 'деление'
        ];

        // Возвращаем результат решения.
        return [
            'operator' => $operator,
            'operatorName' => $operatorNames[$operator],
            'position' => $isXLeft ? 'X находится слева от оператора' : 'X находится справа от оператора',
            'x' => $x
        ];
    }

    // Запускаем функцию решения уравнения.
    $solution = solveEquation($equation);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Equation</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="logo-block">
        <img src="img/logo.png" alt="Логотип Московского Политеха">
    </div>

    <h1>Equation</h1>
</header>

<main>
    <section class="card">
        <h2>Решение уравнения</h2>

        <div class="result">
            <p><strong>Последняя цифра номера студенческого:</strong> <?= $variant ?></p>
            <p><strong>Уравнение:</strong> <?= htmlspecialchars($equation) ?></p>

            <?php if (isset($solution['error'])): ?>
                <p class="error"><?= htmlspecialchars($solution['error']) ?></p>
            <?php else: ?>
                <p><strong>Оператор:</strong> <?= htmlspecialchars($solution['operator']) ?> — <?= htmlspecialchars($solution['operatorName']) ?></p>
                <p><strong>Расположение неизвестной переменной:</strong> <?= htmlspecialchars($solution['position']) ?></p>
                <p><strong>Решение:</strong> X = 56 / 9</p>
                <p><strong>Ответ:</strong> X = <?= formatNumber($solution['x']) ?></p>
            <?php endif; ?>
        </div>

        <h2>Блок-схема алгоритма</h2>

        <div class="scheme">
            <img src="img/flowchart.png" alt="Блок-схема алгоритма">
        </div>
    </section>
</main>

<footer>
    <p>Задание выполнено самостоятельно. Лабораторная работа №3.</p>
</footer>

</body>
</html>