$(document).ready(function() {
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

    $('#opsireklame').on('select2:select', function (e)  {
        var recordId = $(this).val();
        if (recordId) {
            $.ajax({
                url: 'PembayaranSkpd/get_record_data',
                type: 'POST',
                data: { id: recordId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#iddinas').val(response.data.iddinas);
                        $('#nomor').val(response.data.nomor);
                        $('#tanggal').val(response.data.tanggal);
                        $('#isnonkas').val(response.data.isnonkas);
                        $('#tmpbayar').val(response.data.tmpbayar);
                        $('#keterangan').val(response.data.keterangan);
                        $('#isdispenda').val(response.data.isdispenda);

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
                        console.log("tes ini id record nya:", recordId + ' dan id dinas nya:', iddinasId);
                        table.ajax.url('PembayaranSkpd/get_datatable_data?id=' + recordId + '&iddinas=' + iddinasId).load();
                                        
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
    
   /*  
    $('#opsireklame').on('select2:select', function (e) {
        var data = e.params.data;
        var recordId = $(this).val();
        
        $('#iddinas').val(data.iddinas);
        $('#nomor').val(data.nomor);
        $('#tanggal').val(data.tanggal);
        $('#isnonkas').val(data.isnonkas);
        $('#tmpbayar').val(data.tmpbayar);
        $('#keterangan').val(data.keterangan);
        $('#isdispenda').val(data.isdispenda);
    
        // Handle tmpbayar radio buttons
        var tmpbayarValue = data.tmpbayar;
        if (tmpbayarValue == 'B') {
            $('#flexRadioDefault1').prop('checked', true);
            $('#flexRadioDefault2').prop('checked', false);
        } else if (tmpbayarValue == 'D') {
            $('#flexRadioDefault1').prop('checked', false);
            $('#flexRadioDefault2').prop('checked', true);
        } else {
            // Clear radio buttons if tmpbayarValue is neither 'B' nor 'D'
            $('#flexRadioDefault1').prop('checked', false);
            $('#flexRadioDefault2').prop('checked', false);
        }
    
        // Handle isdispenda jenis dropdown
        var isdispendaValue = data.isdispenda;
        if (isdispendaValue == 1) {
            $('#jenis').val('Bapenda');
        } else {
            $('#jenis').val('Non Bapenda');
        }
    
        // Handle isnonkas checkbox
        var isnonkasValue = data.isnonkas;
        if (isnonkasValue == 1) {
            $('#isnonkas').prop('checked', true);
        } else {
            $('#isnonkas').prop('checked', false);
        }
    
        var iddinasId = data.iddinas;
        var iddinasName = data.nama;
        $('#iddinas').val(iddinasId).trigger('change');
        $('#nama_dinas_display').text(iddinasName);
    
        $('#add-data').show();
        $('#hapus_data').show();
        $('#cari_data_table').show();
        table.ajax.url('PembayaranSkpd/get_datatable_data?id=' + recordId + '&iddinas=' + iddinasId).load();
    });
     */
   
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
                console.log('idstsmasternya dan iddinas yang diambil:', json.extra_data);
                return json.data;
            },
            "data": function() {
                return {};
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

    /* script  untuk get data api dan table skpd reklame data terambil tapi blm tampil table*/



  /*   $('#syncTable').DataTable({
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
                    var data = response.data;
                    var promises = data.map(function(item, index) {
                        return new Promise(function(resolve, reject) {
                            var kodebayarori = item.payment_code;
                            var kodebayar = kodebayarori.substring(4, 14);
                            var nosptpdtemp = kodebayar;

                            $.ajax({
                                url: 'SyncSkpd/selectBySKPD',
                                method: 'GET',
                                data: {
                                    query: nosptpdtemp,
                                    id: index,
                                    npwpd: item.NPWPD,
                                    nmwp: item.Nama,
                                    alamat: item.ALamatObjek,
                                    noskpd: item.NOSKPDN,
                                    kodebayar: kodebayar,
                                    nosptpd: item.NOSPTPD,
                                    kecamatan: item.kecamatan,
                                    kelurahan: item.kelurahan,
                                    masapajak: item.masapajak,
                                    tgljthtmp: item.masapajak.substr(15, 2) + '-' + item.masapajak.substr(18, 2) + '-' + item.masapajak.substr(21, 4),
                                    teks: item.NamaObjekPajak,
                                    blnpajak: item.masapajak.substr(3, 2),
                                    thnpajak: item.tahun,
                                    jumlah: Math.round(item.jumlahbayar),
                                    bunga: Math.round(item.denda),
                                    total: parseInt(Math.round(item.jumlahbayar)) + parseInt(Math.round(item.denda)),
                                    tglbayar: item.tanggal,
                                    keterangan: item.JenisReklame
                                },
                                success: function(response) {
                                    if (response.total >= 1) {
                                        var items = response.data;
                                        var kohir = items[0].nomor.split('/')[0];
                                        var newDataRow = {
                                            NPWPD: response.npwpd,
                                            NamaObjekPajak: response.nmwp,
                                            ALamatObjek: response.alamat,
                                            NOSKPDN: response.noskpd,
                                            kelurahan: response.kelurahan,
                                            kecamatan: response.kecamatan,
                                            NOSPTPD: response.nosptpd,
                                            masapajak: response.masapajak,
                                            TglJatuhTempo: response.tgljthtmp,
                                            tahun: response.thnpajak,
                                            jumlahbayar: Math.round(response.jumlah),
                                            denda: Math.round(response.bunga),
                                            totalbayar: parseInt(Math.round(response.jumlah)) + parseInt(Math.round(response.bunga)),
                                            tanggalbayar: response.tglbayar,
                                            JenisReklame: response.keterangan
                                        };
                                        resolve(newDataRow);
                                    } else {
                                        resolve(null);
                                    }
                                },
                                error: function() {
                                    console.error('Failed to fetch additional data.');
                                    resolve(null);
                                }
                            });
                        });
                    });

                    return Promise.all(promises).then(function(results) {
                        return results.filter(function(item) {
                            return item !== null;
                        });
                    });
                } else {
                  
                    return [];
                }
            }
        },
        columns: [
            { data: 'NPWPD' },
            { data: 'NamaObjekPajak' },
            { data: 'ALamatObjek' },
            { data: 'kelurahan' },
            { data: 'kecamatan' },
            { data: 'NOSKPDN' },
            { data: 'masapajak' },
            { data: 'TglJatuhTempo' },
            { data: 'tahun' },
            { data: 'jumlahbayar' },
            { data: 'denda' },
            { data: 'totalbayar' },
            { data: 'tanggalbayar' },
            { data: 'JenisReklame' }
        ]
    }); */


    /* script  untuk get data api tampil pada table tapi blm cek sinkron dengan database*/
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
        console.log('Tambah data dengan idstsmaster:', idstsmaster, 'dan iddinas:', iddinas);
        
        $('#addModal #idstsmaster').val(idstsmaster);
        $('#addModal #iddinas').val(iddinas); 

        $.ajax({
            url: 'PembayaranSkpd/get_namarekening_by_iddinas',
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


});
