
<?php require_once ROOT . DS . 'templates/partials/nav/base-nav.html.php' ?>

<div class="h-screen flex justify-center items-center text-sm font-sans w-full">

    <form method="post" class="flex flex-col px-5 pt-6 pb-10 rounded-t-lg shadow-lg">
        
        <h1 class="font-bold text-2xl pb-3" >Connexion</h1>
        <div class="mb-5 shadow-md">
            <?php $this->var['session']->display() ?>
        </div>
        
        <div class="flex flex-col py-2 pb-3 ">
            <label class="pb-2" for="email">E-mail</label>
            <input class="border-solid p-2 border-2 text-gray-700 border-gray-400 rounded" type="mail" name="email" required>
        </div>
        
        <div class="flex flex-col py-2 pb-8 ">
            <label class="pb-2" for="password">Mot de passe</label>
            <input class="border-solid p-2 border-2 text-gray-700 border-gray-400 rounded" type="password" name="password" required>
        </div>

        <button class="mb-2 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white 
            py-1 px-1 border border-blue-500 hover:border-transparent rounded" type="submit"
        >Se connecter
        </button>

        <a class="mb-2 text-center bg-transparent hover:bg-green-500 text-blue-700 font-semibold hover:text-white py-1 px-1 border border-blue-500 hover:border-transparent rounded" href="signup">S'inscrire</a>
        
        <a class="mb-2 text-center bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white 
            py-1 px-1 border border-blue-500 hover:border-transparent rounded" href="/public/shop/accueil">Retourner sur le shop</a>
    </form>
</div>

