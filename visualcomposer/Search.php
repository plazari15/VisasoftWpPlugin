<?php
/**
 * Esta classe mostra todas as casas que tem disponível
 */

class SearchComposer{
    function __construct()
    {
        add_action('init', array($this, 'integrateSearch'));

        add_shortcode('showsearch', array($this, 'ShortcodeSearch'));

        //add_action('wp_enqueue_scripts', )
    }


    public function integrateSearch(){
        // Check if Visual Composer is installed
        if ( ! defined( 'WPB_VC_VERSION' ) ) {
            // Display notice that Visual Compser is required
            add_action('admin_notices', array( $this, 'showVcVersionNotice' ));
            return;
        }

        vc_map([
            'name' => 'Search',
            'description' => 'Busca do usuário',
            'base' => 'showsearch',
            'controls' => 'full',
            'category' => 'Vistasoft'
        ]);

    }

    public function ShortcodeSearch(){
        $Finalidade = get_query_var('Finalidade', 'Vendsaa');
        $Categoria = filter_input(INPUT_GET,'Categoria');
        $Dormitorios = filter_input(INPUT_GET,'Dormitorios');
        $Vagas = filter_input(INPUT_GET,'Vagas');
        /*Api dos Imóveis */
        $dados = array(
            'fields' => array( "Cidade","Categoria","InfraEstrutura", "Bairro","ValorVenda", "ValorLocacao", "Latitude","Longitude", "Status", "FotoDestaque", "Dormitorios", "Vagas", "AreaTotal", "Caracteristicas", "ValorVenda", "ValorLocacao"),
            'filter' => array(
                'Categoria' => $Categoria,
                'Finalidade' => $Finalidade,
                'Dormitorios' => $Dormitorios,
                'Vagas' => $Vagas
            )
        );

        $filter = array(
            'Categoria' => 'Apartamento'
        );

        $api = getCall($dados, 'imoveis/listar', null,  false);
        if(count($api) > 0){
            $html .= "<div class='container'>";
                $html .= "<div class='row'>";
                    if(!isset($api['message'])){
                        foreach ($api as $item) {
                            if($item['Status'] == 'ALUGUEL'){
                                if($valor > 0){
                                    $valor = number_format($item['ValorLocacao'], 2, ',', '.');
                                }
                            }else{
                                if($valor > 0){
                                    $valor = number_format($item['ValorVenda'], 2, ',', '.');
                                }
                            }

                            $query = getOption('pagina_de_detalhes_do_imovel');
                            $Url = add_query_arg( array('imovel' => $item['Codigo']), $query );
                            $template = file_get_contents(getPath('assets/tpl/listagem.tpl.html'));

                            $html .= str_replace(array(
                                '{{ Categoria }}',
                                '{{ FotoGrande }}',
                                '{{ Status }}',
                                '{{ Valor }}',
                                '{{ url }}',
                                '{{ Descricao }}',
                                '{{ link }}'
                            ), array(
                                $item['Categoria'],
                                showImage($item['FotoDestaque']),
                                $item['Status'],
                                $item['ValorVenda'] > 0 ? number_format($item['ValorVenda'], 2, ',', '.') : number_format($item['ValorLocacao'], 2, ',', '.'),
                                $img = getUrl('/assets/img'),
                                "{$item['Categoria']}, com {$item['Dormitorios']} dormitórios + {$item['Vagas']} vaga(s) de garagem, com {$item['AreaTotal']} de área total...",
                                $Url
                            ), $template);
                        }
                    }else{
                        echo "<h3>" . $api['message'] . "</h3>";
                    }
                $html .= "</div>";
            $html .= "</div>";
            return $html;
        }else{
            print_r('Não mostra nada, sem campos suficientes');
        }
    }
}
new SearchComposer();
