$(document).ready(function() {
    
    $('#idrecord').select2({
        placeholder: $('#idrecord').data('placeholder'),
        minimumInputLength: 3,
    });
   
    $('#pendapatan').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "PendDaerah/get_datatable_data",
            "type": "POST",
            "data": function(d) {
                d.id = $('#idrecord').val(); 
            }
        },
        "columns": [
            { "data": "nourut" },
            { "data": "nobukti" },
            { "data": "tglpajak" },
            { "data": "blnpajak" },
            { "data": "thnpajak" },
            { "data": "jumlah" },
            { "data": "prs_denda" },
            { "data": "nil_denda" },
            { "data": "total" },
            { "data": "keterangan" },
            { "data": "action", "orderable": false, "searchable": false }
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

    $('#isnonkas').change(function() {
        if ($(this).is(':checked')) {
            $(this).val('1');
        } else {
            $(this).val('');
        }
    });

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
   
});
