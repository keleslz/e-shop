<?php $cart = $this->var['cart'] ?>
<?php $productsCart = $this->var['productsCart'] ?>

<?php require_once ROOT . DS . 'templates/partials/nav/base-nav.html.php' ?>
<?php require_once ROOT . DS . 'templates/partials/nav/e-shop-nav.html.php' ?>

<?php $this->var['session']->display() ?>

<div class="cont">

    
    <?php if( is_array($cart) && count($cart) > 0 ) : ?>

        <h1 class="h2 block mb-10 shadow-sm">Votre panier</h1>
        <i class="error"></i>

        <?php $totalCost = displayCartList($productsCart, $cart) ?>

        <a href="/public/shop/delivery">
            <div style="height:max-content;position:fixed; top:200px; right:10px" class="cursor-pointer bg-green-700 h-10 hover:bg-green-800 text-white font-bold py-2  mb-5 px-4 border border-green-700 rounded">
                <p class="text-center">Total : <span id="price"><?= totalPriceForAllCart($totalCost) ?></span> €</p>
            <span>Passer à la caisse</span>
            </div>
        </a>
        
    <?php else :  ?>

        <h2 >Votre panier est vide </h2>
        <a href="/public/shop/home">Remplissez le avec des articles</a>

    <?php endif ?>

</div>