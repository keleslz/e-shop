<?php $product = $this->var['product'] ?>

<?php require_once ROOT . DS . 'templates/partials/nav/base-nav.html.php' ?>
<?php require_once ROOT . DS . 'templates/partials/nav/e-shop-nav.html.php' ?>

<?php if( $product && intval($product['product_status']) === 1 ) : ?>
    
    
    <?php $this->var['session']->display() ?> 

    <div id="show-article-container" class="flex justify-center mt-24 mb-5">
    
        <div class="big-card-container w-1/2 p-5 shadow-xl rounded-t">
        
            <div>
                <p>Catégorie  : <a href="#LaCategorie"><?= $this->var['currentProductCategory'] ?? 'Non-Classée' ?></a></p>
            </div>

            <div>
                <h3 class="text-center text-2xl mb-3"><?= $product['product_name']?></h3>
            </div>
            <div class="container flex items-center justify-center mb-3">
                <img class="w-100 p-0 mb-3" src="/public/img-storage/<?= $this->var['productImg']['img_name'] ?? 'default-image.jpg'?>" alt="Image alternative  bientot dispo">
            </div>

            <div class="flex flex-col mb-5">
    
                <form class="flex flex-col justify-center mb-0" method="post">
                
                    <div class="flex flex-wrap justify-left items-center px-2">

                        <input type="hidden" name="productId" value="<?= $product['product_id'] ?>">

                        <div>
                            <p style="width:105px" class="mb-3">Quantité</p>
                            <input id="cart-quantity" style="width:105px" class="mb-3 p-2"  type="number" min="0" required  value="1" name="quantity">
                        </div>

                        <div>
                            <button id="add-on-cart"type="submit" style="height:50px!important" class="enabled mb-2 text-center bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-1 px-1 border border-blue-500 hover:border-transparent rounded">
                                Ajouter au panier
                            </button>
                        </div>

                    </div>
                </form>
                    
                <a href="/public/shop/cart"  style="height:50px!important"  
                    id="remove-product" class="text-center mb-2 bg-transparent hover:bg-green-500 text-blue-700 font-semibold hover:text-white p-2 mx-2 border border-blue-500 hover:border-transparent rounded" 
                >
                    Acheter maintenant
                </a>
            </div>

            <div>
                <h4 class="text-left text-2xl"><?= $product['product_price']?> €</h4>
                <h4 class="inline-block mt-3 rounded-t-lg border-t-2 border-l-2 border-r-2 px-2" >description</h4>
                <h4 class="inline-block mt-3 rounded-t-lg border-t-2 border-l-2 border-r-2 px-2 bg-gray-200 border-b-2" >Avis</h4>
                <p class="block py-3 border-t-2"><?= $product['product_description']?></p>
            </div>

        </div>
    </div>

    <div class="flex flex-wrap justify-baroundetween pl-16">
        <?php displayPictureAssociated($this->var['pictureAssociated']) ?>
    </div>

<?php else : ?>
    <?php $this->redirectTo('shop/home') ?>
<?php endif ?>

<?php require_once ROOT . DS . 'templates/partials/footer.html.php' ?>
