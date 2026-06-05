<?php
// Складываем два числа.
function calculatorAdd(float $a, float $b): float
{
  return $a + $b;
}

// Вычитаем второе число из первого.
function calculatorSubtract(float $a, float $b): float
{
  return $a - $b;
}

// Умножаем два числа.
function calculatorMultiply(float $a, float $b): float
{
  return $a * $b;
}

// Делим первое число на второе.
function calculatorDivide(float $a, float $b): float
{
  if ($b == 0) {
    throw new Exception('Ошибка: деление на ноль.');
  }

  return $a / $b;
}

// Возводим число в степень.
function calculatorPower(float $a, float $b): float
{
  return $a ** $b;
}

// Извлекаем квадратный корень.
function calculatorSqrt(float $a): float
{
  if ($a < 0) {
    throw new Exception('Ошибка: нельзя извлечь корень из отрицательного числа.');
  }

  return sqrt($a);
}

// Вычисляем факториал числа.
function calculatorFactorial(float $number): float
{
  if ($number < 0 || floor($number) != $number) {
    throw new Exception('Ошибка: факториал можно вычислить только для целого неотрицательного числа.');
  }

  if ($number > 170) {
    throw new Exception('Ошибка: слишком большое число для факториала.');
  }

  if ($number <= 1) {
    return 1;
  }

  return $number * calculatorFactorial($number - 1);
}

// Форматируем результат без лишних нулей.
function formatNumber(float $number): string
{
  if (is_infinite($number) || is_nan($number)) {
    return 'Ошибка вычисления';
  }

  if ($number == (int)$number) {
    return (string)(int)$number;
  }

  return rtrim(rtrim(number_format($number, 10, '.', ''), '0'), '.');
}

// Класс разбирает математическое выражение и считает результат.
class ExpressionParser
{
  private string $expression;
  private int $position = 0;

  // Сохраняем выражение без пробелов и приводим его к нижнему регистру.
  public function __construct(string $expression)
  {
    $this->expression = str_replace(' ', '', strtolower($expression));
  }

  // Запускаем разбор выражения.
  public function parse(): float
  {
    if ($this->expression === '') {
      throw new Exception('Введите выражение.');
    }

    $result = $this->parseExpression();

    if ($this->position < strlen($this->expression)) {
      throw new Exception('Ошибка: выражение записано некорректно.');
    }

    return $result;
  }

  // Обрабатываем сложение и вычитание.
  private function parseExpression(): float
  {
    $result = $this->parseTerm();

    while ($this->position < strlen($this->expression)) {
      $operator = $this->expression[$this->position];

      if ($operator !== '+' && $operator !== '-') {
        break;
      }

      $this->position++;
      $nextNumber = $this->parseTerm();

      if ($operator === '+') {
        $result = calculatorAdd($result, $nextNumber);
      } else {
        $result = calculatorSubtract($result, $nextNumber);
      }
    }

    return $result;
  }

  // Обрабатываем умножение и деление.
  private function parseTerm(): float
  {
    $result = $this->parsePower();

    while ($this->position < strlen($this->expression)) {
      $operator = $this->expression[$this->position];

      if ($operator !== '*' && $operator !== '/') {
        break;
      }

      $this->position++;
      $nextNumber = $this->parsePower();

      if ($operator === '*') {
        $result = calculatorMultiply($result, $nextNumber);
      } else {
        $result = calculatorDivide($result, $nextNumber);
      }
    }

    return $result;
  }

  // Обрабатываем возведение в степень.
  private function parsePower(): float
  {
    $result = $this->parseUnary();

    if ($this->position < strlen($this->expression) && $this->expression[$this->position] === '^') {
      $this->position++;
      $degree = $this->parsePower();
      $result = calculatorPower($result, $degree);
    }

    return $result;
  }

  // Обрабатываем унарные плюс и минус.
  private function parseUnary(): float
  {
    if ($this->position < strlen($this->expression) && $this->expression[$this->position] === '+') {
      $this->position++;
      return $this->parseUnary();
    }

    if ($this->position < strlen($this->expression) && $this->expression[$this->position] === '-') {
      $this->position++;
      return -$this->parseUnary();
    }

    return $this->parseFactorial();
  }

  // Обрабатываем факториал.
  private function parseFactorial(): float
  {
    $result = $this->parsePrimary();

    while ($this->position < strlen($this->expression) && $this->expression[$this->position] === '!') {
      $this->position++;
      $result = calculatorFactorial($result);
    }

    return $result;
  }

  // Обрабатываем числа, скобки, sqrt, pi и e.
  private function parsePrimary(): float
  {
    if ($this->startsWith('sqrt')) {
      $this->position += 4;

      if (!$this->match('(')) {
        throw new Exception('Ошибка: после sqrt должна быть открывающая скобка.');
      }

      $result = $this->parseExpression();

      if (!$this->match(')')) {
        throw new Exception('Ошибка: после аргумента sqrt должна быть закрывающая скобка.');
      }

      return calculatorSqrt($result);
    }

    if ($this->startsWith('pi')) {
      $this->position += 2;
      return pi();
    }

    if ($this->startsWith('e')) {
      $this->position++;
      return exp(1);
    }

    if ($this->match('(')) {
      $result = $this->parseExpression();

      if (!$this->match(')')) {
        throw new Exception('Ошибка: не закрыта скобка.');
      }

      return $result;
    }

    return $this->parseNumber();
  }

