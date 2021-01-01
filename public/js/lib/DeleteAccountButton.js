export class DeleteAccountButton
{
    /**
     * 
     * @param {String} button css Id
     */
    constructor(button)
    {
        this.button = document.getElementById(button)
        this.run();
    }

    run()
    {
        if(this.button)
        {
            this.event();
        }
    }
    
    event()
    {
        this.button.addEventListener('click', (e)=> {
            const message = 'Etes-vous sûr de vouloir supprimer votre compte de manière permanente ? Cette action est irréversible';
            
            if(!confirm(message))
            {
                e.preventDefault();
            }
        })
    }
}