<!DOCTYPE html>
<html>

<head>
    <title>Page Title</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

    <script>
        $(document).ready(function() {
            $("#check2").on("change", function() {
                if ($(this).is(":checked")) {
                    $(".check-2").prop("checked", true);
                    $(".check-24").prop("checked", true);
                } else {
                    $(".check-2").prop("checked", false);
                    $(".check-24").prop("checked", false);
                }
            });
            $(".check-2").on("change", function() {
                if ($(this).is(":checked")) {
                    $("#check2").prop("checked", true);
                } else {
                    if ($(".check-2:checked").length > 0) {
                        $("#check2").prop("checked", true);
                    } else {
                        $("#check2").prop("checked", false);
                    }
                }
            });

            $("#check24").on("change", function() {
                if ($(this).is(":checked")) {
                    $(".check-24").prop("checked", true);
                } else {
                    $(".check-24").prop("checked", false);
                }
            });

            $(".check-24").on("change", function() {
                if ($(this).is(":checked")) {
                    $("#check24").prop("checked", true);
                    $("#check2").prop("checked", true);
                } else {
                    if ($(".check-24:checked").length > 0) {
                        $("#check24").prop("checked", true);
                    } else {
                        $("#check24").prop("checked", false);
                    }
                }
            });

            function checked(id = null, clas = null, clas2 = null) {

            }

            function toggleSubmenuCheckboxes(checkbox, isChecked) {
                checkbox.closest(".form-check").find(".submenu input[type=\"checkbox\"]").each(function() {
                    $(this).prop("checked", isChecked);
                });
            }

            $("#check2").on("change", ".check2", function() {
                var isChecked = $(this).is(":checked");
                toggleSubmenuCheckboxes($(this), isChecked);
            });


            // membuat fungsi simpan dan delete di checkbox
            $(".form-check-input").change(function() {
                var isChecked = $(this).is(":checked");
                var id = $(this).val();
                var role = $("#role").val();
                if (isChecked) {
                    $.ajax({
                        url: "",
                        type: "",
                        data: {
                            id: id,
                            role: role,
                        },
                        seccess: function(response) {
                            console.log('Data saved successfully');
                        },
                        error: function(xhr, status, error) {
                            console.error('Error saving data:', error);
                        }
                    })
                } else {
                    $.ajax({
                        url: "",
                        type: "",
                        data: {
                            id: id,
                            role: role,
                        },
                        seccess: function(response) {
                            console.log('Data saved successfully');
                        },
                        error: function(xhr, status, error) {
                            console.error('Error saving data:', error);
                        }
                    })
                }
            });






            $("#wajibpajak").select2({
                ajax: {
                    url: "<?= site_url("'.transaksi / formsptpd / get_data "); ?>",
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
                minimumInputLength: 1,
                width: "resolve"
            });
        });
    </script>

</body>

</html>