<?php
/**
 * Plugin Name: Vista Soft API
 * Description: Plugin criado para exibir todos os imóveis cadastrados pelo usuário!
 * Version: 1.5.0
 * Author: Pedro Lazari
 * Author URI: http://pedrolazari.com
 * http://www.vistasoft.com.br/api/#pesquisar
 */
if(!defined('ABSPATH')) die('Você não tem permissão');

function startThisPlugin() {
    global $wp_version;
    if(version_compare($wp_version, '4.7', '<')):
        wp_die( 'Opss: essa versão do WordPress Não condiz com nosso plugin' );
    endif;
}
register_activation_hook( __FILE__, 'startThisPlugin' );

require plugin_dir_path(__FILE__) . 'thirdParty/advanced-custom-fields-pro/acf.php';
require plugin_dir_path(__FILE__) . 'thirdParty/advanced-custom-fields-pro/Fields.php';
require plugin_dir_path(__FILE__) . 'visualcomposer/ShowAllHouses.php';
require plugin_dir_path(__FILE__) . 'visualcomposer/ShowSpecificHouses.php';
require plugin_dir_path(__FILE__) . 'visualcomposer/Search.php';
require_once( plugin_dir_path(__FILE__) . 'update.php' );

/**
 * Removendo exibição do menu
 */

function my_acf_init() {

    acf_update_setting('show_admin', false);

}

add_action('acf/init', 'my_acf_init');

/**
 * Criar uma página de opções
 */
acf_add_options_page( [
    'page_title' => 'VistaSoft API',
    'menu_title' => 'VistaSoft Manager',
    'capability' => 'edit_posts',
    'autoload' => true,
] );

/**
 * Adicionando o Bootstrap no jogo
 */
function wpdocs_theme_name_scripts() {
    $googleKey = getOption('google_api');
    wp_enqueue_style( 'bootstrap', plugin_dir_url(__FILE__) . 'thirdParty/bootstrap/css/bootstrap.css', array(), date('s'));
    wp_enqueue_style( 'custom-plugin', plugin_dir_url(__FILE__) . 'assets/css/custom.css');
    wp_enqueue_style( 'fancy-box', plugin_dir_url(__FILE__) . 'assets/css/jquery.fancybox.css');
    wp_enqueue_style( 'flexslider', plugin_dir_url(__FILE__) . 'assets/css/flexslider.css');
    wp_enqueue_style( 'estilo_exibe_imovel', plugin_dir_url(__FILE__) . 'assets/css/estilo_exibe_imovel.css');
    wp_enqueue_style( 'estilo_listagem', plugin_dir_url(__FILE__) . 'assets/css/estilo-listagem.css');
    wp_enqueue_script( 'script-maps', plugin_dir_url(__FILE__) . 'assets/js/GoogleMaps.js', array('jquery', 'script-google'), '1.0.0', true );
    wp_enqueue_script( 'script-google', "https://maps.googleapis.com/maps/api/js?key={$googleKey}", array('jquery'), '1.0.0', true );
    wp_enqueue_script( 'jquery.fancybox', plugin_dir_url(__FILE__) . 'assets/js/jquery.fancybox.js', array('jquery'), '1.0.0', true );
    wp_enqueue_script( 'jquery.fancybox-media', plugin_dir_url(__FILE__) . 'assets/js/jquery.fancybox-media.js', array('jquery'), '1.0.0', true );
    wp_enqueue_script( 'jquery.flexslider', plugin_dir_url(__FILE__) . 'assets/js/jquery.flexslider.js', array('jquery'), '1.0.0', true );
    wp_enqueue_script( 'bootstrap', plugin_dir_url(__FILE__) . 'assets/js/bootstrap.js', array('jquery'), '1.0.0', true );
    wp_enqueue_script( 'comum', plugin_dir_url(__FILE__) . 'assets/js/comum.js', array('jquery'), '1.0.0', true );
//    wp_enqueue_script( 'script-name', get_template_directory_uri() . '/js/example.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'wpdocs_theme_name_scripts' );

/**
 * Mãe da API
 */
function getCall($dados, $action, $imovel = null, $paginar = false, $debug = false){
    $key         =  get_field('api_key', 'option'); //Informe sua chave aqui
    $postFields  =  json_encode( $dados );
    $url         =  get_field('url_do_cliente', 'option') . '/'. $action .'?key=' . $key;
    if($paginar){
        $url .= '&showtotal=1';
    }
    if($imovel != null){
        $url .= '&imovel=' . $imovel;
    }

    $url        .=  '&pesquisa=' . $postFields;

    $ch = curl_init($url);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_HTTPHEADER , array( 'Accept: application/json' ) );
    $result = curl_exec( $ch );

    $result = json_decode( $result, true );

    if($debug){
        return $url;
    }
    return $result;
}

/**
 * Google API
 */
function getGoogleMaps($url, $debug = false){
    $ch = curl_init($url);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_HTTPHEADER , array( 'Accept: application/json' ) );
    $result = curl_exec( $ch );

    $result = json_decode( $result, true );

    if($debug){
        return $url;
    }
    return $result;
}

/**
 * Valida a presença de uma foto
 */
function showImage($foto){
    if(!empty($foto)){
        return $foto;
    }

    return plugin_dir_url(__FILE__) . 'assets/img/sem-foto.gif';
}

/**
 * Pega a URL Que chega ao plugin
 * @param null $file
 * @return string
 */
function getUrl($file = null){
    return plugin_dir_url(__FILE__) . $file;
}

function getPath($file = null){
    return plugin_dir_path(__FILE__) . $file;
}

/**
 *
 */
function getOption($field){
    return get_field($field, 'option');
}

/**
 * Criar paginação
 */
function CreatePagination($paginas = 2, $args = array() ){
    global $wp;
    $html = '<div class="container">';
        $html .= '<div class="row">';
            $html .= '<ul class="pagination">';
            //Aqui vem meu repetidor
                for ($i = 1; $i <= $paginas; $i++){
                    $args['pagina'] = $i;
                    $url = home_url(add_query_arg(array($args),$wp->request));
                    $html .= "<li><a href='{$url}'>{$i}</a></li>";
                }
            $html .= '</ul>';
        $html .= '</div>';
    $html .= '</div>';

    return $html;
}

/**
 * Plugin Auto Update
 */
new WPUpdatesPluginUpdater_1541( 'http://wp-updates.com/api/2/plugin', plugin_basename(__FILE__));
