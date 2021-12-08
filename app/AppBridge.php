<?php

namespace MDoc;

abstract class AppBridge extends \WPMVC\Bridge
{
    public function _models()
    {
        foreach ( $this->models as $model ) {
            $post_name = $this->config->get('namespace').'\Models\\'.$model;
            $post = new $post_name;
            unset( $post_name );
            // Create registry
            $registry = $post->registry;
            // Build registration
            if ( !empty( $registry ) ) {
                if ( !empty( $post->registry_labels ) ) {
                    $name = ucwords( preg_replace( '/\-\_/', ' ', $post->type ) );
                    $plural = strpos( $name, ' ' ) === false ? $name.'s' : $name;
                    $registry['labels'] = [
                        'name'               => $plural,
                        'singular_name'      => $name,
                        'menu_name'          => $plural,
                        'name_admin_bar'     => $name,
                        'add_new_item'       => sprintf( 'Add New %s', $name ),
                        'new_item'           => sprintf( 'New %s', $name ),
                        'edit_item'          => sprintf( 'Edit %s', $name ),
                        'view_item'          => sprintf( 'View %s', $name ),
                        'all_items'          => sprintf( 'All %s', $plural ),
                        'search_items'       => sprintf( 'Search %s', $plural ),
                        'parent_item_colon'  => sprintf( 'Parent %s', $plural ),
                        'not_found'          => sprintf( 'No %s found.', strtolower( $plural ) ),
                        'not_found_in_trash' => sprintf( 'No %s found in Trash.', strtolower( $plural ) ),
                    ];
                } else {
                    $registry['labels'] = $post->registry_labels;
                }
                $registry['supports'] = $post->registry_supports;
                if ( empty( $post->registry_rewrite ) ) {
                    $slug = strtolower(preg_replace('/\_/', '-', $post->type));
                    $registry['rewrite'] = [
                        'slug' => strtolower(preg_replace('/\_/', '-', $post->type)),
                    ];
                } else {
                    $registry['rewrite'] = $post->registry_rewrite;
                }
                // Register
                register_post_type( $post->type, $registry );
            } else if ( ! empty( $post->registry_supports ) ) {
                add_action( 'admin_init', [ &$this, '_registry_supports_'.$post->type ] );
            }
            if ( $post->registry_metabox ) {
                // Add save action once
                $addAction = true;
                for ( $i = count( $this->_automatedModels )-1; $i >= 0; --$i ) {
                    if ( $this->_automatedModels[$i]->type === $post->type ) {
                        $addAction = false;
                        break;
                    }
                }
                // Add post
                $this->_automatedModels[] = $post;
                // Hook save_post
                if ( $addAction )
                    add_action( 'save_post', [ &$this, '_save_'.$post->type ] );
                unset( $addAction );
            }
            // Register taxonomies
            if ( !empty( $post->registry_taxonomies ) ) {
                foreach ( $post->registry_taxonomies as $taxonomy => $args ) {
                    if ( !isset( $args ) || !is_array( $args ) )
                        throw new \Exception( 'Arguments are missing for taxonomy "'.$taxonomy.'", post type "'.$post->type.'".' );
                    register_taxonomy( $taxonomy, $post->type, $args );
                }
            }
        }
        // Metabox hook
        add_action( 'add_meta_boxes', [ &$this, '_metabox' ] );
    }
}
