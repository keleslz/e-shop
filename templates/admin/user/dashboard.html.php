<?php $orders = $this->var['orders'] ?>
<?php $myOrders = $this->var['myOrders'] ?>
<?php $totalOrders = count($orders['noValidated']) + count($orders['validated']) + count($orders['rejected']); ?>

<?php require_once ROOT . DS . 'templates/partials/nav/user-nav.html.php' ?>

<h1 class="h1 flex justify-around p-3 bg-black flex-wrap text-white" >Dashboard</h1>

<?php $this->var['session']->display() ?>

<div class="cont" style="min-height:100vh">


    <?php if($law >= 100) : ?>
        <h2 class="h2 bg-white text-black mb-3">Commandes</h2>
        <i></i>
        <div>
            <h2 class="h2 bg-white text-gray-900 pb-3 justify-around ">
                A valider 
                <span id="no-validated-counter" class="mx-1 "><?=  count($orders['noValidated']) . '/' .  $totalOrders ?></span>
                <button id="order-button-to-validated" class="mx-2 btn btn-gray">Voir</button></h2>
        </div>
        <div id="order-to-validated" class="order hidden flex overflow-x-auto p-5 border border-yellow-900 bg-yellow-100">
            <?php displayOrdersList($orders['noValidated'], true) ?>
        </div>
    
        <div>
            <h2 class="h2 bg-white text-gray-900 pb-3 justify-around ">
                Validé
                <span id="validated-counter" class="mx-1 ">
                    <?=  count($orders['validated']) . '/' .  $totalOrders ?>
                </span>
                <button id="order-button-validated" class="mx-2 btn btn-gray">Voir</button></h2>
        </div>
        <div id="order-validated" class="hidden flex flex-wrap p-5 border border-green-900 bg-green-100">
            <?php displayOrdersList($orders['validated']) ?>
        </div>

        <div>
            <h2 class="h2 bg-white text-gray-900 pb-3 justify-around ">
                Rejecté
                <span id="rejected-counter" class="mx-1 ">
                    <?=  count($orders['rejected']) . '/' .  $totalOrders ?>
                </span>
                <button id="order-button-rejected" class="mx-2 btn btn-gray">Voir</button></h2>
        </div>
        <div id="order-rejected" class="hidden flex flex-wrap p-5  border border-red-900 bg-red-100 ">
            <?php displayOrdersList($orders['rejected']) ?>
        </div> 

    <?php endif; ?>

    <h2  class="h2 bg-white text-black mt-3">Historique de mes commandes<span></span><button  id="order-history-button"  class="mx-2 btn btn-gray">Voir</button></h2>
    <div id="order-history" class="hidden p-3 flex flex-wrap align-center">
        <?php displayOrdersListForClient($myOrders) ?>
    </div>
    
</div>