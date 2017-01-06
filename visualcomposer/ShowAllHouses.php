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
        $FinalidadeDescription = "VENDA = Imoveis que estão a venda<br>";
        $FinalidadeDescription .= "ALUGUEL = Imoveis que estão disponiveis para alugar<br>";
        $FinalidadeDescription .= "TEMPORADA = Imoveis que estão disponiveis para alugar e estão marcados como 'Super Destaque'<br>";
        $FinalidadeDescription .= "LANÇAMENTO = Imoveis que estão disponiveis para venda ou aluguel e estão marcados como 'Lançamento'<br>";
        $FinalidadeDescription .= "FRENTE PARA O MAR = Imoveis para venda ou aluguel que estão marcados com a opção 'Exclusivo'*<br>";
        $FinalidadeDescription .= "<small>*Devido a problemas com a API, estamos em contato com a equipe de desenvolvimento vistaSoft para tentar filtar melhor
                                    imoveis com vista para o mar.</small>";
        vc_map([
            'name' => 'Show All Houses',
            'description' => 'Mostra todas as casas',
            'base' => 'allhouses',
            'controls' => 'full',
            'category' => 'Vistasoft',
            'params' => array(
                array(
                    'type' => 'dropdown',
                    'heading' => "Finalidade",
                    'param_name' => 'finalidade',
                    'value' => array( "VENDA", "ALUGUEL", "TEMPORADA", "LANÇAMENTO", "FRENTE PARA O MAR"),
                    'description' => $FinalidadeDescription
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => "Destaque Web",
                    'param_name' => 'destaque',
                    'value' => array("Não", "Sim"),
                    'description' => 'Exibir imoveis em destaque'
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => "Criar paginação",
                    'param_name' => 'paginar',
                    'value' => array("Sim" => true),
                    'description' => 'Criar paginação'
                ),
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "class" => "",
                    "heading" => 'Qtd. Por página',
                    "param_name" => "itens",
                    "value" => "10",
                    "description" => "O Máximo permitido por página são 50 itens!"
                ),
            )
        ]);

    }

    public function ShortcodeAllHouses( $atts, $content = null ){
        extract( shortcode_atts( array(
            'finalidade' => 'VENDA',
            'itens' => '50',
            'destaque' => 'Não',
            'paginar' => false,
        ), $atts ) );
        $pagina_atual = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT);
        // Carrega um array com algumas infos de filtro
        $array = array();
        if($destaque != 'Não'){
            $array['DestaqueWeb'] = 'Sim';
        }

        /**
         * Carrega a finalidade do imovel
         */
//        if(!empty($finalidade)){
//            $array['Status'] = $finalidade;
//        }

        // Exibe no site
        $array['ExibirNoSite'] = "Sim";

        /* Alguns filtros pré-definidos*/
        switch ($finalidade){
            case 'VENDA':
                $array['Status'] = 'VENDA';
                break;

            case 'ALUGUEL':
                $array['Status'] = 'ALUGUEL';
            break;

            case 'TEMPORADA':
                $array['Status'] = 'ALUGUEL';
                $array['SuperDestaqueWeb'] = 'Sim';
                break;

            case 'LANÇAMENTO':
                $array['Lancamento'] = 'Sim';
            break;

            case 'FRENTE PARA O MAR':
                $array['Exclusivo'] = 'Sim';
            break;

        }

        //Busca pela página
        $pagina['pagina'] = (!empty($pagina_atual) ? $pagina_atual : 1);
        $pagina['quantidade'] = !empty($itens) ? $itens : '50';

        $dados = array(
            'fields' => array( "TituloSite", "DescricaoWeb","BanheiroSocialQtd", "Categoria","Cidade","Bairro","ValorVenda", "ValorLocacao", "Status", "FotoDestaque", "Dormitorios", "Vagas", "AreaTotal", "Caracteristicas" ),
            'filter' => $array,
            'paginacao' => $pagina
        );
        $api = getCall($dados, '/imoveis/listar', null, $paginar, false);

        if(count($api) > 0){
            $html .= "<div class='container'>";
                $html .= "<div class='row'>";
                    foreach ($api as $item) {
                        if ($item['Codigo']) {
                            if ($item['Status'] == 'ALUGUEL') {
                                if ($item['ValorLocacao'] > 0) {
                                    $valor = 'R$ ' . number_format($item['ValorLocacao'], 2, ',', '.');
                                } else {
                                    $valor = 'Consulte';
                                }
                            } else {
                                if ($item['ValorVenda'] > 0) {
                                    $valor = 'R$ ' . number_format($item['ValorVenda'], 2, ',', '.');
                                } else {
                                    $valor = 'Consulte';
                                }
                            }

                            $query = getOption('pagina_de_detalhes_do_imovel');
                            $Url = add_query_arg(array('imovel' => $item['Codigo']), $query);
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
                                wp_trim_words($item['DescricaoWeb'], 15, '...'),
                                $Url,
                                ($item['AreaTotal'] > 0 ? $item['AreaTotal'] . 'm²' : 'N/d'),
                                $item['Dormitorios'],
                                $item['BanheiroSocialQtd'],
                                $item['Vagas']
                            ), $template);
                        } //Fim Foreach
                    }
                    if($paginar){
                        $html .= CreatePagination($api['paginas']);
                    }
                $html .= "</div>";
            $html .= "</div>";
            return $html;
        }
    }
}
new ShowAllHouses();
