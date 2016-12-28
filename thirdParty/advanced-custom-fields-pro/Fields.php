<?php

if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array (
        'key' => 'group_585de85445a17',
        'title' => 'Configurações',
        'fields' => array (
            array (
                'message' => 'Mantenha seu plugin sempre atualizado! Após o desenvolvimento do mesmo, durante 30 dias você tem suporte contra qualquer tipo de problema ou conflito em seu plugin, e por meio das atualizações automáticas, nos podemos te entregar os últimos updates!',
                'esc_html' => 0,
                'new_lines' => 'wpautop',
                'key' => 'field_5863448d813dd',
                'label' => 'Lembre-se!',
                'name' => '',
                'type' => 'message',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array (
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
            ),
            array (
                'default_value' => '',
                'maxlength' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'key' => 'field_585de85b326a2',
                'label' => 'API Key',
                'name' => 'api_key',
                'type' => 'text',
                'instructions' => 'Digite o Token da API',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array (
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
            ),
            array (
                'default_value' => '',
                'maxlength' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'key' => 'field_5862cebaf5216',
                'label' => 'Google API',
                'name' => 'google_api',
                'type' => 'text',
                'instructions' => 'Digite o Token da API',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array (
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
            ),
            array (
                'default_value' => '',
                'placeholder' => '',
                'key' => 'field_585de88a326a3',
                'label' => 'URL do Cliente',
                'name' => 'url_do_cliente',
                'type' => 'url',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array (
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
            ),
            array (
                'post_type' => array (
                    0 => 'page',
                ),
                'taxonomy' => array (
                ),
                'allow_null' => 0,
                'multiple' => 0,
                'allow_archives' => 1,
                'key' => 'field_5862bd5ad0e07',
                'label' => 'Página de Detalhes do imóvel',
                'name' => 'pagina_de_detalhes_do_imovel',
                'type' => 'page_link',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array (
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
            ),
            array (
                'post_type' => array (
                    0 => 'page',
                ),
                'taxonomy' => array (
                ),
                'allow_null' => 0,
                'multiple' => 0,
                'allow_archives' => 1,
                'key' => 'field_5862bd81d0e08',
                'label' => 'Página de Busca',
                'name' => 'página_busca',
                'type' => 'page_link',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array (
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
            ),
        ),
        'location' => array (
            array (
                array (
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'acf-options-vistasoft-manager',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => '',
    ));

endif;