<!DOCTYPE html>
<html>

<head>
    <title>Page Title</title>
    <script src="<?= site_url('assets/modules/jquery.min.js') ?>"></script>
    <link rel="stylesheet" href="<?= site_url('assets/modules/bootstrap/css/bootstrap.min.css') ?>">

    <link rel=" icon" href="<?= site_url('assets/img/default/ico.png" type="image/x-icon') ?>">
    <link rel=" stylesheet" href="<?= site_url('assets/modules/fontawesome/css/all.min.css') ?>">
    <link rel=" stylesheet" href="<?= site_url('assets/css/style.css') ?>">
    <link rel=" stylesheet" href="<?= site_url('assets/css/boxicons.min.css') ?>">
    <link rel=" stylesheet" href="<?= site_url('assets/css/components.css') ?>">
    <link rel=" stylesheet" href="<?= site_url('assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css') ?>">

    <script src=" <?= site_url('assets/modules/datatables/datatables.min.js') ?>">
    </script>
    <script src="<?= site_url('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') ?>"></script>
    <script src=" <?= site_url('assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js') ?>"></script>

    <link rel="stylesheet" href="<?= site_url('assets/modules/datatables/datatables.min.css') ?>">


    <link rel=" stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.1.2/css/rowGroup.dataTables.min.css">
    <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script>
    <script src="https://cdn.datatables.net/rowgroup/1.1.2/js/dataTables.rowGroup.min.js"></script>

    <link rel="stylesheet" href="<?= site_url('assets/modules/select2/dist/css/select2.css') ?>">
    <script src=" <?= site_url('assets/modules/select2/dist/js/select2.full.js') ?>">
    </script>

    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
</head>

<body>

    <div class="col-sm-9">
        <select class="form-control form-control-sm" id="wajibpajak" name="wajibpajak"></select>
    </div>

</body>
<<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11">
    </script>

    <script src="<?= site_url('assets/modules/popper.js') ?>"></script>
    <script src="<?= site_url('assets/modules/tooltip.js') ?>"></script>
    <script src="<?= site_url('assets/modules/bootstrap/js/bootstrap.min.js') ?>"></script>
    <script src="<?= site_url('assets/modules/nicescroll/jquery.nicescroll.min.js') ?>"></script>
    <script src="<?= site_url('assets/modules/moment.min.js') ?>"></script>
    <script src="<?= site_url('assets/js/stisla.js') ?>"></script>
    <script src="<?= site_url('assets/js/scripts.js') ?>"></script>
    <script src="<?= site_url('assets/js/custom.js') ?>"></script>
    <script src="<?= site_url('assets/js/select2.js') ?>"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#wajibpajak").select2({
                ajax: {
                    url: "http://localhost/siapad-dev/transaksi/formsptpd/get_data",
                    type: "POST",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data) {
                        console.log("Hasil dari server:", data);
                        return {
                            results: data.items,
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 4,
                width: "resolve"
            });
        });
        $(document).ready(function() {
            $("#filters").click(function() {
                var $icon = $(this).find("i");
                if ($icon.hasClass("fa-search")) {
                    $icon.removeClass("fa-search").addClass("spinner-border");
                    $(this).prop("disabled", true);
                }
                var nomor = $("#nomor").val();
                var rekening = $("#rekening").val();
                var npwpd = $("#npwpd").val();
                var nop = $("#nop").val();
                var wajibpajak = $("#wajibpajak").val();
                var tahun = $("#tahun").val();
                cariData();
            });
            var table = $("#ftf").DataTable({
                "processing": true,
                "serverSide": true,
                "searching": false,
                "paging": true,
                "ajax": {
                    url: "http://localhost/siapad-dev/transaksi/FormSptpd/getDinas",
                    type: "POST",
                    data: function(d) {
                        d.nomor = $("#nomor").val();
                        d.rekening = $("#rekening").val();
                        d.npwpd = $("#npwpd").val();
                        d.nop = $("#nop").val();
                        d.wajibpajak = $("#wajibpajak").val();
                        d.tahun = $("#tahun").val();
                    }
                },
                "columnDefs": [{
                    "targets": 0,
                    "orderable": false,
                    "width": "1%"
                }, {
                    "targets": -1,
                    "width": "10%"
                }],
                "drawCallback": function(settings) {
                    var api = this.api();
                    var start = api.page.info().start;
                    api.column(0, {
                        page: "current"
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = start + i + 1;
                    });
                    $("#filters").prop("disabled", false);
                    var $icon = $("#filters").find("i");
                    if ($icon.hasClass("spinner-border")) {
                        $icon.removeClass("spinner-border").addClass("fas fa-search");
                    }
                },
                "buttons": ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"]
            });

            function cariData() {
                table.ajax.reload();
            }
            $(document).on("click", "#edit", function() {
                var idnya = $(this).data("id");
                var wadi = "Edit";
                $.ajax({
                    url: "http://localhost/siapad-dev/transaksi/formsptpd/myModal",
                    type: "POST",
                    data: {
                        WADI: wadi,
                        idnya: idnya
                    },
                    cache: false,
                    success: function(data) {
                        $("#modalkuE").empty();
                        $("#modalkuE").html(data);
                    }
                });
            });
            $(document).on("click", "#delete", function() {
                var idnya = $(this).data("id");
                var wadi = "Delete";
                $.ajax({
                    url: "http://localhost/siapad-dev/transaksi/formsptpd/myModal",
                    type: "POST",
                    data: {
                        WADI: wadi,
                        idnya: idnya
                    },
                    cache: false,
                    success: function(data) {
                        $("#modalkuD").empty();
                        $("#modalkuD").html(data);
                    }
                });
            });
            $(document).ready(function() {
                $(".datepicker").datepicker({
                    dateFormat: "yy-mm-dd",
                    changeMonth: 1,
                    changeYear: 1
                });
            });

        });
    </script>

</html>