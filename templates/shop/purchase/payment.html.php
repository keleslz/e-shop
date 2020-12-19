<?php $cart = $this->var['cart'] ?>
<?php $productsCart = $this->var['productsCart'] ?>
<?php $deliveryCustomerSession = $this->var['customerSession']['delivery'] ?? null ?>
<?php $save = $this->var['saveInputs'] ?? null?>
<?php $totalCost = displayCartList($productsCart, $cart, false) ?>
<?php $adminSession = $this->var['adminSession'] ?>

<?php require_once ROOT . DS . 'templates/partials/nav/base-nav.html.php' ?>
<?php require_once ROOT . DS . 'templates/partials/nav/e-shop-nav.html.php' ?>

<h1 class="h2 mb-0 block shadow-sm">Informations de paiement</h1>

<?php $this->var['session']->display() ?>

<?= ifNotConnectedMessage($adminSession) ?>

<div class="h-screen flex justify-center items-start pt-10 text-sm font-sans w-full mb-32">

    <form  action="" method="post" class="flex flex-col px-5 pt-6 pb-10 rounded-t-lg shadow-lg">

       <h3 class="font-bold text-center text-2xl pb-3">Information de facturation</h3>

        <div class="flex flex-col py-2 pb-3">
            <label class="mb-2" for="name">Nom</label>
            <input class="p-1" type="text" name="name" required value="<?=  $save['name'] ?? $deliveryCustomerSession->getName() ?>">
        </div>

        <div class="flex flex-col py-2 pb-3 ">      
            <label class="mb-2" for="surname">Prenom</label>
            <input class="p-1" type="text" name="surname" required value="<?= $save['surname'] ?? $deliveryCustomerSession->getSurname() ?>">
        </div>      

        <div class="flex flex-col py-2 pb-3 ">      
            <label class="mb-2" for="address">Adresse</label>
            <input class="p-1" type="text" name="address" required value="<?=  $save['address'] ?? $deliveryCustomerSession->getAddress() ?>">
        </div>      
        
        <div class="flex flex-col py-2 pb-3 ">      
            <label class="mb-2" for="creditCardNumber">Numero carte</label>
            <input class="p-1" type="text" name="creditCardNumber" placeholder="XXXX-XXXX-XXXX-XXXX" required value="<?= $save['creditCardNumber'] ?? '' ?>">
        </div>     
        
        <div class="flex flex-col py-2 pb-3 ">      
            <label class="mb-2" for="expirationDate">Date d'expiration</label>
            <input class="p-1" type="text" name="expirationDate" placeholder="MM/AA" required value="<?= $save['expirationDate'] ?? '' ?>">
        </div>     
        
        <div class="flex flex-col py-2 pb-3 ">      
            <label class="mb-2" for="cryptoNumber">Numero cryptogramme</label>
            <small><span class="text-blue-500">Au dos </span> de votre carte bleu</small>
            <input class="p-1" type="text" name="cryptoNumber" placeholder="123" required value="<?= $save['cryptoNumber'] ?? '' ?>">
        </div>

        <div class="flex flex-col py-2 ">      
          <label class="mb-2" for="department">Departement</label>
            <select id="department-list-select" name="department" required>
                <option value="">-- Choisissez -- </option>
            </select>
        </div>
        
        <div class="flex flex-col mt-10 ">      
            <button id="checkout-button" class="mb-2 bg-transparent hover:bg-green-500 text-blue-700 font-semibold hover:text-white 
                py-1 px-1 border border-blue-500 hover:border-green-500 rounded" type="submit"
            >Payer les <?= totalPriceForAllCart($totalCost) ?> €</p>
            </button>
        </div>

    </form>
</div>
