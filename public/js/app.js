import { Dropdownbox } from './lib/Dropdownbox.js';
import { AddToCart } from './lib/cart/addToCart.js';
import { getOneCategory } from './lib/category/getOneCategory.js';

//Elements

new Dropdownbox({
    header : 'category-header',
    container : 'dropdown',
    items : 'items'
});

//Cart
AddToCart;

//category
getOneCategory