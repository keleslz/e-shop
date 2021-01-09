<?php $cart = $this->var['cart'] ?>
<?php $productsCart = $this->var['productsCart'] ?>

<?php require_once ROOT . DS . 'templates/partials/nav/base-nav.html.php' ?>
<?php require_once ROOT . DS . 'templates/partials/nav/e-shop-nav.html.php' ?>

<?php $this->var['session']->display() ?>

<div class="cont">

    <i id="info"></i>
    <?php if( is_array($cart) && count($cart) > 0 ) : ?>

        <h1 class="h2 block mb-10 shadow-sm">Votre panier</h1>
        <i class="error"></i>

        <?php $totalCost = displayCartList($productsCart, $cart) ?>

        <a href="/public/shop/delivery" id="go-to-info-customer">
            <div style="height:max-content;position:fixed; top:200px; right:10px" class="card-to-cart">
            <p class="text-center">Total : <span id="price"><?= totalPriceForAllCart($totalCost) ?></span> €</p>
                <span>Passer à la caisse</span>
            </div>
        </a>
        
    <?php else :  ?>

        <h2 >Votre panier est vide </h2>
        <a href="/public/shop/home">Remplissez le avec des articles</a>

    <?php endif ?>

</div>