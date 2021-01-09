/**
 * remove product on cart
 */
export const remove = $( () => {

    if(!$('.remove-product')[0])
    {
        return;
    }
    
    /**
     * Remove product
     */
    const eventRemove = () => 
    {
        const $buttons = $('.remove-product');

        $buttons.on('click', (e) => {
            e.preventDefault();
            const id = e.currentTarget.getAttribute('data-id');
            sendId(id)
        })
    }

    /**
     * Send data to server
     * @param {Number} id product id
     */
    const sendId = (id) =>
    {
        $.ajax({
            method : "post",
            url : "/public/cart/remove",
            dataType : 'text',  // what to expect back from the PHP script, if anything
            data :  { id } ,                         
            success : (res) => {
                const data = JSON.parse(res);
                success(data.id);
            },
            error : (e) => {
                error(e)
            }
        });
    }

    /**
     * Refresh price
     * @param {HTMLButtonElement} $button parent of one cart
     */
    const refreshPrice = ($button) => {
        let $totalPrice = $('#price');
        let price = $('#price').html();
        $.each(  $button.siblings(), (key, value) => {

            const val = value.children[0];

            if(val !== undefined )
            {
                const haveClass = val.classList.contains('one-cart-total-price');

                if(haveClass)
                {
                   const productPrice = parseFloat(val.innerHTML);
                   price =  Math.round((price - productPrice) * 100) /100
                   return;
                }
            }
        });
        
        $totalPrice.html(price)
    }
      /**
     * @param {Number} quantity reduce quantity stored on cart
     */
    const refreshCartQuantity = () => 
    { 
        let span =  $('#cart-quantity-stored');
        let count = parseInt(span.html()) - 1;
        span[0].innerHTML = count ;
    }

    /**
     * Destroy product
     * @param {Number} idProduct
     */
    const success = (idProduct) => {
        const $removeButton = $(".remove-product[data-id=" + parseInt(idProduct) + "]");
        const $a = $removeButton.parent().parent().parent();
        refreshPrice($removeButton);
        refreshCartQuantity();
        $a.remove();
    }

    /**
     * Don't destroy an alert user
     */
    const error = () => {
        const $i = $('i');
        $i.html('Une erreur est survenue');
    }
    
    /**
     * Disable Go to purchase button
     */
    const disableGotToPurchaseButton = (price) => {

        if(parseFloat(price) === 0)
        {
            price.addClass('disable');
        }
    }

    eventRemove();
})