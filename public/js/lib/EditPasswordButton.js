export class EditPasswordButton
{   
    /**
     * Hide or not new and confirm password fields
     * @param {String} button css id
     */
    constructor(button)
    {
        this.button = document.getElementById(button);
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
        this.button.style.cursor = 'pointer';

        this.button.addEventListener('click', (e)=> {
            const itemHidden = document.querySelector('#item-hidden');
            const target = e.target;

            if(itemHidden.classList.contains('hidden'))
            {
                itemHidden.classList.remove('hidden');
                target.textContent = 'Ne pas modifier mon mot de passe';
                
            }else{
                itemHidden.classList.add('hidden');
                target.textContent = 'Modifier mon mot de passe';
                this.clearFields();
            }
        })
    }

    /**
     * Clear new and confirm password fields
     */
    clearFields()
    {
        const newPasswordField = document.querySelector('input[name="newPassword"]');
        const confirmField = document.querySelector('input[name="passwordConfirm"]');

        newPasswordField.value = '';
        confirmField.value = '';;
    }
}