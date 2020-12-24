/**
 * Displayproduct by categiry
 * @typz {NodeList}
 */
export class Category {

    constructor(container) {

        /** @type {NodeList} this.list items list **/
        this.list = document.querySelectorAll('#navbar-category > div.items > a')
        this.url = '/public/product/getProductByCategory';
        this.productContainer = document.getElementById(container);
        this.moreButton = document.getElementById('get-more-product');
        this.limit = 25;
        this.run()
    }

    run = () =>{

        if( typeof(this.list) === 'object')
        {
            this.addEvent(this.list);
        }
    }

    /**
     * Add event to each category 
     */
    addEvent = () => {

        this.list.forEach( item => {

            item.addEventListener('click', (e) => {
                e.preventDefault();
                const value = parseInt(e.target.getAttribute('data-id'));
                this.sendRequest(value)
            })
        });
    }

    /**
     * Send category id to server
     * @param {number} id category
     * @return {array} an array with multiple object
     */
    sendRequest = (id) => {
        
        const promise = new Promise((resolve, reject) => {

            const data = fetch( this.url + '/' + id, {method: 'post'})
                .then( response => response.ok ? response.json() : false )
                .catch( e => e);

            data.then( res => resolve(res))
            .catch(e => reject(e));
        })

        return promise
            .then((data) => {
                this.refreshView(data); 
                this.createMoreCard(data)
            })
            .catch((e) => e)
        ;
    }

    /**
     * If no product stop all loader animation 
     * @param {Number} length 
     * @returns {boolean}
     */
    ifNoProduct = (length) => {
        
        if(length === 0)
        {
            this.moreButton.remove();
            this.productContainer.textContent = "Aucun produit n'a encore été ajouté"
            
            return true 
        }
        return false;
    }
    /**
     * refresh product list
     * @param {object}  data contains product and category array
     */
    refreshView = (data) => {
        
        if(typeof(data)  === 'object' ){
            this.removeCurrentCard();
            this.createCard(data);
        } else{
            this.error(data)
        };
    }

    /**
     * Destroy all card container;
     */
    removeCurrentCard = () => {
        this.productContainer.innerHTML = '';
    }
    /**
     * @param {object} data contains product and categoriy list 
     */
    createCard = (data) => {

        const product =  data.product;
        const length = product.length < this.limit ? product.length : this.limit

        for (let i = 0; i <  length  ; i++) { 
            const p = product[i];
            const idCategory = parseInt(p.id_category);
            const category =  this.setProductCategory(idCategory, data.category);

            this.setCardProperty( category , p)
        }
    }
    
    /**
     * Setting product category
     * @param {Number} idCategory product id category
     * @param {Array} category categegory list
     * @return {String} good category
     */
    setProductCategory = (idCategory, category) => {

        let categoryName = '';
        
        category.forEach( (c) => {

            if(idCategory === parseInt(c.category_id) )
            {
                categoryName =  c.category_name;
                return;
            }
        });

        return categoryName;
    }

    /**
     * Set property card
     * @param {string} category  product category
     * @param {Array} product 
     */
    setCardProperty = (categoryName, product) => {   
        const a = document.createElement('a');
        const card = document.createElement('div');
        const category = document.createElement('span');
        const img = document.createElement('img');
        const title = document.createElement('span');
        const price = document.createElement('span');

        const productId = product.product_id;
        const productName = product.product_name.replace(' ', '_');

        a.href = '/public/shop/show/' + productId + '/' + productName;
        img.src= '/public/img-storage/default-image.jpg';
        img.style.animation = 'null';
        
        card.classList.add('card');
        title.classList.add('title');
        price.classList.add('price');
        category.classList.add('category');
        
        card.setAttribute('data-id', productId);
        const productPrice = product.product_price.replace('.',',');
        title.textContent = product.product_name.toUpperCase();
        price.textContent = productPrice + ' €';
        category.textContent = categoryName;

        this.productContainer.append(a);

        a.append(card);
        card.append(img);
        card.append(title);
        card.append(price);
        card.append(category);
    }

    error = (e) => {
        console.log(e)
    }

     /**
     * Create more card
     * @param {Array} data contains product and categoriy list 
     */
    createMoreCard = (data) => {

        let limit = this.limit;

            this.moreButton.addEventListener('click', (e)=> {
    
                const active = document.querySelectorAll('#navbar-category > div.items > a[data-state="active"]');
                
                if(active.length === 0)
                {   
                    console.log("Category Désactivé");
                    return;
                }

                const product =  data.product;
    
                for (let i = limit  ; i <  limit + this.limit  ; i++) { 
    
                    let p = product[i];

                    if(p === undefined)
                    {
                        return;
                    }
                    
                    if( !p )
                    {   
                        console.log(p)
                        e.target.textContent = "Vous avez vu tous les nouveaux articles de la catégorie";
                        e.target.style.width = 'max-content';
                        e.preventDefault();
                        return
                    }  
                     
                    const idCategory = parseInt(p.id_category);
                    const category =  this.setProductCategory(idCategory, data.category);
                    this.setCardProperty( category , p);
                }
    
                limit = limit + this.limit; 
            });
    }
}