window.blockEditors.SectionCollection = PageBlock.extend({
    editor: null,

    edit: function (modalBody, blockContent) {
        var current = '';

        if (blockContent && blockContent.parent) {
            current = blockContent.parent;
        }

        self.editor = pagePicker('collection-parent', 'Choose an page', current);
        modalBody.append(self.editor);
    },

    save: function () {
        return {parent: self.editor.find('#collection-parent').val()};
    }
});
