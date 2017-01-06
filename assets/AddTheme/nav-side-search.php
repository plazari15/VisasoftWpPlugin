<?php

/**
 * template part for Search form located beside main navigation. views/header/global
 *
 * @author 		Artbees
 * @package 	jupiter/views
 * @version     5.0.0
 */

global $mk_options;

$icon_height = ($view_params['header_style'] != 2) ? 'add-header-height' : '';

$dados = array(
    'fields' => array("Bairro"),
);
$api = getCall($dados, '/imoveis/listarConteudo', null, false, false);

if ($mk_options['header_search_location'] == 'beside_nav') { ?>

<div class="main-nav-side-search">
	
	<a class="mk-search-trigger <?php echo $icon_height; ?> mk-toggle-trigger" href="#"><i class="mk-icon-search"></i></a>

	<div id="mk-nav-search-wrapper" class="mk-box-to-trigger">
		<form method="get" id="mk-header-navside-searchform" action="<?php echo getOption('página_busca'); ?>/">
<!--			<input type="text" name="s" id="mk-ajax-search-input" />-->
<!--			--><?php //wp_nonce_field('mk-ajax-search-form', 'security'); ?>
<!--			<i class="mk-moon-search-3 nav-side-search-icon"><input type="submit" value=""/></i>-->

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
	</div>

</div>

<?php } elseif ($mk_options['header_search_location'] == 'fullscreen_search') { ?>

	<div class="main-nav-side-search">
		<a class="mk-search-trigger <?php echo $icon_height; ?> mk-fullscreen-trigger" href="#"><i class="mk-icon-search"></i></a>
	</div>

<?php
}
