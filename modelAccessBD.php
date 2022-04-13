<?php
// Auteur : 2017-10-01 Anne-Claire Tassel - David Bellemare
// Modifie : 2018-11-16 Dominique
// Étudiant : 2022-04-13 Pierre-Paul Thibault-Messervier
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
        $bd = new PDO('mysql:host=localhost;dbname=tp1;charset=utf8', 'root', '665');
    } catch (Exception $e) {
        echo ('Erreur : ' . $e->getMessage());
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

function verifyAttempt()
{
    $req = readLoginAttempt();
    $rep = $req->fetch();

    $block_delay = $rep["block_delay"];
    $attempts = $rep["attempts"];
    if ($attempts >= 3)
        $attempts = 0;

    if ($rep && $block_delay <= new DateTime()) {
        if (($attempts + 1) >= 3 )
            $block_delay.add(new DateInterval('PT15S'));
        updateAttempt(block_delay, $attempts + 1);
        $return = true;
    } else if (!$rep) {
        $addAttempt();
        $return = true;
    } else {
        $return = false;
    }

    return $return;
}

function readLoginAttempt()
{
    $bdd = new PDO('mysql:host=localhost;dbname=tp1;charset=utf8', 'root', '');

    $req = $bdd->prepare('SELECT * FROM login_attempts WHERE ip = ?;');
    $retour = $req->execute(array(
        $_SERVER['REMOTE_ADDR']
    ));
    $req->closeCursor();

    return $retour;
}

function addAttempt()
{
    $bdd = new PDO('mysql:host=localhost;dbname=tp1;charset=utf8', 'root', '');
    $block_delay = new DateTime();

    $req = $bdd->prepare('INSERT INTO login_attempts(ip, block_delay, attempts) VALUES (?, ?, 1);');
    $retour = $req->execute(array(
        $_SERVER['REMOTE_ADDR'],
        $block_delay
    ));
    $req->closeCursor();

    return $retour;
}

function updateAttempt($block_delay, $attempts)
{
    $bdd = new PDO('mysql:host=localhost;dbname=tp1;charset=utf8', 'root', '');

    $req = $bdd->prepare('UPDATE login_attempts SET block_delay = ?, attempts = ? WHERE ip = ?;');
    $retour = $req->execute(array(
        $block_delay,
        $attempts,
        $_SERVER['REMOTE_ADDR']
    ));
    $req->closeCursor();

    return $retour;
}
?>

