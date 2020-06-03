<?php

namespace System;
use Exception;

/**
	* ElevatorSystem short summary.
	*
	* ElevatorSystem description.
	*
	* @version 1.0
	* @author Matt
	*/
class ElevatorSystem implements ElevatorSystemInterface {


    #region System\ElevatorSystemInterface Members

    /**
     * Request for an elevator to pick up passengers at this floor.
     *
     * @param int $floor
     */
    function callElevator(int $floor) : void {
        if ($this->validateFloor($floor)) {
            // instant fulfillment for now
        }
        else {
            throw new Exception("Floor out of bounds.");
        }
    }

    /**
     * Request for an elevator to deliver passengers to this floor.
     *
     * @param int $floor
     */
    function deliverTo(int $floor) : void {
        if ($this->validateFloor($floor)) {
            // instant fulfillment for now
        }
        else {
            throw new Exception("Floor out of bounds.");
        }
    }

    #endregion

    private function validateFloor(int $floor) : bool {
        if ($floor > $_ENV['FLOOR_COUNT'] || $floor < 1) {
            return false;
        }

        return true;
    }
}
