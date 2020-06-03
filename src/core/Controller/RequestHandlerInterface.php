<?php

namespace Core;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * RequestHandlerInterface for routes.
 *
 * Every route handler should implement this for consistency's sake.
 *
 * @version 1.0
 * @author Matt
 */
interface RequestHandlerInterface {

   /**
    * Entry point for requests coming through Slim to our app. 

    * @param RequestInterface $request 
    * @param ResponseInterface $response 
    * @param array $arguments 
    */
   function handleRequest(RequestInterface $request, ResponseInterface $response, array $arguments);
    
}
