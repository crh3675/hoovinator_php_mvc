<?php
/*
 * Policy methods are pre-filters for incoming requests
 * We can use these policies to validate ownership of an asset, modify
 * incoming request data or whatever we want before the application
 * actually responds to the request
 *
 * Configure which routes use which policy method under config/policies.php
 *
 * The variable passed is the $_REQUEST object which we can modify as necessary
 * An extra parameter is added for the user request (_user)
 *
 * $_REQUEST['_incoming'] = array(
 *     'url' => [url requested],
 *     'controller' => [controller name],
 *     'action' => [action name],
 *     'params' => array( [params after controller and action ] )
 *     );
 *
 * Return TRUE or an array of error messages
 */

 
class UserAuth {
   
   public function __construct(){
   }  

   public function valid( &$incoming ){


      return true;
   }

   public function cool( &$incoming ){

      return true;
   }
}