<?php
include_once dirname( __FILE__ ) . '/../logger.php';
include_once dirname( __FILE__ ) . '/WpAdapter.php';

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AjaxAdapter {
         /**
     * Logger to debug
     *
     * @var Logger
     */
    protected $logger;
           /**
     * Wordpress Adapter
     *
     * @var WpAdapter
     */
    protected $wpAdapter;

    function __construct($wpAdapter,$logger)
    {
        $this->logger = $logger;    
        $this->wpAdapter = $wpAdapter;
    }

    public function ThrowError($message = "")
    {
        echo "NOK " + $message;
    }

        
    public function OK()
    {
        echo "OK";
    }

    public function AddAction($action,$object,$function)
    {
        $this->wpAdapter->AddAction($action,$object,$function);
    }

    public function GetPost($key)
    {
        return  $this->wpAdapter->GetPost($key);
    }

    public function WpDie()
    {
        return  $this->wpAdapter->WpDie();
    }
  


}

