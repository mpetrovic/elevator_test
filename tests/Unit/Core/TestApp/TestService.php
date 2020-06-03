<?php

namespace Core\TestApp;
use Core\RequestHandlerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
	* TestService short summary.
	*
	* TestService description.
	*
	* @version 1.0
	* @author Matt
	*/
class TestService implements RequestHandlerInterface {

    #region Core\RequestHandlerInterface Members

    /**
     * Entry point for requests coming through Slim to our app.
     *
     * @param RequestInterface $request 
     * @param ResponseInterface $response 
     * @param array $arguments 
     */
    function handleRequest(RequestInterface $request, ResponseInterface $response, array $arguments) {
        echo __CLASS__;
        $response->getBody()->write('test run successful');
    }

    #endregion
}
