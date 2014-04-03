<?php

/**
 * Class Main
 * The main controller
 */
class MainController extends MVC\Framework\Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    function __construct()
    {
            parent::__construct();
    }

    /**
     * Handles what happens when user moves to URL/index/index, which is the same like URL/index or in this
     * case even URL (without any controller/action) as this is the default controller-action when user gives no input.
     */
    function index()
    {
       $userModel = $this->model('MaasAds\User');
       
       $this->view->render('index/index', array('dog' => 'Jim'));
    }
    
    function thisIsARoute(){
         $this->view->render('index/index', array('dog' => 'Jim'));
    }
}
