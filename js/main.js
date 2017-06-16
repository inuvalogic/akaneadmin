$(function() {
    $('#datatable').dataTable({
        "bPaginate": true,
        "bLengthChange": false,
        "bFilter": true,
        "bSort": true,
        "bInfo": true,
        "bAutoWidth": false
    });
    $('.iconpicker').iconpicker(".iconpicker");
});