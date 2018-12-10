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
    private $namespace = "shop-notify";

    function __construct($logger)
    {
        $this->logger = $logger;    
    }

    public function AddAction($action,$object,$function)
    {
        $this->logger->Call("AddAction");
        $this->logger->Info("Add action: $action");

        add_action('wp_ajax_' . $action, array($object, $function));
        add_action('wp_ajax_nopriv_' . $action, array($object, $function));
    }

    public function GetPost($key)
    {
        $this->logger->Call("GetPost");
        $this->logger->Info("Key: $key");

        $val = $_POST[$key];
        $this->logger->Info("Val: $val");

        return $val;
    }

    public function EnqueueStyle($name,$path) {
      wp_enqueue_style("sn_".$name, WCN_PATH.$path.'?'.WCN_VERSION);
    }

    public function EnqueueExternalStyle($name,$path) {
      wp_enqueue_style("sn_".$name, $path);
    }

    public function EnqueueRegisteredScript($name) {
      wp_enqueue_script("sn_".$name);
    }

    public function EnqueueScript($name,$path) {
      wp_enqueue_script("sn_".$name, WCN_PATH.$path.'?'.WCN_VERSION, array(), null, 1);
    }

    public function RegisterScript($name,$path) {
      wp_register_script("sn_".$name, WCN_PATH.$path.'?'.WCN_VERSION, array(), null, 1);
    }

    public function EnqueueExternalScript($name,$path) {
      wp_enqueue_script("sn_".$name, $path);
    }

    public function LocalizeScript($name,$varname,$array) {
      wp_localize_script("sn_".$name, $varname, $array);
    }

    public function locate($text) {
      return __($text,$this->namespace);
    }

    public function WpDie()
    {
        wp_die();
    }
}

