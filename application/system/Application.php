<?php

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
           
            // check for controller: does such a controller exist ?
            if (file_exists(CONTROLLER_PATH . $this->url_controller . '.php')) {
                // if so, then load this file and create this controller
                // example: if controller would be "car", then this line would translate into: $this->car = new car();
                require CONTROLLER_PATH . $this->url_controller . '.php';
                $this->url_controller = new $this->url_controller();

                // check for method: does such a method exist in the controller ?
                if ($this->url_action) {
                    if (method_exists($this->url_controller, $this->url_action)) {
                       
                        if($policies) {
                           $request = array(
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
                              ),
                              'errors' => array() 
                           );
                           
                           $this->applyPolicies( $request );
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
                        header('location: ' . URL . 'error/index');
                    }
                } else {
                    // default/fallback: call the index() method of a selected controller
                    $this->url_controller->index();
                }
            // obviously mistyped controller name, therefore show 404
            } else {
                // redirect user to error page (there's a controller for that)
                header('location: ' . URL . 'error/index');
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
            
            // if only one param is sent, assume default controller
            if(count($url) == 1 ){
               $url = array(
                  DEFAULT_CONTROLLER,
                  $url[0]
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
       
       foreach($this->policies as $route => $policies){
          
          if(!is_array($policies)) {
             $policies = array( $policies );
          }
          
          $routexpr = str_replace('/', "\\/", $route);
          $routexpr = str_replace('*', ".*", $routexpr);
          
          if(! preg_match('|'.$routexpr.'|', $request['controller'] . '/' . $request['action'] )) {
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
