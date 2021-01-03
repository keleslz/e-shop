/**
 * Add click event on an element to hide it or not
 */
export class Hide {
    
    /**
     * @param {String} button trigger
     * @param {String} element id class of element to hide or not 
     */
    constructor(button, element) {
        this.button = document.getElementById(button);
        this.element = document.getElementById(element);
        this.run();
    }

    run = () => {
        if(this.element && this.button) {

            this.button.addEventListener('click', ()=>{
                this.trigger()
            })
        }
    }

    trigger = () => {
        const hidden = 'hidden';

        if(this.element.classList.contains(hidden)) {
            this.element.classList.remove(hidden);
            this.button.textContent = 'Ne plus voir';
        }else {
            this.element.classList.add(hidden);
            this.button.textContent = 'Voir';
        }
    }
}