import { Dropdownbox } from './lib/Dropdownbox.js';
import { AddToCart } from './lib/cart/addToCart.js';
import { getOneCategory } from './lib/category/getOneCategory.js';
import { getQuantity } from './lib/cart/getQuantity.js';
import { remove } from './lib/cart/remove.js';
import { ProductDisplayer } from './lib/ProductDisplayer.js';

//Elements

new Dropdownbox({
    header : 'category-header',
    container : 'dropdown',
    items : 'items'
});

new ProductDisplayer('product-container');

//Cart
AddToCart;
getQuantity;
remove;
//category
getOneCategory