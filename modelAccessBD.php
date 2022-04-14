<?php
// Auteur : 2017-10-01 Anne-Claire Tassel - David Bellemare
// Modifie : 2018-11-16 Dominique

function ajouterUtilisateur($user, $mdp)
{
    $bdd = new PDO('mysql:host=localhost;dbname=tp1;charset=utf8', 'root', '');

    $req = $bdd->prepare('INSERT INTO utilisateur(id, nom, motpasse) VALUES (null, ?, ?);');
    $retour = $req->execute(array(
        $user,
        $mdp
    ));
    $req->closeCursor();

    return $retour;
}

function connection($user, $mdp)
{
    try {
        $bd = new PDO('mysql:host=localhost;dbname=tp1;charset=utf8', 'root', '');
    } catch (Exception $e) {
        echo('Erreur : ' . $e->getMessage());
        die();
    }
    $req = $bd->prepare('select * from utilisateur where nom = ? and motpasse = ?');
    $req->execute(array(
        $user,
        $mdp
    ));
    $rep = $req->fetch();

    if ($rep) {
        $_SESSION['submit'] = "Connexion";
        $_SESSION['user'] = $user;
        $_SESSION['pass'] = $mdp;
        $return = true;
    } else {
        $return = false;
    }

    $req->closeCursor();

    return $return;
}

function verifyAttempt($ok, $user)
{
    $rep = readLoginAttempt($user);
    $block_delay = 0;

    if (isset($rep["id"])) {
        $block_delay = $rep["block_delay"];
        $attempts = $rep["attempts"];
    }

    if (!$ok)
        if (isset($rep["id"]) && date('Y-m-d H:i:s') > $block_delay) {
            $attempts++;
            if ($attempts > 3) {
                $block_delay = date('Y-m-d H:i:s', strtotime('+15 seconds', strtotime(date('Y-m-d H:i:s'))));
                $attempts = 1;
            }
            updateAttempt($user, $block_delay, $attempts);
        } else if (!isset($rep["id"]))
            addAttempt($user);

    return $block_delay;
}

function readLoginAttempt($user)
{
    $bdd = new PDO('mysql:host=localhost;dbname=tp1;charset=utf8', 'root', '');

    $req = $bdd->prepare('SELECT * FROM login_attempts WHERE user = ?;');
    $req->execute(array(
        $user
    ));
    $return = $req->fetch();
    $req->closeCursor();

    return $return;
}

function addAttempt($user)
{
    $bdd = new PDO('mysql:host=localhost;dbname=tp1;charset=utf8', 'root', '');

    $req = $bdd->prepare('INSERT INTO login_attempts(ip, user, block_delay, attempts) VALUES (?, ?, ?, 1);');
    $retour = $req->execute(array(
        $_SERVER["REMOTE_ADDR"],
        $user,
        date('Y-m-d H:i:s')
    ));
    $req->closeCursor();

    return $retour;
}

function updateAttempt($user, $block_delay, $attempts)
{
    $bdd = new PDO('mysql:host=localhost;dbname=tp1;charset=utf8', 'root', '');

    $req = $bdd->prepare('UPDATE login_attempts SET block_delay = ?, attempts = ? WHERE user = ?;');
    $retour = $req->execute(array(
        $block_delay,
        $attempts,
        $user
    ));
    $req->closeCursor();

    return $retour;
}

?>
