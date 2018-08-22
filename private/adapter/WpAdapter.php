<?php
include_once dirname( __FILE__ ) . '/../logger.php';

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WpAdapter {

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

    public function AddAction($action,$object,$function)
    {
        add_action('wp_ajax_' . $action, array($object, $function));
        add_action('wp_ajax_nopriv_' . $action, array($object, $function));
    }

    public function GetPost($key)
    {
        return $_POST[$key];
    }

    public function WpDie()
    {
        wp_die();
    }
}

