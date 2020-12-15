<?php

/**
 * @class get $_SERVER['REQUEST_URI']
 */
class Request 
{   
    /**
     * Get $_SERVER['REQUEST_URI'] and delete '/public/'
     */
    public function getPath()
    {
        return str_replace('/public/', '', $_SERVER['REQUEST_URI']);
    }
}