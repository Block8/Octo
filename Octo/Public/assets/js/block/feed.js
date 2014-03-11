window.blockEditors.Feed = PageBlock.extend({
    editor: null,

    edit: function (modalBody, blockContent) {
        var element = $('<input type="text">');

        if (blockContent && blockContent.url) {
            element.val(blockContent.url);
        }

        self.editor = element;
        modalBody.append($('<h4>Feed URL (Atom or RSS):</h4>'));
        modalBody.append(self.editor);
    },

    save: function () {
        return {url: self.editor.val()};
    }
});
