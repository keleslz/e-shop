/**
 * Delete user button
 */
export class DeleteUser
{   
    /**
     * @param {String} button css Class
     */
    constructor(button)
    {
        this.buttons = document.querySelectorAll(button)
        this.userList = document.getElementById('user-list')
        this.run();
    }

    run = () => {
        if(this.buttons)
        {
            this.event()
        }
    }

    /**
     * Add click event
     */
    event = () => {   
        this.buttons.forEach( (button) => {
            
            button.addEventListener('click', (e)=> {
                const target = e.target;
                const container = target.parentNode.parentNode;
                const firstLi = target.parentNode.children[0]
                const email = firstLi.textContent.replace('Email : ', '');
                const message = "Souhaitez-vous supprimer l'utilisateur " + email + " ? ";  
                const id = target.parentNode.getAttribute('data-id');

                confirm(message) 
                    ? this.send(email, id, container) 
                    : e.preventDefault()
                ;
            });
        });
    }

    /**
     * Send element id to delete
     * @param {Number} id 
     * @param {String} email
     * @param {HTMLElement} container element container
     */
    send = (email, id, container) => {
        const promise = new Promise( (resolve, reject) => {
            fetch(
                '/public/administration/delete/' + id,
                {method : 'post'} )
                .then( response => resolve( response ))
                .catch( e => reject(e))
            ;
        });
        
        promise.then( res => this.success(res, email, container))
            .catch( e => this.error())
        ;
    }

    /**
     * Display success message
     * @param {Response} resa response object
     * @param {String} email
     * @param {HTMLElement} element 
     */
    success = (res, email, element) => {
        
        if( res.status === 200)
        {
            const info = document.createElement('i');
            const message = email + ' supprimé avec succès';
            
            this.userList.prepend(info);
            info.innerHTML = message;
            info.classList.add('success')
            element.remove();
            
            setTimeout(()=> {
                info.remove();
            }, 2000);
            
            return;
        }

        this.error(email);
    }

    
    /**
     * Display error message
     * @param {String} email
     * @param {HTMLElement} element 
     */
    error = (email) => {

        const info = document.createElement('i');
        const message = 'Une erreur est survenue lors de la suppression du compte ' + email;

        this.userList.prepend(info);
        info.innerHTML = message;
        info.classList.add('success')
        
        setTimeout(()=> {
            info.remove();
        }, 3000);
    }
}