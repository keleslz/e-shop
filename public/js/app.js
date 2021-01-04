import { Dropdownbox } from './lib/Dropdownbox.js';
import { AddToCart } from './lib/cart/addToCart.js';
import { getQuantity } from './lib/cart/getQuantity.js';
import { remove } from './lib/cart/remove.js';
import { ProductDisplayer } from './lib/ProductDisplayer.js';
import { DepartmentList } from './lib/delivery/DepartmentList.js';
import { Category } from './lib/Category.js';
import { Sort } from './lib/Sort.js';
import { Sticky } from './lib/Sticky.js';

/** Button */
import { DeleteAccountButton } from './lib/DeleteAccountButton.js';
import { EditPasswordButton } from './lib/EditPasswordButton.js';
import { DeleteUser } from './lib/administration/DeleteUser.js';
import { Hide } from './lib/Hide.js';
import { DisableGoToCheckout } from './lib/DisableGoToCheckout.js'
import { sendCustomerData } from './lib/delivery/sendCustomerData.js';


//Elements

new Dropdownbox({
    header : 'category-header',
    container : 'dropdown',
    items : 'items'
});
new Sticky('nav');
new DisableGoToCheckout('go-to-info-customer','price');
new Hide('product-button-with-category', 'product-with-category');
new Hide('product-button-without-category', 'product-without-category');

/* --------------- shop ---------------------------*/ 
/** home */
new ProductDisplayer('product-container');
new Category('product-container');
new Sort('filter-board-button', 'filter-choice-container');

/** delivery */
new DepartmentList();
sendCustomerData
// new RegistreCustomer('checkout-button');

/* --------------- Cart ---------------------------*/ 
AddToCart;
getQuantity;
remove;

/* --------------- Edit Account ---------------------------*/ 

new EditPasswordButton('edit-password');
new DeleteAccountButton('delete-account-button');

/* --------------- Administration ---------------------------*/ 
new DeleteUser('.delete-account-button');
