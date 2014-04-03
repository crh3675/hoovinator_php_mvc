<?php

/**
 * the auto-loading function, which will be called every time a file "is missing"
 * NOTE: don't get confused, this is not "__autoload", the now deprecated function
 * The PHP Framework Interoperability Group (@see https://github.com/php-fig/fig-standards) recommends using a
 * standardized auto-loader https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md, so we do:
 */
function autoload($class) {
    // if file does not exist in SYSTEM_PATH folder [set it in config/config.php]
    
    $class = str_replace("\\", '/', $class);
    
    if (file_exists(SYSTEM_PATH . $class . ".php")) {
        require_once SYSTEM_PATH . $class . ".php";
    } else if(file_exists(MODELS_PATH . $class . ".php")) {
       require_once MODELS_PATH . $class . ".php";
    } else {
        exit ('The file ' . $class . '.php is missing in the libs folder.');
    }
}

// spl_autoload_register defines the function that is called every time a file is missing. as we created this
// function above, every time a file is needed, autoload(THENEEDEDCLASS) is called
spl_autoload_register("autoload");
