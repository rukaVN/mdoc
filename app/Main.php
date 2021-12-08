<?php

namespace MDoc;

use MDoc\Controllers\ListDocument;
use WPMVC\Bridge;
use MDoc\AppBridge;
/**
 * Main class.
 * Bridge between WordPress and App.
 * Class contains declaration of hooks and filters.
 *
 * @author Tinh Phan <tinhpt.38@gmail.com>
 * @package mdoc
 * @version 1.0.0
 */
class Main extends AppBridge
{
    /**
     * Declaration of public WordPress hooks.
     */
    public function init()
    {
        $this->add_model( 'Document' );
        //$this->add_action( 'init', 'DocumentController@demo' );
        
        $this->add_action( 'init', 'DocumentController@register_tax_document_categories' );
        $this->add_action( 'init', 'DocumentController@register_tax_document_managers' );
        add_filter( 'metaboxer_controls', function ( $controls ) {
            $controls[ListDocument::TYPE] = ListDocument::class;
            return $controls;
        } );
        $this->add_shortcode( 'SearchBar', 'view@shortcodes.search-bar' );
    }
    /**
     * Declaration of admin only WordPress hooks.
     * For WordPress admin dashboard.
     */
    public function on_admin()
    {
    }
}