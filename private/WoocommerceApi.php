<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once dirname( __FILE__ ) . '/Review.php';

class WoocommerceApi
{
    public static $woocommerceApiLogic;
         
    function __construct(){
    }
        
    static function AddAjaxFunction($code, $funcName)
    {
        add_action( 'wp_ajax_nopriv_' . $code, array( "WoocommerceApi", $funcName ) );
        add_action( 'wp_ajax_' . $code, array( "WoocommerceApi", $funcName ) );
    }

    static function DisableAuthentification()
    {
        add_filter( 'woocommerce_api_check_authentication', function() { return new WP_User( 1 ); } );
    }
    
    static public function InitAjax()
    {
        self::AddAjaxFunction("get_language","GetLanguageAjax");
        self::AddAjaxFunction("get_product","GetProductAjax");
        self::AddAjaxFunction("get_all_orders","GetAllOrdersAjax");
        self::AddAjaxFunction("get_all_reviews","GetAllReviewsAjax");
        self::AddAjaxFunction("get_css","GetCssAjax");
    }

    public static function GetLanguageAjax()
    {
        echo  self::$woocommerceApiLogic->GetLanguage($_POST['code']);
        wp_die();
    }

    public static function GetProductAjax()
    {
        echo self::$woocommerceApiLogic->GetProduct(intval($_POST['id']));
        wp_die();
    }

    public static function GetAllOrdersAjax()
    {
        echo self::$woocommerceApiLogic->GetAllOrders();
        wp_die();
    }

   public static function GetAllReviewsAjax()
    {
        echo self::$woocommerceApiLogic->GetAllReviews();
        wp_die();
    }
    
     public static function GetCssAjax()
    {
        echo "#000000";
        wp_die();
    }
}
