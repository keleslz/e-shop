<?php $law = $this->var['law'] ?>

<nav id="nav" class="flex justify-around p-3 bg-black flex-wrap">
    <li class="mb-2 list-none pr-5"><a class="hover:text-gray-400 text-white" href="/public/user/dashboard">Tableau de board</a></li>
    <div>
        <li class="mb-2 list-none pr-5"><a class="hover:text-gray-400 text-white" href="/public/user/edit">Mon profil : <?= $this->var['email']?></a></li>
    </div>

    <?php if( !(intval($law) < 100) ) : ?>

        <li class="mb-2 list-none pr-5"><a class="hover:text-gray-400 text-white" href="/public/product/show">Gestion boutique</a></li>

    <?php endif ?>

    <li class="mb-2 list-none pr-5"><a class="hover:text-gray-400 text-white" href="/public/shop/home">Voir ma Boutique</a></li>
    <form style="width:95px" action="/public/user/signout" method="post"><button type="submit" class="mb-2 hover:text-gray-400 text-white">Deconnexion</button></form>
</nav>