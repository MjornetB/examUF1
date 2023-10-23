
<?php

require_once 'db-connection.php';


/**
 * Consulta l'existència d'un usuari mitjançant un email
 *
 * @param string $email email de l'usuari a consultar
 * 
 * @return boolean si existeix o no
 */
function userExistsByEmail($email)
{
    try {
        $connexio = getConnection();

        $statement = $connexio->prepare('SELECT email FROM users WHERE email = :email');

        $statement->bindValue(':email', $email);

        $statement->execute();

        $count = count($statement->fetchAll());

        return ($count > 0);
    } catch (PDOException $e) {
        die("No es pot establir connexió amb la base de dades");
    }
}

/**
 * Consulta l'existència d'un usuari mitjançant un nickname
 *
 * @param string $nickname nickname de l'usuari a consultar
 * 
 * @return boolean si existeix o no
 */
function userExistsByNickname($nickname)
{
    try {
        $connexio = getConnection();

        $statement = $connexio->prepare('SELECT email FROM users WHERE nickname = :nickname');

        $statement->bindValue(':nickname', $nickname);

        $statement->execute();

        $count = count($statement->fetchAll());

        return ($count > 0);
    } catch (PDOException $e) {
        die("No es pot establir connexió amb la base de dades");
    }
}

/**
 * Obté el hash MD5 del password d'un usuari
 *
 * @param string $email email de l'usuari a consultar
 * 
 * @return string hash MD5
 */
//function getUserHash($email)
function getUserHash($email, $password)
{
    try {
        $connexio = getConnection();

        $statement = $connexio->prepare('SELECT password FROM users WHERE email = :email');

        $statement->bindValue(':email', $email);

        $statement->execute();

        $result = $statement->fetch();
        $resultat = $statement->fetch(PDO::FETCH_ASSOC);

        //return $result['password'];
        // Si hi han resultats
            if (password_verify($password, $result['password'])) {
                return "Correcto";
            };
    } catch (PDOException $e) {
        die("No es pot establir connexió amb la base de dades");
    }
}

/**
 * Obté el ID d'un usuari, donat un email
 *
 * @param string $email email de l'usuari a consultar
 * 
 * @return string ID de l'usuari
 */
function getUserId($email)
{
    try {
        $connexio = getConnection();

        $statement = $connexio->prepare('SELECT id FROM users WHERE email = :email');

        $statement->bindValue(':email', $email);

        $statement->execute();

        $result = $statement->fetch();

        return $result['id'];
    } catch (PDOException $e) {
        die("No es pot establir connexió amb la base de dades");
    }
}

/**
 * Obté el ID d'un usuari, donat un ID
 *
 * @param int $userId ID de l'usuari a consultar
 * 
 * @return string ID de l'usuari
 */
function getUserNicknameById($userId)
{
    try {
        $connexio = getConnection();

        $statement = $connexio->prepare('SELECT nickname FROM users WHERE id = :userId');

        $statement->bindParam('userId', $userId, PDO::PARAM_INT);

        $statement->execute();

        $result = $statement->fetch();

        return $result['nickname'];
    } catch (PDOException $e) {
        die("No es pot establir connexió amb la base de dades");
    }
}

/**
 * Obté el ID d'un usuari mitjançant el seu token de recuperació
 *
 * @param string $resetToken token de recuperació de contrasenya
 * 
 * @return int ID de l'usuari
 */
function getUserIdByResetToken($resetToken)
{
    try {
        $connexio = getConnection();

        $statement = $connexio->prepare('SELECT id FROM users WHERE reset_token = ?');

        $statement->execute([$resetToken]);

        $result = $statement->fetch();

        return $result['id'];
    } catch (PDOException $e) {
        die("No es pot establir connexió amb la base de dades");
    }
}

/**
 * Obté el ID d'un usuari mitjançant el seu token rememberme.
 * Serveix per mantindre la sessió de l'usuari encara que tanqui el navegador
 *
 * @param string $resetToken token rememberme
 * 
 * @return int ID de l'usuari
 */
function getUserIdByRememberMeToken($rememberMeToken)
{
    try {
        $connexio = getConnection();

        $statement = $connexio->prepare('SELECT id FROM users WHERE remember_me_token = ?');

        $statement->execute([$rememberMeToken]);

        $result = $statement->fetch();

        return $result['id'];
    } catch (PDOException $e) {
        die("No es pot establir connexió amb la base de dades");
    }
}

/**
 * Registra un nou usuari
 *
 * @param string $email email del nou usuari
 * @param string $nickname nickname del nou usuari
 * @param string $passwordEncriptat hash del password del nou usuari
 * 
 */
