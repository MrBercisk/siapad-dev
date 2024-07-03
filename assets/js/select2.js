$(document).ready(function() {
    
    $('#idrecord').select2({
        placeholder: $('#idrecord').data('placeholder'),
        minimumInputLength: 3,
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
                        $('#ididinas').val(response.data.ididinas);
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
    
                        var iddinasId = response.data.iddinas;
                        var iddinasName = response.data.nama; 
                        $('#iddinas').val(iddinasId).trigger('change');
                        $('#nama_dinas_display').text(iddinasName);
    
                        $('#add-data').show();
                        $('#hapus_data').show();
    
                        table.ajax.url('PendDaerah/get_datatable_data?id=' + recordId).load();
                        
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
    
  
    
    var table = $('#pendapatan').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "PendDaerah/get_datatable_data",
            "type": "POST",
            "data": function(d) {
                d.id = $('#idrecord').val(); 
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
                "targets": "_all", 
                "createdCell": function(td, cellData, rowData, row, col) {
                    $(td).css('padding', '10px'); 
                }
            }
        ]
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
        var nourut = $(this).data('nourut');
        var nobukti = $(this).data('nobukti');
        var iduptd = $(this).data('iduptd');
        var blnpajak = $(this).data('blnpajak');
        var thnpajak = $(this).data('thnpajak');
        var jumlah = $(this).data('jumlah');
        var prs_denda = $(this).data('prs_denda');
        var nil_denda = $(this).data('nil_denda');
        var keterangan = $(this).data('keterangan');
        var formulir = $(this).data('formulir');
        var kodebayar = $(this).data('kodebayar');
        var tgl_input = $(this).data('tgl_input');
        var nopelaporan = $(this).data('nopelaporan');
        
        $.ajax({
            url: 'PendDaerah/get_edit_data',
            method: 'POST',
            data: {
                idstsmaster: idstsmaster,
                nourut: nourut,
                nobukti: nobukti,
                iduptd: iduptd,
                blnpajak: blnpajak,
                thnpajak: thnpajak,
                jumlah: jumlah,
                prs_denda: prs_denda,
                nil_denda: nil_denda,
                keterangan: keterangan,
                formulir: formulir,
                kodebayar: kodebayar,
                tgl_input: tgl_input,
                nopelaporan: nopelaporan,
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#editTableForm input[name="idstsmaster"]').val(response.data.idstsmaster);
                    $('#editTableForm input[name="nourut"]').val(response.data.nourut);
                    $('#editTableForm input[name="nobukti"]').val(response.data.nobukti);
                    $('#editTableForm input[name="iduptd"]').val(response.data.iduptd);
                    $('#editTableForm input[name="blnpajak"]').val(response.data.blnpajak);
                    $('#editTableForm input[name="thnpajak"]').val(response.data.thnpajak);
                    $('#editTableForm input[name="jumlah"]').val(response.data.jumlah);
                    $('#editTableForm input[name="prs_denda"]').val(response.data.prs_denda);
                    $('#editTableForm input[name="nil_denda"]').val(response.data.nil_denda);
                    $('#editTableForm input[name="keterangan"]').val(response.data.keterangan);
                    $('#editTableForm input[name="formulir"]').val(response.data.formulir);
                    $('#editTableForm input[name="kodebayar"]').val(response.data.kodebayar);
                    $('#editTableForm input[name="tgl_input"]').val(response.data.tgl_input);
                    $('#editTableForm input[name="nopelaporan"]').val(response.data.nopelaporan);
                
                    $('#editModal').modal('show');
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
    $('#pendapatan').on('click', '.add-data', function() {
        var idstsmaster = $(this).data('idstsmaster');
        
        $('#idstsmaster').val(idstsmaster);
        $('#addModal').modal('show');
    });
  
   
    $('#pendapatan').on('click', '.delete-all-data', function(){
        var idstsmaster = $(this).data('idstsmaster');
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
    });
    
    
    
    function formatResult(result) {
        return '<option value="' + result.id + '">' + result.text + '</option>';
    }
  

    $('#idwp').select2({
        ajax: {
            url: 'PendDaerah/get_wajibpajak',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    page: params.page || 1
                };
            },
            processResults: function(data, params) {
                params.page = params.page || 1;

                return {
                    results: data,
                    pagination: {
                        more: data.length === 10 
                    }
                };
            },
            cache: true
        },
        escapeMarkup: function(markup) {
            return markup;
        },
        minimumInputLength: 0, 
        templateResult: formatResult
    });
   
});
