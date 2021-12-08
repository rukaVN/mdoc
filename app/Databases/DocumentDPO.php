<?php

namespace MDoc\Databases;

use TenQuality\WP\Database\QueryBuilder;
use MDoc\Models\Document;
use PhpParser\Node\Expr\Cast\Array_;

class DocumentDPO
{
    /*
        Hàm truy vấn tất cả tài liệu từ cơ sở dữ liệu
    */
    static function get_all_documents()
    {
        try {
            $records = QueryBuilder::create()
                ->select('*')
                ->from('posts')
                ->where(
                    array(
                        'post_type' => 'document',
                        'post_status' => 'publish'
                    )
                )->get();
            return $records;
        } catch (\Throwable $th) {
            return [];
        }
    }
    /*
        Hàm truy vấn ra id tài liệu và liên kết tới các tài liệu liên quan
        trong bảng wp_post_meta
        output:
        [0] => stdClass Object
        (
            [post_id] => 283
            [meta_value] => a:2:{i:0;i:332;i:1;i:335;} //Các tài liệu liên quan đến post_id=283
        )
        ...
    */
    static function get_all_documents_ref()
    {
        try {
            $records = QueryBuilder::create()
                ->select('post_id')
                ->select('meta_value')
                ->from('postmeta')
                ->join('posts', [
                    [
                        'key_a' => 'wp_posts.ID',
                        'key_b' => 'wp_postmeta.post_id',
                    ],
                    [
                        'key' => 'wp_postmeta.meta_key',
                        'value' => ['document_references'],
                    ],
                ])
                ->get();
            return $records;
        } catch (\Throwable $th) {
            return [];
        }
    }
    /*
        Hàm lấy trường ID và Post Title của tất cả tài liệu
    */
    static function get_option_all_document()
    {
        $records = self::get_all_documents();
        $post_ids = array_column($records, 'ID');
        $post_titles = array_column($records, 'post_title');
        $options = array_combine($post_ids, $post_titles);
        return $options;
    }
    /*
        Truy vấn tài liệu theo ID
        Truyền vào tham số $document_id
    */
    static function get_documents_byID($document_id)
    {
        try {
            $records = QueryBuilder::create()
                ->select('*')
                ->from('posts')
                ->where(
                    array(
                        'ID' => $document_id,
                        'post_type' => 'document',
                        'post_status' => 'publish'
                    )
                )->get();
            return $records;
        } catch (\Throwable $th) {
            return [];
        }
    }

    static function get_documents_to_table($meta_key, $tax)
    {
        try {
            $records = QueryBuilder::create()
                ->select('a.ID')
                ->select('a.post_title')
                ->select('b.meta_value')
                ->select('c.name')
                ->from('postmeta as b')
                ->join('posts as a', [
                    [
                        'key_a' => 'a.ID',
                        'key_b' => 'b.post_id',
                    ],
                ])
                ->join('term_relationships as d', [
                    [
                        'key_a' => 'd.object_id',
                        'key_b' => 'b.post_id',
                    ],
                ])
                ->join('terms as c', [
                    [
                        'key_a' => 'c.term_id',
                        'key_b' => 'd.term_taxonomy_id',
                    ],
                ])
                ->join('term_taxonomy as e', [
                    [
                        'key_a' => 'e.term_id',
                        'key_b' => 'c.term_id',
                    ],
                ])
                ->where(
                    array(
                        'b.meta_key' => $meta_key,
                        'e.taxonomy' => $tax,
                        'a.post_type' => 'document',
                        'a.post_status' => 'publish'
                    )
                )->get(ARRAY_A);
            return $records;
        } catch (\Throwable $th) {
            return [];
        }
    }
    /*
        Danh sách tài liệu liên quan đến tài liệu hiện tại
    */
    static function get_option_document_references_parent()
    {
        /* Lấy tất cả tài liệu liên quan của tài liệu hiện tại */
        $records = self::get_all_documents_ref();
        /* Chọn ra key=post_id và value dạng serialize */
        $document_references = array_column($records, 'meta_value', 'post_id');
        /* Lấy ID hiện tại của bài viết */
        $document_id = get_the_ID();
        /* Tạo một mảng chứa ID của các tài liệu liên quan đến bài viết hiện tại */
        $document_references_byID = array(); 
        if (isset($document_references[$document_id])) {
            $document_references_byID = unserialize($document_references[$document_id]);
        }
        /* Tạo một mảng chứa thông tin tài liệu liên quan*/
        $new_array = array();
        if (is_array($document_references_byID) || is_object($document_references_byID)) {
            foreach ($document_references_byID as $element) {
                $new_array = array_merge($new_array, self::get_documents_byID($element));
            }
        }
        /* Trả về key=ID và value=post_title */
        $post_titles = array_column($new_array, 'post_title', 'ID');
        return $post_titles;
    }
    /**
     * Output:
     */
    // [0] => Array
    //     (
    //         [ID] => 283
    //         [post_title] => Test
    //         [meta_value] => 15/10/2021
    //         [name] => Biểu mẫu
    //         [0] => Phòng Quản lý Đào tạo
    //         [1] => Hello
    //     )
    //  [1] => ...
    static function table_load_data()
    {
        $records = self::get_documents_to_table('document_release_date', 'loai-tai-lieu');
        $records_1 = self::get_documents_to_table('document_description', 'don-vi-quan-ly');
        $new_array = array();
        //Lấy ra trường document_description và don-vi-quan-ly bỏ vào mảng mới
        foreach ($records_1 as $values) {
            array_push($new_array, $values['name']);
            array_push($new_array, $values['meta_value']);
        }
        //tách mảng mới thành các mảng con với mỗi mảng chứa 'name' và 'meta_value'
        $chunckedArray = array_chunk($new_array, 2);
        error_log(print_r($new_array, true));
        //gộp mảng từ truy vấn ban đầu + trường document_description và don-vi-quan-ly theo từng post_id
        foreach ($records as $key => &$value) {
            $value = array_merge($value, $chunckedArray[$key]);
        }
        return $records;
    }
   

    
}
