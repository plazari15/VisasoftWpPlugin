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
            'fields' => array( "Categoria","Cidade","Bairro","ValorVenda", "ValorLocacao", "Status", "FotoDestaque", "Dormitorios", "Vagas", "AreaTotal", "Caracteristicas" )
        );
        $api = getCall($dados, '/imoveis/listar', null, false);
        //var_dump($api);
        if(count($api) > 0){
            $html .= "<div class='conteudo_meio'>";
                foreach ($api as $item) {
                    $foto = showImage($item['FotoDestaque']);
                    if($item['Status'] == 'ALUGUEL'){
                        $valor = number_format($item['ValorLocacao'], 2, ',', '.');
                    }else{
                        $valor = number_format($item['ValorVenda'], 2, ',', '.');
                    }

                    $img = getUrl();
                    $html .= "<div class='listagem_imoveis'>";
                        $html .= "<div class='titulo_imovel'>{$item['Categoria']}</div>";
                        $html .= "<div class='foto_imovel'>";
                            $html .= "<img src='{$foto}' width='235' height='157' style='float:left' alt=''>";
                            $html .= "<div class='tarja_vermelha'>{$item['Status']}</div>";
                        $html .= "</div>";

                        $html .= "<div class='informacoes_imovel'>";
                            $html .= "<div class='preco_imovel'>R$ {$valor}</div>";
                            $html .= "<div class='resumo-imovel'>{$item['Categoria']}, com {$item['Dormitorios']} dormitórios + {$item['Vagas']} vaga(s) de garagem, com {$item['AreaTotal']} de área total...</div>";
                            $html .= "<div class='ir_imovel'>Mais Detalhes</div>";
                        $html .= "</div> ";

                        $html .= "<div class='dados-imovel'>";
                            $html .= "<div class='caracteristica_imovel'><img src='{$img}/assets/img/area.jpg' style='float: left;margin-top: -4px;' width='21' height='23' alt=''/>{$item['AreaTotal']}m²</div>";
                            $html .= "<div class='caracteristica_imovel'><img src='{$img}/assets/img/quartos.jpg' style='float: left;margin-top: -4px;' width='21' height='23' alt=''/>{$item['Dormitorios']} quartos</div>";
                            $html .= "<div class='caracteristica_imovel'><img src='{$img}/assets/img/banheiros.jpg' style='float: left;margin-top: -4px;' width='21' height='23' alt=''/>{$item['AreaTotal']} banheiros</div>";
                            $html .= "<div class='caracteristica_imovel'><img src='{$img}/assets/img/garagem.jpg' style='float: left;margin-top: -4px;' width='21' height='23' alt=''/>{$item['Vagas']} vagas</div>";
                        $html .= "</div> ";
                    $html .= "</div>";
                }
            $html .= "</div>";
            return $html;
        }
    }
}
new ShowAllHouses();
