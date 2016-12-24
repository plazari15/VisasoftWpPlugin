<?php
/**
 * Plugin Name: Vista Soft API
 * Description: Plugin criado para exibir todos os imóveis cadastrados pelo usuário!
 * Version: 1.0.0
 * Author: Pedro Lazari
 * Author URI: http://pedrolazari.com
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
require plugin_dir_path(__FILE__) . 'visualcomposer/ShowAllHouses.php';
require plugin_dir_path(__FILE__) . 'visualcomposer/ShowSpecificHouses.php';


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
 * MAe da API
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