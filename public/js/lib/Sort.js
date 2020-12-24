/*
    TODO Ne pas trier les donées en les recuperant sur le serveur mais plutot trier celle deja presente sur la page en 
    TODO conservant le tri désiré a chaque fois que l'on fait "voir plus" !! important !!
*/
export class Sort {

    /**
     * @param {String} button Html id interact to open or close
     * @param {String} choiceContainer html id That contains all choice
     */
    constructor(button, choiceContainer) {
        this.button = document.getElementById(button);
        this.choiceContainer = document.getElementById(choiceContainer);
        this.inputs =  document.querySelectorAll('#' + choiceContainer + ' input[type="radio"]')
        this.run();
    }

    run = () => {

        if(this.button && this.choiceContainer && this.inputs.length > 0)
        {
            this.openOrClose();
            this.by();
        }
    }

    /**
     * Open or close filter board choice
     */
    openOrClose = () => {

        this.button.addEventListener('click', ()=> {

            this.choiceContainer.classList.contains('hidden')
                ? this.choiceContainer.classList.remove('hidden')
                : this.choiceContainer.classList.add('hidden')
            ;
        })
    }

    /**
     * Sorty by "value" send by client
     */
    by = () => {
        this.inputs.forEach( input => {

            input.addEventListener('click', (e)=> {
                const target = e.target;
                const value = target.value;
                this.request(value);
            });
        });
    }

    /**
     * Send request to server and receive data sorted 
     * @param {string} targetValue a value which contains the asc or desc therm
     */
    request = (targetValue) => {
        const req = new Promise((resolve, reject)=> {
        const url = '/public/product/byFilter/' + targetValue;

            fetch(url, {
                method: 'post',
                body: { "sort" : targetValue }
            })
                .then( success => resolve(success.json()) )
                .catch( e => reject(e))
            ;
        })

        req.then( (s) => console.log(s) )
            .catch( (e) => console.error(e))

    }
}

