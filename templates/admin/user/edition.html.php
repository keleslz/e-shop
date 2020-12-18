<?php $law = $this->var['law'] ?>
<?php $email = $this->var['email'] ?>

<?php require_once ROOT . DS . 'templates/partials/nav/user-nav.html.php' ?>

<h1 class="h1 flex justify-around p-3 bg-black flex-wrap text-white" >Dashboard</h1>

<?php $this->var['session']->display() ?>


<div class="cont flex flex-row justify-center pt-10">

    <div class="pt-3 pb-8 px-5 shadow-xl rounded mb-24 ">

        <h2 class="font-bold text-2xl pb-3 text-center my-5">Modifier</h2>
        
        <form id="registre-product" action="" method="post"
            style="min-height:350px;width:250px" class="flex flex-wrap "
        >   
            <div class="input-container mb-2">
                <label class="inline-block " for="email">Utilisateur</label>
                <input class="p-2 border" type="text" name="email" placeholder="votre@email.fr" required  value="<?= $email ?>">
            </div>

            <div class="input-container mb-2">
                <label class="inline-block " for="password">Mot de passe actuel</label>
                <input class="p-2 border" type="password" name="password"  required>
            </div>

            <div class="input-container mb-2">
                <label class="inline-block " for="newPassword">Nouveau mot de passe</label>
                <input class="p-2 border" type="password" name="newPassword">
            </div>

            <div class="input-container mb-5">
                <label class="inline-block " for="passwordConfirm">Confirmer nouveau mot de passe</label>
                <input class="p-2 border" type="password" name="passwordConfirm">
            </div>

            <button class="bg-green-700 h-10 hover:bg-green-800 text-white font-bold py-2  mb-5 px-4 border border-green-700 rounded" type="submit">Enregistrer</button>
        </form>

        <?php displayDeleteAccountButton($law) ?>

    </div>
</div>
