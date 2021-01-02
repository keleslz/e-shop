export class ProductDisplayer {

    constructor(container)
    {   
        this.url = '/public/product/getAll';
        this.limit = 25;
        this.moreButton = document.getElementById('get-more-product');
        this.productContainer = document.getElementById(container);
        this.categoryList = document.querySelectorAll('#navbar-category > div.items > a')

        this.run();
    }

    run = () => {
        
        if(this.productContainer)
        {
            const promise = this.fetch();
            this.handle(promise);
        }
    }

    /**
     * fetch products from server
     */
    fetch = () => {
        return fetch(this.url)
            .then( (response) => 
            {   
                return response.json();
            })
            .catch( () => {this.error()} )
        ;
    }

    /**
     * Handle product and dispaly card;
     * @param {Promise} data product promise 
     */
    handle = (data) => {

        return data.then((d)=>{
            if(d === undefined)
            {
                return;
            }

            if(this.ifNoProduct(d.product.length))
            {
                return;
            }
            
            this.createCard(d);
            this.stopLoader();
            this.stopCardLoad();
            this.createMoreCard(d)
        })
    }
    
    /**
     * @param {Array} data contains product and categoriy list 
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
    /*
        TODO remplacer le nom du produit dans l'url par son slug && afficher seulement si le produit est en ligne
    */
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

    /**
     * Display error
     */
    error = () => {
        const i = document.createElement('i');
        i.classList.add('error');
        this.productContainer.append(i);
        i.innerHTML = 'Désolé une erreur est survenue';
    }

    /**
     * Stop loading animation
     */
    stopLoader = () => {
        const loader = document.getElementById('loader');
        loader.classList.add('hidden');
        this.productContainer.append(loader);
    }

    /**
     * Stop loading card animation
     */
    stopCardLoad = () => {
        const card = document.getElementById('card-loader');
        card.remove();
    }

    /**
     * Create more card
     * @param {Array} data contains product and categoriy list 
     */
    createMoreCard = (data) => {

        let limit = this.limit;
        const loader = document.getElementById('loader');

        this.moreButton.addEventListener('click', (e)=> {
            const active = document.querySelectorAll('#navbar-category > div.items > a[data-state="active"]');

            if(active.length > 0)
            {   
                e.target.removeEventListener('click', (e)=> e )
                return;
            }

            const product =  data.product;

            for (let i = limit  ; i <  limit + this.limit  ; i++) { 

                let p = product[i];

                if( !p )
                {
                    e.target.textContent = "Vous avez vu tous les nouveaux articles";
                    e.target.style.width = 'max-content';
                    e.preventDefault();
                    return
                }

                const idCategory = parseInt(p.id_category);
                const category =  this.setProductCategory(idCategory, data.category);
                this.setCardProperty( category , p);
                this.productContainer.append(loader)
            }

            limit = limit + this.limit;
        })
    }

    /**
     * If no product stop all loader animation 
     * @param {Number} length 
     * @returns {boolean}
     */
    ifNoProduct = (length) => {
        if(length === 0)
        {
            this.stopLoader();
            this.stopCardLoad();   
            this.moreButton.remove();
            this.productContainer.textContent = "Aucun produit n'a encore été ajouté"
            
            return true 
        }
        return false;
    }
}