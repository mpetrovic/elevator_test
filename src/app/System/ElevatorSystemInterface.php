<?php

namespace System;

/**
 * ElevatorSystemInterface.
 *
 * @version 1.0
 * @author Matt
 */
interface ElevatorSystemInterface {

    /**
     * Request for an elevator to pick up passengers at this floor.
     * @param int $floor 
     */
    function callElevator(int $floor);

    /**
     * Request for an elevator to deliver passengers to this floor.
     * @param int $floor 
     */
    function deliverTo(int $floor);
}
