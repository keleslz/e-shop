<?php $empty = $this->var['empty']; ?>
<?php $product = $this->var['product']; ?>
<?php $currentImg = $this->var['currentImg']; ?>
<?php $imageAssociated = $this->var['imageAssociated']; ?>
<?php $categories = $this->var['categories']; ?>


<?php if( $product ) : ?>
    
<?php require_once ROOT . DS . 'templates/partials/nav/user-nav.html.php' ?>

<h1 class="h1">Editer "<?=$product['product_name']?>"</h1>
<?php require_once ROOT . DS . 'templates/partials/nav/admin-product-nav.html.php' ?>

<?php $this->var['session']->display() ?>

<div class="flex flex-col items-center justify-center mt-24">

    <div class="medium-form pt-10 px-5 shadow-xl rounded mb-10">
        <div>
            <span class="inline-block text-left ml-0 my-2 italic text-blue-400 border-b-2 mb-5" ><?php (intval($product['id_category']) !== -1) ? displayProductCategory($product['id_category'], $categories) : 'Non classée' ?></span>
        </div>

        <div class="flex justify-center  mb-3 p-2 shadow-md">
            <img class="medium-picture mb-2" src="/public/img-storage/<?= $currentImg['img_name'] ?? 'default-image.jpg' ?>" alt="" >
        </div>
        
        <form enctype="multipart/form-data" method="post" class="flex flex-wrap pb-10">

            <div class="input-container mb-5">
                <p class="block w-full "><?= intval($product['product_status']) === 1 ? 'En ligne' : 'Hors-ligne '; ?></p>
            </div>
            <div class="input-container mb-5">
                <label class="inline-block mb-2"for="product_name">Nom produit</label>
                <input class="p-1" type="text" name="product_name"  required value="<?= $product['product_name']?>" >
            </div>

            <div class="input-container mb-5 w-full">
                <label class="inline-block mb-2"for="product_description">Description</label>
                <textarea class="p-1"  name="product_description" id="product_description"
                    cols="30" rows="10" required ><?= $product['product_description'] ?></textarea>
            </div>
            
            <div class="input-container mb-5">
                <label class="inline-block mb-2" for="price">Prix</label>
                <input type="text" name="price" id="price"  required value="<?= $product['product_price']?>">
            </div>  
            
            <div class="input-container my-3">
                <select class="border-2 rounded-lg border-gray-400 p-1 w-full" name="category_id" id="category_id">

                    <option  class="text-red" value="<?= (intval($product['id_category']) !== -1) ? displayProductCategory($product['id_category'], $categories, false) : 'Non classée' ?>">-- Selectionner une categorie --</option>

                    <?php foreach ($categories as $key => $value) : ?>

                        <option value="<?= $value['category_id'] ?>"><?= $value['category_name'] ?></option>

                    <?php endforeach ; ?>

                </select>
            </div>

            <div class="input-container mb-5">
                <input class="p-1" type="hidden" name="maxfileSize" value="30000" />
                <input name="product_picture" type="file" />
            </div>

            <div class="input-container mt-2 w-full">
                <div class="flex items-center w-100 mb-3 border-b-2">
                    <label class="inline-block text-center" style="width:50%" for="status">On</label>
                    <input class="inline-block" style="width:50%" type="radio" id="on" name="status" value="1" <?= intval($product['product_status']) === 1 ? 'checked' : '' ?> >
                </div>
                <div class="flex items-center w-100 mb-10 border-b-2">
                    <label class="inline-block text-center" style="width:50%" for="status">Off</label>
                    <input class="inline-block" style="width:50%" type="radio" id="off" name="status" value="-1"  <?= intval($product['product_status']) === -1 ? 'checked' : '' ?> >
                </div>
            </div>

            <button class="bg-green-700 h-10 hover:bg-green-800 text-white font-bold py-2 px-4 border border-green-700 rounded" type="submit">Enregistrer modification</button>
        </form>
    </div>
        
    <div  class="mini-pic-list-container flex flex-1 flex-wrap w-4/5">

        <div class="add-mini-pic flex  h-48 pt-5 rounded shadow-lg">

            <div>
                <h3 class="text-center text-2xl mb-3">Ajouter plus de photo</h3>
                <form class="flex flex-col justify-center p-5" enctype="multipart/form-data" method="post">

                    <input type="hidden" name="maxfileSize" value="30000" />
                    <input style="min-width:250px;max-width:320px" name="more_product_picture" type="file"  class="mb-3 "/>
                    <button style="width:280px" class="bg-green-700 h-10 hover:bg-green-800 text-white font-bold py-2 px-4 border border-green-700 rounded" type="submit">Enregistrer</button>

                </form>
            </div>

        </div>
        
        <section class="show-mini-pic flex flex-col pt-5 mb-24 ">
            
            <h3 class="text-center text-2xl mb-5">Images produit</h3>

            <?php if( count($imageAssociated) > 0 ) :  ?>

                <?php foreach ($imageAssociated as $key => $image): ?>
                    
                    <div class="mini-card flex flex-row justify-center my-5 rounded shadow-lg"> 

                        <img style="width:150px;height:150px;" 
                            src="/public/img-storage/<?= $image['img_name']?>" alt="L'alternatif arrive bientot"
                        >
                        <form  class="flex justify-center align-center"
                            action="/public/picture/delete/<?= $image['img_id'] ?>
                            " method="post">
                            <button type="submit"
                                class="bg-red-700 h-10 hover:bg-red-800 text-white font-bold py-2 px-4 border border-green-700 rounded mt-12"
                            >Supprimer</button>
                        </form>

                    </div>
                <?php endforeach ?>

            <?php else :?>

                <p class="">Ajoutez plus d'image a votre article</p>
            <?php endif ?>

        </section>
    </div>
</div>

<?php endif; ?>

