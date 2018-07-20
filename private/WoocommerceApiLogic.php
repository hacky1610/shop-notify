<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once dirname( __FILE__ ) . '/Review.php';
include_once dirname( __FILE__ ) . '/model/Product.php';
include_once dirname( __FILE__ ) . '/model/Order.php';



class WoocommerceApiLogic
{
    private $logger;
         
    function __construct( $logger){
        $this->logger = $logger;
    }
       
    //https://docs.woocommerce.com/wp-content/images/wc-apidocs/class-WC_Product_Simple.html
    public function GetProduct($id)
    {              
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 1,
            'post__in'=> array($product_id)
        );

        return self::CreateProduct($id,wc_get_products( $args )[0]);
    }

    public function GetLastOrders($count)
    {          
        $args = array(
            'status' => 'completed',
        );
        $wcOrders =  wc_get_orders( $args );
        $orders = array();

        foreach ($wcOrders  as &$wcOrder) {
            $items = array();
            foreach ($wcOrder->get_items()  as $item_id => $item_obj) {
                //https://docs.woocommerce.com/wc-apidocs/class-WC_Order_Item_Product.html
                array_push($items,self::CreateProduct($item_obj->get_product_id(),$item_obj->get_product()));
            }
    
            $order = self::CreateOrder($wcOrder,$items);
            array_push($orders,$order);
        }
       
        return $orders;

    
    }

    private static function CreateProduct($id, $wcProd)
    {
        $image_url = wp_get_attachment_image_src( $wcProd->get_image_id(), 'single-post-thumbnail' );

        return new Product($id, $wcProd->get_name(),$wcProd->get_permalink(),$image_url[0]);
    }


    private static function CreateOrder( $wcOrder,$items)
    {
        $country = WC()->countries->countries[$wcOrder->get_billing_country()];
        return new Order($wcOrder->get_id(),$wcOrder->get_billing_first_name(),$country,$wcOrder->get_date_created() ,$items);
    }

    public function GetLastReviews($count)
    {      
        global $wpdb;
       //comment_date,meta_value,comment_content,comment_author,comment_post_ID

        $comments = $wpdb->get_results("
        SELECT comment_date,meta_value,comment_content,comment_author,comment_post_ID FROM $wpdb->commentmeta
        LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
        WHERE meta_key = 'rating'
        ");

        return $comments;

    }


    public function GetLanguage($code)
    {
        $countries = array(  "AF" => "Afghanistan" , "ZA" => "Afrique du Sud" , "AX" => "Aland Islands" , "AL" => "Albanie" , "DZ" => "Algérie" , "DE" => "Allemagne" , "AS" => "American Samoa" , "AD" => "Andorre" , "AO" => "Angola" , "AI" => "Anguilla" , "AQ" => "Antarctique" , "AG" => "Antigua-et-Barbuda" , "SA" => "Arabie Saoudite" , "AR" => "Argentine" , "AM" => "Arménie" , "AW" => "Aruba" , "AU" => "Australie" , "AT" => "Autriche" , "AZ" => "Azerbaijan" , "BS" => "Bahamas" , "BH" => "Bahrain" , "BD" => "Bangladesh" , "BB" => "Barbades" , "PW" => "Belau" , "BE" => "Belgique" , "BZ" => "Belize" , "BJ" => "Benin" , "BM" => "Bermudes" , "BT" => "Bhutan" , "BY" => "Biélorussie" , "BO" => "Bolivie" , "BA" => "Bosnie-Herzégovine" , "BW" => "Botswana" , "BN" => "Brunei" , "BR" => "Brésil" , "BG" => "Bulgarie" , "BF" => "Burkina Faso" , "BI" => "Burundi" , "KH" => "Cambodge" , "CM" => "Cameroun" , "CA" => "Canada" , "CV" => "Cap Vert" , "CL" => "Chili" , "CN" => "Chine" , "CX" => "Christmas Island" , "CY" => "Chypre" , "CO" => "Colombie" , "KM" => "Comores" , "CG" => "Congo (Brazzaville)" , "CD" => "Congo (Kinshasa)" , "KP" => "Corée du Nord" , "KR" => "Corée du Sud" , "CR" => "Costa Rica" , "HR" => "Croatie" , "CU" => "Cuba" , "CW" => "Curaçao" , "CI" => "Côte-d’Ivoire" , "DK" => "Danemark" , "DJ" => "Djibouti" , "DM" => "Dominique" , "ER" => "Erythrée" , "ES" => "Espagne" , "EE" => "Estonie" , "US" => "Etats-Unis (USA)" , "FJ" => "Fidji" , "FI" => "Finlande" , "FR" => "France" , "GA" => "Gabon" , "GM" => "Gambie" , "GH" => "Ghana" , "GI" => "Gibraltar" , "GD" => "Grenade" , "GL" => "Groenland" , "GR" => "Grèce" , "GP" => "Guadeloupe" , "GU" => "Guam" , "GT" => "Guatemala" , "GG" => "Guernesey" , "GN" => "Guinée" , "GQ" => "Guinée équatoriale" , "GW" => "Guinée-Bissau" , "GY" => "Guyane" , "GF" => "Guyane Française" , "GE" => "Géorgie" , "GS" => "Géorgie du Sud / îles Sandwich" , "HT" => "Haïti" , "HN" => "Honduras" , "HK" => "Hong Kong" , "HU" => "Hongrie" , "IN" => "Inde" , "ID" => "Indonésie" , "IR" => "Iran" , "IQ" => "Iraq" , "IE" => "Irlande" , "IS" => "Islande" , "IL" => "Israël" , "IT" => "Italie" , "JM" => "Jamaïque" , "JP" => "Japon" , "JE" => "Jersey" , "JO" => "Jordanie" , "KZ" => "Kazakhstan" , "KE" => "Kenya" , "KI" => "Kiribati" , "KW" => "Koweït" , "KG" => "Kyrgyzstan" , "RE" => "La Réunion" , "LA" => "Laos" , "LS" => "Lesotho" , "LV" => "Lettonie" , "LB" => "Liban" , "LR" => "Liberia" , "LY" => "Libye" , "LI" => "Liechtenstein" , "LT" => "Lituanie" , "LU" => "Luxembourg" , "MO" => "Macao S.A.R., Chine" , "MK" => "Macédoine" , "MG" => "Madagascar" , "MY" => "Malaisie" , "MW" => "Malawi" , "MV" => "Maldives" , "ML" => "Mali" , "MT" => "Malte" , "MA" => "Maroc" , "MQ" => "Martinique" , "MU" => "Maurice" , "MR" => "Mauritanie" , "YT" => "Mayotte" , "MX" => "Mexique" , "FM" => "Micronésie" , "MD" => "Moldavie" , "MC" => "Monaco" , "MN" => "Mongolie" , "ME" => "Montenegro" , "MS" => "Montserrat" , "MZ" => "Mozambique" , "MM" => "Myanmar" , "NA" => "Namibie" , "NR" => "Nauru" , "NI" => "Nicaragua" , "NE" => "Niger" , "NG" => "Nigeria" , "NU" => "Niue" , "MP" => "Northern Mariana Islands" , "NO" => "Norvège" , "NC" => "Nouvelle-Calédonie" , "NZ" => "Nouvelle-Zélande" , "NP" => "Népal" , "OM" => "Oman" , "PK" => "Pakistan" , "PA" => "Panama" , "PG" => "Papouasie-Nouvelle-Guinée" , "PY" => "Paraguay" , "NL" => "Pays-Bas" , "PH" => "Philippines" , "PN" => "Pitcairn" , "PL" => "Pologne" , "PF" => "Polynésie Française" , "PT" => "Portugal" , "PR" => "Puerto Rico" , "PE" => "Pérou" , "QA" => "Qatar" , "RO" => "Roumanie" , "GB" => "Royaume-Uni (UK)" , "RU" => "Russie" , "RW" => "Rwanda" , "CF" => "République Centrafricaine" , "DO" => "République Dominicaine" , "CZ" => "République Tchèque" , "BQ" => "Saba, Saint-Eustache et Bonaire" , "EH" => "Sahara occidental" , "BL" => "Saint Barthélemy" , "PM" => "Saint Pierre et Miquelon" , "KN" => "Saint-Kitts-et-Nevis" , "MF" => "Saint-Martin (partie française)" , "SX" => "Saint-Martin (partie néerlandaise)" , "VC" => "Saint-Vincent-et-les-Grenadines" , "SH" => "Sainte-Hélène" , "LC" => "Sainte-Lucie" , "SV" => "Salvador" , "WS" => "Samoa" , "SM" => "San Marino" , "ST" => "Sao Tomé-et-Principe" , "RS" => "Serbie" , "SC" => "Seychelles" , "SL" => "Sierra Leone" , "SG" => "Singapour" , "SK" => "Slovaquie" , "SI" => "Slovénie" , "SO" => "Somalie" , "SD" => "Soudan" , "SS" => "Soudan du Sud" , "LK" => "Sri Lanka" , "CH" => "Suisse" , "SR" => "Suriname" , "SE" => "Suède" , "SJ" => "Svalbard et Jan Mayen" , "SZ" => "Swaziland" , "SY" => "Syrie" , "SN" => "Sénégal" , "TW" => "Taiwan" , "TJ" => "Tajikistan" , "TZ" => "Tanzanie" , "TD" => "Tchad" , "TF" => "Terres Australes Françaises" , "PS" => "Territoire Palestinien" , "IO" => "Territoire britannique de l’océan Indien" , "TH" => "Thailande" , "TL" => "Timor-Leste" , "TG" => "Togo" , "TK" => "Tokelau" , "TO" => "Tonga" , "TT" => "Trinité-et-Tobago" , "TN" => "Tunisie" , "TM" => "Turkménistan" , "TR" => "Turquie" , "TV" => "Tuvalu" , "UG" => "Uganda" , "UA" => "Ukraine" , "UY" => "Uruguay" , "UZ" => "Uzbekistan" , "VU" => "Vanuatu" , "VA" => "Vatican" , "VE" => "Venezuela" , "VN" => "Vietnam" , "WF" => "Wallis et Futuna" , "YE" => "Yemen" , "ZM" => "Zambie" , "ZW" => "Zimbabwe" , "EG" => "Égypte" , "AE" => "Émirats Arabes Unis" , "EC" => "Équateur" , "ET" => "Éthiopie" , "BV" => "Île Bouvet" , "NF" => "Île Norfolk" , "IM" => "Île de Man" , "KY" => "Îles Caïmans" , "CC" => "Îles Cocos" , "CK" => "Îles Cook" , "FK" => "Îles Falkland" , "FO" => "Îles Féroé" , "HM" => "Îles Heard-et-MacDonald" , "MH" => "Îles Marshall" , "UM" => "Îles Mineures éloignées des États-Unis" , "SB" => "Îles Salomon" , "TC" => "Îles Turques et Caïques" , "VI" => "Îles Vierges américaines (US)" , "VG" => "Îles Vierges britanniques" );
        return $countries[$code];
    }
    
   
}
