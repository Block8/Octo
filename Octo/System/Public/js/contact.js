$(document).ready(function () {

    $('.contact').on('mouseover', function () {
        $('.contact-card').remove();
        $(this).css({'display': 'relative'});

        $.get('/'+window.adminUri+'/contact/popup/' + $(this).data('id'), function (data) {
            $(this).append(data);
        });
    });

});

function attachContactCard(el) {
    var cardActive = false;
    var cardTimeout = null;

    el.on('mouseover', function () {
        var id = $(this).data('id');

        if (cardActive) {
            if (cardTimeout) {
                clearTimeout(cardTimeout);
            }

            if (cardActive == id) {
                return;
            } else if (cardActive != id) {
                $('.contact-card').remove();
            }
        }

        var self = $(this);
        cardActive = id;

        $(this).css({'position': 'relative'});

        $.get('/'+window.adminUri+'/contact/popup/' + id, function (data) {
            self.append(data);
        });
    });

    el.on('mouseout', function () {
        cardTimeout = setTimeout(function () {
            cardActive = null;
            $('.contact-card').remove();
        }, 200);
    });
}