// STICK STANDARD CMS-WIDE JS IN HERE

$(document).ready( function(e) {

    // Setup datepickers
    $(".sa-datepicker").each(function() {
        var val = $(this).val();
        $(this).datepicker({
            setDate: val,
            dateFormat: 'DD dd MM yy',
            prevText: ' <',
            nextText: ' >'
        });
    });

});

// Sortable
var fixHelper = function(e, ui) {
    ui.children().each(function() {
        $(this).width($(this).width());
    });
    return ui;
};
