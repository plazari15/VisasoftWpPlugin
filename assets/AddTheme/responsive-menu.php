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
	?>

	<?php if ($mk_options['header_search_location'] != 'disable') { ?>
		<form class="responsive-searchform" method="get" action="<?php echo getOption('página_busca'); ?>/">
		    <i class="mk-icon-search"><input value=""  type="submit" /></i>
            <div class="busca_site">

                <div class="select-style">
                    <select name="Finalidade">
                        <option value="Venda">Venda</option>
                        <option value="Aluguel">Aluguel</option>
                        <option value="Venda e Aluguel">Venda e Aluguel</option>
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


<!--                <div class="select-style">-->
<!--                    <select>-->
<!--                        <option value="Localizacaoo">Localização</option>-->
<!--                        <option value="saab">Bairro1</option>-->
<!--                        <option value="mercedes">Bairro2</option>-->
<!--                    </select>-->
<!--                </div>-->

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
