<?php

/**
 * NotFoundException
 *
 *
 */
class Application_Model_Exceptions_NotFoundException extends Exception
{

    /**
     * Constructor
     */
    public function __construct($msg)
    {
        parent::__construct($msg);
    }

}
