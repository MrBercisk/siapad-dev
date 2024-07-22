<?php defined('BASEPATH') or exit('No direct script access allowed');
class Jssetup extends CI_Model
{
    public function jsDatatable($id = NULL, $link = NULL)
    {
        if ($id === NULL || $link === NULL) {
            return '';
        }
        $escaped_id     = htmlspecialchars($id, ENT_QUOTES, 'UTF-8');
        $escaped_link   = htmlspecialchars($link, ENT_QUOTES, 'UTF-8');
        $form = '
        $("' . $escaped_id . '").DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "' . site_url($escaped_link) . '",
                "type": "POST"
            },
            "columnDefs": [
            {
                "targets": 0,
                "orderable": false,
                "width": "1%"
            },
            {
                "targets": -1,
                "width": "10%"
            }
            ],
            "drawCallback": function(settings) {
                var api = this.api();
                var start = api.page.info().start;
                api.column(0, { page: "current" }).nodes().each(function(cell, i) {
                    cell.innerHTML = start + i + 1;
                });
            }
        });
        ';
        return $form;
    }
    public function jsDatatable2($id = NULL, $link = NULL)
    {
        if ($id === NULL || $link === NULL) {
            return '';
        }
        $escaped_id     = htmlspecialchars($id, ENT_QUOTES, 'UTF-8');
        $escaped_link   = htmlspecialchars($link, ENT_QUOTES, 'UTF-8');
        $form = '
        $("' . $escaped_id . '").DataTable({
            "paging": false, 
			"lengthChange": false, 
			"searching": false, 
			"info": false,
			"processing": true,
            "serverSide": true,
			"layout": {
				"topStart": {
					"buttons": ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"]
				}
			},
            "ajax": {
                "url": "' . site_url($escaped_link) . '",
                "type": "POST"
            },
            "columnDefs": [
            {
                "targets": 0,
                "orderable": false,
                "width": "1%"
            },
            {
                "targets": -1,
                "width": "10%"
            }
            ],
            "drawCallback": function(settings) {
                var api = this.api();
                var start = api.page.info().start;
                api.column(0, { page: "current" }).nodes().each(function(cell, i) {
                    cell.innerHTML = start + i + 1;
                });
            }
        });
        ';
        return $form;
    }
    public function jsDatatable3($id = NULL, $link = NULL)
    {
        if ($id === NULL || $link === NULL) {
            return '';
        }
        $escaped_id     = htmlspecialchars($id, ENT_QUOTES, 'UTF-8');
        $escaped_link   = htmlspecialchars($link, ENT_QUOTES, 'UTF-8');
        $form = '
        $("' . $escaped_id . '").DataTable({
            "paging": true, 
			"lengthChange": true, 
			"searching": false, 
			"info": false,
			"processing": false,
            "serverSide": true,
			
            "ajax": {
                "url": "' . site_url($escaped_link) . '",
                "type": "POST"
            },
            "columnDefs": [
            {
                "targets": 0,
                "orderable": false,
                "width": "1%"
            },
            {
                "targets": -1,
                "width": "10%"
            }
            ],
            "drawCallback": function(settings) {
                var api = this.api();
                var start = api.page.info().start;
                api.column(0, { page: "current" }).nodes().each(function(cell, i) {
                    cell.innerHTML = start + i + 1;
                });
            }
        });
        ';
        return $form;
    }
    public function jsModal($id = NULL, $mode = NULL, $link = NULL, $panggil = NULL)
    {
        $modal = '
        $(document).on("click", "' . $id . '", function() {
            var idnya = $(this).data("id");
            var wadi = "' . $mode . '";
            $.ajax({
                url: "' . site_url($link) . '",
                type: "POST",
                data: { WADI: wadi, idnya: idnya },
                cache: false, 
                success: function(data) {
                    $("' . $panggil . '").empty();
                    $("' . $panggil . '").html(data);
                }
            });
        });
        ';
        return $modal;
    }

    public function jsKecamatan($link)
    {
        $escaped_link = htmlspecialchars($link, ENT_QUOTES, 'UTF-8');
        $select = '
        $.ajax({
                url: "' . site_url($escaped_link) . '",
                type: "POST",
                dataType: "json",
                success: function(data) {
                    var select = $("#kecamatan-select");
                    select.empty();
                    select.append("<option value=\'\'>Select Kecamatan</option>");
                    $.each(data, function(index, item) {
                        select.append($("<option>", {
                            value: item.id,
                            text: item.nama
                        }));
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data: ", error);
                    alert("Failed to fetch data");
                }
            });
        ';
        return $select;
    }

    public function jsKelurahan($link, $idkecamatan = NULL, $idkelur = NULL)
    {
        $escaped_link = htmlspecialchars($link, ENT_QUOTES, 'UTF-8');
        $select = '
		$("#' . $idkecamatan . '").change(function() {
            var kecamatan_id = $(this).val();
            $.ajax({
                url: "' . site_url($escaped_link) . '",
                type: "POST",
                data: { idnya: kecamatan_id },
                dataType: "json",
                success: function(response) {
                    $("#' . $idkelur . '").empty();
                    $("#' . $idkelur . '").append("<option value=\'\'>Pilih Kelurahan</option>");
                    $.each(response, function(key, value) {
                        $("#' . $idkelur . '").append("<option value=\'" + value.id + "\'>" + value.nama + "</option>");
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data: ", error);
                }
            });
        });
        ';
        return $select;
    }

    public function datePicker($class = '', $id = '', $format = 'yy-mm-dd', $bulan = true, $tahun = true)
    {
        $panggil = '.datepicker';
        if ($class != '.datepicker') {
            $panggil = '#' . $id;
        }
        // var_dump($class);
        $datePicker = '
                $(document).ready(function(){
                $( "' . $panggil . '" ).datepicker({
                    dateFormat: "' . $format . '", 
                        changeMonth: ' . $bulan . ',       
                        changeYear: ' . $tahun . ' 
                });
                });
 
        ';
        return $datePicker;
    }
}
