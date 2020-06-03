<?php

namespace Controller;

use Core\RequestHandlerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use System\ElevatorSystemInterface;
use Exception;

/**
 * Interface between request handlers and the backend that executes elevator requests.
 *
 * @version 1.0
 * @author Matt
 */
class ElevatorRequest implements RequestHandlerInterface {

    /**
     * The Elevator System we're dealing with.
     * @var ElevatorSystemInterface
     */
    private $elevatorSystem;

    /**
     * Constructor.
     * @param ElevatorSystemInterface $es 
     */
    public function __construct(ElevatorSystemInterface $es) {
        $this->elevatorSystem = $es;
    }

    #region Core\RequestHandlerInterface Members

    /**
     * {@inheritdoc}
     */
    function handleRequest(RequestInterface $request, ResponseInterface $response, array $arguments) {
        
        $body = $request->getParsedBody();
        if (!isset($body['floor'])) {
            $response->withStatus(400);
            $response->getBody()->write('Floor not set and is required.');
        }

        try {
            $this->elevatorSystem->callElevator($body['floor']);
            $response->withStatus(200);
            $response->getBody()->write("Calling elevator to ".$body['floor']);
        }
        catch (Exception $e) {
            $response->withStatus(400);
            $response->getBody()->write("Exception: ".$e->getMessage());
        }

    }

    #endregion
}
