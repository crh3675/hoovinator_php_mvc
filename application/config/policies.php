<?php
/**
 * Policies
 *
 * These are injected into the Application controller and are used to perform pre-filters
 * on incoming requests once the URL has been parsed.
 *
 * The format is:  controller/action => Policy.method     
 *
 * You can apply multiple policies in an array and they will be called consecutively
 */

return array(
  '*' => array( 'UserAuth.valid', 'UserAuth.cool' )
);