<?php
/**
 * Plugin Name: Vista Soft API
 * Description: Plugin criado para exibir todos os imóveis cadastrados pelo usuário!
 * Version: 1.0.0
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
    wp_enqueue_style( 'bootstrap', plugin_dir_url(__FILE__) . 'thirdParty/bootstrap/css/bootstrap.css', array(), date('s'));
    wp_enqueue_style( 'cutom-plugin', plugin_dir_url(__FILE__) . 'assets/css/custom.css');
//    wp_enqueue_script( 'script-name', get_template_directory_uri() . '/js/example.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'wpdocs_theme_name_scripts' );

/**
 * Mãe da API
 */
function getCall($dados, $action, $imovel = null, $debug = false){
    $key         =  get_field('api_key', 'option'); //Informe sua chave aqui
    $postFields  =  json_encode( $dados );
    $url         =  get_field('url_do_cliente', 'option') . $action .'?key=' . $key;
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
 * Valida a presença de uma foto
 */
function showImage($foto){
    if(!empty($foto)){
        return $foto;
    }

    return plugin_dir_url(__FILE__) . 'assets/img/sem-foto.gif';
}

function getUrl($file = null){
    return plugin_dir_url(__FILE__) . $file;
}