//function insertNewUser($email, $nickname, $md5Hash) Ex 11 canviem els noms perque sigue entendible
function insertNewUser($email, $nickname, $passwordEncriptat)
{
    try {
        $connexio = getConnection();
        $statement = $connexio->prepare('INSERT INTO users (email, nickname, password) VALUES (:email, :nickname, :pass)');
        $statement->execute([
            'email' => $email,
            'nickname' => $nickname,
            //'pass' => $md5Hash
            'pass' => $passwordEncriptat
        ]);
    } catch (PDOException $e) {
        die("No es pot establir connexió amb la base de dades");
    }
}

/**
 * Registra un nou usuari utilitzant social authentication
 *
 * @param string $email email del nou usuari
 * @param string $nickname nickname del nou usuari
 * @param string $socialProvider nom del social provider ("GitHub", "Twitter", "Google")
 * 
 * @return string l'id de l'usuari inserit
 */
function insertNewSocialUser($email, $nickname, $socialProvider)
{
    try {
        $connexio = getConnection();
        $statement = $connexio->prepare('
        INSERT INTO users (email, nickname, social_provider)
        VALUES (:email, :nickname, :socialProvider)');
        $statement->execute([
            'email' => $email,
            'nickname' => $nickname,
            'socialProvider' => $socialProvider
        ]);
        return $connexio->lastInsertId();
    } catch (PDOException $e) {
        die("No es pot establir connexió amb la base de dades");
    }
}

/**
 * Estableix un hash pel password d'un usuari
 *
 * @param int $userId ID de l'usuari
 * @param mixed $md5Hash hash del password
 * 
 */
function setUserHash($userId, $md5Hash)
{
    try {
        $connexio = getConnection();

        $statement = $connexio->prepare('UPDATE users SET password = :md5Hash WHERE id = :userId');

        $statement->bindParam('md5Hash', $md5Hash);
        $statement->bindParam('userId', $userId, PDO::PARAM_INT);

        $statement->execute();
    } catch (PDOException $e) {
        die("No es pot establir connexió amb la base de dades");
    }
}

/**
 * Estableix un token de recuperació a un usuari
 *
 * @param int $userId ID de l'usuari
 * @param string $resetToken reset token
 * 
 */
function setResetToken($userId, $resetToken)
{
    try {
        $connexio = getConnection();

        $statement = $connexio->prepare('UPDATE users SET reset_token = :resetToken WHERE id = :userId');

        $statement->bindParam('resetToken', $resetToken);
        $statement->bindParam('userId', $userId, PDO::PARAM_INT);

        $statement->execute();
    } catch (PDOException $e) {
        die("No es pot establir connexió amb la base de dades");
    }
}

/**
 * Estableix un token de remember me
 * Serveix per mantindre la sessió de l'usuari encara que tanqui el navegador
 *
 * @param int $userId ID de l'usuari
 * @param string $resetToken remember me token
 * 
 */
function setRememberMeToken($userId, $rememberMeToken)
{
    try {
        $connexio = getConnection();

        $statement = $connexio->prepare('UPDATE users SET remember_me_token = :rememberMeToken WHERE id = :userId');

        $statement->bindParam('rememberMeToken', $rememberMeToken);
        $statement->bindParam('userId', $userId, PDO::PARAM_INT);

        $statement->execute();
    } catch (PDOException $e) {
        die("No es pot establir connexió amb la base de dades");
    }
}

function borrarUsuari($userId){ //Ex 6
    $connexio = getConnection();
    $stmt = $connexio->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bindParam(1, $userId);
    $stmt->execute();
}

function checkPass($password, $userId){ //Ex 6
    try {
        $connexio = getConnection();
        $stmt = $connexio->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bindParam(1, $userId);
        $stmt->execute();
        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($resultat !== false) { // Si hi han resultats
            if (password_verify($password, $resultat['password'])) {
                return "Correcto";
            } else {
                return "Login incorrecto";
            }
        } else {
            return "Usuario no encontrado";
        }
    } catch (PDOException $e) {
        return "Error al realizar el login: " . $e->getMessage();
    }
  }

  function realitzarLogin($email, $password){ //ex 11
    try {
        $connexio = getConnection();
        $stmt = $connexio->prepare("SELECT password FROM users WHERE email = ?");
        $stmt->bindParam(1, $email);
        $stmt->execute();
        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($resultat !== false) { // Si hi han resultats
            if (password_verify($password, $resultat['password'])) {
                return "Correcto";
            } else {
                return "Login incorrecto";
            }
        } else {
            return "Usuario no encontrado";
        }
    } catch (PDOException $e) {
        return "Error al realizar el login: " . $e->getMessage();
    }
  }

/**
 * Neteja el token de recuperació de contrasenya d'un usuari
 *
 * @param int $userId user ID de l'usuari
 * 
 */
function clearResetToken($userId)
{
    setResetToken($userId, "");
}

