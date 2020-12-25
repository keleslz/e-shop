
<?php require_once ROOT . DS . 'templates/partials/nav/user-nav.html.php' ?>
<h1 class="h1">Categories</h1>
<?php require_once ROOT . DS . 'templates/partials/nav/admin-product-nav.html.php' ?>
<?php require_once ROOT . DS . 'templates/partials/nav/admin-administration-nav.html.php' ?>

<?php $this->var['session']->display() ?>

<div class="cont flex items-center justify-center flex-wrap">
    <div class="p-5 shadow-xl rounded mb-12 mt-5">
        <h3 class="h2 p2 bg-white text-black">Comptes existant</h3>
        <?php displayAllAccount( $this->var['accounts'] )?>
    </div>
</div>
