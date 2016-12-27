<?php
/**
 * Esta classe mostra todas as casas que tem disponível
 */

class ShowAllHouses{
    function __construct()
    {
        add_action('init', array($this, 'integrateShowAllHOuses'));

        add_shortcode('allhouses', array($this, 'ShortcodeAllHouses'));

        //add_action('wp_enqueue_scripts', )
    }


    public function integrateShowAllHOuses(){
        // Check if Visual Composer is installed
        if ( ! defined( 'WPB_VC_VERSION' ) ) {
            // Display notice that Visual Compser is required
            add_action('admin_notices', array( $this, 'showVcVersionNotice' ));
            return;
        }

        vc_map([
            'name' => 'Show All Houses',
            'description' => 'Mostra todas as casas',
            'base' => 'allhouses',
            'controls' => 'full',
            'category' => 'Vistasoft'
        ]);

    }

    public function ShortcodeAllHouses(){
        $dados = array(
            'fields' => array( "Categoria","Cidade","Bairro","ValorVenda", "Status", "FotoDestaque", "Dormitorios", "Vagas", "AreaTotal", "Caracteristicas" )
        );
        $api = getCall($dados, '/imoveis/listar', null, false);
        //var_dump($api);
        if(count($api) > 0){
            foreach ($api as $item) {
                $html .= "<div class='col-md-5 custom_box'>";
                $html .= "<h1 class='title_h1'>{$item['Categoria']}</h1>";
                $html .= "<div class='col-md-4'><img class='img_width' width='246' height='167' src='{$item['FotoDestaque']}'></div>";
                $html .= "<div class='col-md-8 topo_color'>TOPO</div>";
                $html .= "<div class='col-md-8'>TOPO</div>";
                $html .= "</div>";
            }

            return $html;
        }
    }
}
new ShowAllHouses();
