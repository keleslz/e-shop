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
        if(!document.querySelector('#navbar-category'))
        {
            return;
        }
        this.container =  document.querySelector('#navbar-category').children;
        this.run()
    }

    run()
    {
        if( document.querySelector('#navbar-category') !== null )
        {
            let target = this.container[0];
            let listContainer = this.container[1];
            this.open(target, listContainer);
            this.close(target, listContainer);
            this.active();
        }
    }

    /**
     * @param {HTMLElement} target 
     */
    open = (target, listContainer)  =>{
        

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

            target.addEventListener('mouseover', ()=>{

                if(listContainer.classList.contains('hidden'))
                {
                    listContainer.classList.remove('hidden');
                }else{
                    listContainer.classList.add('hidden');
                }
            })
        }
    }

    close = (target, listContainer)  =>{
        
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

    /**
     * Active item clicked
     */
    active = () =>
    {  
        const container = this.container[1].children;
        
        for(let i = 0; i < container.length; i++ )
        {
            const item = container[i];
            item.addEventListener('click', (e)=> {

                const active = document.querySelectorAll("#navbar-category > div > a[data-state='active']");
                
                if(active.length > 0)
                {   
                    active.forEach( active => {

                        active.removeAttribute('data-state');
                        
                    });
                }

                e.target.setAttribute('data-state', 'active');
            })  
        }
    }

    //TODO ajouter style cursor:pointer
}

/* 



*/