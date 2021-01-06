/**
 * dynamic update of orders
 */
export const updateStateOrder = $(()=> {

    const url = '/public/order/update';
    const $acceptButtons = $('.order .valid');
    const $rejectButtons = $('.order .reject');

    if(!$acceptButtons && !$rejectButtons)
    {
        return;
    }
    
    /**Counter */
    const noValidatedCount = $('#no-validated-counter');
    const validatedCount = $('#validated-counter');
    const rejectedCount = $('#rejected-counter');

    /**OrderListContainer */
    const orderNoValidatedContainer = $('#order-to-validated');
    
    /**
     * empty list message to user
     * @param {array} containerList
     */
    const noElement = () => {

        if(orderNoValidatedContainer[0].childElementCount === 0) {
            orderNoValidatedContainer[0].innerHTML = '<p>Rien a signaler par ici ..</p>';            
        }
    }
    /**
     * format string counter and remove all space
     * @param {String} counter  
     * @returns {object} with two members of a counter . left (currentCount) and right (/total) member
     */
    const formatCounter = (counter)=> {
        return {
            left : parseInt(counter.textContent.replace(' ','').split('/')[0]),
            right : parseInt(counter.textContent.replace(' ','').split('/')[1])
        }
    } 

    /**
     * Refresh quantity of list 
     * @param {string} choice accept or reject
     */
    const refreshQuantityList = (choice) => {
        const noValidatedcurrentCounter = formatCounter(noValidatedCount[0]).left ;
        const noValidatedTotal = formatCounter(noValidatedCount[0]).right ;

        const validatedcurrentCounter =  formatCounter(validatedCount[0]).left;
        const validatedCountTotal =  formatCounter(validatedCount[0]).right;
        
        const rejectedcurrentCounter =  formatCounter(rejectedCount[0]).left;
        const rejectedCountTotal =  formatCounter(rejectedCount[0]).right;


        const decrementNoValidatedcurrentCounter = noValidatedcurrentCounter - 1;
        noValidatedCount.html(decrementNoValidatedcurrentCounter + '/' + noValidatedTotal );

        if (choice === 'accept')
        {   
            validatedCount.html( (validatedcurrentCounter + 1) + '/' + validatedCountTotal);
        }

        if (choice === 'reject')
        {   
            rejectedCount.html( (rejectedcurrentCounter + 1) + '/' + rejectedCountTotal);
        }

        noElement();
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

    /**
     * Move element to his new destination
     * @param {HTMLELement} elementToMove 
     */
    const moveElement = (elementToMove, choice) => {
        const element = elementToMove.parentNode.parentNode
        const elementRejected = $('#order-rejected') ;
        const elementAccepted = $('#order-validated') ;

        if( choice === 'accept') {

            elementAccepted[0].appendChild(element);
            element.children[8].remove(); //remove 8th element
            element.children[8].remove(); // remove 8th element which was been old 9th 
        }

        if( choice === 'reject') {

            elementRejected[0].appendChild(element);
        }

        refreshQuantityList(choice);
    }

    /**
     * Display success message
     */
    const success = () => {
        const $title = $('.cont h2')[0];
        const $i = $('<i></i>');
        $i.addClass('success');
        $i.html( '<strong>Commande mise a jour</strong>')
        $i.insertBefore($title);
        destroy($i[0]);
    }

    /**
     * display error message
     */
    const error = () => {
        console.error('Echec');
    }

    /**
     * @param {HTMLElement} target html elemet to move
     * @param {Number} id order id
     * @param {String} choice 'valid' or 'reject'
     */
    const sendData = (target, id, choice) => {
        const data = {
                id : id ,
                choice : choice 
            }
        $.ajax({
            method : "post",
            url : url,
            dataType : 'json',  // what to expect back from the PHP script, if anything
            data :  {data : data} ,                         
            success : (res) => {
                
                if(typeof res === 'object' && res.success === true)
                {
                    success();
                    moveElement(target, res.choice);
                }
            },
            error : (err) => {
                if(err.status !== 200)
                {   
                    console.log(err)
                    error();
                }
            }
        });
    } 
    
    /**
     * @param {Object} $buttons 
     */
    const getData = ( $buttons ) => {

        $buttons.click((e)=>{
            const target = e.target;
            const id = parseInt(target.getAttribute('data-id'));
            const choice = target.getAttribute('data-choice');
            sendData(target, id, choice);
        })
    }

    getData($acceptButtons);
    getData($rejectButtons);
    
})