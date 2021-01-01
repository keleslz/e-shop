import { Dropdownbox } from './lib/Dropdownbox.js';
import { AddToCart } from './lib/cart/addToCart.js';
import { getQuantity } from './lib/cart/getQuantity.js';
import { remove } from './lib/cart/remove.js';
import { ProductDisplayer } from './lib/ProductDisplayer.js';
import { DepartmentList } from './lib/payment/DepartmentList.js';
import { Category } from './lib/Category.js';
import { Sort } from './lib/Sort.js';

/** Button */
import { DeleteAccountButton } from './lib/DeleteAccountButton.js';
import { EditPasswordButton } from './lib/EditPasswordButton.js';
import { DeleteUser } from './lib/administration/DeleteUser.js';

//Elements

new Dropdownbox({
    header : 'category-header',
    container : 'dropdown',
    items : 'items'
});

//shop/home
new ProductDisplayer('product-container');
new Category('product-container');
new Sort('filter-board-button', 'filter-choice-container');

//Cart
AddToCart;
getQuantity;
remove;

new DepartmentList();

//Edit Account
new EditPasswordButton('edit-password');
new DeleteAccountButton('delete-account-button');

//Administration
new DeleteUser('.delete-account-button');