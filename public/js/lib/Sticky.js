/**
 * Set position to an element at 0 when scroll bar is 0
 */
export class Sticky
{
    constructor() {
        this.nav = document.getElementById('nav');
        this.actualPositionScroll();
        this.run();
    }

    /**
     * do if nav exist
     */
    run = () => {

        if(this.nav)
        {   
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
        this.addOrRemoveClass(this.nav);
    }

    /**
     * Add or remove sticky/no-sticky
     * @param {HTMLelement} nav 
     */
    addOrRemoveClass = (nav) => {

        const enable = 'stick';
        const disable = 'no-stick';
        const activationPoint = 0;

        if( window.scrollY > activationPoint)
        {   
            if(nav.classList.contains(disable))
            {
                nav.classList.remove(disable);
            }
            nav.classList.add(enable);
        }else{
            if( nav.classList.contains(enable) ) {
                nav.classList.remove(enable);
            }
            nav.classList.add(disable);
        }
    }
}