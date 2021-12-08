<?php
namespace MDoc\Controllers;

use WPMVC\Addons\Metaboxer\Abstracts\Control;

class ListDocument extends Control
{
    /**
     * tên loại control.
     * @var string
     */
    const TYPE = 'listdocument';

    protected $type = self::TYPE;
    /**
     * liên kết tới tập tin render 
     */
    public function render( $args = [] )
    {
        get_bridge( 'MDoc' )->view( 'metaboxer.controls.my-control', $args );
    }
    /**
     * liên kết tới tập tin css
     */
    public function enqueue()
    {
        wp_enqueue_style(
            'my-control-style',
            assets_url( 'raw/css/listdocument.css', __FILE__ ),
            [],
            '1.0.0'
        );
    }
}

