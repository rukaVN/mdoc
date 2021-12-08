<?php

/**
 * metaboxer.controls.my-control view.
 * WordPress MVC view.
 *
 * @author Tinh Phan <tinhpt.38@gmail.com>
 * @package mdoc
 * @version 1.0.0
 */
?>
<table class="table-document-ref">
    <colgroup>
        <col span="1" style="width: 5%;">
        <col span="1" style="width: 85%;">
    </colgroup>
    <tr>
        <th>#</th>
        <th>Tên tài liệu</th>
    </tr>
    <?php $x = 0;
    foreach ($options as $key => $label) : ?>
        <tr>
            <td class="scope"><?php $x = $x + 1;echo $x; ?></td>
            <td><a href="post.php?post=<?php echo esc_attr($key)?>&action=edit"><?php echo esc_attr($label) ?></td>
        </tr>
    <?php endforeach ?>
</table>
<?php if (isset($control)) : ?>

<?php endif ?>