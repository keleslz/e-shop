<?php

/** Admin Category */
function displayProductCategory(int $idProductCategory, array $categoryList, $name = true) : void
{   
    foreach ($categoryList as $key => $category) {

        if(intval($category['category_id']) === $idProductCategory )
        {   
            if($name === true)
            {
                echo $category['category_name'];
            }else{
                echo intval($category['category_id']);
            }
        }
    }
}


function displayCategories(array $categories) : void
{
    if( count($categories) > 0)
    {
        foreach ($categories as $category => $value) {

            $id = $value['category_id'];
            $name = $value['category_name'];

            echo "
                <div class='flex items-center w-100 border-b-2 mb-3 pb-1'>
                    <input class='inline-block' style='width:50%' type='checkbox' id='$name-$id' name='$name' value='$id'>
                    <label class='inline-block text-center' style='width:50%' for='category'>$name</label>
                    <a href='/public/category/edit/$id' class='bg-gray-700 h-10 hover:bg-gray-800 text-white font-bold p-1 border border-gray-700 rounded' type='submit'>Editer</a>
                </div>
            ";
        }

    } else{

        echo    "<p class='text-2xl text-center py-5 text-gray-700'>
                    Aucune catégorie vous pouvez en enregistrer 
                    <a href='/public/category/create' class='text-black border-b-2 border-black'>ici</a>
                </p>";
    }
}

function displayButtonSubmit(array $categories) : void
{   
    if(count($categories) > 0)
    {
        echo "<button class='text-center bg-red-500 m-2 w-1/3 rounded hover:bg-red-600 hover:text-white py-1' type='submit'>Supprimer</button>";
    }
}

/** General Navbar Shop */

function displayCategoriesList (array $categories)
{
    foreach ($categories as $key => $category) {

        echo "<a class=' px-2 flex items text-left hover:text-black text-white hover:bg-white' href='#{$category['category_name']}'>{$category['category_name']}</a>";
    }
}

/**
 * Display user cart 
 * @param array|null
 */
function displayCart($cart) : void
{   
    if(is_array($cart) && count($cart) > 0)
    {   
        $count = count($cart);
        echo "<span class='inline-block rounded-full h-6 w-6 mx-1 bg-green-700 p-1 text-xs text-white'>{$count}</span>";
    }
}


// shop/panier

function isSame(array $listoOfValue, int $idCurrentProduct) : int
{   
    foreach ($listoOfValue as $key => $val) {

        if($key === $idCurrentProduct)
        {
            return intval($val);
        }
    }
}   

/**
 * Display user cart list 
 * @param array $carts =  cartRepo
 * @param bool $display if true dipslay all product cart else display nothing 
 * @param array $totalCost of all articles
 */
function displayCartList(array $cartsRepo, array $sessionCarts, bool $display = true ) : array
{  
    $i = 0;
    $totalCost = null;

    foreach ($cartsRepo as $key => $cartRepo) {

        $slug = $cartRepo['product_slug'];
        $productId =  $cartRepo['product_id'];
        $quantity = isSame($sessionCarts, intval($productId));

        $totalPrice = totalPriceForOneCart( $cartRepo['product_price'], $quantity);
        $productId = intval($productId);

        if( $display === true ) {

            echo "  
        
                <a href='/public/shop/show/$productId/$slug'>
                    <div class='flex flex-wrap mx-5 shadow-lg mb-3 p-1'> 
                
                        <div class='flex mb-3'>
                            <img class='rounded' style='width:150px;height:150px;' src='/img-storage/{$cartRepo['img_name']}' alt=''>
                        </div>

                        <div    style='width:calc(100% - 160px);padding-left:5px;padding-right:5px;min-width:200px'
                                class='flex flex-col mb-3' 
                        >
                            <p style='min-width:200px;min-height:50px' class='block p-2 '>{$cartRepo['product_description']}</p>
                            <p style='min-width:200px;' class='block mx-2'><span id=''>$quantity</span> x {$cartRepo['product_price']}€</p>
                            <p style='min-width:200px;' class='block mx-2'><span id=''>Prix : </span> {$totalPrice} €</p>
                        </div>
                    </div> 
                </a>
            ";
        };
        

        $productPrice = intval($cartRepo['product_price']);
        $price = str_replace(',','.',$productPrice);

        $totalCost[] = $totalPrice ; 
    }
    return $totalCost;
}

/**
 * Shop/show
 */
function displayPictureAssociated(array $pictures) : void
{
    $string = "Image alternative  bientot dispo" ;

    foreach ($pictures as $key => $picture) {
        echo "  
            <img    class='m-2 rounded' 
                    style='width:150px;height:150px;' 
                    src='/public/img-storage/{$picture['img_name']}' 
                    alt='$string'
            >
        ";
    }
}

/**
 * Shop/show/[id]/product-slug
 */
function displayProductQuantityIfExist(int $productId) : void
{   
    
    if(isset($_SESSION['_cart']) && count($_SESSION['_cart']) > 0 )
    {
        foreach ($_SESSION['_cart'] as $key => $cart) {
            
            if(intval($key) === intval($productId))
            {
                echo $cart;
                return;
            }
        }
    }else{
        echo 1 ;
    }
}


function totalPriceForOneCart(string $price, int $quantity) : float
{   
    $p = str_replace(',','.', $price);

    return $p * $quantity;
}


function totalPriceForAllCart(array $prices) : float
{   

    foreach ($prices as $key => $price) {

        $p[] = str_replace(',','.', $price);
    }

    return array_sum($p);
}


/**
 * shop/payment
 */

function ifNotConnectedMessage() : string 
{   
    if(isset($_SESSION['_userStart']) && is_array($_SESSION['_userStart']))
    {
        $text ="";

    }else{

        $text = "    <div class='inline-block w-full flex justify-center mt-10'>
                        <a href='/public/user/signin'>Vous avez un compte ? 
                            <span class='text-blue-700'>Connectez-vous</span>
                        </a>
                    </div>";
    }
    
    return $text;
}

// User/edit

function displayDeleteAccountButton(int $law) : void
{
    if($law < 65535)
    {
        echo "
            <form action='/public/user/delete' method='post'>

                <div class='input-container'>

                    <button class='bg-red-700 h-10 hover:bg-red-800 text-white font-bold py-2 mx-auto px-4 border border-red-700 rounded' type='submit'>Suppimer mon compte </button>
                    <small class='text-red-800 mb-5'>(suppression définitive)</small>

                </div>

            </form>
        ";
    }

}