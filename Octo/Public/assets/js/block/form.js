window.blockEditors.Form = PageBlock.extend({
    editor: null,

    edit: function (modalBody, blockContent) {

        var select = $('<select></select>');
        select.addClass('select2');
        select.css('width', '100%');
        select.append($('<option>Please wait...</option>'));

        $.getJSON('/backoffice/form', function (data) {
            select.find('option').remove();

            for (var i in data) {
                var option = $('<option></option>').attr('value', i).text(data[i]);

                if (blockContent && blockContent.id) {
                    if (blockContent.id == data[i]) {
                        option.prop('selected', true);
                    }
                }

                select.append(option);
            }

            select.select2();
        });

        self.editor = select;
        modalBody.append(self.editor);
    },

    save: function () {
        return {id: self.editor.val()};
    }
});