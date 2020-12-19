Lien de previsualisation du projet : 
    - http://3e8spahifw.preview.infomaniak.website/public/shop/home

#e-shop

Une boutique en ligne fait main sans framework en architecture.
Organistation de la structure inspirée de Symfony 5.

1. Base de donnée

    Configuration Base de donnée :
    
        Défini dans des constantes : 
        
            - /src/Repository/Repository --->
                const HOST = 'localhost:3308';
                const DB_NAME = 'e_shop';
                const USERNAME = "root";
                const PASSWORD = "";

    CREATION DES TABLES :

        Fichier e_shop.sql

        Fourni un admin de base : 
            email :  Admin@fr.Fr
            password : Admin123*

        Fourni clients de base : 
            email :  b@fr.Fr
            password : Client123*

            email :  c@fr.Fr
            password : Client123*



2. Amélioration

    Un design basique , des améliorations arrivent très prochainement :
    
    Côté front :
        - Integration Ajax
        - Integration ReactJS 
        - Integration Webpack

    Coté Back :
        - Integration moyen de paiement (Stripe)
        - Amelioration du code en général


