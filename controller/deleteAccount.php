
<?php

require_once '../model/pdo-users.php';
require_once '../controller/session.php';
// Ex 6
session_start();
$userId = getSessionUserId();
if ($userId == 0) {
    header('Location: login.php');
    return;
}

$password1 = "";
$password2 = "";
$errores = array();

//Comprova si l'usuari ha enviat el formulari
if (isset($_POST['submit'])) {
    


$password = $_POST["password1"];
$password2 = $_POST["password2"];


//Comprovem les dades introduides



if($password == ""){
    $errores[] = "La contraseña es requerida";
}
if($password2 == ""){
    $errores[] = "La segunda contraseña es requerida";
}


if ($password != $password2){
    $errores[] = "Las contraseñas no coinciden";
}


//Si el login es correcte, es crea una sessio i es redirigeix a la pagina webLogada.php


if (isset($_POST['submit']) && empty($errores)) {
$ferLogin = checkPass($password, $userId);
if ($ferLogin != "Correcto"){
    borrarUsuari($userId);
    header("Location: index.php");
}
}
};
include "../view/deleteAccount.view.php";
?>
