<?php

namespace MDoc\Controllers;

use MDoc\Models\DonVi;
use WPMVC\MVC\Controller;


/**
 * DocumentController
 * WordPress MVC controller.
 *
 * @author Tinh Phan <tinhpt.38@gmail.com>
 * @package mdoc
 * @version 1.0.0
 */

class DocumentController extends Controller
{
	/**
	 * @since 1.0.0
	 *
	 * @hook init
	 *
	 * @return
	 */
	public function demo()
	{
	}
	public function register_tax_document_categories()
	{
		/* Biến $label chứa các tham số thiết lập tên hiển thị của Taxonomy
	 */
		$labels = array(
			'name' => 'Các loại tài liệu',
			'singular' => 'Loại tài liệu',
			'menu_name' => 'Loại tài liệu'
		);
		/* Biến $args khai báo các tham số trong custom taxonomy cần tạo
	 */
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
		);
		/* Hàm register_taxonomy để khởi tạo taxonomy
	 */
		register_taxonomy('loai-tai-lieu', 'document', $args);
	}
	public function register_tax_document_managers()
	{
		/* Biến $label chứa các tham số thiết lập tên hiển thị của Taxonomy
	 */
		$labels = array(
			'name' => 'Các đơn vị quản lý',
			'singular' => 'Đơn vị quản lý',
			'menu_name' => 'Đơn vị quản lý'
		);
		/* Biến $args khai báo các tham số trong custom taxonomy cần tạo
	 */
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
		);
		/* Hàm register_taxonomy để khởi tạo taxonomy
	 */
		register_taxonomy('don-vi-quan-ly', 'document', $args);
	}
}
