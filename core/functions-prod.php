<?php

/** Admin */
    /**Category */
        function displayProductCategory(int $idProductCategory, array $categoryList, $name = true) 
        {   
            foreach ($categoryList as $key => $category) {

                if(intval($category['category_id']) === $idProductCategory )
                {   
                    if($name === true)
                    {   
                        return $category['category_name'];
                    }else{
                        return intval($category['category_id']);
                    }
                }
            }
        }

        function displayCategories(array $categories) : void
        {   
        
            if( count($categories) > 0 && intval($_SESSION['_userStart']['law']) < 1000 )  /** Is simple contributor */
            {   
                foreach ($categories as $category => $value) {

                    $id = $value['category_id'];
                    $name = $value['category_name'];

                    echo "
                        <div class='flex items-center w-100 border-b-2 mb-3 pb-1'>
                            <label class='inline-block text-center' style='width:50%' for='category'>$name</label>
                            <a href='/public/category/edit/$id' class='bg-gray-700 h-10 hover:bg-gray-800 text-white font-bold p-1 border border-gray-700 rounded' type='submit'>Editer</a>
                        </div>
                    ";
                }
                
            }
            else if( count($categories) > 0) /** Is contributor or admin */
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

                echo "<button class='text-center bg-red-500 m-2 w-1/3 rounded hover:bg-red-600 hover:text-white py-1' type='submit'>Supprimer</button>";
                
            } else{ /** No category */

                echo    "<p class='text-2xl text-center py-5 text-gray-700'>
                            Aucune catégorie vous pouvez en enregistrer 
                            <a href='/public/category/create' class='text-black border-b-2 border-black'>ici</a>
                        </p>";
            }
        }


        /** General Navbar Shop */
        function displayCategoriesList (array $categories)
        {
            foreach ($categories as $key => $category) {

                echo "<a class=' px-2 flex items text-left hover:text-black text-white hover:bg-white' href='?category/{$category['category_name']}' data-id='{$category['category_id']}' >{$category['category_name']}</a>";
            }
        }
        
    /** Dashboard */
        /** Orderslist */
        function displayOrdersList(array $orders, bool $displayButton = false) : void
        {   
            if(count($orders) === 0) {
                echo '<p>Rien a signaler par ici ..</p>';
                return;
            }
            foreach($orders as $order)
            {   
                $articles = displayArticles($order->getArticle());
                $price = str_replace('.', ',', $order->getTotal()) . '€';
                $buttons = displayButtons($displayButton, $order->getId()); 
                
                echo "
                    <ul class='m-3 px-2 border-r'>
                        <li><span>Nom : </span>{$order->getName()}</li>
                        <li><span>Prenom : </span>{$order->getSurname()}</li>
                        <li><span>Email : </span>{$order->getEmail()}</li>
                        <li><span>Adresse : </span>{$order->getAddress()}</li>
                        <li><span>Code postale : </span>{$order->getZip()}</li>
                        <li><span>Ville : </span>{$order->getCity()}</li>
                        <li><span>Departement : </span>{$order->getDepartment()}</li>
                        <ul>
                        <span>Articles</span>
                            <div class='my-2'>
                                {$articles}
                            </div>
                            <ul><span>Total </span>{$price}</ul>
                        </ul>
                        {$buttons}
                        <li class='text-black-900'>{$order->getCreatedAt()}</li>
                    </ul>
                ";

            }
        }

        /**
         * Display article list with quantity
         */
        function displayArticles(string $articles) : string
        {   
            $articleList = (array) json_decode($articles);
            $string = '';

            foreach( $articleList as $article)
            {
                $string .= '<li class="hidden">' . (string) $article->id . '</li>';
                $string .= '<li>' . (string) $article->name . ' x ' . $article->quantity .  '</li>';
            }

            return $string;
        }

        /**
         * Display or not  the buttons
         * @param bool $display if true display button else not
         */
        function displayButtons(bool $display, ?int $id) : string
        {   
            $buttons = "
                <li class='mt-2'>
                    <button class='btn btn-green valid' data-id='{$id}' data-choice='accept'>Accepter</button>
                </li>

                <li class='mt-2'>
                    <button class='btn btn-r reject' data-id='{$id}' data-choice='reject'>Refuser</button>
                </li>
            " ;
            if($display)
            {
                return $buttons;
            }
            return '';
        }

        
