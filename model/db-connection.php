

<?php
// Ex 8
require_once "../env.php";
/**
 * Crea una nova connexió amb la base de dades
 *
 * @return PDO objecte PDO amb la connexió
 */
function getConnection()
{
    try {
        return new PDO(sprintf("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME), DB_USERNAME, DB_PASSWORD);
    } catch (PDOException $e) {
        die("No es pot establir connexió amb la base de dades");
    }
}
?>