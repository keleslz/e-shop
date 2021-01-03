<?php $products = $this->var['products'] ?>
<?php $currentProduct = $this->var['currentProduct'] ?>
<?php $currentImage = $this->var['currentImage'] ?>
<?php $currentCategoryName = $this->var['currentCategoryName'] ?>
<?php $price = $this->var['price'] ?>
<?php $categories = $this->var['categories'] ?>

<?php require_once ROOT . DS . 'templates/partials/nav/user-nav.html.php' ?>

<h1 class="h1 flex justify-around p-3 bg-black flex-wrap text-white">Produits</h1>

<?php require_once ROOT . DS . 'templates/partials/nav/admin-product-nav.html.php' ?>

<div class="flex p-2">
    <a class="btn btn-gray" href="/public/product/create">Ajouter Produit</a>
</div>


<div class="cont" style="min-height:100vh">

    <?php $this->var['session']->display() ?>

    <h2 class="h2 bg-white text-gray-900 pb-3">Produit non classée</h2>
    <div class="flex overflow-x-auto p-5 border border-red-900 bg-red-100">
        <?php displayProductWithoutCategory($products, $categories) ?>
    </div>


    <?php if( $currentProduct ) : ?>

        <div class="flex justify-center mt-24 mb-5">

            <div class="big-card-container w-1/2 p-5 shadow-xl rounded-t">

                <div>
                    <span class="inline-block text-left mx-2 italic text-blue-400 border-b-2 mb-5" ><?= $currentCategoryName ?></span>
                    <h3 class="text-center text-2xl mb-3"><?= $currentProduct['product_name']?></h3>
                </div>

                <div class="container flex items-center justify-center">
                    <img class="w-100 p-0 mb-3" src="/public/img-storage/<?= $currentImage['img_name'] ?? 'default-image.jpg'?>" alt="Image alternative  bientot dispo">
                </div>

                <div>
                    <h4 class="text-left text-2xl"><?= $currentProduct['product_price']?> €</h4>
                    <h2 class="block my-3"><?= $currentProduct['product_description']?></h2>
                    <p class="block mb-5 w-100 "><?= intval($currentProduct['product_status']) === 1 ? 'En ligne' : 'Hors-ligne '; ?></p>
                </div>

                <div class="button-container flex flex-wrap">
                    <form class="flex justify-center" action="/public/product/edition/<?= $currentProduct['product_id']?>" method="post">
                        <button class="text-center bg-gray-500 m-2 w-1/2 rounded hover:bg-gray-600 hover:text-white" type="submit">Modifier</button>
                    </form>
                    <?= deleteProductButton(intval($currentProduct['product_id'])) ?>
                </div>
            </div>
        </div>

    <?php endif ?>

    <h2 class="h2 bg-white text-gray-900 py-3 mt-2">Produit classée</h2>

    <div class="flex flex-wrap p-5" >
        <?php if( count($products) > 0) : ?>
     
            <?php displayProductWithCategory($products, $categories); ?>
            <?php else :?>
            <p class="text-2xl text-center pt-5 text-gray-700" >Aucun produit vous pouvez en enregistrer <a href="/public/product/create" class="text-black border-b-2 border-black">ici</a></p>
        <?php endif ;?>
    </div>

</div>
        