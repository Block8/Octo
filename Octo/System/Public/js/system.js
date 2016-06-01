var Octo = {};

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

    triggerSaveDetails: function () {
        $('.page-save-notice').addClass('alert-warning').removeClass('alert-success').text('Saving...').fadeIn('fast');

        var self = this;
        var form = $('#details form');
        var serialized = form.serializeArray();
        var details = {};

        for (var i in serialized) {
            var name = serialized[i].name;

            if (name.substring(name.length - 2) == '[]') {
                name = name.substring(0, name.length - 2);

                if (!details[name]) {
                    details[name] = [];
                }

                details[name].push(serialized[i].value);
            } else {
                details[name] = serialized[i].value;
            }
        }

        self.page = details;
        self.saveMetaData();
    },

    triggerSaveContent: function (form) {
        $('.page-save-notice').addClass('alert-warning').removeClass('alert-success').text('Saving...').fadeIn('fast');

        var self = this;
        var formId = form.attr('id').replace('block_', '');
        var serialized = form.serializeArray();
        var content = {};

        for (var i in serialized) {
            var name = serialized[i].name;

            if (name.substring(name.length - 2) == '[]') {
                name = name.substring(0, name.length - 2);

                if (!content[name]) {
                    content[name] = [];
                }

                content[name].push(serialized[i].value);
            } else {
                if (serialized[i].value != '' && serialized[i] != '<p></p>') {
                    content[name] = serialized[i].value;
                }
            }
        }

        var itemsCount = 0;
        for (var idx in content) {
            itemsCount++;
            break;
        }

        if (itemsCount > 0) {
            self.content[formId] = content;
        } else if (self.content[formId]) {
            delete self.content[formId];
        }

        self.saveContent();
    },

    saveContent: function () {
        var self = this;

        $.post(window.adminUri + '/page/save/' + this.id, {content: JSON.stringify(this.content)}, function (response) {
            response = JSON.parse(response);

            if (self.content_id != response.content_id) {
                document.getElementById('page-preview').contentWindow.location.reload();
            }

            $('.page-save-notice').addClass('alert-success').removeClass('alert-warning').text('Saved.').fadeOut('slow');
        });
    },

    saveMetaData: function() {
        $('.pace').removeClass('hide');

        $.post(window.adminUri + '/page/save/' + this.id, {page: this.page}, function () {
            document.getElementById('page-preview').contentWindow.location.reload();
            $('.page-save-notice').addClass('alert-success').removeClass('alert-warning').text('Saved.').fadeOut('slow');
        });
    }

});

$(document).ready(function () {
    $('.btn-delete, .btn-danger').on('click', function () {
        return confirm('Are you sure?');
    });

    $('select.select2, input.select2').addClass('initialised').select2({
        templateResult: function (item) {
            var element = $(item.element);

            if (element.parents('select').hasClass('icon')) {
                var itemHtml = '<span><i class="fa fa-'+element.val()+'"></i>&nbsp; ' + item.text + '</span>';
                return $(itemHtml);
            }

            return item.text;
        }
    });

    $('.datetime-picker').datetimepicker({format: 'YYYY-MM-DD HH:mm'});
});

// Sortable
var fixHelper = function(e, ui) {
    ui.children().each(function() {
        $(this).width($(this).width());
    });
    return ui;
};
