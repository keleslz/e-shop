<?php  // $products = $this->var['products'] ?>

<?php require_once ROOT . DS . 'templates/partials/nav/base-nav.html.php' ?>
<?php require_once ROOT . DS . 'templates/partials/nav/e-shop-nav.html.php' ?>
<h1 class="h2 flex justify-around p-3 bg-black flex-wrap text-white">Bienvenue</h1>

<?php $this->var['session']->display() ?>

<div class="cont">

    <div class="flex flex-wrap p-5" style="min-height:100vh" >

        <div id="product-container"></div>

    </div>

</div>

<?php require_once ROOT . DS . 'templates/partials/footer.html.php' ?>
