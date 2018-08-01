<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class StyleAdapter {
  
    const ACTION = 'wcn_get_style';
    private $datastore;
    
    /**
     * Action argument used by the nonce validating the AJAX request.
     *
     * @var string
     */
    const NONCE = 'my-plugin-ajax';

    function __construct($datastore){
        $this->datastore = $datastore;
        add_action('wp_ajax_' . self::ACTION, array($this, 'GetStyle'));
        add_action('wp_ajax_nopriv_' . self::ACTION, array($this, 'GetStyle'));
    }

    public function SaveStyle()
    {
        $style = $_POST['style'];
        wp_die();
    }

    public function GetStyle()
    {
        $styleId = $_POST['style_id'];
        $style = Style::GetStyle($this->datastore->GetStyleList(),$styleId);
        print_r($style->content);
        wp_die();
    }


}

