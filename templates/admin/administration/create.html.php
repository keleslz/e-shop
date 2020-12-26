
<?php require_once ROOT . DS . 'templates/partials/nav/user-nav.html.php' ?>
<h1 class="h1">Categories</h1>
<?php require_once ROOT . DS . 'templates/partials/nav/admin-product-nav.html.php' ?>
<?php require_once ROOT . DS . 'templates/partials/nav/admin-administration-nav.html.php' ?>

<?php $this->var['session']->display() ?>

<div class="cont flex items-center justify-center flex-wrap">

    <form method="post" class="flex flex-col px-5 pt-6 pb-10 rounded-t-lg shadow-lg mt-20">
        
        <h1 class="font-bold text-2xl pb-3">Creer un utilisateur</h1>

        <div class="mb-5 shadow-md">
            <?php $this->var['session']->display('user') ?>
        </div>

        <div class="flex flex-col py-2 pb-3 ">
            <label class="pb-2" for="email">e-mail</label>
            <input class="border-solid p-2 border-2 text-gray-700 border-gray-400 rounded" type="mail" name="email" required>
        </div>
        
        <div class="flex flex-col py-2 pb-8" >
            <label class="pb-2"  for="law" >Droit</label>

            <div class="my-1">
                <input class="border-solid p-2 border-2 text-gray-700 border-gray-400 rounded" type="radio" name="law" value="1000" checked required>
                <span class="mx-3">Contributeur Superieur</span>
            </div>

            <div class="my-1">
                <input class="border-solid p-2 border-2 text-gray-700 border-gray-400 rounded" type="radio" name="law" value="100"  required>
                <span class="mx-3">Contributeur</span>
            </div>
            
            <div class="my-1">
                <input class="border-solid p-2 border-2 text-gray-700 border-gray-400 rounded" type="radio" name="law" value="1" required>
                <span class="mx-3">Client</span>
            </div>
        </div>

        <div class="flex flex-col py-2 pb-3 ">
            <label class="pb-2" for="password">Mot de passe</label>
            <input class="border-solid p-2 border-2 text-gray-700 border-gray-400 rounded" type="password" name="password" required>
        </div>
        
        <div class="flex flex-col py-2 pb-8" >
            <label class="pb-2"  for="confirmPass">Confirmer mot de passe</label>
            <input class="border-solid p-2 border-2 text-gray-700 border-gray-400 rounded" type="password" name="confirmPass" required>
        </div>
        <button class="mb-2 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-1 px-1 
            border border-blue-500 hover:border-transparent rounded" type="submit"
        >Créer l'utilisateur</button>
    </form>

    <div class="flex flex-col px-5 pt-6 pb-10 rounded-t-lg shadow-lg mt-20 mx-5">
        <h3>Liste des droits</h3>
        <p>Le <strong>contributeur superieur</strong> peut creer, mettre a jour, supprimer tous types d'entitées.</p>
        <p>Le <strong>contributeur</strong> peut creer, mettre a jour toutes entitées sauf les utilisateurs.</p>
        <p>Le <strong>client</strong> n'a pas accès à l'administation de l'E-shop.</p>
    </div>
</div>
