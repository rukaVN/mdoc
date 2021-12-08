<?php

namespace MDoc\Models;

use WPMVC\MVC\Traits\FindTrait;
use WPMVC\Addons\Metaboxer\Abstracts\PostModel as Model;
use MDoc\Databases\DocumentDPO;

/**
 * Document model.
 * WordPress MVC model.
 *
 * @author Tinh Phan <tinhpt.38@gmail.com>
 * @package mdoc
 * @version 1.0.0
 */
class Document extends Model
{
    use FindTrait;
    /**
     * Property type.
     * @since 1.0.0
     *
     * @var string
     */
    protected $type = 'document';

    protected $registry = [
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
    ];
    /**
     * Labels.
     * @var array
     */
    protected $registry_labels  = [
        'name'               => 'Tài liệu',
        'singular_name'      => 'Tài liệu',
        'menu_name'          => 'Tài liệu',
        'name_admin_bar'     => 'Tài liệu',
        'add_new'            => 'Tài liệu mới',
        'add_new_item'       => 'Tạo tài liệu mới',
        'new_item'           => 'Tài liệu mới',
        'edit_item'          => 'Chỉnh sửa tài liệu',
        'view_item'          => 'Xem tài liệu',
        'all_items'          => 'Tất cả tài liệu',
        'search_items'       => 'Tìm tài liệu',
        'not_found'          => 'Không tìm thấy tài liệu nào',
        'not_found_in_trash' => 'Không có tài liệu nào trong thùng rác',
    ];

    /**
     * Wordpress support for during registration.
     * @var array
     */
    protected $registry_supports = [
        'title',
        // 'editor',
        // 'author',
        // 'thumbnail',
        // 'excerpt',
        // 'comments',
    ];

    /**
     * Rewrite.
     * @var array
     */
    protected $registry_rewrite = [
        'slug' => 'document',
    ];

    /**
     * Taxonomies.
     * @var array
     */
    protected $registry_taxonomies = [
        'category',
        'post_tag',
    ];

    /**
     * Property aliases.
     * @since 1.0.0
     *
     * @var array
     */
    protected $aliases = array();
    protected function init()
    {
        $this->metaboxes = [
            'metabox_id' => [
                'title' => __('Mô tả', 'my-domain'),
                'tabs' => [
                    'tab_id' => [
                        'fields' => [
                            'document_description' => [
                                'type' => 'textarea',
                                'show_title' => false,
                                'title' => __('Mô tả tài liệu'),
                                'control' => [
                                    'wide' => true,
                                    'attributes' => [
                                        'rows' => 3,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'metabox_id2' => [
                'title' => __('Tải lên tài liệu', 'my-domain'),
                'tabs' => [
                    'tab_id' => [
                        'title' => __('Tải tài liệu', 'my-domain'),
                        'icon' => 'fa-star',
                        'fields' => [
                            'document_number' => [
                                'title' => __('Số/ký hiệu văn bản'),
                                'control' => [
                                    'wide' => false,
                                ],
                            ],
                            'document_release_date' => [
                                'type' => 'datepicker',
                                'title' => __('Ngày ban hành'),
                                'control' => [
                                    'attributes' => [
                                        'data-date-format' => 'dd/mm/yy',
                                        'data-show-button-panel' => 1,
                                        'data-show-other-months' => 1,
                                        'data-select-other-months' => 1,
                                        'data-change-month' => 1,
                                        'data-change-year' => 1,
                                    ],
                                ],
                            ],
                            'document_effective_date' => [
                                'type' => 'datepicker',
                                'title' => __('Ngày có hiệu lực'),
                                'control' => [
                                    'attributes' => [
                                        'data-date-format' => 'dd/mm/yy',
                                        'data-show-other-months' => 1,
                                        'data-select-other-months' => 1,
                                        'data-change-month' => 1,
                                        'data-change-year' => 1,
                                    ],
                                ],
                            ],
                            'choose_document' => [
                                'type' => 'media',
                                'title' => __('Tập tin'),
                                'control' => [
                                    'wide' => true,
                                    'button_label' => __('Chọn tập tin'),
                                    'icon' => 'fa-file',
                                    'attributes' => [
                                        'data-show-input' => 1,
                                        'data-id-value' => 0,
                                        'data-type	' => 'application/pdf',
                                    ],
                                ],
                            ],
                            'document_references' => [
                                'type' => 'select2',
                                'title' => __('Các tài liệu liên quan', 'my-domain'),
                                'options' => DocumentDPO::get_option_all_document(),
                                'control' => [
                                    'wide' => true,
                                    'attributes' => [
                                        'placeholder' => __('Chọn tài liệu liên quan...', 'my_domain'),
                                        'multiple' => true,
                                        'data-allow-clear' => 1,
                                    ]
                                ],
                            ],
                            'document_note' => [
                                'type' => 'textarea',
                                'title' => __('Ghi chú'),
                                'control' => [
                                    'wide' => true,
                                    'attributes' => [
                                        'rows' => 2,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'metabox_id3' => [
                'title' => __('Danh sách tài liệu liên quan', 'my-domain'),
                'tabs' => [
                    'tab_id' => [
                        'fields' => [
                            'listdocument' => [
                                'type' => 'listdocument',
                                'show_title' => false,
                                'options' => DocumentDPO::get_option_document_references_parent(),
                                'title' => __( 'Duplicator', 'my-domain' ),
                                'control' => [
                                    'wide' => true,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}