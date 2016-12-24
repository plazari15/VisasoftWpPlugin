<?php
/**
 * Esta classe mostra todas as casas que tem disponível
 */

class ShowSpecificHouses{
    function __construct()
    {
        add_action('init', array($this, 'integrateShowSpecificHouses'));

        add_shortcode('showspecifichouse', array($this, 'ShortcodeShowSpecificHouses'));

        //add_action('wp_enqueue_scripts', )
    }


    public function integrateShowSpecificHouses(){
        // Check if Visual Composer is installed
        if ( ! defined( 'WPB_VC_VERSION' ) ) {
            // Display notice that Visual Compser is required
            add_action('admin_notices', array( $this, 'showVcVersionNotice' ));
            return;
        }

        vc_map([
            'name' => 'Show Specific House',
            'description' => 'Mostra uma casa específica',
            'base' => 'showspecifichouse',
            'controls' => 'full',
            'category' => 'Vistasoft'
        ]);

    }

    public function ShortcodeShowSpecificHouses(){
        $dados = array(
            'fields' => array( "Cidade","Bairro","ValorVenda", "Status", "FotoDestaque", "Dormitorios", "Vagas", "AreaTotal", "Caracteristicas", "ValorVenda", "ValorLocacao", array("Foto" => ["Foto", "FotoPequena", "Destaque"])),

        );
        $api = getCall($dados, 'imoveis/detalhes', filter_input(INPUT_GET, 'imovel', FILTER_VALIDATE_INT), true);
        print_r($api);
        if(count($api) > 0){
            foreach ($api as $item) {
                echo $_GET['imovel'] . '<br>';
            }
        }
    }
}
new ShowSpecificHouses();
