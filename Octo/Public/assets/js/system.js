/**
 * Create a modal dialog and return it.
 */
function createDialog(id, options)
{
    var modal  = $('<div></div>').attr('id', id).addClass('modal fade');
    var dialog = $('<div></div>').addClass('modal-dialog');
    var content = $('<div></div>').addClass('modal-content');
    dialog.append(content);
    modal.append(dialog);


    // Add the modal header, but only if we allow a close button and/or have a title:
    if(options.allowClose || options.title)
    {
        var header  = $('<div></div>').addClass('modal-header');

        if(options.allowClose)
        {
            header.append('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>');
        }

        if(options.title)
        {
            header.append($('<h4></h4>').text(options.title));
        }

        content.append(header);
    }

    // Add the modal body:
    content.append($('<div></div>').addClass('modal-body'));

    if(options.body)
    {
        content.find('.modal-body').append(options.body);
    }

    // If we have been passed a button, add it as the modal footer:
    if(options.button)
    {
        var footer = $('<div></div>').addClass('modal-footer');
        footer.append(options.button);

        content.append(footer);
    }

    // Show immediately?
    if(options.show)
    {
        modal.modal();
    }

    $('body').append(modal);

    return modal;

}


window.pageEditor = Class.extend({

    id: null,
    content: {},
    page: {},

    saveContent: function () {
        $('.pace').removeClass('hide');

        $.post('/'+window.adminUri+'/page/save/' + this.id, {content: JSON.stringify(this.content)}, function () {
            document.getElementById('page-preview').contentWindow.location.reload();
            $('.pace').addClass('hide');
        });
    },

    saveMetaData: function() {
        $('.pace').removeClass('hide');

        $.post('/'+window.adminUri+'/page/save/' + this.id, {page: this.page}, function () {
            document.getElementById('page-preview').contentWindow.location.reload();
            $('.pace').addClass('hide');
        });
    }

});


function textElement(id, label, value)
{
    var section = $('<section></section>').addClass('control-group');
    var label1 = $('<label></label>').addClass('label').text(label);
    section.append(label1);

    var label2 = $('<label></label>').addClass('input');
    var input = $('<input>').attr('id', id).attr('type', 'text').val(value);
    section.append(label2);
    label2.append(input);

    return section;
}

function selectElement(id, label, options, value)
{
    var section = $('<section></section>').addClass('control-group');
    var label1 = $('<label></label>').addClass('label').text(label);
    section.append(label1);

    var label2 = $('<label></label>').addClass('input');
    var input = $('<select></select>').attr('id', id).attr('type', 'text');

    for (var i in options) {
        input.append($('<option></option>').val(options[i]).text(options[i]));
    }

    input.val(value);

    section.append(label2);
    label2.append(input);
    input.css('width', '100%');
    input.select2();

    return section;
}

function imagePicker(id, label, value)
{
    var section = $('<section></section>').addClass('control-group');
//    var label1 = $('<label></label>').addClass('label').text(label);
//    section.append(label1);

    var label2 = $('<label></label>').addClass('input');
    var input = $('<input>').attr('id', id).attr('type', 'text');
    input.css('width', '100%');
    section.append(label2);

    var img = $('<img>');
    label2.append(img);

    if (value) {
        img.attr('src', '/media/render/' + value + '/160/90');
    }

    label2.append('<br /><br />');
    label2.append(input);
    input.css('width', '100%');

    input.select2({
        placeholder: "Search for an image",
        minimumInputLength: 1,
        width: '560px',
        ajax: {
            url: '/'+window.adminUri+'/media/autocomplete/images',
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

    input.on('change', function () {
        img.attr('src', '/media/render/' + $(this).val() + '/160/90');
    });

    input.val(value);

    return section;
}
