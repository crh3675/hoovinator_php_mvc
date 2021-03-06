<?php

/**
 * Class Error
 * This controller simply shows a page that will be displayed when a controller/method is not found.
 * Simple 404 handling.
 */
class ErrorController extends MVC\Framework\Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * This method controls what happens / what the user sees when an error happens (404)
     */
    function display()
    {
        $this->view->render('error/index', array( 'messages' => @json_decode($_GET['messages']) ?: array() ));
    }
}
