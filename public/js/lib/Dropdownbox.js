export class Dropdownbox
{   
    /**
     * Valid valid
     * 
     * Work with any HTML tag 
     * 
     *  <ul id="navbar-category">
     *      <a>Categories</a>
     *      <div class="items bg-black p-2 hidden">
     *          <li class="items">Items..</li>
     *          <li class="items">Items..</li>
     *          <li class="items">Items..</li>
     *          <li class="items">Items..</li>
     *          <li class="items">Items..</li>
     *      </div>
     *  </ul> 
     */
    constructor()
    {
        this.run()
    }

    run()
    {
        if( document.querySelector('#navbar-category') !== null )
        {
            let container =  document.querySelector('#navbar-category').children;
            let target = container[0];
            let listContainer = container[1];
            this.open(target, listContainer);
            this.close(target, listContainer);
        }
    }

    /**
     * @param {HTMLElement} target 
     */
    open(target, listContainer) {
        

        if(target)
        {
            target.addEventListener('click', ()=>{

                if(listContainer.classList.contains('hidden'))
                {
                    listContainer.classList.remove('hidden');
                }else{
                    listContainer.classList.add('hidden');
                }
            })
        }
    }

    close(target, listContainer) {
        
        window.addEventListener('click', (e)=> {
            
            if(e.target !== target)
            {
                if(!listContainer.classList.contains('hidden'))
                {
                    listContainer.classList.add('hidden');
                }
            }
        })
    }
}

/* 



*/