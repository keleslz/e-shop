export class ProductDisplayer {

    constructor(container)
    {   
        this.url = '/public/product/getAll';
        this.container = document.getElementById(container);
        this.run();
    }

    run = () => {
        
        if(this.container)
        {
            const data = this.fetch();
            this.handle(data);
        }
    }

    /**
     * fetch products
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

    handle = (data) => {

        data.then((d)=>{
            if(d === undefined)
            {
                return;
            }

            this.createCard(d);
            this.stopLoader();
            this.stopCardLoad();

        })
    }
    
    /**
     * @param {Array} data contains product and categoriy list 
     */
    createCard = (data) => {
        const product =  data.product;
        const limit = 25;
        const length = product.length < limit ? product.length : limit

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
        card.classList.add('card');
        title.classList.add('title');
        price.classList.add('price');
        category.classList.add('category');
        
        card.setAttribute('data-id', productId);
        const productPrice = product.product_price.replace('.',',');
        title.textContent = product.product_name.toUpperCase();
        price.textContent = productPrice + ' €';
        category.textContent = categoryName;

        this.container.append(a);

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
        this.container.append(i);
        i.innerHTML = 'Désolé une erreur est survenue';
    }

    /**
     * Stop loading animation
     */
    stopLoader = () => {
        const loader = document.getElementById('loader');
        loader.classList.add('hidden');
    }

    /**
     * Stop loading card animation
     */
    stopCardLoad = () => {
        const card = document.getElementById('card-loader');
        card.remove();
    }
}