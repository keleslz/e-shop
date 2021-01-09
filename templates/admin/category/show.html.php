<?php $categories = $this->var['categories'] ?>

<?php require_once ROOT . DS . 'templates/partials/nav/user-nav.html.php' ?>

<h1 class="h1">Categories</h1>
<?php require_once ROOT . DS . 'templates/partials/nav/admin-product-nav.html.php' ?>
<?php require_once ROOT . DS . 'templates/partials/nav/admin-category-nav.html.php' ?>

<?php $this->var['session']->display() ?>

<div class="cont flex items-start justify-center flex-wrap mt-24">

    <div class="pt-3 px-5 shadow-xl rounded mb-24">

        <h2 class="font-bold text-2xl pb-3 text-center my-5">Toutes les catégories</h2>
            
            <form action="/public/category/delete" method="post">
                <?php displayCategories($categories) ?>
            </form>
    </div>
</div>
