<?php 
namespace App\Controller;

use PDO;
use App\Lib\Tool;
use App\Lib\input\Input;
use App\Lib\Input\InputError;
use App\Repository\Repository;
use App\Lib\Session\UserSession;
use App\Repository\UserRepository;
use App\Service\User\UserCreation;
use App\AbstractClass\AbstractController;

/**
 * Manage users account, affect status and more
 */
class AdministrationController extends AbstractController
{   
    const USER_TABLE_NAME = 'user';
    const EMAIL_TABLE_FIELD_NAME = 'email';
    const USER_ID_FIELD = 'id';

    /**
     * Show all user account
     */
    public function show () : void
    {
        $session = new UserSession();
        $user = $session->get('_userStart');
        $session->ifNotConnected();
        $session->ifSimpleContributor();
        $session->isClient();
        
        $userRepo = new UserRepository();
        $userData = $userRepo->findOneBy(self::USER_TABLE_NAME,'id', $user['id']);
        //TODO Ajouter la securité si id n'existe pas 
        $accounts  = $userRepo->findAll(self::USER_TABLE_NAME, PDO::FETCH_ASSOC);

        (new Repository())->disconnect();
     
        $this->render('admin/administration/show', [
            'email' => $userData['email'],
            'session' => $session,
            'law' => intval($userData['law']),
            'accounts' => $accounts
        ]);    
    }
    
    /**
     * User who has good law level can create user account
     */
    public function create () : void
    {
        $session = new UserSession();
        $user = $session->get('_userStart');
        $session->ifNotConnected();
        $session->ifSimpleContributor();
        $session->isClient();

        $userData = (new UserRepository())->findOneBy(self::USER_TABLE_NAME,'id', $user['id']);
        
        $error = [];
        
        if( count($_POST) > 0)
        {
            $data =  (new Input)->hasGoodFormat($_POST);
            $data['equal'] =  Tool::equal($_POST['password'], $_POST['confirmPass']) ;
            $error = InputError::get($data);

            if(!in_array( false, $data))
            {   
                $law = intval($_POST['law']);
                (new UserCreation( $law ))->new($_POST['email'], $_POST['password']);
            }
        }

        (new Repository())->disconnect();

        $this->render('admin/administration/create', [
            'email' => $userData['email'],
            'session' => $session,
            'law' => intval($userData['law']),
            'error' => $error,
        ]);    
    }

    //TODO Delete
    /**
     * Get an email and delete him
     * @param string email id 
     */
    public function delete(string $id)
    {   
        header('Content-Type: application/json');
        
        $session = new UserSession();
        $session->ifNotConnected();
        $session->ifSimpleContributor();
        
        //TODO ajouter cette request sur les traces asynchones necessitant d'être caché
        if($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            http_response_code(404);
            die();
        }

        if(isset($_POST) && isset($id))
        {   
            $id = intval(htmlspecialchars($id));
            $userRepo = new UserRepository();

            if($id === 1)
            {   
                http_response_code(401);
                die();
            }

            $userExist = $userRepo->findOneBy( self::USER_TABLE_NAME, self::USER_ID_FIELD, $id);

            if(!$userExist)
            {
                http_response_code(401);
                die();
            }

            $accounToDelete = $userRepo->delete( self::USER_TABLE_NAME, self::USER_ID_FIELD, intval($userExist['id']) );
            $userRepo->disconnect();

            header('Content-Type: application/json');

            if( $accounToDelete )
            {   
                http_response_code(200);
                die();
            }
        }
        http_response_code(401);
    }
}