<?php $save = $this->var['saveInputs'] ?? null ?>
<?php $adminSession = $this->var['adminSession'] ?>

<?php require_once ROOT . DS . 'templates/partials/nav/base-nav.html.php' ?>
<?php require_once ROOT . DS . 'templates/partials/nav/e-shop-nav.html.php' ?>

<h1 class="h2 mb-0 block shadow-sm">Informations de paiement</h1>

<?php $this->var['session']->display() ?>

<?= ifNotConnectedMessage($adminSession) ?>

<div id="delivery-container" class="h-screen flex justify-center items-start pt-10 text-sm font-sans w-full mb-32">

    <form  method="post" class="flex flex-col px-5 pt-6 pb-10 rounded-t-lg shadow-lg">

       <h3 class="font-bold text-center text-2xl pb-3">Information de livraison</h3>

        <div class="flex flex-col py-2 pb-3">
            <label class="mb-2" for="name">Nom</label>
            <input type="text" name="name" required value="<?= $save['name'] ?? '' ?>">
        </div>

        <div class="flex flex-col py-2 pb-3 ">      
            <label class="mb-2" for="surname">Prenom</label>
            <input type="text" name="surname" required value="<?= $save['surname'] ?? '' ?>">
        </div>    

        <div class="flex flex-col py-2 pb-3 ">      
            <label class="mb-2" for="email">Email</label>
            <input type="email" name="email" required value="<?= $save['email'] ?? '' ?>">
        </div>    

        <div class="flex flex-col py-2 pb-3 ">      
            <label class="mb-2" for="address">Adresse</label>
            <input type="text" name="address" required value="<?= $save['address'] ?? '' ?>">
        </div>      
        
        <div class="flex flex-col py-2 pb-3 ">      
            <label class="mb-2" for="zip">Code postale</label>
            <input type="text" name="zip" required value="<?= $save['zip'] ?? '' ?>">
        </div>     
        
        <div class="flex flex-col py-2 pb-3 ">      
            <label class="mb-2" for="city">Ville</label>
            <input type="text" name="city" required value="<?= $save['city'] ?? '' ?>">
        </div>
        
        <div class="flex flex-col py-2 ">      
          <label class="mb-2" for="department">Departement</label>
            <select id="department-list-select" name="department" required>
                <option value="">-- Choisissez -- </option>
            </select>
        </div>

        <div class="flex flex-col mt-10 ">      
            <button 
                id="checkout-button" 
                class="mb-2 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white 
                py-1 px-1 border border-blue-500 hover:border-transparent rounded" 
                type="submit"
            >Payer les X€
            </button>
        </div>
    </form>
</div>
//TODO afficher le prix
<script type="text/javascript" src="/public/js/api/stripe/checkout.js"></script>

