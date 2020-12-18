<?php require_once ROOT . DS . 'templates/partials/nav/user-nav.html.php' ?>
<h1 class="h1">Categories</h1>
<?php require_once ROOT . DS . 'templates/partials/nav/admin-product-nav.html.php' ?>
<?php require_once ROOT . DS . 'templates/partials/nav/admin-category-nav.html.php' ?>

<?php $this->var['session']->display() ?>

<div class="cont flex items-center justify-center flex-wrap">

    <div class="pt-3 px-5 shadow-xl rounded mb-12">

        <h2 class="font-bold text-2xl pb-3 text-center my-5">Modifier "<?= $this->var['category']['category_name']?>"</h2>
        
        <form id="registre-category" method="post"
            style="min-height:250px" class="flex flex-wrap pb-5 "
        >   
            <div class="input-container mb-3">
                <label class="inline-block mb-2" for="category_name">Nom</label>
                <input class="px-2" type="text" name="category_name"  required>
            </div>
            <button class="bg-green-700 h-10 hover:bg-green-800 text-white font-bold py-2  mb-2 px-4 border border-green-700 rounded" type="submit">Enregistrer</button>
            <a href="/public/category/show" class="text-center bg-red-700 h-10 hover:bg-red-800 text-white font-bold py-2  mb-5 px-4 border border-red-700 rounded" type="submit">Annuler</a>
        </form>
    </div>
</div>
