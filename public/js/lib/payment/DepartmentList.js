// TODO Ajouter la liste des départements dans des <option> list
export class DepartmentList {
    constructor () {
        this.jsonPath = '/public/js/data/department.json';
        this.select = document.getElementById('department-list-select');
        this.run();
    }

    run = () => {
        
        if(this.select)
        {
            this.fetch();
        }
    }

    /**
     * Get json data
     */
    fetch = () => {
        const department = fetch(this.jsonPath)
            .then( (response) => response.json() )
            .catch( () => this.error())
        ;
        
       department.then( (data) => this.create(data) );
    }

    /**
     * Create options
     * @param {JSON} data 
     */
    create = (data) => {
        data.forEach( item => {
            const dept = item.departmentName;
            this.option(dept);
        });
    }

    /**
     * department name
     * @param {Strin} departmentName 
     */
    option = (departmentName) => {
        const option =  document.createElement('option');
        option.setAttribute('value', departmentName);
        option.textContent = departmentName
        this.select.append(option);
    }

    error = () => {
        const i = document.createElement('i');
        const container = document.getElementById('delivery-container');
        i.classList.add('error');
        container.append(i);
        i.innerHTML = "Erreur : impossible d'obtenir la liste des départements";
    }
}