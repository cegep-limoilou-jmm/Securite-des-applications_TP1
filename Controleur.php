<?php
require 'Modele/modelAccessBD.php';

function accueil()
{
    require 'Vue/vueAccueil.php';
}

function nouvelUtilisateur($user, $mdp)
{
    $ajout_user = true;
    $ok = ajouterUtilisateur($user, $mdp);
    if ($ok)
        require 'Vue/vueAccueil.php';
    else {
        $_POST["sub_connexion"] = "Login failed";
        $erreur = true;
        $err_msg = "Erreur ajout utilisateur";
        accueil();
    }
}

function journal($user, $mdp)
{
    $ajout_user = false;

    $ok = connection($user, $mdp);
    $block_delay = verifyAttempt($ok, $user);
    $actual_time = date('Y-m-d H:i:s');
    $lock = $actual_time <= $block_delay;
    if ($ok && !$lock)
        require 'Vue/vueJournal.php';
    else {
        $_POST["sub_connexion"] = "Login failed";
        $erreur = true;

        if ($lock)
            $err_msg = "Locked during : " . (strtotime($block_delay) - strtotime($actual_time)) . ' seconds';
        else
            $err_msg = "Login failed";

        require 'Vue/vueAccueil.php';
    }
}

function erreur($msgErreur)
{
    require 'Vue/vueErreur.php';
}

