export class DisableGoToCheckout
{
    /**
     * 
     * @param {String} button css id
     */
    constructor(button, price) {
        this.button = document.getElementById(button);
        this.price = document.getElementById(price);
        this.minimumPrice = 0.50;
        this.run();
    }

    run = () => {
        
        if(this.button && this.price)
        {
            this.button.addEventListener('click', (e)=> {
                const target = e.target;
                const price = parseFloat(this.price.textContent);                
                
                if( price < this.minimumPrice )
                {   
                    e.preventDefault();
                    const message = 'Votre panier doit contenir au minimum 0.50€'
                    const span = this.button.children[0].children[1]
                    span.textContent = message;
                    span.style.color = 'red';
                }
            })
        }
    }

}