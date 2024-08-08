
$(document).ready(function() {
    $('#idrecord').select2({
        ajax: {
            url: 'PendDaerah/get_record_option',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term,
                    limit: 10,
                    offset: params.page ? (params.page - 1) * 10 : 0
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: $.map(data, function (item) {
                        return {
                            id: item.id,
                            iddinas: item.iddinas,
                            text: item.nomor + ' (' + item.tanggal +')',
                            nomor: item.nomor,
                            tanggal: item.tanggal,
                            keterangan: item.keterangan,
                            isnonkas: item.isnonkas,
                            isdispenda: item.isdispenda,
                            nama: item.nama
                        };
                    }),
                    pagination: {
                        more: data.length === 10
                    }
                };
            },
            cache: true
        },
        placeholder: 'Pilih Record',
        templateResult: formatRecord,
        templateSelection: formatRecordSelection
    });
    
    function formatRecord(record) {
        if (record.loading) {
            return record.text; 
        }
        var $container = $('<div>' + record.nomor + ' ('+ record.tanggal + ')'+ '</div>');
        return $container;
    }
    
    function formatRecordSelection(record) {
        return record.text || record.nomor + ' ('+ record.tanggal + ')' ;
    }
  
 
   /*  $('#idrecord').select2({
        placeholder: $('#idrecord').data('placeholder'),
        minimumInputLength: 3,
    });
     */
    $('#idwp').select2({
            placeholder: $('#idwp').data('placeholder'),
            minimumInputLength: 5, 

    });
    $('.opsiwp').select2({
        ajax: {
            url: 'SkpdReklame/get_wp_data',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term, 
                    limit: 10,
                    offset: params.page ? (params.page - 1) * 5 : 0 
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: $.map(data, function (item) {
                        return {
                            id: item.id,
                            text: item.nama,
                            nomor: item.nomor, 
                            tgljthtmp: item.tgljthtmp, 
                        };
                    }),
                    pagination: {
                        more: data.length === 10
                    }
                };
            },
            cache: true
        },
        placeholder: 'Pilih WP',
        templateResult: formatWp,
        templateSelection: formatWpSelection
    });

    function formatWp(wp) {
        if (wp.loading) {
            return wp.text;
        }
        var $container = $('<div>' + wp.text + '</div>');
        return $container;
    }

    function formatWpSelection(wp) {
        return wp.text || wp.id;
    }

    $('.opsiwp').on('select2:select', function (e) {
        var data = e.params.data;
        $('.nomor').val(data.nomor); 
        $('.tgljthtmp').val(data.tgljthtmp); 
    });
    

    $('#kdrekening').select2({
        placeholder: $('#kdrekening').data('placeholder'),

    });
    $('#kdrekening2').select2({
            placeholder: $('#kdrekening2').data('placeholder'),

    });
    
    $('#nosptpd').keypress(function(event) {
        if (event.which === 13) {
            event.preventDefault();
    
            var nosptpd = $('#nosptpd').val();
            var NoSSPD = $('#NoSSPD').val();
            var TGLKirim = $('#TGLKirim').val();
            var statusbayar = $('#statusbayar').val();
            var jumlahbayar = $('#jumlahbayar').val();
            var denda = $('#denda').val();
            var totalbayar = $('#totalbayar').val();
            var masapajak = $('#masapajak').val();
            var tahunpajak = $('#tahunpajak').val();
            var namaop = $('#namaop').val();
            var jenisop = $('#jenisop').val();
            var alamatop = $('#alamatop').val();
            var npwpd = $('#npwpd').val();
            var kodebayar = nosptpd;
           
            $.ajax({
                url: 'PendDaerah/getapisimpada',
                type: 'GET',
                data: {
                    nosptpd: nosptpd,
                    NoSSPD: NoSSPD,
                    TGLKirim: TGLKirim,
                    statusbayar: statusbayar,
                    jumlahbayar: jumlahbayar,
                    denda: denda,
                    totalbayar: totalbayar,
                    masapajak: masapajak,
                    tahunpajak: tahunpajak,
                    namaop: namaop,
                    jenisop: jenisop,
                    alamatop: alamatop,
                    npwpd: npwpd,
                    kodebayar: kodebayar,
                    nopelaporan: nosptpd,
                    /* idwp : namaop */
                },
                dataType: 'json',
                success: function(response) {

                    if (response && response.data) {
                        $('#NoSSPD').val(response.data.NoSSPD);
                        $('#TGLKirim').val(response.data.TGLKirim);
                        $('#statusbayar').val(response.data.statusbayar);
                        $('#jumlahbayar').val(response.data.jumlahbayar);
                        $('#denda').val(response.data.denda);
                        $('#totalbayar').val(response.data.totalbayar);
                        $('#masapajak').val(response.data.masapajak);
                        $('#tahunpajak').val(response.data.tahunpajak);
                        $('#namaop').val(response.data.namaop);
                        $('#jenisop').val(response.data.jenisop);
                        $('#alamatop').val(response.data.alamatop);
                        $('#npwpd').val(response.data.npwpd);
                        $('#kodebayar').val(response.data.kodebayar);
                        $('#nopelaporan').val(response.data.nosptpd);
                        /* $('#idwp').val(response.data.namaop); */

    
                        Swal.fire({
                            title: 'Success',
                            text: 'Koneksi Berhasil, Data Wajib Pajak Pada Server SIAPAD Ditemukan',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
    
                        $('#submitDataButton').removeClass('d-none');
                    } else {
                        clearFormFields();
                        Swal.fire({
                            title: 'Warning',
                            text: 'Data SIMPADA tidak ditemukan',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        title: 'Error',
                        text: 'Terjadi kesalahan saat menghubungi server.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
   /*  $('#nosptpd2').off('keypress').on('keypress', function(event) {
        if (event.which === 13) {
            event.preventDefault();
            var kodebayar = $('#nosptpd2').val();
            var nobukti = $('#NoSSPD2').val();
            var tgl_input = $('#TGLKirim2').val();
            var jumlah = $('#jumlahbayar2').val();
            var nil_denda = $('#denda2').val();
            var total = $('#totalbayar2').val();
            var blnpajak = $('#blnpajak2').val();
            var thnpajak = $('#thnpajak2').val();
            var idwp = $('#idwp').val();
            var nama = $('#namaop2').val();
            var alamat = $('#alamatop2').val();
            var npwpd = $('#npwpd2').val();
            var nopelaporan = $('#nopelaporan2').val();
            var nmrekening = $('#jenisop2').val();
    
            $.ajax({
                url: 'PendDaerah/get_data_from_stsdetail',
                type: 'GET',
                data: {
                    kodebayar: kodebayar,
                    nopelaporan: nopelaporan,
                    blnpajak: blnpajak,
                    thnpajak: thnpajak,
                    nobukti: nobukti,
                    jumlah: jumlah,
                    tgl_input: tgl_input,
                    nil_denda: nil_denda,
                    total: total,
                    idwp: idwp,
                    nama: nama,
                    alamat: alamat,
                    npwpd: npwpd,
                    nmrekening: nmrekening,
                },
                dataType: 'json',
                success: function(response) {
                    if (response && response.length > 0) {
                        var record = response[0];
    
                        $('#nosptpd2').val(record.kodebayar);
                        $('#NoSSPD2').val(record.nobukti);
                        $('#TGLKirim2').val(record.tgl_input);
                        $('#jumlahbayar2').val(record.jumlah);
                        $('#denda2').val(record.nil_denda);
                        $('#totalbayar2').val(record.total);
                        $('#blnpajak2').val(record.blnpajak);
                        $('#thnpajak2').val(record.thnpajak);
                        $('#namaop2').val(record.nama); 
                        $('#alamatop2').val(record.alamat); 
                        $('#jenisop2').val(record.nmrekening); 
                        $('#npwpd2').val(record.npwpd); 
                        $('#nopelaporan2').val(record.nopelaporan);
    
                        $('#idwp').val(record.idwp);
                        $('#idrapbd').val(record.idrapbd);
                        $('#iduptd').val(record.iduptd);

                        $('#statusbayar2').val(1);
                        
                        Swal.fire({
                            title: 'Success',
                            text: 'Koneksi Berhasil, Data Wajib Pajak Pada Server SIAPAD Ditemukan',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
    
                        $('#submitDataButton').removeClass('d-none');
                    } else {
                        clearFormFields2();
                        Swal.fire({
                            title: 'Warning',
                            text: 'Data SIMPADA tidak ditemukan',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        title: 'Error',
                        text: 'Terjadi kesalahan saat menghubungi server.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    }); */
    
    function clearFormFields() {
        $('#nosptpd').val('');
        $('#NoSSPD').val('');
        $('#TGLKirim').val('');
        $('#statusbayar').val('');
        $('#jumlahbayar').val('');
        $('#denda').val('');
        $('#totalbayar').val('');
        $('#masapajak').val('');
        $('#tahunpajak').val('');
        $('#namaop').val('');
        $('#jenisop').val('');
        $('#alamatop').val('');
        $('#npwpd').val('');
        $('#kodebayar').val('');
        $('#submitDataButton').addClass('d-none');
    }

    $('#fetchDataButton').on('click', function(e) {
        e.preventDefault();
        var idstsmaster = $('#idstsmaster').val(); 
        var namaop = $('#namaop').val(); 
        var nosptpd = $('#nosptpd').val(); 
        var nopelaporan = $('#nopelaporan').val(); 
        var nourut = $('#nourut').val(); 
        var NoSSPD = $('#NoSSPD').val();
        var masapajak = $('#masapajak').val();
        var tahunpajak = $('#tahunpajak').val();
        var totalbayar = $('#totalbayar').val();
        var denda = $('#denda').val();
        var jumlahbayar = $('#jumlahbayar').val();
        var TGLKirim = $('#TGLKirim').val();
        var formulir = nosptpd.substring(4, 14); 
    
        $.ajax({
            url: 'PendDaerah/add_data_temp',
            type: 'POST',
            data: {
                idstsmaster: idstsmaster,
                /* idwp: namaop, */
                nourut: nourut,
                kodebayar: nosptpd,
                nopelaporan: nopelaporan,
                nobukti: NoSSPD,
                blnpajak: masapajak,
                thnpajak: tahunpajak,
                total: totalbayar,
                nil_denda: denda,
                jumlah: jumlahbayar,
                tgl_input: TGLKirim,
                formulir: formulir 
            },
            dataType: 'json',
            success: function(response) {
                console.log(response.data);
                if (response.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#searchModal').modal('hide');
                            $('#pendapatan').DataTable().ajax.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
    $('#fetchTableButton').on('click', function(e) {
        e.preventDefault();
        var idstsmaster = $('#idstsmaster').val(); 
        var kodebayar = $('#nosptpd2').val();
        var idrapbd = $('#idrapbd').val();
        var iduptd = $('#iduptd').val();
        var nobukti = $('#NoSSPD2').val();
        var tgl_input = $('#TGLKirim2').val();
        var jumlah = $('#jumlahbayar2').val();
        var nil_denda = $('#denda2').val();
        var total = $('#totalbayar2').val();
        var blnpajak = $('#blnpajak2').val();
        var thnpajak = $('#thnpajak2').val();
        var idwp = $('#idwp').val();
        var nopelaporan = $('#nopelaporan2').val();
        var formulir = kodebayar.substring(4, 14); 
    
        $.ajax({
            url: 'PendDaerah/add_data_temp',
            type: 'POST',
            data: {
                idstsmaster: idstsmaster,
                idwp: idwp,
                idrapbd: idrapbd,
                iduptd: iduptd,
                kodebayar: kodebayar,
                nopelaporan: nopelaporan,
                nobukti: nobukti,
                blnpajak: blnpajak,
                thnpajak: thnpajak,
                total: total,
                nil_denda: nil_denda,
                jumlah: jumlah,
                tgl_input: tgl_input,
                formulir: formulir 
            },
            dataType: 'json',
            success: function(response) {

                if (response.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#searchTableModal').modal('hide');
                            $('#pendapatan').DataTable().ajax.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
     
    var table = $('#pendapatan').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "PendDaerah/get_datatable_data",
            "type": "POST",
            "data": function(d) {
                d.id = $('#idrecord').val(); 
                d.iddinas = $('#iddinas').val();
            },
            "dataSrc": function(json) {
                $('#table-buttons').find('#idstsmaster').remove();
                $('#table-buttons').find('#iddinas').remove(); 
                $('#table-buttons').append(json.extra_data);
                console.log('idstsmasternya dan iddinas yang diambil:', json.extra_data);
                return json.data;
            },
        },
        "searching": false,
        "ordering": false,
        "info": false,
        "lengthChange": false,
        "paging": false,
        "scrollX": true,
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
        },
        "buttons": [
            "copyHtml5",
            "excelHtml5",
            "csvHtml5",
            "pdfHtml5"
        ]
    });


    $("#idrecord").change(function() {
        var recordId = $(this).val();
        if (recordId) {
            $.ajax({
                url: 'PendDaerah/get_record_data',
                type: 'POST',
                data: { id: recordId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#nomor').val(response.data.nomor);
                        $('#isdispenda').val(response.data.isdispenda);
                        $('#tanggal').val(response.data.tanggal);
                        $('#keterangan').val(response.data.keterangan);
                        $('#isnonkas').val(response.data.isnonkas);
    
                        $('#formedit input, #formedit select').prop('disabled', true);
                        $('#idrecord').prop('disabled', false);
                        
                        var tmpbayarValue = response.data.tmpbayar;
                        if (tmpbayarValue == 'B') {
                            $('#flexRadioDefault1').prop('checked', true);
                        } else if (tmpbayarValue == 'D') {
                            $('#flexRadioDefault2').prop('checked', true);
                        }
    
                        var isdispendaValue = response.data.isdispenda;
                        if (isdispendaValue == 1) {
                            $('#jenis').val('Bapenda');
                        } else {
                            $('#jenis').val('Non Bapenda');
                        }
    
                        var isnonkasValue = response.data.isnonkas;
                        if (isnonkasValue == 1) {
                            $('#isnonkas').prop('checked', true);
                        } else {
                            $('#isnonkas').prop('checked', false);
                        }
    
                        var iddinasId = response.data.iddinas;
                        var iddinasName = response.data.nama;
                        $('#iddinas').val(iddinasId).trigger('change');
                        $('#nama_dinas_display').text(iddinasName);
    
                        $('#add-data').show();
                        $('#hapus_data').show();
                        $('#cari_data').show();
                       /*  $('#cari_data_table').show(); */
    
                        table.ajax.url('PendDaerah/get_datatable_data?id=' + recordId + '&iddinas=' + iddinasId).load();
                        
                    } else {
                        alert('Data not found');
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                    alert('An error occurred while fetching data');
                }
            });
        } else {
            alert('Data not found');
        }
    });
    
   
   
        const editButton = document.getElementById('editButton');
        const submitButton = document.getElementById('submitButton');
        const cancelEditButton = document.createElement('button');
        cancelEditButton.textContent = 'Cancel Edit';
        cancelEditButton.type = 'button';
        cancelEditButton.style.display = 'none';

        submitButton.disabled = true;
        submitButton.classList.add('disabled-button');
        submitButton.style.cursor = 'not-allowed';

        editButton.addEventListener('click', function() {
            const isEditing = editButton.textContent === 'Edit';
            if (isEditing) {
                enableForm();
            } else {
                disableForm();
            }
            submitButton.disabled = !isEditing;
            submitButton.classList.toggle('disabled-button', !isEditing);
            editButton.textContent = isEditing ? 'Cancel Edit' : 'Edit';
            cancelEditButton.style.display = isEditing ? 'inline-block' : 'none';


            submitButton.style.cursor = submitButton.disabled ? 'not-allowed' : 'pointer';
        });

        function enableForm() {
            document.getElementById('iddinas').disabled = false;
            document.getElementById('nomor').disabled = false;
            document.getElementById('tanggal').disabled = false;
            document.getElementById('flexRadioDefault1').disabled = false;
            document.getElementById('flexRadioDefault2').disabled = false;
            document.getElementById('isnonkas').disabled = false;
            document.getElementById('keterangan').disabled = false;
        }

        function disableForm() {
            document.getElementById('iddinas').disabled = true;
            document.getElementById('nomor').disabled = true;
            document.getElementById('jenis').disabled = true;
            document.getElementById('tanggal').disabled = true;
            document.getElementById('flexRadioDefault1').disabled = true;
            document.getElementById('flexRadioDefault2').disabled = true;
            document.getElementById('isnonkas').disabled = true;
            document.getElementById('keterangan').disabled = true;
        }

    document.addEventListener('DOMContentLoaded', (event) => {
        const isdispendaCheckbox = document.getElementById('isdispenda');
        isdispendaCheckbox.value = isdispendaCheckbox.checked ? '1' : '';

        isdispendaCheckbox.addEventListener('change', function() {
            if (this.checked) {
                this.value = '1';
            } else {
                this.value = '';
            }
        });
    });
 
    $('#pendapatan').on('click', '.edit-data', function(e) {
        var idstsmaster = $(this).data('idstsmaster');
        var iduptd = $(this).data('iduptd');
        var idwp = $(this).data('idwp');
        var idrapbd = $(this).data('idrapbd');
        var nourut = $(this).data('nourut');
        var nobukti = $(this).data('nobukti');
        var tglpajak = $(this).data('tglpajak');
        var blnpajak = $(this).data('blnpajak');
        var thnpajak = $(this).data('thnpajak');
        var jumlah = $(this).data('jumlah');
        var total = $(this).data('total');
        var prs_denda = $(this).data('prs_denda');
        var nil_denda = $(this).data('nil_denda');
        var keterangan = $(this).data('keterangan');
        var formulir = $(this).data('formulir');
        var kodebayar = $(this).data('kodebayar');
        var tgl_input = $(this).data('tgl_input');
        var nopelaporan = $(this).data('nopelaporan');
        var alamatop = $(this).data('alamatop');
        var iddinas = $('#iddinas').val(); 
    
        if (idstsmaster && iddinas) {
            console.log('Edit data dengan idstsmaster:', idstsmaster, 'dan iddinas:', iddinas);
            
            $('#editModal #idstsmaster').val(idstsmaster);
            $('#editModal #iddinas').val(iddinas); 
    
            $.ajax({
                url: 'PendDaerah/get_edit_data',
                method: 'POST',
                dataType: 'json',
                data: {
                    idstsmaster: idstsmaster,
                    iduptd: iduptd,
                    idwp: idwp,
                    idrapbd: idrapbd,
                    nourut: nourut,
                    nobukti: nobukti,
                    tglpajak: tglpajak,
                    blnpajak: blnpajak,
                    thnpajak: thnpajak,
                    jumlah: jumlah,
                    total: total,
                    prs_denda: prs_denda,
                    nil_denda: nil_denda,
                    keterangan: keterangan,
                    formulir: formulir,
                    kodebayar: kodebayar,
                    tgl_input: tgl_input,
                    nopelaporan: nopelaporan,
                    alamatop: alamatop,
                    iddinas: iddinas
                },
                success: function(response) {
                    if (response.success) {
                        $('#editTableForm input[name="idstsmaster"]').val(response.data.idstsmaster);
                        $('#editTableForm input[name="iduptd"]').val(response.data.iduptd);
                        $('#editTableForm input[name="idwp"]').val(response.data.idwp);
                        $('#editTableForm input[name="idrapbd"]').val(response.data.idrapbd);
                        $('#editTableForm input[name="nourut"]').val(response.data.nourut);
                        $('#editTableForm input[name="nobukti"]').val(response.data.nobukti);
                        $('#editTableForm input[name="tglpajak"]').val(response.data.tglpajak);
                        $('#editTableForm input[name="blnpajak"]').val(response.data.blnpajak);
                        $('#editTableForm input[name="thnpajak"]').val(response.data.thnpajak);
                        $('#editTableForm input[name="jumlah"]').val(response.data.jumlah);
                        $('#editTableForm input[name="total"]').val(response.data.total);
                        $('#editTableForm input[name="prs_denda"]').val(response.data.prs_denda);
                        $('#editTableForm input[name="nil_denda"]').val(response.data.nil_denda);
                        $('#editTableForm input[name="keterangan"]').val(response.data.keterangan);
                        $('#editTableForm input[name="formulir"]').val(response.data.formulir);
                        $('#editTableForm input[name="kodebayar"]').val(response.data.kodebayar);
                        $('#editTableForm input[name="tgl_input"]').val(response.data.tgl_input);
                        $('#editTableForm input[name="nopelaporan"]').val(response.data.nopelaporan);
                        $('#editTableForm input[name="alamatop"]').val(response.data.alamatop);
                        
                        $.ajax({
                            url: 'PendDaerah/get_namarekening_by_iddinas',
                            type: 'POST',
                            data: { iddinas: iddinas },
                            success: function(response) {
                                $('#editTableForm select[name="idrapbd"]').html(response);
                                $('#editModal').modal('show');
                            },
                            error: function(xhr, status, error) {
                                console.error('Error mendapatkan nmrekening:', error);
                                $('#editModal').modal('show');
                            }
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            'Gagal mendapatkan data: ' + response.message,
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire(
                        'An error occurred!',
                        'An error occurred: ' + xhr.responseText,
                        'error'
                    );
                }
            });
        } else {
            console.error('idstsmaster atau iddinas tidak ditemukan');
        }
    });
    
    $('#idwp2').select2({
        placeholder: $('#idwp2').data('placeholder'),
        minimumInputLength: 5, 

        });
        
        $('#editForm').on('submit', function(e) {
            e.preventDefault();
        
            $.ajax({
                url: 'PendDaerah/update_record_data',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#idrecord').val(response.data.idrecord);
                                $('#ididinas').val(response.data.ididinas);
                                $('#nomor').val(response.data.nomor);
                                $('#isdispenda').val(response.data.isdispenda);
                                $('#tanggal').val(response.data.tanggal);
                                $('#keterangan').val(response.data.keterangan);
                                
                                var tmpbayarValue = response.data.tmpbayar;
                                if (tmpbayarValue == 'B') {
                                    $('#flexRadioDefault1').prop('checked', true);
                                } else if (tmpbayarValue == 'D') {
                                    $('#flexRadioDefault2').prop('checked', true);
                                }
                                
                                var isdispendaValue = response.data.isdispenda;
                                if (isdispendaValue == 1) {
                                    $('#jenis').val('BPPRD');
                                } else {
                                    $('#jenis').val('Non BPPRD');
                                }
                                
                                var isnonkasValue = response.data.isnonkas;
                                if (isnonkasValue == 1) {
                                    $('#isnonkas').prop('checked', true);
                                } else {
                                    $('#isnonkas').prop('checked', false);
                                }
    
                            }
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            'Gagal Update Data: ' + response.message,
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire(
                        'An error occurred!',
                        'An error occurred: ' + xhr.responseText,
                        'error'
                    );
                }
            });
        });
        
    $('#editTableForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: 'PendDaerah/update_data',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#editModal').modal('hide');
                            $('#pendapatan').DataTable().ajax.reload();
                        }
                    });
                } else {
                    Swal.fire(
                        'Error!',
                        'Gagal Update Data: ' + response.message,
                        'error'
                    );
                }
            },
            error: function(xhr, status, error) {
                Swal.fire(
                    'An error occurred!',
                    'An error occurred: ' + xhr.responseText,
                    'error'
                );
            }
        });
    });
   /*  $('#forminput').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: 'PendDaerah/add_record_data',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#addDataModal').modal('hide');
                        }
                    });
                } else {
                    Swal.fire(
                        'Error!',
                        'Gagal Tambah Record: ' + response.message,
                        'error'
                    );
                }
            },
            error: function(xhr, status, error) {
                Swal.fire(
                    'An error occurred!',
                    'An error occurred: ' + xhr.responseText,
                    'error'
                );
            }
        });
    }); */
     
    $('#table-buttons').on('click', '.delete-all-data', function() {
        var idstsmaster = $('#idstsmaster').val();
        console.log('Hapus semua data dengan idstsmaster:', idstsmaster);
        if (idstsmaster) {
            Swal.fire({
                title: 'Anda yakin ingin menghapus semua data?',
                text: "Akan menghapus semua data pada record ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'PendDaerah/delete_all_data',
                        method: 'POST',
                        data: {
                            idstsmaster: idstsmaster
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                $('#pendapatan').DataTable().ajax.reload();
                                Swal.fire(
                                    'Deleted!',
                                    'Semua Data Berhasil Dihapus!',
                                    'success'
                                );
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'Gagal Hapus Data ' + response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire(
                                'An error occurred!',
                                'An error occurred: ' + xhr.responseText,
                                'error'
                            );
                        }
                    });
                }
            });
        } else {
            console.error('idstsmaster tidak ditemukan');
        }
    });

    $('#table-buttons').on('click', '#cari_data', function() {
        var idstsmaster = $('#idstsmaster').val();
        if (idstsmaster) {
            console.log('Cari data dengan idstsmaster:', idstsmaster);
            $('#searchModal #idstsmaster').val(idstsmaster);
        } else {
            console.error('idstsmaster tidak ditemukan');
        }
    });
    $('#table-buttons').on('click', '#cari_data_table', function() {
        var idstsmaster = $('#idstsmaster').val();
        if (idstsmaster) {
            console.log('Cari data dengan idstsmaster:', idstsmaster);
            $('#searchTableModal #idstsmaster').val(idstsmaster);
        } else {
            console.error('idstsmaster tidak ditemukan');
        }
    });

 
   

    function clearFormFields2() {
        $('#nosptpd2').val();
        $('#NoSSPD2').val();
        $('#TGLKirim2').val();
        $('#jumlahbayar2').val();
        $('#denda2').val();
        $('#totalbayar2').val();
        $('#blnpajak2').val();
        $('#thnpajak2').val();
        $('#namaop2').val();
        $('#nopelaporan2').val();
        $('#submitDataButton').addClass('d-none');
    }


    $('#table-buttons').on('click', '#add-data', function() {
        var idstsmaster = $('#idstsmaster').val();
        var iddinas = $('#iddinas').val();
        var nomornya = $('#nomor').val();
    
        if (idstsmaster && iddinas  && nomornya) {
            console.log('Tambah data dengan idstsmaster:', idstsmaster, 'dan iddinas:', iddinas,'dan nomornya:', nomornya);
            
            $('#addModal #idstsmaster').val(idstsmaster);
            $('#addModal #iddinas').val(iddinas); 
            $('#addModal #nobukti').val(nomornya); 
    
            $.ajax({
                url: 'PendDaerah/get_namarekening_by_iddinas',
                type: 'POST',
                data: { iddinas: iddinas },
                success: function(response) {
                    $('#addModal select[name="idrapbd"]').html(response);
                    $('#addModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error('Error mendapatkan nmrekening:', error);
                    $('#addModal').modal('show');
                }
            });
        } else {
            console.error('idstsmaster atau iddinas tidak ditemukan');
        }
    });
    
    
    
    $('#formadd').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: 'PendDaerah/add_data',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#addModal').modal('hide');
                            $('#pendapatan').DataTable().ajax.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Something went wrong. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
    
    $('#forminput').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: 'PendDaerah/add_record_data',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#addDataModal').modal('hide');
                            window.location.href = 'PendDaerah';
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Something went wrong. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
    /* End add */


    $('#pendapatan').on('click', '.delete-data', function(e) {
        var idstsmaster = $(this).data('idstsmaster');
        var nourut = $(this).data('nourut');
    
        Swal.fire({
            title: 'Anda yakin ingin menghapus data ini?',
            text: "Akan menghapus data record ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'PendDaerah/delete',
                    method: 'POST',
                    data: {
                        idstsmaster: idstsmaster,
                        nourut: nourut
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#pendapatan').DataTable().ajax.reload();
                            Swal.fire(
                                'Deleted!',
                                'Data Berhasil Dihapus!',
                                'success'
                            );
                        } else {
                            Swal.fire(
                                'Error!',
                                'Gagal Hapus Data ' + response.message,
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire(
                            'An error occurred!',
                            'An error occurred: ' + xhr.responseText,
                            'error'
                        );
                    }
                });
            }
        });
    });
    $('.modal').on('input', '.jumlah, .prs_denda', function() {
        hitungdenda($(this).closest('.modal'));
    });
    
    $('.modal').on('input', '.nil_denda', function() {
        hitungprsdenda($(this).closest('.modal'));
    });
    
    function hitungdenda(modal) {
        var jumlah = parseFloat(modal.find('.jumlah').val()) || 0;
        var prs_denda = parseFloat(modal.find('.prs_denda').val()) || 0;

        var nil_denda = Math.round((jumlah * prs_denda) / 100);
        modal.find('.nil_denda').val(nil_denda);
    
        var total = Math.round(jumlah + nil_denda); 
        modal.find('.total').val(total);
    }
    
    function hitungprsdenda(modal) {
        var jumlah = parseFloat(modal.find('.jumlah').val()) || 0;
        var nil_denda = parseFloat(modal.find('.nil_denda').val()) || 0;
        
        var prs_denda = Math.round((nil_denda / jumlah) * 100); 
        var total = Math.round(jumlah + nil_denda); 
    
        modal.find('.prs_denda').val(prs_denda);
        modal.find('.total').val(total);
    }
    
   
   
});
