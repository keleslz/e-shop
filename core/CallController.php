<?php

/**
 * Call the right controller depending on the method
 * @contrcutor strin
 */
class CallController
{

    public function __construct()
    { 
       $req = (new Request())->getPath();
       $pathSplited = $this->splitPath($req);
       $this->get($pathSplited);
    }

    /**
     * Get a Controller thank path splited
     * @param array $pathSplited an array provided by Request
     */
    private function get(array $pathSplited)
    {   
        $controller = ucfirst($pathSplited['controller']) . 'Controller';
        $method = $pathSplited['method'];
        $param = $pathSplited['param'];

        if ( !$this->catchController($controller) ) // if true has no error
        {   
            $this->catchMethod($controller, $method, $param);
        }
    }
    
    /**
     * Split a request path in 3 part :
     * - controller called
     * - method called in controller
     * - [number] optionnal
     * 
     * @param string $path ex : /user/signin/[param]
     * @return array contains each part
     */
    private function splitPath(string $path)
    {
        $part =  explode('/', $path) ;

        return 
        [
            'controller' => $part[0] ?? null,
            'method' => $part[1] ?? null,
            'param' => $part[2] ?? null,
        ];
    }

    /**
     * Stop exec if controller name is not correct
     */
    private function catchController($controller) : bool
    {   
        $error = false;

        // include_once  ROOT . DS . 'src/Controller/' . $controller . '.php';
        
        if(! @include_once  ROOT . DS . 'src/Controller/' . $controller . '.php' )
        {
            try {

                throw new Exception();

            } catch (\Throwable $th) {

                header("HTTP/1.0 404 Not Found");
                include_once ROOT . DS . 'templates/error/404.html.php';
                die();
            }

            $error = true;
        }
        return $error; 
    }

    private function catchMethod($controller, $method, $number)
    {
        try {

            $fullPath = 'App\Controller\\' . $controller ;
            (new $fullPath)->$method($number);

        } catch (\Throwable $th) {
            header("HTTP/1.0 404 Not Found");
            include_once ROOT . DS . 'templates/error/404.html.php';
            die();
        }
    }
}