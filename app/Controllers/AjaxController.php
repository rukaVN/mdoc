<?php

namespace MDoc\Controllers;

use WPMVC\Response;
use WPMVC\Request;
use WPMVC\Log;
use WPMVC\Exceptions;
use TenQuality\WP\Database\QueryBuilder;
use WPMVC\MVC\Controller;

/**
 * AjaxController
 * WordPress MVC controller.
 *
 * @author Tinh Phan <tinhpt.38@gmail.com>
 * @package mdoc
 * @version 1.0.0
 */
class AjaxController extends Controller
{
    /**
     * @since 1.0.0
     *
     * @hook wp_ajax_select2_users
     *
     * @return
     */
    public function ajax_users()
    {
        $response = new Response;
        $response->data = [];
        try {
            // Request
            $request = [
                'term' => Request::input('term'),
            ];
            if (empty($request['term'])) {
                $response->error('term', 'Empty search term.');
            }
            if ($response->passes) {
                $view_engine = $this->view;
                $response->data = QueryBuilder::create()
                    ->select('users.ID as `id`')
                    ->select('users.user_email as `text`')
                    ->select('users.user_login as `username`')
                    ->from('wp_users as users')
                    ->join('wp_usermeta as name', [
                        [
                            'key_a' => 'name.user_id',
                            'key_b' => 'users.ID',
                        ],
                        [
                            'key' => 'name.meta_key',
                            'operator' => 'IN',
                            'value' => ['first_name', 'last_name'],
                        ],
                    ])
                    ->keywords($request['term'], ['users.user_login', 'name.meta_value'])
                    ->group_by('users.ID')
                    ->get( ARRAY_A, function( $row ) use( &$view_engine ) {
                        $row['text'] = $view_engine->get( 'view.key', $row );
                        return $row;
                    } );
                $response->success = true;
            }
        } catch (\Throwable $th) {
            Log::error($th);
        }
        $response->json();
    }
    public function field_id_value($id)
    {
        $user = get_user_by('id', $id);
        return $user->user_email;
    }
}
