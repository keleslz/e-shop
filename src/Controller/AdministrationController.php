<?php 
namespace App\Controller;

use PDO;
use App\Lib\Tool;
use App\Entity\User;
use App\Lib\input\Input;
use App\Lib\Input\InputError;
use App\Repository\Repository;
use App\Lib\Session\UserSession;
use App\Repository\UserRepository;
use App\Service\User\UserCreation;
use App\AbstractClass\AbstractController;
//TODO Verifier les droits par niveau de role, fixer les valeur des roles 100 1000 1000 etc..
//TODO Verifier que le ifNot Contributor ne nous bloque pas 

/**
 * Manage users account, affect status and more
 */
class AdministrationController extends AbstractController
{   
    const USER_TABLE_NAME = 'user';
    /**
     * Show all user account
     */
    public function show () : void
    {
        $session = new UserSession();
        $user = $session->get('_userStart');
        $session->ifNotConnected();
        $session->ifNotContributor();


        $userRepo = new UserRepository();
        $userData = $userRepo->findOneBy(self::USER_TABLE_NAME,'id', $user['id']);
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
        $session->ifNotContributor();

        $userData = (new UserRepository())->findOneBy(self::USER_TABLE_NAME,'id', $user['id']);
        
        $error = [];
        
        if( count($_POST) > 0)
        {
            $data =  (new Input)->hasGoodFormat($_POST);
            $data['equal'] =  Tool::equal($_POST['password'], $_POST['confirmPass']) ;
            $error = InputError::get($data);

            if(!in_array( false, $data))
            {   
                $law = intval(str_replace('_','', $_POST['law']));
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
}