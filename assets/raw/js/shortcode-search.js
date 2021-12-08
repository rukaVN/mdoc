/**
 * shortcode-search JavaScript asset.
 *
 * @author Tinh Phan <tinhpt.38@gmail.com>
 * @package mdoc
 * @version 1.0.0
 */
$(document).ready(function () {
    $('#datatable').DataTable({
        initComplete: function () {
            this.api().columns(3).every(function () {
                var column = this;
                var select = $('<select class="cols"><option value="">--- Chọn tất cả ---</option></select>')
                    .appendTo("#document_hold")
                    .on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column
                            .search(val ? '^' + val + '$' : '', true, false)
                            .draw();
                    });

                column.data().unique().sort().each(function (d, j) {
                    select.append('<option value="' + d + '">' + d + '</option>')
                });
            });
            this.api().columns(4).every(function () {
                var column = this;
                var select = $('<select class="cols"><option value="">--- Chọn tất cả ---</option></select>')
                    .appendTo("#document_type")
                    .on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column
                            .search(val ? '^' + val + '$' : '', true, false)
                            .draw();
                    });

                column.data().unique().sort().each(function (d, j) {
                    select.append('<option value="' + d + '">' + d + '</option>')
                });
            });
            this.api().columns(1).every(function () {
                var that = this;
                var input = $('<input class="cols" type="text" placeholder="Nhập từ khóa... " />')
                    .appendTo("#input_key")
                    .on('keyup change clear', function () {
                        if (that.search() !== this.value) {
                            that
                                .search(this.value)
                                .draw();
                        }
                    });
            });
        }
        
    });
    
    $("#datatable_filter").addClass("hidden");
    $("#eg").on("input", function (e) {
        e.preventDefault();
        $('#datatable').DataTable().search(this.value).draw();
    });

    
});

  
$.extend(true, $.fn.dataTable.defaults, {
    //"searching": false,
    "ordering": true,
    "language": {
        "decimal": "",
        "emptyTable": "Không có tài liệu nào trong bảng",
        "info": "Hiển thị từ _START_ đến _END_ của _TOTAL_ tài liệu",
        "infoEmpty": "0 đến 0 của 0 tài liệu",
        "infoFiltered": "(Tìm kiếm từ tất cả _MAX_ tài liệu)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Hiển thị _MENU_ tài liệu",
        "loadingRecords": "Đang tải...",
        "processing": "Processing...",
        "search": "_INPUT_",
        "searchPlaceholder": "Tìm kiếm...",
        "zeroRecords": "Không có kết quả nào phù hợp",
        "paginate": {
            "first": "Trang đầu",
            "last": "Trang cuối",
            "next": "Sau",
            "previous": "Trước"
        },
        "aria": {
            "sortAscending": ": activate to sort column ascending",
            "sortDescending": ": activate to sort column descending"
        }
    }
});
var coll = document.getElementsByClassName("collapsible");
var sear = document.getElementById("eg");

var content = document.getElementById("content");
var btn_quickSearch = document.getElementById("btn");
btn_quickSearch.addEventListener("click", () => {
    if (btn_quickSearch.innerText === "Tìm kiếm nâng cao") {
        btn_quickSearch.innerText = "Tìm kiếm nhanh";
        btn_quickSearch.style.background = "white";
        btn_quickSearch.style.border = "2px solid #79a227";
        btn_quickSearch.style.color = "black";
    } else {
        btn_quickSearch.innerText = "Tìm kiếm nâng cao";
        btn_quickSearch.style.background = "#79a227";
        btn_quickSearch.style.color = "white";
    }
    if (sear.style.visibility === "hidden") {
        sear.style.visibility = "visible";
    } else {
        sear.style.visibility = "hidden";
    }
    if (content.style.display === "block") {
        content.style.display = "none";
    } else {
        content.style.display = "block";
    }
});




   
