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
    
                        $('#tambah_data_button').show();
                        $('#hapus_data').show();
                        $('#cari_data').show();

                        $('#tambah_data_button').off('click').on('click', function() {
                            if (recordId) {
                                $.ajax({
                                    url: 'PendDaerah/add_data',
                                    type: 'POST',
                                    data: { id: recordId },
                                    dataType: 'json',
                                    success: function(response) {
                                        if (response.status == 'success') {
                                            alert('Data berhasil ditambahkan');
                                            table.ajax.reload(null, false);
                                        } else {
                                            alert('Gagal menambahkan data');
                                        }
                                    },
                                });
                            } else {
                                alert('Pilih data terlebih dahulu');
                            }
                        });
    
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
    $('#pendapatan').on('click', '.edit-data', function() {
        var idstsmaster = $(this).data('idstsmaster');
        var nourut = $(this).data('nourut');
    
        $.ajax({
            url: 'PendDaerah/edit_datatable_row',
            method: 'POST',
            data: { idstsmaster: idstsmaster, nourut: nourut },
            dataType: 'json',
            success: function(response) {
                $('#idstsmaster').val(response.idstsmaster);
                $('#nourut').val(response.nourut);
      
                $('#editModal').modal('show');
            },
        });
    });
    $('#pendapatan').on('click', '.edit-data', function() {
        var idstsmaster = $(this).data('idstsmaster');
        var nourut = $(this).data('nourut');
        var nobukti = $(this).data('nobukti');
        
        // Set values to form fields
        $('#idstsmaster').val(idstsmaster);
        $('#nourut').val(nourut);
        $('#nobukti').val(nobukti);
        $('#wajibpajak').val($(this).data('wajibpajak'));
        $('#namarekening').val($(this).data('namarekening'));
        $('#uptd').val($(this).data('uptd'));
        $('#tglpajak').val($(this).data('tglpajak'));
        $('#blnpajak').val($(this).data('blnpajak'));
        $('#thnpajak').val($(this).data('thnpajak'));
        $('#jumlah').val($(this).data('jumlah'));
        $('#prs_denda').val($(this).data('prs_denda'));
        $('#nil_denda').val($(this).data('nil_denda'));
        $('#total').val($(this).data('total'));
        $('#keterangan').val($(this).data('keterangan'));
        $('#formulir').val($(this).data('formulir'));
        $('#kodebayar').val($(this).data('kodebayar'));
        $('#tgl_input').val($(this).data('tgl_input'));
        $('#nopelaporan').val($(this).data('nopelaporan'));
        
        // Show modal
        $('#editModal').modal('show');
    });
    
});
