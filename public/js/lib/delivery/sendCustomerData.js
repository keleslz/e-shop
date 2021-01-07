export const sendCustomerData = $(()=> {

    const $button = $('#checkout-button');
    const url = '/public/shop/registreUserOnSession';

    if(!$button[0]) {
        return;
    }

    $button.click((e)=>{
        e.preventDefault();
        sendData();
    })

    /**
     * Send data to server
     */
    const sendData = () => {
        
        $.ajax({
            method : "post",
            url : url,
            dataType : 'json',
            data : customerData() ,                         
            error : (err) => {

                if(err.tatus !== 200)
                {
                    error();
                }
            }
        })
    }

    /**
     * Get customer form data
     */
    const customerData = ()=> {

        return {
            name : $('input[name="name"]')[0].value,
            surname : $('input[name="surname"]')[0].value,
            email : $('input[name="email"]')[0].value,
            address : $('input[name="address"]')[0].value,
            zip : $('input[name="zip"]')[0].value,
            city : $('input[name="city"]')[0].value,
            department : $('select[name="department"]')[0].value
        }
    }
    
    /**
     * destroy html element after a time given
     * @param {HTMLElement} i
     */
    const destroy = (i) =>
    {
        setTimeout( () => {
            i.remove()
        }, 1500)
    }

    const error = () => {
        const $title = $('form h3')[0];
        const $i = $('<i></i>');
        $i.addClass('error');
        $i.html( '<strong>Tous les champs sont obligatoires</strong>')
        $i.insertBefore($title);
        destroy($i[0]);
    }
});