/**
 * Set position to an element at 0 when scroll bar is 0
 */
export class Sticky
{
    constructor(element) {
        this.nav = document.getElementById(element);
        this.run();
    }

    /**
     * do if nav exist
     */
    run = () => {

        if(this.nav)
        {   
            this.actualPositionScroll();
            this.event();
        }
    }

    /**
     * Listen sccroll bar
     */
    event = () => {

        window.addEventListener('scroll', ()=> {
            
            this.addOrRemoveClass(this.nav);
        });
    }

    /**
     * scroll bar actual position
     */
    actualPositionScroll = () => {
        this.addOrRemoveClass();
    }

    /**
     * Add or remove sticky/no-sticky
     * @param {HTMLelement} nav 
     */
    addOrRemoveClass = () => {

        const enable = 'stick';
        const disable = 'no-stick';
        const activationPoint = 0;

        if( window.scrollY > activationPoint)
        {   
            if(this.nav.classList.contains(disable))
            {   
                this.nav.classList.remove(disable);
            }
            this.nav.classList.add(enable);
        }else{
            if( this.nav.classList.contains(enable) ) {
                this.nav.classList.remove(enable);
            }
            this.nav.classList.add(disable);
        }
    }
}