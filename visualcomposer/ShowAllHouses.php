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
            'category' => 'Vistasoft',
            'params' => array(
                array(
                    'type' => 'dropdown',
                    'heading' => "Finalidade",
                    'param_name' => 'finalidade',
                    'value' => array( "VENDA", "ALUGUEL", "VENDA E ALUGUEL" ),
                    'description' => 'Qual o tipo de imovel que deseja exibir'
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => "Destaque Web",
                    'param_name' => 'destaque',
                    'value' => array("Não", "Sim"),
                    'description' => 'Exibir imoveis em destaque'
                ),
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "class" => "",
                    "heading" => 'Qtd. Por página',
                    "param_name" => "itens",
                    "value" => "10",
                    "description" => "Exibir itens por página"
                ),
            )
        ]);

    }

    public function ShortcodeAllHouses( $atts, $content = null ){
        extract( shortcode_atts( array(
            'finalidade' => 'VENDA',
            'itens' => '50',
            'destaque' => 'Não'
        ), $atts ) );
        $array = array();
        if($DestaqueWeb != 'Não'){
            $array['DestaqueWeb'] = 'Sim';
        }

        if(!empty($finalidade)){
            $array['Status'] = $finalidade;
        }

        $array['ExibirNoSite'] = "Sim";

        $pagina['pagina'] = '1',
        $pagina['quantidade'] = 

        $dados = array(
            'fields' => array( "TituloSite", "DescricaoWeb","BanheiroSocialQtd", "Categoria","Cidade","Bairro","ValorVenda", "ValorLocacao", "Status", "FotoDestaque", "Dormitorios", "Vagas", "AreaTotal", "Caracteristicas" ),
            'filter' => $array,
            'paginacao' => array("pagina" => '1', "quantidade" => $itens)
        );
        $api = getCall($dados, '/imoveis/listar', null, false);

        if(count($api) > 0){
            $html .= "<div class='container'>";
                $html .= "<div class='row'>";
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
                            wp_trim_words($item['DescricaoWeb'], 15, '...'),
                            $Url,
                            ($item['AreaTotal'] > 0 ? $item['AreaTotal'] . 'm²' : 'N/d'),
                            $item['Dormitorios'],
                            $item['BanheiroSocialQtd'],
                            $item['Vagas']
                        ), $template);
                    }
                $html .= "</div>";
            $html .= "</div>";
            return $html;
        }
    }
}
new ShowAllHouses();