/** shop/cart */

    /**
     * Display user cart 
     * @param array|null
     */
    function displayCart($cart) : void
    {   
        if(is_array($cart))
        {   
            $count = count($cart) > 0 ? count($cart) : 0;
            echo "<span id='cart-quantity-stored' class='inline-block rounded-full h-6 w-6 mx-1 bg-green-700 p-1 text-xs text-white'>{$count}</span>";
            return;
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

                $img = $cartRepo['img_name'] ?? 'default-image.jpg' ;
                
                echo "  
                    <a href='/public/shop/show/{$productId}/{$slug}'>
                        <div class='flex flex-wrap mx-5 shadow-lg mb-3 p-1'> 
                    
                            <div class='flex mb-3'>
                                <img class='rounded' style='width:150px;height:150px;' src='/public/img-storage/{$img}' alt=''>
                            </div>

                            <div    style='width:calc(100% - 160px);padding-left:5px;padding-right:5px;min-width:200px'
                                    class='flex flex-col mb-3' 
                            >
                                <p style='min-width:200px;min-height:50px' class='block p-2 '>{$cartRepo['product_description']}</p>
                                <p style='min-width:200px;' class='block mx-2'>Quantité : <span>{$quantity}</span></p>
                                <p style='min-width:200px;' class='block mx-2'>Prix : <span>{$cartRepo['product_price']} </span>€</p>
                                <p style='min-width:200px;' class='block mx-2'>Total : <span class='one-cart-total-price'>{$totalPrice}</span> €</p>
                                <button 
                                    style='width:150px' 
                                    class='remove-product btn btn-r m-1 mt-2'
                                    data-id='{$productId}' 
                                >Retirer l'article
                                </button>
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
     * Get quantity article and his product
     */
    function isSame(array $listoOfValue, int $idCurrentProduct) : int 
    {   
        foreach ($listoOfValue as $key => $val) {
            
            if($key === $idCurrentProduct)
            {
                return  intval($val['quantity']);
            }
        }
        return 0;
    }

/** Shop/show */
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


/** shop/payment */
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

/** User */
    /* User/edit */
    function displayDeleteAccountButton(int $law) : void
    {
        if($law < 65535)
        {
            echo "
                <form action='/public/user/delete' method='post'>

                    <div class='input-container'>

                        <button id='delete-account-button' class='btn btn-r' type='submit'>Supprimer mon compte (définitivement)</button>
                    </div>

                </form>
            ";
        }
    }
    

    function displayOrdersListForClient(array $orders, bool $displayButton = false) : void
    {   
        if(count($orders) === 0 )
        {
            echo "<p 
                class='bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative ' 
                role='alert'>Commencez à remplir votre panier en cliquant <a href='/public/shop/home' 
                class='underline'>ici</a></p>";
            return; 
        }
        foreach($orders as $order)
        {   
            $articles = displayArticles($order->getArticle());
            $price = str_replace('.', ',', $order->getTotal()) . '€';
            $buttons = displayButtons($displayButton, $order->getId()); 
            $state = displayOrderStateForClient(intval($order->getState()));

            echo "      
                <ul class='card-no-pic'>
                    <li><span>Etat d'avancement</span>{$state}</li>
                    <li><span>Nom : </span>{$order->getName()}</li>
                    <li><span>Prenom : </span>{$order->getSurname()}</li>
                    <li><span>Email : </span>{$order->getEmail()}</li>
                    <li><span>Adresse : </span>{$order->getAddress()}</li>
                    <li><span>Code postale : </span>{$order->getZip()}</li>
                    <li><span>Ville : </span>{$order->getCity()}</li>
                    <li><span>Departement : </span>{$order->getDepartment()}</li>
                    <ul>
                    <span>Articles commandés : </span>
                        <div class='my-2'>
                            {$articles}
                        </div>
                        <ul><span>Total </span>{$price}</ul>
                    </ul>
                    {$buttons}
                    <li class='text-black-900'>{$order->getCreatedAt()}</li>
                </ul>
            ";
        }
    }

    function displayOrderStateForClient(int $state) : string 
    {
        if($state === 1)
        {
            return 'Votre commande a été validée et sera expédié dans un délai de 1 jour ouvré';
        }
        
        if($state === 0)
        {
            return 'Commande en attente de validation';
        }

        if($state === -1)
        {
            return "Nous sommes dans le regret de vous annoncé que cette commande ne pourra être honoré veuillez contacte e-shop@contact.fr pour plus d'information";
        }

        return '';
    }

/** Administration/show */

    function displayAllAccount(array $accounts) : void
    {
        foreach($accounts as $account)
        {      
            $law = displayLawLevel( intval($account['law']));
            echo "
                <ul class='p-3 m-2 border rounded' >
                    <div data-id='{$account['id']}'>
                        <li>Email : {$account['email']}</li>
                        <li>Droit :  {$law}</li>
                        <li>Crée le : {$account['created_at']}</li>
                        <button class='delete-account-button btn btn-r'>X</button>
                    </div>
                </ul>
            ";
        }
    }

    function displayLawLevel (int $law) : string
    {
        if($law === 1000)
        {
            return '<strong>Contributeur Superieur</strong>';
        }
        
        if($law === 100)
        {
            return 'Contributeur';
        }

        if($law === 1)
        {
            return 'Client';
        }
        
        return 'Erreur inattendu';
    }

/** Product */

    /** product/show/id */
    function deleteProductButton(string $currentProductId) : string
    {   
        if(intval($_SESSION['_userStart']['law']) > 100)
        {
            return "
            <form 
                class='flex justify-center' action='/public/product/delete/$currentProductId' 
                method='post' onclick='window.confirm('Êtes-vous sur de vouloir supprimer ce produit ?')' 
            >
                <button class='text-center bg-red-500 m-2 w-1/2 rounded hover:bg-red-600 hover:text-white' type='submit'>Supprimer</button>
            </form>
            ";
        }

        return '';
    }

    /** product/show */

    function displayProductWithCategory(array $products, array $categories) : void
    {
        foreach ($products as $key => $product) {

            $image = $product['img_name']  ?? 'default-image.jpg';
            $status = intval($product['product_status']) === 1 ? 'En ligne' : 'Hors-ligne '; 
            $category = intval($product['id_category'])  === -1 ? 'Non classée' :  displayProductCategory( intval($product['id_category']), $categories) ;
            $category = $category ?? 'Non classée';

            if( intval($product['id_category']) > 0 ) 
            {
                echo "<a class='max-w-max m-5 ' style='height:max-content'  href='/public/product/show/{$product['product_id']}'>
                    <div  class='hover:border-white cursor-pointer hover:shadow-xl'>
                
                        <img class='flex m-auto flex-wrap w-48 h-48 rounded-t-xl border-2 border-gray-200' src='/public/img-storage/$image' alt='Image alternative  bientot dispo'>
                        <div class='shadow-lg border-gray-200 text-center ' >
                            <span class='block my-2 mb-' >{$product['product_name']}</span>
                            <span class='block my-2 border-2 border-gray-300' >{$product['product_price']} €</span>
                            <div class='flex flex-1 justify-center ' style='width:192px;height:25px;overflow:hidden'>
                                <p class='text-right pr-2' >{$product['product_description']}</p>
                            </div>
                            <div class='flex flex-1 justify-center' style='width:192px;height:25px;overflow:hidden'>
                                <p class='text-center  p-1'>$status</p>
                            </div>
                            <div class='flex flex-1 justify-center bg-white'>
                                <p class='text-center   p-1'>{$category}</p>
                            </div>
                        </div>
                    </div>
                </a>";
            }
        }
    }

    function displayProductWithoutCategory(array $products, array $categories)
    {
        foreach($products as $product)
        {   
            if( intval($product['id_category']) < 0 ) 
            {
                $statut = intval($product['product_status']) === 1 ? 'En ligne' : 'Hors-ligne ';
                $category = intval($product['id_category'])  !== -1 ? displayProductCategory( intval($product['id_category']), $categories) : 'Non classée';
                $image = $product['img_name']  ?? 'default-image.jpg' ;

                echo "<a class='max-w-max m-5 ' style='height:max-content' href='/public/product/show/{$product['product_id']}'>
                    <div  class='hover:border-white cursor-pointer hover:shadow-xl'>
                
                        <img class='flex m-auto flex-wrap w-48 h-48 rounded-t-xl border-2 border-gray-200' src='/public/img-storage/$image' alt='Image alternative  bientot dispo'>
                        <div class='shadow-lg border-gray-200 text-center ' >
                            <span class='block py-2 pb-2 bg-white' >{$product['product_name']}</span>
                            <span class='block py-2  bg-white border-2 border-gray-300' >{$product['product_price']} €</span>
                            <div class='flex flex-1 justify-center ' style='width:192px;height:25px;overflow:hidden'>
                                <p class='text-right pr-2  bg-white ' >{$product['product_description']}</p>
                            </div>
                            <div class='flex flex-1 justify-center bg-white' style='width:192px;height:25px;overflow:hidden'>
                                <p class='text-center p-1'>$statut</p>
                            </div>
                            <div class='flex flex-1 justify-center bg-white'>
                                <p class='text-center bg-white d-block p-1'>$category</p>
                            </div>
                        </div>
                    </div>
                </a>";
            }
        }
    }

