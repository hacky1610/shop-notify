<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once dirname( __FILE__ ) . '/WoocommerceApiLogic.php';

class WoocommerceApi
{
     /**
     * Logic for Woocommerce 
     *
     * @var WoocommerceApiLogic
     */
    private $woocommerceApiLogic;
         
    function __construct($woocommerceApiLogic){
        $this->woocommerceApiLogic = $woocommerceApiLogic;
        $this->InitAjax();
    }
        
    private function AddAjaxFunction($code, $funcName)
    {
        add_action( 'wp_ajax_nopriv_' . $code, array( $this, $funcName ) );
        add_action( 'wp_ajax_' . $code, array( $this, $funcName ) );
    }
    
    private function InitAjax()
    {
        $this->AddAjaxFunction("get_language","GetLanguageAjax");
        $this->AddAjaxFunction("get_product","GetProductAjax");
        $this->AddAjaxFunction("get_last_orders","GetLastOrdersAjax");
        $this->AddAjaxFunction("get_last_reviews","GetLastReviewsAjax");
        $this->AddAjaxFunction("get_css","GetCssAjax");  
    }

    public function GetLanguageAjax()
    {
        echo  $this->woocommerceApiLogic->GetLanguage($_POST['code']);
        wp_die();
    }


    public function GetProductAjax()
    {
        $prod = $this->woocommerceApiLogic->GetProduct(intval($_POST['id']));
        echo json_encode($prod);
        wp_die();
    }

    public  function GetLastOrdersAjax()
    {
        echo json_encode($this->woocommerceApiLogic->GetLastOrders(5));
        wp_die();
    }

    public function GetLastReviewsAjax()
    {
        echo json_encode($this->woocommerceApiLogic->GetLastReviews(5));
        wp_die();
    }
    

}
