<?php
$empty = $this->var['empty']; 
$producName = $this->var['save']['product_name'] ?? null;
$productDescription = $this->var['save']['product_description'] ?? null;
$productPrice = $this->var['save']['price'] ?? null;
$productCategory = $this->var['save']['category_name'] ?? null;

$categories = $this->var['categories'];
?>

<?php require_once ROOT . DS . 'templates/partials/nav/admin-nav.html.php' ?>

<h1 class="h1">Ajouter un produit</h1>
<?php require_once ROOT . DS . 'templates/partials/nav/admin-product-nav.html.php' ?>
<?php require_once ROOT . DS . 'templates/partials/nav/admin-nav.html.php' ?>

<?php $this->var['session']->display() ?>

<div class="flex items-center justify-center flex-wrap mt-24">

    <div class="pt-3 px-5 shadow-xl rounded mb-24">

        <h2 class="font-bold text-2xl pb-3 text-center my-5">Produit</h2>
        
        <form id="registre-product" enctype="multipart/form-data" action="/public/product/create" method="post"
            style="min-height:600px" class="flex flex-wrap pb-5 flex-col"
        >   
            <div class="input-container mb-3">
                <label class="inline-block mb-2" for="product_name">Nom produit</label>
                <input class="border border-light-gray-500 border-opacity-0 rounded" type="text" name="product_name" value="<?= $producName ?>" required>
            </div>

            <div class="input-container mb-3">
                <label class="inline-block mb-2" for="product_description">Description</label>
                <textarea class="border border-light-gray-500 border-opacity-0 rounded" name="product_description" id="product_description" cols="30" rows="10" required><?= $productDescription ?></textarea>
                
                <div class="input-container mb-3">
                    <label class="inline-block mb-2" for="price">Prix</label>
                    <input type="text" name="price" id="price"  required value="<?= $productPrice ?>">
                </div>
                        
                <div class="input-container my-3">
                    <select class="border-2 rounded-lg border-gray-400 p-1 w-full" name="category_id" id="category_id">

                        <option  class="text-red" value="Non classée">-- Selectionner une categorie --</option>

                        <?php foreach ($categories as $key => $value) : ?>

                            <option value="<?= $value['category_id'] ?>"><?= $value['category_name'] ?></option>

                        <?php endforeach ; ?>

                    </select>
                </div>

                <div class="input-container mb-5">
                    <input name="product_picture" type="file" />
                    <input type="hidden" name="maxfileSize" value="30000"/>
                </div>

                <div class="input-container mt-2">
                    <div class="flex items-center w-100 mb-3 border-b-2">
                        <label class="inline-block text-center" style="width:50%" for="status">On</label>
                        <input class="inline-block" style="width:50%" type="radio" id="on" name="status" value="1" checked>
                    </div>
                    <div class="flex items-center w-100 mb-10 border-b-2">
                        <label class="inline-block text-center" style="width:50%" for="status">Off</label>
                        <input class="inline-block" style="width:50%" type="radio" id="off" name="status" value="-1" >
                    </div>
                </div>
            <button class="bg-green-700 h-10 hover:bg-green-800 text-white font-bold py-2  mb-5 px-4 border border-green-700 rounded" type="submit">Enregistrer</button>
        </form>
    </div>
</div>


