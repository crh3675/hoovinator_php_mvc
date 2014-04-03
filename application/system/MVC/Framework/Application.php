<?php

namespace MVC\Framework;

/**
 * Class Application
 * The heart of the app
 */
class Application
{
    /** @var null The controller part of the URL */
    private $url_controller;
    /** @var null The method part (of the above controller) of the URL */
    private $url_action;
    /** @var null Parameter one of the URL */
    private $url_parameter_1;
    /** @var null Parameter two of the URL */
    private $url_parameter_2;
    /** @var null Parameter three of the URL */
    private $url_parameter_3;
    /** @var null Parameter four of the URL */
    private $url_parameter_4;
    /** @var null Parameter five of the URL */
    private $url_parameter_5;
    /** @var null Parameter six of the URL */
    private $url_parameter_6;
    
    private $policies = array();

    /**
     * Starts the Application
     * Takes the parts of the URL and loads the according controller & method and passes the parameter arguments to it
     * TODO: get rid of deep if/else nesting
     * TODO: make the hardcoded locations ("error/index", "index.php", new Index()) dynamic, maybe via config.php
     */
    public function __construct( $policies = array() )
    {
        $this->policies = $policies;
       
        $this->splitUrl();
        // check for controller: is the url_controller NOT empty ?
        if ($this->url_controller) {
           
           $stored_controller = $this->url_controller;

           // fix format to CamelCase + Controller
           $controller = ucfirst(strtolower($this->url_controller)) . 'Controller';
                      
            // check for controller: does such a controller exist ?
            if (file_exists(CONTROLLER_PATH . $controller . '.php')) {
               
                // if so, then load this file and create this controller
                // example: if controller would be "car", then this line would translate into: $this->car = new car();
                
                require CONTROLLER_PATH . $controller . '.php';
                
                $this->url_controller = new $controller();

                // check for method: does such a method exist in the controller ?
                if ($this->url_action) {
                   
                    if (method_exists($this->url_controller, $this->url_action)) {
                       
                        // skip error controller for policies
                        if(!preg_match("|^error|i", $stored_controller)) {
                       
                           if($policies) {
                              $_REQUEST['_incoming'] = array(
                                'url' => isset($_GET['url'])  ? $_GET['url'] : '',
                                'controller' => $stored_controller,
                                'action' => $this->url_action,
                                'params' => array(
                                   $this->url_parameter_1, 
                                   $this->url_parameter_2, 
                                   $this->url_parameter_3.
                                   $this->url_parameter_4.
                                   $this->url_parameter_5,
                                   $this->url_parameter_6
                                 )
                              );
                           
                              $this->applyPolicies( $_REQUEST );
                           }
                        
                        }

                        // call the method and pass the arguments to it
                        if (isset($this->url_parameter_3)) {
                            $this->url_controller->{$this->url_action}($this->url_parameter_1, $this->url_parameter_2, $this->url_parameter_3);
                        } elseif (isset($this->url_parameter_2)) {
                            $this->url_controller->{$this->url_action}($this->url_parameter_1, $this->url_parameter_2);
                        } elseif (isset($this->url_parameter_1)) {
                            $this->url_controller->{$this->url_action}($this->url_parameter_1);
                        } else {
                            // if no parameters given, just call the method without arguments
                            $this->url_controller->{$this->url_action}();
                        }
                    } else {

                        // redirect user to error page (there's a controller for that)
                        header('location: ' . URL . 'error/display?messages=' . json_encode(array("Method [{$this->url_action}] not found for route [$stored_controller]")));
                    }
                } else {
                    // default/fallback: call the index() method of a selected controller
                    $this->url_controller->index();
                }
            // obviously mistyped controller name, therefore show 404
            } else {
                // redirect user to error page (there's a controller for that)
                header('location: ' . URL . 'error/display?messages=' . json_encode(array("Route not found for [$stored_controller]")));
            }
        // if url_controller is empty, simply show the main page (index/index)
        } else {
            // invalid URL, so simply show home/index
            require CONTROLLER_PATH . 'index.php';
            $controller = new Index();
            $controller->index();
        }
    }

    /**
     * Gets and splits the URL
     */
    private function splitUrl()
    {
        if (isset($_GET['url'])) {

            // split URL
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            
            // if only one param is sent, assume index
            if(count($url) == 1 ){
               $url = array(
                  $url[0],
                  'index'
               );
            }

            // Put URL parts into according properties
            // By the way, the syntax here if just a short form of if/else, called "Ternary Operators"
            // http://davidwalsh.name/php-shorthand-if-else-ternary-operators
            $this->url_controller = (isset($url[0]) ? $url[0] : null);
            $this->url_action = (isset($url[1]) ? $url[1] : null);
            $this->url_parameter_1 = (isset($url[2]) ? $url[2] : null);
            $this->url_parameter_2 = (isset($url[3]) ? $url[3] : null);
            $this->url_parameter_3 = (isset($url[4]) ? $url[4] : null);
            $this->url_parameter_4 = (isset($url[5]) ? $url[5] : null);
            $this->url_parameter_5 = (isset($url[6]) ? $url[6] : null);
            $this->url_parameter_6 = (isset($url[7]) ? $url[7] : null);
        
        } else {
           
            $this->url_controller = DEFAULT_CONTROLLER;
            $this->url_action = 'index';
        }
    }
    
    /*
     * Applies policies to route
     */
    private function applyPolicies( &$request ) {
       
       $user = $request['_incoming'];
       
       foreach($this->policies as $route => $policies){
          
          if(!is_array($policies)) {
             $policies = array( $policies );
          }
          
          // replace standalone * with .*
          $routexpr = str_replace('*', ".*", $route);
          
          // escape delimiter
          $routexpr = str_replace('/', "\\/", $routexpr);          
          
          if(! preg_match('|'.$routexpr.'|', $user['controller'] . '/' . $user['action'] )) {
             continue;
          }
          
          foreach($policies as $policy){
          
             list($className, $classMethod) = explode('.', $policy);
             
             $classFile = POLICY_PATH. DIRECTORY_SEPARATOR . $className . '.php';
          
             if(is_file($classFile)) {
             
                require_once $classFile;
             
                $klass = new $className();
                
                if(method_exists($klass, $classMethod)) {
                
                   $result = $klass->$classMethod( $request );
                
                   if($result !== true) {
                      
                      header('Location: /error/display?messages=' . urlencode(json_encode($result)));
                      exit(0);
                   }
                   
                   unset($klass);
                
                } else {
                
                 trigger_error('Method '.$parts[1].' does no exist for policy ' . $parts[0], E_USER_WARNING);  
              
                }
             
             } else {
             
                trigger_error('Policy does no exist for ' . $parts[0], E_USER_WARNING);  
             }
          }
       }       
    }
}
