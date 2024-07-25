$(document).on('click', '.edit-data', function() {
    var idstsmaster = $(this).data('idstsmaster');
    var nourut = $(this).data('nourut');
    var nobukti = $(this).data('nobukti');
    
    $.ajax({
        url: '<?= base_url("PendDaerah/get_edit_data") ?>',
        method: 'POST',
        data: {idstsmaster: idstsmaster, nourut: nourut, nobukti:nobukti},
        dataType: 'json',
        success: function(response) {
            $('#edit_idstsmaster').val(response.idstsmaster);
            $('#edit_nourut').val(response.nourut);
            $('#nobukti').val(response.nobukti); 
        
        }
    });
});