<?php

/**
 * template part for responsive search. views/header/global
 *
 * @author 		Artbees
 * @package 	jupiter/views
 * @version     5.0.0
 */

global $mk_options;

if(!is_header_show() && $view_params['is_shortcode'] != 'true') return false;

$menu_location = !empty($view_params['menu_location']) ? $view_params['menu_location'] : mk_main_nav_location();

$hide_header_nav = isset($mk_options['hide_header_nav']) ? $mk_options['hide_header_nav'] : 'true';

?>

<div class="mk-responsive-wrap">

	<?php if($hide_header_nav != 'false') { 
		echo wp_nav_menu(array(
		    'theme_location' => $menu_location,
		    'container' => 'nav',
		    'menu_class' => 'mk-responsive-nav',
		    'echo' => false,
		    'fallback_cb' => 'mk_link_to_menu_editor',
		    'walker' => new mk_main_menu_responsive_walker,
		));
	}

    $dados = array(
        'fields' => array("Bairro"),
    );
    $api = getCall($dados, '/imoveis/listarConteudo', null, false, false);
	?>

	<?php if ($mk_options['header_search_location'] != 'disable') { ?>
		<form class="responsive-searchform" method="get" action="<?php echo getOption('página_busca'); ?>/">
		    <i class="mk-icon-search"><input value=""  type="submit" /></i>
            <div class="busca_site">

                <div class="select-style">
                    <select name="Finalidade">
                        <option value="VENDA">Venda</option>
                        <option value="ALUGUEL">Aluguel</option>
                        <option value="TEMPORADA">Temporada</option>
                        <option value="LANÇAMENTO">Lançamento</option>
                        <option value="FRENTE PARA O MAR">Frente para o Mar</option>
                    </select>
                </div>

                <div class="select-style">
                    <select name="Categoria">
                        <option value="">Categoria</option>
                        <option value="Apartamento">Apartamento</option>
                        <option value="Casa">Casa</option>
                        <option value="Terreno">Terreno</option>
                        <option value="Sobrado">Sobrado</option>
                    </select>
                </div>


                <div class="select-style">
                    <select name="Bairro">
                        <option value="">Bairro</option>
                        <?php if($api['Bairro']): foreach ($api['Bairro'] as $item): ?>
                            <option value="<?= $item ?>"><?= $item ?></option>
                        <?php endforeach; endif;?>
                    </select>
                </div>

                <div class="select-style" >
                    <select name="Dormitorios">
                        <option value="">Dormitórios</option>
                        <option value="1">01</option>
                        <option value="2">02</option>
                        <option value="2">03</option>
                        <option value="99">Mais que 3</option>
                    </select>
                </div>

                <div class="select-style">
                    <select name="Vagas">
                        <option value="">Garagem</option>
                        <option value="1">01</option>
                        <option value="2">02</option>
                        <option value="3">03</option>
                        <option value="99">Mais de 3</option>
                    </select>
                </div>

                <button class="botao_buscar" name="Buscar" type="submit" id="Buscar">Buscar</button>

            </div>
            
		</form>
	<?php } ?>	

</div>
