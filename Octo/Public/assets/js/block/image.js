window.blockEditors.Image = PageBlock.extend({
    editor: null,

    edit: function (modalBody, blockContent) {
        var current = '';

        if (blockContent && blockContent.image) {
            current = blockContent.image;
        }

        self.editor = imagePicker('image-image', 'Choose an image', current);
        modalBody.append(self.editor);
    },

    save: function () {
        return {image: self.editor.find('#image-image').val()};
    }
});
