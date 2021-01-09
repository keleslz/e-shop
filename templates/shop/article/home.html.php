<?php require_once ROOT . DS . 'templates/partials/nav/base-nav.html.php' ?>
<?php require_once ROOT . DS . 'templates/partials/nav/e-shop-nav.html.php' ?>
<h1 class="h2 flex justify-around p-3 bg-black flex-wrap text-white">Bienvenue</h1>

<?php $this->var['session']->display() ?>

<div class="cont">
    
    <div class="flex flex-wrap p-5" style="min-height:100vh" >

        <div id="product-container">

            <div id="loader">
                <div class="lds-ripple"><div></div><div></div></div>
            </div> 

            <div id="card-loader">

                <div class="card">
                    <img >
                    <span class="title"></span>
                    <span class="price"></span>
                    <span class="category"></span>
                </div>
                <div class="card">
                    <img >
                    <span class="title"></span>
                    <span class="price"></span>
                    <span class="category"></span>
                </div>
                <div class="card">
                    <img >
                    <span class="title"></span>
                    <span class="price"></span>
                    <span class="category"></span>
                </div>
                <div class="card">
                    <img >
                    <span class="title"></span>
                    <span class="price"></span>
                    <span class="category"></span>
                </div>
                <div class="card">
                    <img >
                    <span class="title"></span>
                    <span class="price"></span>
                    <span class="category"></span>
                </div>
                <div class="card">
                    <img >
                    <span class="title"></span>
                    <span class="price"></span>
                    <span class="category"></span>
                </div>
                <div class="card">
                    <img >
                    <span class="title"></span>
                    <span class="price"></span>
                    <span class="category"></span>
                </div>
                <div class="card">
                    <img >
                    <span class="title"></span>
                    <span class="price"></span>
                    <span class="category"></span>
                </div>
            </div>

        </div>

    </div>

    <div id="get-more-product-container">
        <button id="get-more-product" class="btn btn-gray">Voir plus</button>
    </div>
    
</div>

<?php require_once ROOT . DS . 'templates/partials/footer.html.php' ?>
