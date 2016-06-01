Octo.Forms = {};

Octo.Forms.init = function () {

    // Automatically convert select tags with class octo-image-picker to image picker elements.
    $('select.octo-image-picker').each(function () {
        Octo.Forms.createImagePicker($(this));
    });

    // Automatically convert select tags with class octo-page-picker to page picker elements.
    $('select.octo-page-picker').each(function () {
        Octo.Forms.createPagePicker($(this));
    });

    // Automatically convert select tags with class octo-file-picker to file picker elements.
    $('select.octo-file-picker').each(function () {
        Octo.Forms.createFilePicker($(this));
    });

    $('input.switch').each(function () {
        Octo.Forms.createSwitch($(this));
    });
};

Octo.Forms.createImagePicker = function (input) {
    if (input.prop('nodeName') != 'SELECT') {
        console.error('Octo.Forms: You can only create an image picker from a SELECT element.');
        return;
    }

    var img = $('<img>');
    img.insertAfter(input).css({'margin': '10px 0'}).hide();

    input.select2({
        placeholder: "Search for an image",
        allowClear: true,
        minimumInputLength: 1,
        width: '100%',
        ajax: {
            url: window.adminUri + '/media/autocomplete/images',
            dataType: 'json',
            data: function(term) {
                return {
                    q: term
                };
            },
            results: function(data) {
                return data;
            }
        },
        templateResult: function (item) {
            var itemHtml = '' +
                '<span><img class="item-thumb" src="/media/render/'+item.id+'/100/60"></span>' +
                '<span>' +
                '<strong>'+item.text+'</strong><br><small>'+item.filename+'</small>' +
                '</span>';
            return $(itemHtml);
        }
    });

    input.on('change', function () {
        var val = $(this).val();

        if (val) {
            img.attr('src', '/media/render/' + $(this).val() + '/160/90');
            img.show();
        }
    });

    if (input.val() != '') {
        input.trigger('change');
    }
};

Octo.Forms.createPagePicker = function (input) {
    if (input.prop('nodeName') != 'SELECT') {
        console.error('Octo.Forms: You can only create a page picker from a SELECT element.');
        return;
    }

    input.select2({
        placeholder: "Search for a page",
        minimumInputLength: 1,
        allowClear: true,
        width: '100%',
        initSelection : function(element, callback) {

            if (input.val()) {
                $.getJSON(window.adminUri + '/page/autocomplete?q=' + input.val(), function (data) {
                    if (data.results[0]) {
                        callback(data.results[0]);
                    }
                });
            }

        },
        ajax: {
            url: window.adminUri + '/page/autocomplete',
            dataType: 'json',
            data: function(term) {
                return {
                    q: term.term
                };
            },
            results: function(data) {
                return data;
            }
        },
        templateResult: function (item) {
            var itemHtml = '';

            if (item.image) {
                itemHtml += '<span><img class="item-thumb" src="/media/render/'+item.image+'/75/50"></span>';
            }

            itemHtml += '<span>' +
                '<strong>'+item.text+'</strong><br><small>'+item.uri+'</small>' +
                '</span>';

            return $(itemHtml);
        }
    });
};

Octo.Forms.createFilePicker = function (input) {
    if (input.prop('nodeName') != 'SELECT') {
        console.error('Octo.Forms: You can only create a file picker from a SELECT element.');
        return;
    }

    input.select2({
        placeholder: "Search for a file",
        allowClear: true,
        minimumInputLength: 1,
        width: '100%',
        initSelection : function(element, callback) {

            if (input.val()) {
                $.getJSON(window.adminUri + '/media/autocomplete/files?q=' + input.val(), function (data) {
                    if (data.results[0]) {
                        callback(data.results[0]);
                    }
                });
            }

        },
        ajax: {
            url: window.adminUri + '/media/autocomplete/files',
            dataType: 'json',
            data: function(term) {
                return {
                    q: term
                };
            },
            results: function(data) {
                return data;
            }
        }
    });
};

Octo.Forms.createSwitch = function (check) {
    if (check.prop('nodeName') != 'INPUT' || check.attr('type') != 'checkbox') {
        console.error('Octo.Forms: You can only create an on/off switch from an CHECKBOX element.');
        return;
    }

    check.hide();

    var onText = check.data('on') || 'On';
    var offText = check.data('off') || 'Off';

    var group = $('<div></div>').addClass('btn-group');
    var onSwitch = $('<button><i class="fa fa-check"></i> '+onText+'</button>').addClass('btn btn-sm btn-default');
    var offSwitch = $('<button><i class="fa fa-close"></i> '+offText+'</button>').addClass('btn btn-sm btn-default');

    group.append(onSwitch);
    group.append(offSwitch);

    if (check.prop('checked')) {
        onSwitch.addClass('active btn-success').removeClass('btn-default');
    } else {
        offSwitch.addClass('active btn-danger').removeClass('btn-default');
    }

    onSwitch.on('click', function (e) {
        e.preventDefault();

        offSwitch.removeClass('active btn-danger').addClass('btn-default');
        onSwitch.addClass('active btn-success').removeClass('btn-default');

        check.prop('checked', true);
        check.trigger('change');
    });

    offSwitch.on('click', function (e) {
        e.preventDefault();

        onSwitch.removeClass('active btn-success').addClass('btn-default');
        offSwitch.addClass('active btn-danger').removeClass('btn-default');

        check.prop('checked', false);
        check.trigger('change');
    });

    group.css({'margin-right': '15px'});
    check.after(group);
};

$(document).ready(Octo.Forms.init);