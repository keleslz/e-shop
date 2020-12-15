<?php $products = $this->var['products'] ?>

<?php require_once ROOT . DS . 'templates/partials/nav/base-nav.html.php' ?>
<?php require_once ROOT . DS . 'templates/partials/nav/e-shop-nav.html.php' ?>
<h1 class="h2 flex justify-around p-3 bg-black flex-wrap text-white">Bienvenue</h1>

<?php $this->var['session']->display() ?>

<div class="cont">

    <div class="flex flex-wrap p-5" style="min-height:100vh" >

        <?php if( count($products) > 0) : ?>

            <?php foreach ($products as $key => $product) : ?>
                
                <?php if( intval($product['product_status']) === 1) : ?>

                    <a href="/public/shop/show/<?= $product['product_id']?>/<?= $product['product_slug']?>">
                        <div class=" m-5 hover:border-white cursor-pointer hover:shadow-xl">
                            <img class="flex m-auto flex-wrap w-48 h-48 rounded-t-xl border-2 border-gray-200" src="/public/img-storage/<?= $product['img_name']  ?? 'default-image.jpg' ?>" alt="Image alternative  bientot dispo">
                            <div class="shadow-lg border-gray-200 text-center " >
                                <span class="block my-2 mb-" ><?= $product['product_name']?></span>
                                <span class="block my-2 border-2 border-gray-300" ><?= $product['product_price']?> €</span>
                            </div>
                        </div>
                    </a>
                <?php endif ;?>

            <?php endforeach ?>

            <?php else :?>
            <p class="text-2xl text-center pt-5 text-gray-700" >La boutique est vide</p>
        <?php endif ;?>

    </div>

</div>

<?php require_once ROOT . DS . 'templates/partials/footer.html.php' ?>
