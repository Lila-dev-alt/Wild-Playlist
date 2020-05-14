
$(document).ready( function () {
    $('#commentTable').DataTable( {
        "aaSorting": [],
        "aoColumnDefs": [
            { "bSortable": false, "aTargets": [2] }
        ]
    });
} );
