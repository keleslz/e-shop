/**
 * get quantityof product in cart and display it on quantity input
 */
export const getQuantity = $( () => {

    const $input = $('#cart-quantity');

    if(!$input[0])
    {
        return;
    }

    const id = getId();

    $.ajax({
        method : "post",
        url : "/public/cart/quantity",
        dataType : 'text',  // what to expect back from the PHP script, if anything
        data :  { id } ,                         
        success : (res) => {
            console.log(res)
            const data = JSON.parse(res);
            display(data.quantity)
        }
    });

    /**
     * get product id
     */
    function getId()
    {   
        let url = window.location.href.split('/')
        let id = url[ url.length - 2]
        return parseInt(id);
    }

    /**
     * Quantity
     * @param {Number} quantity 
     */
    function display(quantity)
    {
        $('#cart-quantity').attr('value', quantity)
    }
})