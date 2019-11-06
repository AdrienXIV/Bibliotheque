<div style="display:flex; align-items:center;margin-bottom:1%;">

      <input class="checkbox" type="checkbox" name="checkbox[]" value="<?=$donnee['id'];?>">
 
    <div id="<?= $donnee['id']; ?>" class="message-texte" data-target="#modalMessagerie" data-toggle="modal">
        <img class="photo-message" src="<?= $donnee['photo'] ?>"><div class="div-texte"><span class="destinataire"><?php echo $donnee['prenom']. " : &nbsp; "; ?></span>
        <p class="message" style="<?php if ($donnee['vu'] == 1) echo 'color:rgb(124, 124, 124) !important;'; ?>"><?php echo $donnee['message']; ?></p></div>
        <?php if ($donnee['vu'] == 0) echo "<span class='notif'></span>"; ?>

        <small class="message-date" style="display:none"><?= $donnee['date'] ?></small>
        <small class="message-email" style="display:none"><?= $donnee['email'] ?></small>
        <small class="message-vu" style="display:none"><?= $donnee['vu'] ?></small>
    </div>
</div>