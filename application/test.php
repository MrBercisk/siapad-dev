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