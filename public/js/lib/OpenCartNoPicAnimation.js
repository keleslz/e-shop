export class OpenCartNoPicAnimation {

    constructor(cssClass) {
        this.elements = document.querySelectorAll(cssClass);
        this.active = 'active';
        this.run();
    }

    run = () => {

        if(this.elements.length > 0)
        {
            this.anime()            
        }
    }

    anime = ()=> {

        for(let i = 0; i < this.elements.length; i++) {
            const element = this.elements[i];
            
            element.addEventListener('click', ()=> {

                const active = document.querySelector('.active');

               
                if(element.classList.contains(this.active)) {
                    element.classList.remove(this.active);
                }else {
                    element.classList.add(this.active);
                }

                if (active) {
                    active.classList.remove(this.active)
                }

            })
        }
    }
}