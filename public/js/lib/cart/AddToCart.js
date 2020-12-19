/**
 * Add article to cart
 */
export const AddToCart = $(() => {

    addProduct()
    negativeNumberDisable();
    disableSubmitButton();

    /**
     * Event caller ajax function
     */
    function addProduct()
    {
        const $submitButton = $('#add-on-cart');
        
        $submitButton.click((e)=>{
            e.preventDefault();
            sendToCart();
        })
    }
    
    /**
     * Send data to server
     */
    function sendToCart()
    {
        const product = setProductInfo();

        $.ajax({
            method : "post",
            url : "/public/cart/add",
            dataType : 'text',  // what to expect back from the PHP script, if anything
            data :  { product } ,                         
            success : (res) => {
                // console.log(res)
                const data = JSON.parse(res);
                // console.log(data);
                refreshCartQuantity(data.count)
            }
        });
    }

    /**
     * set product id and name thanks url
     * @returns {Object} contains name and id product
     */
    function setProductInfo() 
    {
        const request = window.location.href.split('/');
        const last = request.length - 1;
        const beforeLast = request.length - 2;
        const $quantityInput = $('#cart-quantity').val();

        return {
            name : request[last],
            id : parseInt(request[beforeLast]),
            quantity :  parseInt($quantityInput),
        }
    }

    /**
     * Disable negative number on input quantity
     */
    function negativeNumberDisable()
    {
        const $inputQuantity = $('#cart-quantity');
        
        $inputQuantity.change((e)=>{
            
            let $quantity = $inputQuantity.val();

            if($quantity < 1 )
            {
                $inputQuantity.val(0)
            }
        })
    }

    /**
     * Disable submit button
     */
    function disableSubmitButton()
    {   
        const $submitButton = $('#add-on-cart');
        const $input = $('#cart-quantity');

        $input.change((e)=> {

            let $quantity = $input.val();

            if($quantity < 1 )
            {
               !$submitButton.hasClass('disable') ? $submitButton.addClass('disable').removeClass('enabled') : '' ;
              
            }else {
               !$submitButton.hasClass('enabled') ? $submitButton.addClass('enabled').removeClass('disable') : '' ;
            }
        })
    }

    /**
     * @param {Number} quantity quantity stored on cart
     */
    function refreshCartQuantity(quantity)
    { 
        let count = parseInt(quantity);
        let span =  $('#cart-quantity-stored')[0];

        if(!span)
        {
            return;
        }

        span.innerHTML = count;
    }
})