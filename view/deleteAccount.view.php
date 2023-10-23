<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<!-- Ex 6 -->
<body>
    <h1>Desea borrar la cuenta? En caso afirmativo introduzca su password dos veces y click en confirmar</h1>
    <form action="../controller/deleteAccount.php" method="post" id="form">
        <label>Password: </label>
        <input type="password1" name="password1" placeholder="Password">
        <label>Repita su password: </label>
        <input type="password2" name="password2" placeholder="Password2">
        <input type="submit" name="submit" value="Elimina la cuenta"></input>
    </form>

    <span>
        <?php
        if (isset($_POST['submit']) && !empty($errores)) {
            echo "<div class='alert alert-danger'>";
            foreach ($errores as $error) {
                echo "<li>$error</li>";
            }
            echo "</div>";
        }
        ?>
    </span>
</body>

</html>