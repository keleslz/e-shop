<?php $products = $this->var['products'] ?>
<?php $currentProduct = $this->var['currentProduct'] ?>
<?php $currentImage = $this->var['currentImage'] ?>
<?php $currentCategoryName = $this->var['currentCategoryName'] ?>
<?php $price = $this->var['price'] ?>
<?php $categories = $this->var['categories'] ?>


<?php require_once ROOT . DS . 'templates/partials/nav/user-nav.html.php' ?>

<h1 class="h1 flex justify-around p-3 bg-black flex-wrap text-white">Produits</h1>

<?php require_once ROOT . DS . 'templates/partials/nav/admin-product-nav.html.php' ?>

<li><a class="text-gray-700 hover:text-black pr-5" href="/public/product/create">Ajouter Produit</a></li>


<div class="cont" style="min-height:100vh">

<?php $this->var['session']->display() ?>

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

    <div class="flex flex-wrap p-5" >

        <?php if( count($products) > 0) : ?>

            <?php foreach ($products as $key => $product) : ?>
                
                <a href="/public/product/show/<?= $product['product_id']?>">
                    <div  class="m-5 hover:border-white cursor-pointer hover:shadow-xl">
                
                        <img class="flex m-auto flex-wrap w-48 h-48 rounded-t-xl border-2 border-gray-200" src="/public/img-storage/<?= $product['img_name']  ?? 'default-image.jpg' ?>" alt="Image alternative  bientot dispo">
                        <div class="shadow-lg border-gray-200 text-center " >
                            <span class="block my-2 mb-" ><?=$product['product_name']?></span>
                            <span class="block my-2 border-2 border-gray-300" ><?=$product['product_price']?> €</span>
                            <div class="flex flex-1 justify-center " style="width:192px;height:25px;overflow:hidden">
                                <p class="text-right pr-2" ><?= $product['product_description']?></p>
                            </div>
                            <div class="flex flex-1 justify-center" style="width:192px;height:25px;overflow:hidden">
                                <p class="text-center"><?= intval($product['product_status']) === 1 ? 'En ligne' : 'Hors-ligne '; ?></p><span class="text-gray-500"></span>
                            </div>
                            <div class="flex flex-1 justify-center">
                                <p class="text-center "><?= intval($product['id_category'])  !== -1 ? displayProductCategory( intval($product['id_category']), $categories) : 'Non classée' ?></p><span class="text-gray-500"></span>
                            </div>
                        </div>
                    </div>
                </a>

            <?php endforeach ?>

            <?php else :?>
            <p class="text-2xl text-center pt-5 text-gray-700" >Aucun produit vous pouvez en enregistrer <a href="/public/product/create" class="text-black border-b-2 border-black">ici</a></p>
        <?php endif ;?>

    </div>

</div>
        