<?php
include_once dirname( __FILE__ ) . '/../logger.php';

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AjaxAdapter {

         /**
     * Action argument used by the nonce validating the AJAX request.
     *
     * @var Logger
     */
    protected $logger;

    function __construct($logger)
    {
        $this->logger = $logger;    
    }


    public function ThrowError($message = "")
    {
        echo "NOK " + $message;
    }

        
        public function OK()
        {
            echo "OK";
        }
  


}

