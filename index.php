<?php session_start();
?>

<!-- HEAD -->
<?php require "head.php"; ?>

<!-- MENU -->
<?php require "menu.php"; ?>


<?php 
if (isset($_SESSION['id']) and !empty($_SESSION['id'])) {
    echo "<script>window.location.href = '/accueil.php?profil=".$_SESSION['prenom']."%20".$_SESSION['nom']."';</script>";
}else{
    echo "<script>window.location.href = '/accueil.php';</script>";
}

?>
<!-- SCRIPT -->
<?php require "script.php"; ?>

<!-- FOOTER -->
<?php require "footer.php"; ?>