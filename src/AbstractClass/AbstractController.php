<?php

namespace App\AbstractClass;

abstract class AbstractController
{   
    public array $var ;

    /**
     * Call a template
     * @param string $template ex ('user/sign-up')
     * @param array $variable to passed
     */
    protected function render(string $template, array $array = [] )
    {   
        $this->var = $array;

        require_once ROOT . DS . 'templates/partials/header.html.php';
        require_once ROOT . DS . 'templates' . DS . $template . '.html.php';
        require_once ROOT . DS . 'templates/partials/footer.html.php';
    }

    /**
     * redirect to templates and kill current execution
     * @param string $path ex $path = user/signin
     */
    public function redirectTo(string $path)
    {
        header('Location:/public/' . $path);
        die();
    }
}