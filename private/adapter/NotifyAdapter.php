<?php

include_once dirname( __FILE__ ) . '/../model/Notify.php';

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class NotifyAdapter {
  
    const ACTION = 'wcn_get_notify';
    private $postMetaAdapter;
    
    function __construct($postMetaAdapter){
        $this->postMetaAdapter = $postMetaAdapter;
        add_action('wp_ajax_' . self::ACTION, array($this, 'GetNotifyAjax'));
        add_action('wp_ajax_nopriv_' . self::ACTION, array($this, 'GetNotifyAjax'));
    }

    public function GetNotifyAjax()
    {
        $id =  $_POST['id'];

        print_r($this->GetNotify($id));
        wp_die();
    }

    public function GetNotify($id)
    {
       $not = new Notify($id,$this->postMetaAdapter);
       return $not->GetObject();
    }

  


}

