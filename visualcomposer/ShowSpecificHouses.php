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
        /*Api dos Imóveis */
        $dados = array(
            'fields' => array( "Cidade","Categoria","InfraEstrutura", "Bairro","ValorVenda", "ValorLocacao", "Latitude","Longitude", "Status", "FotoDestaque", "Dormitorios", "Vagas", "AreaTotal", "Caracteristicas", "ValorVenda", "ValorLocacao", array("Foto" => ["Foto", "FotoPequena", "Destaque"]), array("Video" => ["Video"])),
        );

        $api = getCall($dados, 'imoveis/detalhes', filter_input(INPUT_GET, 'imovel', FILTER_VALIDATE_INT), false);
        //print_r($api);
        if(count($api) > 0){
            /* Api do Google */
            $google = getGoogleMaps("http://maps.googleapis.com/maps/api/geocode/json?latlng={$api['Latitude']},{$api['Longitude']}&sensor=true");
            /* Repete as principais caracteristicas */
            foreach ($api['Caracteristicas'] as $key => $item){
                if($item != 'Nao' && $key != 'Andar Do Apto'){
                    $ArrayComposicao .= "- {$key}: {$item}<br>";
                }
            }
            /* Repete TODAS as  caracteristicas */
            $i = 0;
            foreach ($api['Caracteristicas'] as $key => $item){
                    if(($i % 2 == 0)){
                        $class = 'align-left';
                    }else{
                        $class = null;
                    }
                    $Caracteristicas .= "<span class='{$class}'>- {$key}: {$item}<br></span>";
                    $i++;
            }

            /* Repete INFRAESTRUTURA */
            foreach ($api['InfraEstrutura'] as $key => $item){
                    $Infraestrutura .= "- {$key}: {$item}<br>";
            }
            /* Template */
            $template = file_get_contents(getPath('assets/tpl/exibe_imovel.tpl.html'));

            /**
             * Faz o find geral
             */
            $final = str_replace(array(
                '#FotoGrande#',
                '{{ Status }}',
                '{{ Valor }}',
                '{{ Codigo }}',
                '{{ Titulo }}',
                '{{ Cidade }}',
                '{{ ArrayComposicao }}',
                '{{ url }}',
                '{{ Endereco }}',
                '{{ Numero }}',
                '{{ Bairro }}',
                '{{ Area }}',
                '{{ Quartos }}',
                '{{ Vagas }}',
                '{{ Lavabos }}',
                '{{ Caracteristicas }}',
                '{{ Infraestrutura }}',
                '{{ Video }}',

            ), array(
                $api['FotoDestaque'],
                $api['Status'],
                $api['ValorVenda'] > 0 ? number_format($api['ValorVenda'], 2, ',', '.') : number_format($api['ValorLocacao'], 2, ',', '.'),
                $api['Codigo'],
                $api['Categoria'],
                $google['results'][0]['address_components'][2]['long_name'],
                $ArrayComposicao,
                $img = getUrl('/assets/img'),
                $google['results'][0]['address_components'][1]['long_name'],
                $google['results'][0]['address_components'][0]['long_name'],
                $google['results'][0]['address_components'][3]['long_name'],
                $api['AreaTotal'],
                $api['Dormitorios'],
                $api['Vagas'],
                $api['Caracteristicas']['Lavabo'] == 'Nao' ? '0' : $api['Caracteristicas']['Lavabo'],
                $Caracteristicas,
                $Infraestrutura,
                !empty($api['Video']) ? "<iframe width=\"628\" height=\"315\" src='{$api['Video']['Video']}'
          frameborder=\"0\" allowfullscreen></iframe>" : ''
            ), $template);
            return $final; //Return
        }
    }
}
new ShowSpecificHouses();
