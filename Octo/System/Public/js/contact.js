$(document).ready(function () {

    $('.contact').on('mouseover', function () {
        $('.contact-card').remove();
        $(this).css({'display': 'relative'});

        $.get(window.adminUri + '/contact/popup/' + $(this).data('id'), function (data) {
            $(this).append(data);
        });
    });

});

function attachContactCard(el) {
    var cardActive = false;
    var cardTimein = null;
    var cardTimeout = null;

    el.on('click', function (e) {
        showContactCard($(this).data('id'), $(this));
    });
/*
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

        var cardTimein = setTimeout(function () {

        }, 500);


    });

    el.on('mouseout', function () {

        if (cardTimein) {
            clearTimeout(cardTimein);
        }

        cardTimeout = setTimeout(function () {
            cardActive = null;
            $('.contact-card').remove();
        }, 200);
    });
*/
}


function showContactCard(contactId, el) {
    if (window.activeContactCard == contactId) {
        return;
    }

    window.activeContactCard = contactId;

    // Hide any other contact cards:
    $('.contact-card:not(.contact-'+contactId+')').remove();

    // Make sure the element we're attached to is positioned relative:
    el.css({'position': 'relative'});

    // Load the card and append it to the element we're attached to:
    $.get(window.adminUri + '/contact/popup/' + contactId, function (contactCard) {
        el.append(contactCard);

        $('body').on('click', function (e) {
            if (!$(e.target).hasClass('.contact-' + contactId) && !$(e.target).parents('.contact-' + contactId).length) {
                $('body').off('click');
                window.activeContactCard = null;
                $('.contact-card').remove();
            }

        });
    });
}