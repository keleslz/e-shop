export const sendCustomerData = $(()=> {

    const $button = $('#checkout-button');
    const url = '/public/shop/registreUserOnSession';

    if(!$button[0]) {
        return;
    }

    $button.click((e)=>{
        e.preventDefault();
        console.log();
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
            success : (res) => {
                const data = JSON.parse(res);
            },
            error : (err) => {
                error();
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

    const error = () => {

    }
});