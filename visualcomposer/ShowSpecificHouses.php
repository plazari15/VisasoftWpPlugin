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
            'fields' => array( "DescricaoEmpreendimento", "DescricaoWeb","BanheiroSocialQtd", "Cidade","Categoria","InfraEstrutura", "Bairro","ValorVenda", "ValorLocacao", "Latitude","Longitude", "Status", "FotoDestaque", "Dormitorios", "Vagas", "AreaTotal", "Caracteristicas", "ValorVenda", "ValorLocacao", array("Foto" => ["Foto", "FotoPequena", "Destaque"]), array("Video" => ["Video"])),
        );

        $api = getCall($dados, 'imoveis/detalhes', filter_input(INPUT_GET, 'imovel', FILTER_VALIDATE_INT), false);
        if(count($api) > 0){
            /* Api do Google */
            $google = getGoogleMaps("http://maps.googleapis.com/maps/api/geocode/json?latlng={$api['Latitude']},{$api['Longitude']}&sensor=true");
            /* Repete as principais caracteristicas */
            foreach ($api['Caracteristicas'] as $key => $item){
                if($item != 'Nao' && $key != 'Andares' && $key != 'Andar Do Apto'  && !empty($item)){
                    $Caracteristicas .= "- {$key}". ($item > 0 ? ': '.$item : '') ."<br>";
                }
            }
            /* Repete INFRAESTRUTURA */
            foreach ($api['InfraEstrutura'] as $key => $item){
                    if(!empty($item) && $item != 'Nao' ){
                        $Infraestrutura .= "- {$key}". ($item > 0 ? ': '.$item : '') ."<br>";
                    }
            }
            /* Template */
            $template = file_get_contents(getPath('assets/tpl/exibe_imovel.tpl.html'));

            /*
             * Cria Imagens
             */
            foreach ($api['Foto'] as $foto){
                if($foto['Destaque'] != "Sim"){
                    $Fotos .= "<li><img src='{$foto['Foto']}' width=\"198\" height=\"156\" alt=\"\"/></li>";
                }
            }
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
                '{{ Fotos }}',
                '{{ Latitude }}',
                '{{ Longitude }}',
                '{{ DescricaoEmpreendimento }}'

            ), array(
                $api['FotoDestaque'],
                $api['Status'],
                $api['ValorVenda'] > 0 ? number_format($api['ValorVenda'], 2, ',', '.') : number_format($api['ValorLocacao'], 2, ',', '.'),
                $api['Codigo'],
                $api['Categoria'],
                $google['results'][0]['address_components'][2]['long_name'],
                $api['DescricaoWeb'],
                $img = getUrl('/assets/img'),
                $google['results'][0]['address_components'][1]['long_name'],
                $google['results'][0]['address_components'][0]['long_name'],
                $google['results'][0]['address_components'][3]['long_name'],
                ($api['AreaTotal'] > 0 ? $api['AreaTotal'] . 'm²' : 'N/d') ,
                $api['Dormitorios'],
                $api['Vagas'],
                $api['BanheiroSocialQtd'],
                $Caracteristicas,
                $Infraestrutura,
                !empty($api['Video']) ? "<iframe width=\"628\" height=\"315\" src='{$api['Video']['Video']}'
          frameborder=\"0\" allowfullscreen></iframe>" : '',
                $Fotos,
                $api['Latitude'],
                $api['Longitude'],
                $api['DescricaoEmpreendimento'],
            ), $template);
            return $final; //Return
        }
    }
}
new ShowSpecificHouses();
