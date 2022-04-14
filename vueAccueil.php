<? php// Auteur : 2017-10-01 Anne-Claire Tassel - David Bellemare?>
<? php// Modifie : 2018-11-16 Dominique?>

<?php $titre = 'Login'; ?>

<?php ob_start() ?>
<body>
<form action="index.php" method="post">
    <table>
        <tr>
            <td><label for="identifiant">Identifiant :</label></td>
            <td><input type="text" name="nom" id="nom" value="<?php if(isset($_POST["nom"])){echo $_POST["nom"];} ?>" required/>
            </td>
        </tr>
        <tr>
            <td><label for="mdp">Mot de passe :</label></td>
            <td><input type="password" name="mdp" id="mdp" value="<?php if(isset($_POST["mdp"])){echo $_POST["mdp"];} ?>" required/>
            </td>
        </tr>
    </table>
    <br/> <input type="submit" name="sub_nv" value="Nouvel utilisateur"/>
    <input type="submit" name="sub_connexion" value="Connexion"/>
</form>

<?php

if (isset($ajout_user) && $ajout_user)
    echo $ajout_user;

if (isset($erreur) && $erreur && isset($err_msg)) {
    ?><div class="message"><?php echo $err_msg; ?></div><?php
}

?>

<?php $contenu = ob_get_clean(); ?>
<?php require 'gabarit.php'; ?>
</body>

