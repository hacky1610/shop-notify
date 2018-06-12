<?php
/*
Plugin Name: Woocommerce Notice
*/

class Datastore {
    static $consumerKeyName = "wcn_consumerKey";
    static $consumerSecret = "wcn_consumerSecret";

    public function GetConsumerKey() {
        return get_option(self::$consumerKeyName);
    }

    public function SetConsumerKey($value) {
       update_option(self::$consumerKeyName,$value);
    }

    public function GetConsumerSecret() {
        return get_option(self::$consumerSecret);
    }

    public function SetConsumerSecret($value) {
        update_option(self::$consumerSecret,$value);
    }
}

class WoocommerceApi
{
    public static $website;
    public static $consumerkey; //ck_e3b74a274632a79a51f2e92809c392a30b7e8266
    public static  $consumerSecret; //cs_fa92ad1ba2a3742e8ee3fa72161e650e871ee069
         
    function __construct(){
    }
    
    static function HttpGet($url)
    {
        $curl = curl_init( $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec( $curl );
        curl_close( $curl );
        return $response;
    }
    
    static function GetUrlSecurityString()
    {
        return "consumer_key=" . self::$consumerkey . "&consumer_secret=" . self::$consumerSecret;
    }
    
    static function GetProduct($id)
    {              
        $request = self::$website . "/wp-json/wc/v2/products/$id?" . self::GetUrlSecurityString();
        return  self::HttpGet($request);
    }

    static function GetAllOrders()
    {          
        $request = self::$website . "/wp-json/wc/v1/orders?" . self::GetUrlSecurityString();
        return  self::HttpGet($request);
    }

    static function GetAllReviews($id)
    {      

        $request = self::$website .  "/wp-json/wc/v2/products/$id/reviews?" . self::GetUrlSecurityString();
        return  self::HttpGet($request);
    }

    static function GetLanguage($code)
    {
        $countries = array(  "AF" => "Afghanistan" , "ZA" => "Afrique du Sud" , "AX" => "Aland Islands" , "AL" => "Albanie" , "DZ" => "Algérie" , "DE" => "Allemagne" , "AS" => "American Samoa" , "AD" => "Andorre" , "AO" => "Angola" , "AI" => "Anguilla" , "AQ" => "Antarctique" , "AG" => "Antigua-et-Barbuda" , "SA" => "Arabie Saoudite" , "AR" => "Argentine" , "AM" => "Arménie" , "AW" => "Aruba" , "AU" => "Australie" , "AT" => "Autriche" , "AZ" => "Azerbaijan" , "BS" => "Bahamas" , "BH" => "Bahrain" , "BD" => "Bangladesh" , "BB" => "Barbades" , "PW" => "Belau" , "BE" => "Belgique" , "BZ" => "Belize" , "BJ" => "Benin" , "BM" => "Bermudes" , "BT" => "Bhutan" , "BY" => "Biélorussie" , "BO" => "Bolivie" , "BA" => "Bosnie-Herzégovine" , "BW" => "Botswana" , "BN" => "Brunei" , "BR" => "Brésil" , "BG" => "Bulgarie" , "BF" => "Burkina Faso" , "BI" => "Burundi" , "KH" => "Cambodge" , "CM" => "Cameroun" , "CA" => "Canada" , "CV" => "Cap Vert" , "CL" => "Chili" , "CN" => "Chine" , "CX" => "Christmas Island" , "CY" => "Chypre" , "CO" => "Colombie" , "KM" => "Comores" , "CG" => "Congo (Brazzaville)" , "CD" => "Congo (Kinshasa)" , "KP" => "Corée du Nord" , "KR" => "Corée du Sud" , "CR" => "Costa Rica" , "HR" => "Croatie" , "CU" => "Cuba" , "CW" => "Curaçao" , "CI" => "Côte-d’Ivoire" , "DK" => "Danemark" , "DJ" => "Djibouti" , "DM" => "Dominique" , "ER" => "Erythrée" , "ES" => "Espagne" , "EE" => "Estonie" , "US" => "Etats-Unis (USA)" , "FJ" => "Fidji" , "FI" => "Finlande" , "FR" => "France" , "GA" => "Gabon" , "GM" => "Gambie" , "GH" => "Ghana" , "GI" => "Gibraltar" , "GD" => "Grenade" , "GL" => "Groenland" , "GR" => "Grèce" , "GP" => "Guadeloupe" , "GU" => "Guam" , "GT" => "Guatemala" , "GG" => "Guernesey" , "GN" => "Guinée" , "GQ" => "Guinée équatoriale" , "GW" => "Guinée-Bissau" , "GY" => "Guyane" , "GF" => "Guyane Française" , "GE" => "Géorgie" , "GS" => "Géorgie du Sud / îles Sandwich" , "HT" => "Haïti" , "HN" => "Honduras" , "HK" => "Hong Kong" , "HU" => "Hongrie" , "IN" => "Inde" , "ID" => "Indonésie" , "IR" => "Iran" , "IQ" => "Iraq" , "IE" => "Irlande" , "IS" => "Islande" , "IL" => "Israël" , "IT" => "Italie" , "JM" => "Jamaïque" , "JP" => "Japon" , "JE" => "Jersey" , "JO" => "Jordanie" , "KZ" => "Kazakhstan" , "KE" => "Kenya" , "KI" => "Kiribati" , "KW" => "Koweït" , "KG" => "Kyrgyzstan" , "RE" => "La Réunion" , "LA" => "Laos" , "LS" => "Lesotho" , "LV" => "Lettonie" , "LB" => "Liban" , "LR" => "Liberia" , "LY" => "Libye" , "LI" => "Liechtenstein" , "LT" => "Lituanie" , "LU" => "Luxembourg" , "MO" => "Macao S.A.R., Chine" , "MK" => "Macédoine" , "MG" => "Madagascar" , "MY" => "Malaisie" , "MW" => "Malawi" , "MV" => "Maldives" , "ML" => "Mali" , "MT" => "Malte" , "MA" => "Maroc" , "MQ" => "Martinique" , "MU" => "Maurice" , "MR" => "Mauritanie" , "YT" => "Mayotte" , "MX" => "Mexique" , "FM" => "Micronésie" , "MD" => "Moldavie" , "MC" => "Monaco" , "MN" => "Mongolie" , "ME" => "Montenegro" , "MS" => "Montserrat" , "MZ" => "Mozambique" , "MM" => "Myanmar" , "NA" => "Namibie" , "NR" => "Nauru" , "NI" => "Nicaragua" , "NE" => "Niger" , "NG" => "Nigeria" , "NU" => "Niue" , "MP" => "Northern Mariana Islands" , "NO" => "Norvège" , "NC" => "Nouvelle-Calédonie" , "NZ" => "Nouvelle-Zélande" , "NP" => "Népal" , "OM" => "Oman" , "PK" => "Pakistan" , "PA" => "Panama" , "PG" => "Papouasie-Nouvelle-Guinée" , "PY" => "Paraguay" , "NL" => "Pays-Bas" , "PH" => "Philippines" , "PN" => "Pitcairn" , "PL" => "Pologne" , "PF" => "Polynésie Française" , "PT" => "Portugal" , "PR" => "Puerto Rico" , "PE" => "Pérou" , "QA" => "Qatar" , "RO" => "Roumanie" , "GB" => "Royaume-Uni (UK)" , "RU" => "Russie" , "RW" => "Rwanda" , "CF" => "République Centrafricaine" , "DO" => "République Dominicaine" , "CZ" => "République Tchèque" , "BQ" => "Saba, Saint-Eustache et Bonaire" , "EH" => "Sahara occidental" , "BL" => "Saint Barthélemy" , "PM" => "Saint Pierre et Miquelon" , "KN" => "Saint-Kitts-et-Nevis" , "MF" => "Saint-Martin (partie française)" , "SX" => "Saint-Martin (partie néerlandaise)" , "VC" => "Saint-Vincent-et-les-Grenadines" , "SH" => "Sainte-Hélène" , "LC" => "Sainte-Lucie" , "SV" => "Salvador" , "WS" => "Samoa" , "SM" => "San Marino" , "ST" => "Sao Tomé-et-Principe" , "RS" => "Serbie" , "SC" => "Seychelles" , "SL" => "Sierra Leone" , "SG" => "Singapour" , "SK" => "Slovaquie" , "SI" => "Slovénie" , "SO" => "Somalie" , "SD" => "Soudan" , "SS" => "Soudan du Sud" , "LK" => "Sri Lanka" , "CH" => "Suisse" , "SR" => "Suriname" , "SE" => "Suède" , "SJ" => "Svalbard et Jan Mayen" , "SZ" => "Swaziland" , "SY" => "Syrie" , "SN" => "Sénégal" , "TW" => "Taiwan" , "TJ" => "Tajikistan" , "TZ" => "Tanzanie" , "TD" => "Tchad" , "TF" => "Terres Australes Françaises" , "PS" => "Territoire Palestinien" , "IO" => "Territoire britannique de l’océan Indien" , "TH" => "Thailande" , "TL" => "Timor-Leste" , "TG" => "Togo" , "TK" => "Tokelau" , "TO" => "Tonga" , "TT" => "Trinité-et-Tobago" , "TN" => "Tunisie" , "TM" => "Turkménistan" , "TR" => "Turquie" , "TV" => "Tuvalu" , "UG" => "Uganda" , "UA" => "Ukraine" , "UY" => "Uruguay" , "UZ" => "Uzbekistan" , "VU" => "Vanuatu" , "VA" => "Vatican" , "VE" => "Venezuela" , "VN" => "Vietnam" , "WF" => "Wallis et Futuna" , "YE" => "Yemen" , "ZM" => "Zambie" , "ZW" => "Zimbabwe" , "EG" => "Égypte" , "AE" => "Émirats Arabes Unis" , "EC" => "Équateur" , "ET" => "Éthiopie" , "BV" => "Île Bouvet" , "NF" => "Île Norfolk" , "IM" => "Île de Man" , "KY" => "Îles Caïmans" , "CC" => "Îles Cocos" , "CK" => "Îles Cook" , "FK" => "Îles Falkland" , "FO" => "Îles Féroé" , "HM" => "Îles Heard-et-MacDonald" , "MH" => "Îles Marshall" , "UM" => "Îles Mineures éloignées des États-Unis" , "SB" => "Îles Salomon" , "TC" => "Îles Turques et Caïques" , "VI" => "Îles Vierges américaines (US)" , "VG" => "Îles Vierges britanniques" );
        return $countries[$code];
    }
    
    static function AddAjaxFunction($code, $funcName)
    {
        add_action( 'wp_ajax_nopriv_' . $code, array( "WoocommerceApi", $funcName ) );
        add_action( 'wp_ajax_' . $code, array( "WoocommerceApi", $funcName ) );
    }
    
    static public function InitAjax()
    {
        self::AddAjaxFunction("get_language","GetLanguageAjax");
        self::AddAjaxFunction("get_product","GetProductAjax");
        self::AddAjaxFunction("get_all_orders","GetAllOrdersAjax");
        self::AddAjaxFunction("get_all_reviews","GetAllReviewsAjax");
    }

    public static function GetLanguageAjax()
    {
        echo self::GetLanguage($_POST['code']);
        wp_die();
    }

    public static function GetProductAjax()
    {
        echo self::GetProduct(intval($_POST['id']));
        wp_die();
    }

    public static function GetAllOrdersAjax()
    {
        echo self::GetAllOrders();
        wp_die();
    }

   public static function GetAllReviewsAjax()
    {
        echo self::GetAllReviews($_POST['id']);
        wp_die();
    }
}

  
class Woocommerce_Notice{
    static $version = '0.9.93';
    static $version_file = '0.9.93';
    private $datastore;   
    private $api;

