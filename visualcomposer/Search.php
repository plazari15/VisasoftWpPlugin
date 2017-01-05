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
        $options = array( 'options' => array('default'=> 0) );
        $option['Finalidade'] = filter_input(INPUT_GET,'Finalidade') ;
        $option['Categoria'] = filter_input(INPUT_GET,'Categoria');
        $option['Dormitorios'] = filter_input(INPUT_GET,'Dormitorios');
        $option['Vagas']= filter_input(INPUT_GET,'Vagas');
        $filter = array();
        foreach ($option as $key => $item){
            if(!empty($item)){
                $filter[$key] = $item;
            }
        }
        /*Api dos Imóveis */
        $dados = array(
            'fields' => array("TituloSite", "DescricaoWeb","BanheiroSocialQtd", "Cidade","Categoria","InfraEstrutura", "Bairro","ValorVenda", "ValorLocacao", "Latitude","Longitude", "Status", "FotoDestaque", "Dormitorios", "Vagas", "AreaTotal", "Caracteristicas", "ValorVenda", "ValorLocacao"),
            'filter' => $filter
        );

        $api = getCall($dados, 'imoveis/listar', null,  false);
       // print_r($api);

        if(count($api) > 0){
            $html .= "<div class='container'>";
            $html .= "<div class='row'>";
                if(empty($api['message'])){
                    foreach ($api as $item) {
                        if($item['Status'] == 'ALUGUEL'){
                            if($item['ValorLocacao'] > 0){
                                $valor = 'R$ ' . number_format($item['ValorLocacao'], 2, ',', '.');
                            }else{
                                $valor =  'Consulte';
                            }
                        }else{
                            if($item['ValorVenda'] > 0){
                                $valor = 'R$ ' . number_format($item['ValorVenda'], 2, ',', '.');
                            }else{
                                $valor = 'Consulte';
                            }
                        }

                        $query = getOption('pagina_de_detalhes_do_imovel');
                        $Url = add_query_arg( array('imovel' => $item['Codigo']), $query );
                        $template = file_get_contents(getPath('assets/tpl/listagem.tpl.html'));
                        $html .= str_replace(array(
                            '{{ TituloSite }}',
                            '{{ Categoria }}',
                            '{{ FotoGrande }}',
                            '{{ Status }}',
                            '{{ Valor }}',
                            '{{ url }}',
                            '{{ Descricao }}',
                            '{{ link }}',
                            '{{ tamanho }}',
                            '{{ quartos }}',
                            '{{ banheiros }}',
                            '{{ vagas }}'
                        ), array(
                            $item['Categoria'],
                            $item['Categoria'],
                            showImage($item['FotoDestaque']),
                            $item['Status'],
                            $valor,
                            $img = getUrl('/assets/img'),
                            wp_trim_words($item['DescricaoWeb'], 30, '...'),
                            $Url,
                            ($item['AreaTotal'] > 0 ? $item['AreaTotal'] . 'm²' : 'N/d'),
                            $item['Dormitorios'],
                            $item['BanheiroSocialQtd'],
                            $item['Vagas']
                        ), $template);
                    }
                }else{
                    $template = file_get_contents(getPath('assets/tpl/semResultados.tpl.html'));
                    $html .= str_replace(array(
                        '{{ Message }}'
                    ), array(
                        $api['message']
                    ), $template);
                }
            $html .= "</div>";
            $html .= "</div>";
            return $html;
        }
    }
}
new SearchComposer();
