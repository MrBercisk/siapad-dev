$(document).ready(function() {
    $('#idwpskpd').select2({
        placeholder: $('#idwp').data('placeholder'),
        minimumInputLength: 5, 

    });
    // Script pembayran skpd
    $('#opsireklame').select2({
        ajax: {
            url: 'PembayaranSkpd/get_record_option',
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
  
 
    $(document).ready(function() {
        var table = $('#SKPD').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "PembayaranSkpd/get_datatable_data",
                "type": "POST",
                "data": function(d) {
                    d.id = $('#opsireklame').val(); 
                    d.iddinas = $('#iddinas').val();
                },
                "dataSrc": function(json) {
                    $('#table-buttons-skpd').find('#idstsmaster').remove();
                    $('#table-buttons-skpd').find('#iddinas').remove(); 
                    $('#table-buttons-skpd').append(json.extra_data);
                    console.log('ID stsmaster and iddinas:', json.extra_data);
                    return json.data;
                }
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
    
        $('#opsireklame').change(function() {
            var recordId = $(this).val();
            if (recordId) {
                $.ajax({
                    url: 'PembayaranSkpd/get_record_data',
                    type: 'POST',
                    data: { id: recordId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#idmasternya').val(response.data.id);
                            $('#nomor').val(response.data.nomor);
                            $('#isdispenda').val(response.data.isdispenda);
                            $('#tanggal').val(response.data.tanggal);
                            $('#keterangan').val(response.data.keterangan);
                            $('#isnonkas').val(response.data.isnonkas);
    
                            $('#formedit input, #formedit select').prop('disabled', true);
                            $('#opsireklame').prop('disabled', false);
                            
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
                            $('#cari_data_table').show();
    
                            console.log("Record ID:", recordId + ' and ID Dinas:', iddinasId);
   
                            table.ajax.reload();
                                            
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
    });
    

    $('#forminputskpd').on('submit', function(e) {
        e.preventDefault();
    
        $.ajax({
            type: 'POST',
            url: 'PembayaranSkpd/add_record_data',
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
                            $('#addDataModalSkpd').modal('hide');
                            window.location.href = 'PembayaranSkpd';
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
    $('#table-buttons-skpd').on('click', '#add-data', function() {
        var idstsmaster = $('#idstsmaster').val();
        var iddinas = $('#iddinas').val(); 

        if (idstsmaster && iddinas) {
            console.log('tes:', idstsmaster, 'dan iddinas:', iddinas);
            
            $('#addModal #idstsmaster').val(idstsmaster);
            $('#addModal #iddinas').val(iddinas); 
            $.ajax({
                url: 'PembayaranSkpd/get_namarekening_skpd',
                type: 'POST',
                data: { iddinas: iddinas },
                success: function(responnya) {
                    $('#addModal select[name="idrapbd"]').html(responnya);
                    $('#addModal').modal('show');
                },
                error: function(error) {
                    console.error('ga ada rekening:', error);
                    $('#addModal').modal('show');
                }
            });
        } else {
            console.error('idstsmaster atau iddinas tidak ditemukan');
            Swal.fire({
                title: 'Error',
                text: 'Record tidak ditemukan',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
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
    $('#SKPD').on('click', '.edit-data-skpd', function(e) {
        var idstsmaster = $(this).data('idstsmaster');
        var iduptd = $(this).data('iduptd');
        var idwp = $(this).data('idwp');
        var idrapbd = $(this).data('idrapbd');
        var idskpd = $(this).data('idskpd');
        var nourut = $(this).data('nourut');
        var nobukti = $(this).data('nobukti');
        var noskpd = $(this).data('noskpd');
        var bln = $(this).data('blnpajak');
        var thn = $(this).data('thnpajak');
        var jumlah = $(this).data('jumlah');
        var total = $(this).data('total');
        var persen = $(this).data('persen');
        var bunga = $(this).data('bunga');
        var keterangan = $(this).data('keterangan');
        
        if (idstsmaster) {
            console.log('Edit data dengan idstsmaster:', idstsmaster);
            $('#editTableFormSkpd input[name="idstsmaster"]').val(idstsmaster);
            $('#editTableFormSkpd input[name="iduptd"]').val(iduptd);
            $('#editTableFormSkpd input[name="idwp"]').val(idwp);
            $('#editTableFormSkpd input[name="idrapbd"]').val(idrapbd);
            $('#editTableFormSkpd input[name="idskpd"]').val(idskpd);
            $('#editTableFormSkpd input[name="nourut"]').val(nourut);
            $('#editTableFormSkpd input[name="nobukti"]').val(nobukti);
            $('#editTableFormSkpd input[name="noskpd"]').val(noskpd);
            $('#editTableFormSkpd input[name="blnpajak"]').val(bln);
            $('#editTableFormSkpd input[name="thnpajak"]').val(thn);
            $('#editTableFormSkpd input[name="jumlah"]').val(jumlah);
            $('#editTableFormSkpd input[name="total"]').val(total);
            $('#editTableFormSkpd input[name="prs_denda"]').val(persen);
            $('#editTableFormSkpd input[name="nil_denda"]').val(bunga);
            $('#editTableFormSkpd input[name="keterangan"]').val(keterangan);
        
            $.ajax({
                url: 'PembayaranSkpd/get_namarekening_skpd',
                type: 'POST',
                success: function(response) {
                    $('#editTableFormSkpd select[name="idrapbd"]').html(response);
                    $('editModalSkpd').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error('Error mendapatkan nmrekening:', error);
                    $('editModalSkpd').modal('show');
                }
            });
        } else {
            console.error('idstsmaster tidak ditemukan');
        }
    });
    
    $('#opsiskpd2').select2({
        ajax: {
            url: 'PembayaranSkpd/get_skpd_data',
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
                            text: item.nomor + ' (' + item.nama + ')', 
                            nomor: item.nomor,
                            nama: item.nama,
                            teks: item.teks,
                            total: item.total,
                            bunga: item.bunga,
                            jumlah: item.jumlah,
                            idwp: item.idwp,
                            blnpajak: item.blnpajak,  
                            thnpajak: item.thnpajak
                        };
                    }),
                    pagination: {
                        more: data.length === 10
                    }
                };
            },
            cache: true
        },
        placeholder: 'Pilih SKPD',
        templateResult: formatSkpd,
        templateSelection: formatSkpdSelection
    });
    
    function formatSkpd(skpd) {
        if (skpd.loading) {
            return skpd.text; 
        }
        var $container = $('<div>' + skpd.text + '</div>'); 
        return $container;
    }
    
    function formatSkpdSelection(skpd) {
        return skpd.text || skpd.id; 
    }
    
    $('#opsiskpd2').on('select2:select', function (e) {
        var data = e.params.data;
        console.log(data);
        $('#idwp').val(data.idwp || ''); 
        $('#nmwp').val(data.nama || '');
        $('#bln').val(data.blnpajak || ''); 
        $('#thn').val(data.thnpajak || ''); 
        $('#jumlahskpd').val(data.jumlah || ''); 
        $('#bunga').val(data.bunga || ''); 
        $('#persen').val(data.prs_denda || ''); 
        $('#totalskpd').val(data.total || ''); 
    });
    $('.modal-skpd').on('input', '.jumlahskpd, .persen', function() {
        hitungdenda($(this).closest('.modal-skpd'));
    });
    
    $('.modal-skpd').on('input', '.bunga', function() {
        hitungprsdenda($(this).closest('.modal-skpd'));
    });
    function hitungdenda(modal) {
        var jumlah = parseFloat(modal.find('.jumlahskpd').val()) || 0;
        var prs_denda = parseFloat(modal.find('.persen').val()) || 0;
    
        var nil_denda = (jumlah * prs_denda) / 100;
        modal.find('.bunga').val(nil_denda);
    
        var total = jumlah + nil_denda;
        modal.find('.totalskpd').val(total);
    }
    
    function hitungprsdenda(modal) {
        var jumlah = parseFloat(modal.find('.jumlahskpd').val()) || 0;
        var nil_denda = parseFloat(modal.find('.bunga').val()) || 0;
        var prs_denda = (nil_denda / jumlah) * 100;
        var total = jumlah + nil_denda;
    
        modal.find('.persen').val(prs_denda);
        modal.find('.totalskpd').val(total);
    }

    $('#SKPD').on('click', '.delete-data-skpd', function(e) {
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
                    url: 'PembayaranSkpd/delete',
                    method: 'POST',
                    data: {
                        idstsmaster: idstsmaster,
                        nourut: nourut
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#SKPD').DataTable().ajax.reload();
                            Swal.fire(
                                'Deleted!',
                                'Data Bayar Reklame Berhasil Dihapus!',
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

    // end script pembayaran skpd reklame
   
    
    $('#opsiwp2').select2({
        ajax: {
            url: 'DispenSkpd/get_wp_data',
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
                            tglskp: item.tglskp, 
                            teks: item.teks, 
                            masapajak: item.masapajak, 
                            jumlah: item.jumlah, 
                            idskpdrek: item.idskpdrek, 
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

    $('#opsiwp2').on('select2:select', function (e) {
        var data = e.params.data;
        $('#nomor').val(data.nomor); 
        $('#tgljthtmp').val(data.tgljthtmp); 
        $('#teks').val(data.teks); 
        $('#tglskp').val(data.tglskp); 
        $('#masapajak').val(data.masapajak); 
        $('#jumlah').val(data.jumlah); 
        $('#idskpdrek').val(data.idskpdrek); 
    });

    


    // Script sync skpd reklame

    var table = $('#syncTable').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        ordering: false,
        info: false,
        lengthChange: false,
        paging: false,
        scrollX: true,
        ajax: {
            url: "SyncSkpd/getapisimpadareklame",
            type: "GET",
            data: function(d) {
                var tanggal = $('#tanggal').val();
                d.tanggal = tanggal;
            },
            dataSrc: function(response) {
                if (response.data && Array.isArray(response.data) && response.data.length > 0) {
                    return response.data;
                } else {
                    return [];
                }
            }
        },
        columns: [
            { data: null, defaultContent: '', orderable: false, className: 'select-checkbox', width: '5%' },
            { 
                data: 'nop', 
                render: function(data, type, row) {
                    return data ? data : ''; 
                }
            },
            { data: 'NOSKPDN' },
            { data: 'NamaObjekPajak' },
            { data: 'ALamatObjek' },
            { 
                data: 'nomor', 
                render: function(data, type, row) {
                    return data ? data : ''; 
                }
            },
            { data: 'kelurahan' },
            { data: 'kecamatan' },
            { 
                data: 'payment_code', 
                render: function(data, type, row) {
                    return data ? data.substring(4, 14) : '';
                } 
            },
            { data: 'NPWPD' },
            { data: 'masapajak' }, 
            { data: 'TglJatuhTempo' },
            { data: 'tahun' },
            { data: 'jumlahbayar' },
            { data: 'denda' },
            { data: 'totalbayar' },
            { data: 'tanggal' },
            { data: 'JenisReklame' },
          /*   { 
                data: null, 
                defaultContent: '<button class="btn btn-primary btn-edit">Edit</button>', 
                orderable: false, 
                width: '5%' 
            } */
        ],
        select: {
            style: 'multi',
            selector: 'td:first-child'
        },
        order: [[1, 'asc']]
    });
    

    let inidataygdipilih = null;

    $('#syncTable tbody').on('change', 'input[type="checkbox"]', function() {
        if (!this.checked) {
            var ini = $('#select-all').get(0);
            if (ini && ini.checked && ('indeterminate' in ini)) {
                ini.indeterminate = true;
            }
        }
    });

    $('#syncTable tbody').on('click', 'tr', function() {
        inidataygdipilih = table.row(this).data(); 
        console.log('ini data yg dipilih:', inidataygdipilih);
    });

    $('#btnCheckData').on('click', function() {
        if (inidataygdipilih) {
            let inipaymentcode = inidataygdipilih.payment_code || ''; 
            let paymentCode = inipaymentcode.length >= 14 ? inipaymentcode.substring(4, 14) : ''; 
            
            $.ajax({
                url: 'SyncSkpd/checkAndAddWp', 
                type: 'POST',
                dataType: 'json',
                data: {
                    namaobjekpajak: inidataygdipilih.NamaObjekPajak,
                    alamatobjek: inidataygdipilih.ALamatObjek,
                    noskpdn: inidataygdipilih.NOSKPDN,
                    masapajak: inidataygdipilih.masaPajak, 
                    tahun: inidataygdipilih.tahun,
                    tanggal: inidataygdipilih.tanggal,
                    tanggalbayar: inidataygdipilih.tanggalbayar,
                    jumlahbayar: inidataygdipilih.jumlahbayar,
                    denda: inidataygdipilih.denda,
                    totalbayar: inidataygdipilih.totalbayar,
                    jenisreklame: inidataygdipilih.JenisReklame,
                    nama: inidataygdipilih.Nama,
                    statusbayar: inidataygdipilih.Statusbayar,
                    tgljatuhtempo: inidataygdipilih.TglJatuhTempo,
                    npwpd: inidataygdipilih.NPWPD,
                    kelurahan: inidataygdipilih.kelurahan,
                    paymentcode: paymentCode,
                    nokohir: '' 
                },
                success: function(response) {
                    if (response.exists) {
                        Swal.fire({
                            title: 'Data Pada Database Sudah Ada',
                            text: response.message,
                            icon: 'warning'
                        });
                    } else {
                        Swal.fire({
                            title: 'Data Pajak Reklame Belum Ada',
                            text: 'Masukkan nomor kohir untuk data baru:',
                            input: 'text',
                            inputPlaceholder: 'Nomor Kohir',
                            showCancelButton: true,
                            confirmButtonText: 'Simpan',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed && result.value) {
                                let inputannokohir = result.value;
                                let nokohirnya = inputannokohir + '/' + paymentCode;
                                
                                $.ajax({
                                    url: 'SyncSkpd/checkAndAddWp',
                                    type: 'POST',
                                    dataType: 'json',
                                    data: {
                                        namaobjekpajak: inidataygdipilih.NamaObjekPajak,
                                        alamatobjek: inidataygdipilih.ALamatObjek,
                                        noskpdn: inidataygdipilih.NOSKPDN,
                                        masapajak: inidataygdipilih.masaPajak,
                                        tahun: inidataygdipilih.tahun,
                                        tanggal: inidataygdipilih.tanggal,
                                        tanggalbayar: inidataygdipilih.tanggalbayar,
                                        jumlahbayar: inidataygdipilih.jumlahbayar,
                                        denda: inidataygdipilih.denda,
                                        totalbayar: inidataygdipilih.totalbayar,
                                        jenisreklame: inidataygdipilih.JenisReklame,
                                        nama: inidataygdipilih.Nama,
                                        statusbayar: inidataygdipilih.Statusbayar,
                                        tgljatuhtempo: inidataygdipilih.TglJatuhTempo,
                                        npwpd: inidataygdipilih.NPWPD,
                                        kelurahan: inidataygdipilih.kelurahan,
                                        paymentcode: paymentCode,
                                        nokohir: nokohirnya 
                                    },
                                    success: function(response) {
                                        if (response.exists) {
                                            Swal.fire({
                                                title: 'Error!',
                                                text: response.message,
                                                icon: 'error'
                                            });
                                        } else {
                                            Swal.fire({
                                                title: 'Berhasil!',
                                                text: 'Data berhasil disimpan dengan nomor kohir: ' + nokohirnya,
                                                icon: 'success'
                                            }).then(() => {
                                                $('#syncTable').DataTable().ajax.reload();
                                            });
                                        } 
                                    },
                                    error: function(xhr, status, error) {
                                        console.error('Error occurred:', status, error);
                                        Swal.fire({
                                            title: 'Error!',
                                            text: 'Terjadi kesalahan saat menyimpan data: ' + error,
                                            icon: 'error'
                                        });
                                    }
                                });
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error occurred:', status, error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat memeriksa data: ' + error,
                        icon: 'error'
                    });
                }
            });
        } else {
            Swal.fire({
                title: 'Peringatan!',
                text: 'Anda belum memilih item.',
                icon: 'warning'
            });
        }
    });

    $('#syncTable tbody').on('click', '.btn-edit', function() {
        var data = table.row($(this).parents('tr')).data();
        $('#editRowId').val(table.row($(this).parents('tr')).index());
        $('#editWajibPajak').val(data.NamaObjekPajak);
        $('#editKelurahan').val(data.kelurahan);
        $('#editKecamatan').val(data.kecamatan);
        var nomor = data.NOSKPDN + '/' + (data.payment_code ? data.payment_code.substring(4, 14) : '');
        $('#editNomor').val(nomor);
        $('#editModal').modal('show');
    });

    table.clear().draw();

    $('#cari').on('click', function() {
        var tanggal = $('#tanggal').val();
        if (tanggal) {
            table.ajax.reload(function(json) {
                if (!json.data || json.data.length === 0) {
                    Swal.fire({
                        title: 'Warning',
                        text: 'Data SIMPADA Tidak Ditemukan',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    table.clear().draw();
                }
            });
        } else {
            Swal.fire({
                title: 'Perhatian',
                text: 'Silahkan pilih tanggal',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        }
    });

    // End script sync skpd reklame

   /*  var table = $('#syncTable').DataTable({
        "processing": true,
        "serverSide": true,
        "order" : [],
        "ajax": {
            "url": "SyncSkpd/getSkpd",
            "type": "POST",
            "data": function(d) {
                var tanggal = $('#tanggal').val();
                if (tanggal) {
                    d.tanggal = tanggal;
                } else {
                    return {};
                }
            }
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
                "width": "1%",
                "render": function(data, type, row) {
                    return '<input type="checkbox" class="submit-checkbox" ' +
                        'data-id="' + row.id + '" ' +
                        'data-idwp="' + row.idwp + '" ' +
                        'data-tanggal="' + row.tanggal + '" ' +
                        'data-blnpajak="' + row.blnpajak + '" ' +
                        'data-thnpajak="' + row.thnpajak + '" ' +
                        'data-jumlah="' + row.jumlah + '" ' +
                        'data-keterangan="' + row.keterangan + '">';
                }
            },
            {
                "targets": -1,
                "width": "10%"
            }
        ],
        "buttons": [
            "copyHtml5",
            "excelHtml5",
            "csvHtml5",
            "pdfHtml5"
        ],
        "initComplete": function(settings, json) {
            table.clear().draw();
        }
    });
 
    $('#cari').on('click', function() {
        var tanggal = $('#tanggal').val();
        if (tanggal) {
            $.ajax({
                url: "SyncSkpd/getapisimpadareklame",
                type: "GET",
                data: { tanggal: tanggal },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data && data.length > 0) {
                        table.clear().rows.add(data).draw(); 
                    } else {
                        table.clear().draw();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data from API: " + error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Terjadi kesalahan saat mengambil data dari API',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    table.clear().draw();
                    Swal.fire({
                        title: 'Warning',
                        text: 'Data SIMPADA Tidak Ditemukan',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                }
            });
        } else {
            Swal.fire({
                title: 'Perhatian',
                text: 'Silahkan pilih tanggal',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        }
    });
     */
  
    /* let selectedRows = [];
    $('#syncTable').on('change', '.submit-checkbox', function() {
        let rowData = {
            id: $(this).data('id'),
            idwp: $(this).data('idwp'),
            tanggal: $(this).data('tanggal'),
            blnpajak: $(this).data('blnpajak'),
            thnpajak: $(this).data('thnpajak'),
            jumlah: $(this).data('jumlah'),
            keterangan: $(this).data('keterangan')
        };
    
        if ($(this).is(':checked')) {
            selectedRows.push(rowData);
        } else {
            selectedRows = selectedRows.filter(row => row.id !== rowData.id);
        }
        console.log(selectedRows);
    });
  
  
$('#submit').on('click', function() {
    if (selectedRows.length > 0) {
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Anda akan mengirim ' + selectedRows.length + ' data. Lanjutkan?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, kirim!',
            cancelButtonText: 'Tidak, batalkan'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'SyncSkpd/submit',
                    type: 'POST',
                    data: { selectedRows: selectedRows },
                    success: function(response) {
                        var res = JSON.parse(response);
                        console.log("Response from server: ", res); 
                        if (res.success) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: res.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                            table.ajax.reload();
                            selectedRows = [];
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: res.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error submitting data: " + error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Terjadi kesalahan saat mengirim data.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    } else {
        Swal.fire({
            title: 'Perhatian',
            text: 'Anda belum mengisi item data!',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
    }
}); */
$('#delete').on('click', function() {
    var selectedData = [];
    $('.delete-checkbox:checked').each(function() {
        var data = $(this).data('rowData');
        if (data) {
            selectedData.push(data);
        }
    });

    if (selectedData.length > 0) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to delete " + selectedData.length + " row(s). You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'YourDeleteUrl', 
                    method: 'POST',
                    data: { data: selectedData },
                    success: function(response) {
                        Swal.fire(
                            'Deleted!',
                            'Your data has been deleted.',
                            'success'
                        );
                        table.ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        Swal.fire(
                            'Error!',
                            'There was an error deleting your data.',
                            'error'
                        );
                    }
                });
            }
        });
    } else {
        Swal.fire(
            'No selection',
            'Please select at least one row to delete.',
            'warning'
        );
    }
});



});