    function __construct(){
        $this->datastore  = new Datastore();
        WoocommerceApi::$consumerkey =  $this->datastore->GetConsumerKey();
        WoocommerceApi::$consumerSecret =  $this->datastore->GetConsumerSecret();
        WoocommerceApi::$website = "https://vals-natural-journey.de";
        WoocommerceApi::InitAjax();
        add_action('wp_enqueue_scripts', array($this, 'loadJs'));
        add_action('admin_menu', array($this, 'createMenu'));
    }

    public function loadJs($hook){
        /*if($hook === 'settings_page_yourchannel'){
                   wp_register_script('yrc_script', plugins_url('/js/yrc.js?'.self::$version_file, __FILE__), array('jquery', 'underscore'), null, 1);
                   wp_enqueue_script('yrc_script');
                   wp_register_script('yrc_color_picker', plugins_url('/css/colorpicker/spectrum.js?'.self::$version_file, __FILE__), array('yrc_script'), null, 1);
                   wp_enqueue_script('yrc_color_picker');
                   wp_register_script('yrc_admin_settings', plugins_url('/js/admin.js?'.self::$version_file, __FILE__), array('yrc_color_picker'), null, 1);
                   wp_enqueue_script('yrc_admin_settings');
                   wp_register_style('yrc_color_picker_style', plugins_url('/css/colorpicker/spectrum.css?'.self::$version_file, __FILE__));
                   wp_enqueue_style('yrc_color_picker_style');
                   wp_register_style('yrc_admin_style', plugins_url('/css/admin.css?'.self::$version_file, __FILE__));
                   wp_enqueue_style('yrc_admin_style');
                   wp_register_style('yrc_style', plugins_url('/css/style.css?'.self::$version_file, __FILE__));
                   wp_enqueue_style('yrc_style');

        }*/
        wp_register_script('wcn_script', plugins_url('/js/notice.js?'.self::$version_file, __FILE__), array(), null, 1);
        wp_enqueue_script('wcn_script');
    }

