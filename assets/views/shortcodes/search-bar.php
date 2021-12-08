<?php

/**
 * shortcodes.search-bar view.
 * WordPress MVC view.
 *
 * @author Tinh Phan <tinhpt.38@gmail.com>
 * @package mdoc
 * @version 1.0.0
 */

use MDoc\Databases\DocumentDPO;
?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>



<div class="wrapper">
  <div id="content" class="content">
    <div class="rows" id="input_key">
      <label class="title">
        Từ khóa
      </label>
    </div>
    <div class="rows" id="document_hold">
      <label class="title">
        Chọn đơn vị quản lý
      </label>
    </div>
    <div class="rows" id="document_type">
      <label class="title">
        Chọn loại tài liệu
      </label>
    </div>
    <button type="button" name="Reset" class="adv_search">Tìm kiếm</button>
  </div>
  <input id="eg" type="text" class="quick_search"  placeholder="Nhập từ khóa">
  <button type="button" id="btn"  class="collapsible">Tìm kiếm nâng cao</button>
  <table id="datatable" class="display dta">
    <thead>
      <tr>
        <th class="coll-1">STT</th>
        <th class="coll-2">Tên tài liệu</th>
        <th class="coll-3">Mô tả</th>
        <th class="coll-4">Đơn vị quản lý</th>
        <th class="coll-5">Loại tài liệu</th>
        <th class="coll-6">Ngày ban hành</th>
        
      </tr>
    </thead>
    <tbody>
      <?php $x = 0;
      $options = DocumentDPO::table_load_data();
      foreach ($options as $values) : ?>
        <tr>
          <td class="coll-1"><?php $x = $x + 1;
                              echo $x; ?></td>
          <td class="coll-2"><?php echo esc_attr($values['post_title']) ?></td>
          <td class="coll-3"><?php echo esc_attr($values[1]) ?></td>
          <td class="coll-4"><?php echo esc_attr($values[0]) ?></td>
          <td class="coll-5"><?php echo esc_attr($values['name']) ?></td>
          <td class="coll-6"><?php echo esc_attr($values['meta_value']) ?></td>
          
        </tr>
      <?php endforeach ?>
      
    </tbody>
    <tfoot>
      <tr>
      </tr>
    </tfoot>
  </table>
</div>