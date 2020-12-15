<?php
namespace App\Controller;

use App\Entity\Category;
use App\Lib\input\Input;
use App\Lib\Input\InputError;
use App\Repository\Repository;
use App\Lib\Session\UserSession;
use App\Repository\UserRepository;
use App\Repository\CategoryRepository;
use App\AbstractClass\AbstractController;

class CategoryController extends AbstractController
{
   public function show()
   {
      $session = new UserSession();
      $user = $session->get('_userStart');
      $session->ifNotConnected();
      $session->ifNotContributor();
      
      $userData = (new UserRepository())->findOneBy('user','id', $user['id']);

      $categoryRepo = new CategoryRepository();
      $category = new Category();

      $categories = $categoryRepo->findAll('category');

      $this->render('admin/category/show',  [
         'email' => $userData['email'],
         'categories' => $categories,
         'session' => $session,
         'law' => intval($userData['law'])
      ] );
   }

   public function create()
   {
      $session = new UserSession();
      $user = $session->get('_userStart');
      $session->ifNotConnected();
      $session->ifNotContributor();

      $userData = (new UserRepository())->findOneBy('user','id', $user['id']);

      if(count($_POST) > 0)
        {  
            $input = new Input();
            $post = $input->cleaner($_POST);
            $empty = $input->isEmpty($_POST);
            $conform = $input->hasGoodformat($_POST);
            
            if (empty($empty) && !in_array(false, $conform))
            {   
               $category  = new Category();
               $category->create($post);
            }
        }

      $this->render('admin/category/create',  [
         'email' => $userData['email'],
         'session' => $session,
         'law' => intval($userData['law'])
      ] );

      (new Repository())->disconnect();

   }


   public function edit($param)
   {  
      $id = intval(strip_tags($param));
      $session = new UserSession();
      $user = $session->get('_userStart');
      $session->ifNotConnected();
      $session->ifNotContributor();

      $userData = (new UserRepository())->findOneBy('user','id', $user['id']);

      $categoryRepo = (new CategoryRepository())->findOneBy('category', 'category_id', $id);
      $input = new Input();

      if(!$categoryRepo)
      {
         $session->set('category','error', (new InputError())::basicError());
         $this->redirectTo('category/show');
         die();
      }

      if(isset($_POST) && count($_POST) > 0)
      {  
         $post = $input->cleaner($_POST);
         $hasGoodFormat = $input->hasGoodformat($_POST);
         $empty = $input->isEmpty($_POST);

         $category = new Category();

         if(in_array(false, $hasGoodFormat) || !empty($empty) )
         {
            $this->redirectTo("category/edit/$id");
            die();
         }else{

            $category->edition($post, $id);
         }

      }

      $this->render('admin/category/edit',  [
         'email' => $userData['email'],
         'category' => $categoryRepo,
         'session' => $session,
         'law' => intval($userData['law'])
      ] );

      (new Repository())->disconnect();

   }
   
   public function delete()
   {    
      $session = new UserSession();
      $user = $session->get('_userStart');
      $session->ifNotConnected();
      $session->ifNotContributor();

      $category = new Category();
      $input = new Input();

      $post = $input->cleaner($_POST);
      $empty = $input->isEmpty($post);

      if(count($_POST) == 0)
      {
         $session->set('category','error', "Veuillez selectionnr un élément à supprimer");
         $this->redirectTo('category/show');
         die();
      }

      if(isset($_POST) && count($_POST) > 0 &&  empty($empty))
      {
         $category->delete($post, $this);
         
      }else{
            
         $session->set('category','error', (new InputError())::basicError());
         $this->redirectTo('category/show');
         die();
      }
   }
}