    public function createMenu(){
        add_submenu_page(
           'options-general.php',
           'Woocommerce_Notice',
           'Woocommerce Notice',
           apply_filters('yourchannel_settings_permission', 'manage_options'),
           'Woocommerce_Notice',
           array($this, 'pageTemplate')
        );
    }

    public function pageTemplate(){
        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general_settings';
        ?>
        <div class="wrap">
            <div id="icon-themes" class="icon32"></div>
            <h2 class="wpb-inline" id="yrc-icon">Woocommerce<span class="wpb-inline">Notice</span></h2>
            <h2 class="nav-tab-wrapper">
                        <a href="?page=Woocommerce_Notice&amp;tab=general_settings" class="nav-tab <?php echo $active_tab == 'general_settings' ? 'nav-tab-active' : ''; ?>">General</a>
                        <a href="?page=Woocommerce_Notice&amp;tab=tools" class="nav-tab <?php echo $active_tab == 'tools' ? 'nav-tab-active' : ''; ?>">Tools</a>
            </h2>
                   <?php if ($active_tab == 'general_settings') { ?>
                               <h2>Settings</h2>
                   <?php } elseif($_GET['tab'] == 'tools') 
                         {

                            if (isset($_POST['submit']) && !empty($_POST['submit'])) {
                                $this->datastore->SetConsumerKey($_POST['ConsumerKey']);
                                $this->datastore->SetConsumerSecret($_POST['ConsumerSecret']);
                            }

                    ?>
                        <h2>Woocommerce API</h2>
                        <form method="post">
                                    Consumer Key: <input type="text" name="ConsumerKey" id="ConsumerKey" value="<?php echo $this->datastore->GetConsumerKey();?>"><br>
                                    Consumer Secret: <input type="text" name="ConsumerSecret" id="ConsumerSecret"  value="<?php echo $this->datastore->GetConsumerSecret();?>"><br>
                                    <?php
                                    submit_button(); ?>
                        </form>
                    <?php }?>
                </div>
                <?php 
    }
    public function templates(){
                do_action('wcn_templates');
                include 'templates/templates.php';
    }
}
new Woocommerce_Notice();



function Load()
{
    echo '<script>
      jQuery( document ).ready(function( $ )
            {
            ShowOrder();
            ShowReview();
            });
    </script>"';
}
add_action( 'get_footer', 'Load' );

?>