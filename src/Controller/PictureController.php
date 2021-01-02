<?php
namespace App\Controller;

use App\Lib\Session\UserSession;
use App\Repository\ImageRepository;
use App\AbstractClass\AbstractController;

class PictureController extends AbstractController
{   
    /**
     * Delete picture associated to a product 
     */
    public function delete($id)
    {   
        $id = intval(strip_tags($id));
        $session = new UserSession();
        $user = $session->get('_userStart');
        $session->ifNotConnected();
        $session->ifSimpleContributor();
        $session->isClient();

        $imageRepo = new ImageRepository();
        $image = $imageRepo->findOneBy('img', 'img_id', $id);

        if($image)
        {      
            $imageRepo->delete('img', 'img_id', intval($image['img_id']))
                ? $session->set('miniImage','success','Image supprimée')
                : $session->set('miniImage','success','Désolé une erreur est survenue , suppresion impossible') 
            ;

            unlink($image['img_path']);
            $this->redirectTo('product/edition/' . $image['id_product']);

        }else{
            $session->set('image','error','Désolé une erreur est survenue');
            $this->redirectTo('product/show');
        }
    }
}