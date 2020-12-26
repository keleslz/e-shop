<ul class="flex justify-around p-3 text-black flex-wrap shadow-md text-center">
    <li><a class="text-gray-700 hover:text-black pr-5" href="/public/product/show">Produits</a></li>
    <li><a class="text-gray-700 hover:text-black pr-5" href="/public/category/show">Categories</a></li>
    
    <?php if( intval($this->var['law']) > 100 ) : ?>
        <li><a class="text-gray-700 hover:text-black pr-5" href="/public/administration/show">Administation</a></li>
    <?php endif ?>

    <li><a class="text-gray-700 hover:text-black pr-5" href="">Statistiques</a></li>
</ul>