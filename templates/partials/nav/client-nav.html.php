<nav class="flex justify-start p-3 bg-black flex-wrap">

    <li class="mb-2 list-none pr-5"><a class="hover:text-gray-400 text-white" href="/public/shop/accueil">Accueil</a></li>
    <li class="mb-2 list-none pr-5"><a class="hover:text-gray-400 text-white" href="/public/user/edit"><?= $this->var['email']?></a></li>
    <form style="width:95px" action="/public/user/signout" method="post"><button type="submit" class="mb-2 hover:text-gray-400 text-white">Deconnexion</button></form>

</nav>