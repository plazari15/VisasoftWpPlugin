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
        /** Carrega os dados da URL */
        $option['Finalidade'] = filter_input(INPUT_GET,'Finalidade') ;
        $option['Categoria'] = filter_input(INPUT_GET,'Categoria');
        $option['Dormitorios'] = filter_input(INPUT_GET,'Dormitorios');
        $option['Vagas']= filter_input(INPUT_GET,'Vagas');
        $option['Bairro']= filter_input(INPUT_GET,'Bairro');
        $pagina_atual = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT);

        // Gera o filtro
        $filter = array();
        foreach ($option as $key => $item){
            if(!empty($item)){
                $filter[$key] = $item;
            }
        }

        switch ($filter['Finalidade']) {
            case 'VENDA':
                $filter['Status'] = 'VENDA';
                break;

            case 'ALUGUEL':
                $filter['Status'] = 'ALUGUEL';
                break;

            case 'TEMPORADA':
                $filter['Status'] = 'ALUGUEL';
                $filter['SuperDestaqueWeb'] = 'Sim';
                break;

            case 'LANÇAMENTO':
                $filter['Lancamento'] = 'Sim';
                break;

            case 'FRENTE PARA O MAR':
                $filter['Exclusivo'] = 'Sim';
                break;
        }
        unset($filter['Finalidade']);
                //Busca pela página
        $pagina['pagina'] = (!empty($pagina_atual) ? $pagina_atual : 1);
        $pagina['quantidade'] = 5;

        /*Api dos Imóveis */
        $dados = array(
            'fields' => array("TituloSite", "DescricaoWeb","BanheiroSocialQtd", "Cidade","Categoria","InfraEstrutura", "Bairro","ValorVenda", "ValorLocacao", "Latitude","Longitude", "Status", "FotoDestaque", "Dormitorios", "Vagas", "AreaTotal", "Caracteristicas", "ValorVenda", "ValorLocacao"),
            'filter' => $filter,
            'paginacao' => $pagina
        );

        $api = getCall($dados, 'imoveis/listar', null,  true, false);
        if(count($api) > 0){
            $html .= "<div class='container'>";
            $html .= "<div class='row'>";
                if(!isset($api['message'])){
                    foreach ($api as $item) {
                        if ($item['Codigo']):
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
                            endif;
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
                    $html .= CreatePagination($api['paginas'], $pagina['pagina'], array(
                        'Finalidade' => $option['Finalidade'],
                        'Categoria'  => $option['Categoria'],
                        'Bairro'  => $option['Bairro'],
                        'Dormitorios'  => $option['Dormitorios'],
                        'Vagas'  => $option['Vagas'],
                        'Buscar'  => $option['Buscar'],
                    ));
            $html .= "</div>";
            return $html;
        }
    }
}
new SearchComposer();
