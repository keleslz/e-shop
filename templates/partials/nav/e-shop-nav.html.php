<nav class="flex justify-around p-3 bg-black flex-wrap items-center">
    
    <li class="list-none pr-5"><a class="text-center hover:text-gray-400 text-white" href="/public/shop/home">Accueil</a></li>
    <ul id="navbar-category" class="list-none  dropdown" style="height:30px;">
        <a class=" text-center hover:text-gray-400 text-white px-2" href="#">Categories</a>
        <div id="" style="position:absolute;z-index:100" class="items bg-black hidden pt-2">
            <?php displayCategoriesList($this->var['categories']); ?>
        </div>
    </ul>
    <li class="list-none pr-5"><a class="text-center hover:text-gray-400 text-white" href="#Nouveautés">Nouveautés</a></li>
    <li class="list-none pr-5"><a class="text-center hover:text-gray-400 text-white" href="/public/shop/cart">Panier<?php displayCart($this->var['cart'])?></a></li>

    <?php if (is_array($this->var['adminSession']) && count($this->var['adminSession']) > 0) : ?>
        
        <li class="list-none pr-5"><a class="text-center hover:text-gray-400 text-white" href="/public/user/dashboard"><?= $this->var['adminSession']['email'] ?></a></li>
        
    <?php endif;?>

    <?php if (intval($this->var['adminSessionLaw']) === 65535) : ?>

        <a class="text-center hover:text-gray-400 text-white" href="/public/user/dashboard">Retour à l'Admin</a>

    <?php elseif ( $this->var['adminSessionLaw'] === null ) : ?>

        <form style="width:95px" class=" mb-0" action="/public/user/signin" method="post"><button type="submit" class="hover:text-gray-400 text-white">Se connecter</button></form>
    <?php else : ?>   

        <form class="mb-0" style="width:95px" action="/public/user/signout" method="post"><button type="submit" class=" hover:text-gray-400 text-white">Deconnexion</button></form>
        
    <?php endif ?>
    
</nav>
