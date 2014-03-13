window.blockEditors.Text = PageBlock.extend({
    editor: null,

    edit: function (modalBody, blockContent) {
        var element = $('<textarea></textarea>');

        if (blockContent && blockContent.content) {
            element.text(blockContent.content);
        }

        var myOptions = window.fullCkEditor;
        myOptions.height = '250px';

        element.ckeditor(myOptions);

        self.editor = element;
        modalBody.append(self.editor);
    },

    save: function () {
        return {content: self.editor.val()};
    }
});
