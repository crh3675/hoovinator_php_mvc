<?php
/*
 * Policy methods are pre-filters for incoming requests
 * We can use these policies to validate ownership of an asset, modify
 * incoming request data or whatever we want before the application
 * actually responds to the request
 *
 * Configure which routes use which policy method under config/policies.php
 */
class UserAuth {
   
   public function __construct(){
   }  

   public function valid( Array &$request ){
 
      return true;
   }
}