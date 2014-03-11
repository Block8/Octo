window.blockEditors.Map = PageBlock.extend({
    editor: null,

    edit: function (modalBody, blockContent) {
        var element = $('<textarea></textarea>');

        if (blockContent && blockContent.code) {
            element.text(blockContent.code);
        }

        self.editor = element;
        modalBody.append($('<h4>Paste your map embed code here:</h4>'));
        modalBody.append(self.editor);
    },

    save: function () {
        return {code: self.editor.val()};
    }
});
