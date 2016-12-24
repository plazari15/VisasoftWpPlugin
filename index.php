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


/**
 * Criar uma página de opções
 */
acf_add_options_page( [
    'page_title' => 'VistaSoft API',
    'menu_title' => 'VistaSoft Manager',
    'capability' => 'edit_posts',
    'autoload' => true,
] );