  // Получаем число из строки.
  private function parseNumber(): float
  {
    $start = $this->position;
    $hasDot = false;

    while ($this->position < strlen($this->expression)) {
      $char = $this->expression[$this->position];

      if ($char === '.') {
        if ($hasDot) {
          break;
        }

        $hasDot = true;
        $this->position++;
        continue;
      }

      if (!ctype_digit($char)) {
        break;
      }

      $this->position++;
    }

    if ($start === $this->position) {
      throw new Exception('Ошибка: ожидалось число.');
    }

    return (float)substr($this->expression, $start, $this->position - $start);
  }

  // Проверяем текущий символ и сдвигаем позицию.
  private function match(string $char): bool
  {
    if ($this->position < strlen($this->expression) && $this->expression[$this->position] === $char) {
      $this->position++;
      return true;
    }

    return false;
  }

  // Проверяем, начинается ли часть строки с нужного текста.
  private function startsWith(string $text): bool
  {
    return substr($this->expression, $this->position, strlen($text)) === $text;
  }
}

// Получаем выражение из адресной строки.
$expression = $_GET['expression'] ?? '';

// Переменные для результата и ошибки.
$result = '';
$error = '';

// Если выражение введено, запускаем вычисление.
if ($expression !== '') {
  try {
    $parser = new ExpressionParser($expression);
    $result = formatNumber($parser->parse());
  } catch (Exception $exception) {
    $error = $exception->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Calculator</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <div class="logo-block">
    <img src="img/logo.png" alt="Логотип Московского Политеха">
  </div>

  <h1>Calculator</h1>
</header>

<main>
  <section class="card">
    <h2>Калькулятор</h2>

    <form method="get" id="calculator-form">
      <input
        type="text"
        id="display"
        name="expression"
        value="<?= htmlspecialchars($expression) ?>"
        placeholder="Введите выражение"
        autocomplete="off"
      >

      <div class="result-block">
        <?php if ($error !== ''): ?>
          <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php elseif ($result !== ''): ?>
          <p><strong>Результат:</strong> <?= htmlspecialchars($result) ?></p>
        <?php else: ?>
          <p>Результат появится после вычисления.</p>
        <?php endif; ?>
      </div>

      <div class="buttons">
        <button type="button" onclick="clearDisplay()">C</button>
        <button type="button" onclick="appendToDisplay('(')">(</button>
        <button type="button" onclick="appendToDisplay(')')">)</button>
        <button type="button" onclick="backspace()">⌫</button>

        <button type="button" onclick="appendToDisplay('7')">7</button>
        <button type="button" onclick="appendToDisplay('8')">8</button>
        <button type="button" onclick="appendToDisplay('9')">9</button>
        <button type="button" onclick="appendToDisplay('/')">/</button>

        <button type="button" onclick="appendToDisplay('4')">4</button>
        <button type="button" onclick="appendToDisplay('5')">5</button>
        <button type="button" onclick="appendToDisplay('6')">6</button>
        <button type="button" onclick="appendToDisplay('*')">*</button>

        <button type="button" onclick="appendToDisplay('1')">1</button>
        <button type="button" onclick="appendToDisplay('2')">2</button>
        <button type="button" onclick="appendToDisplay('3')">3</button>
        <button type="button" onclick="appendToDisplay('-')">-</button>

        <button type="button" onclick="appendToDisplay('0')">0</button>
        <button type="button" onclick="appendToDisplay('.')">.</button>
        <button type="button" onclick="appendToDisplay('!')">!</button>
        <button type="button" onclick="appendToDisplay('+')">+</button>

        <button type="button" onclick="appendToDisplay('sqrt(')">√</button>
        <button type="button" onclick="appendToDisplay('^')">xʸ</button>
        <button type="button" onclick="appendToDisplay('pi')">π</button>
        <button type="button" onclick="appendToDisplay('e')">e</button>

        <button type="submit" class="equal">=</button>
      </div>
    </form>

    <div class="help">
      <p><strong>Примеры:</strong></p>
      <p>2+3*4</p>
      <p>(10-3)*2</p>
      <p>sqrt(25)</p>
      <p>5!</p>
      <p>2^3</p>
      <p>pi*2</p>
    </div>
  </section>
</main>

<footer>
  <p>Задание выполнено самостоятельно. Лабораторная работа №4.</p>
</footer>

<script>
  const display = document.getElementById('display');

  function appendToDisplay(value) {
    display.value += value;
    display.focus();
  }

  function clearDisplay() {
    display.value = '';
    display.focus();
  }

  function backspace() {
    display.value = display.value.slice(0, -1);
    display.focus();
  }
</script>

</body>
</html>