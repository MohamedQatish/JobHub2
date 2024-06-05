<?php
    require_once 'include/connector.inc.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="include/calc.inc.php" method="post">
        <input type="number" name="num1" placeholder="first number">
        <select name="op" id="">
            <option value="+">+</option>
            <option value="-">-</option>
            <option value="*">*</option>
            <option value="/">/</option>
        </select>
        <input type="number" name="num2" placeholder="second number"><br>
        <input type="submit" name="calc" value="calculate">
    </form>

</body>
</html>