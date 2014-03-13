window.blockEditors.TextImage = PageBlock.extend({
    editor: null,

    edit: function (modalBody, blockContent) {

        var element = $('<textarea></textarea>');

        if (blockContent && blockContent.content) {
            element.text(blockContent.content);
        }

        var myOptions = window.fullCkEditor;
        myOptions.height = '250px';

        element.ckeditor(myOptions);

        self.textEditor = element;
        modalBody.append(self.textEditor);


        var currentImage = '';

        if (blockContent && blockContent.image) {
            currentImage = blockContent.image;
        }

        self.imageEditor = imagePicker('image-image', 'Choose an image', currentImage);
        modalBody.append('<h4>Select an image:</h4>');
        modalBody.append(self.imageEditor);
    },

    save: function () {
        return {
            content: self.textEditor.val(),
            image: self.imageEditor.find('#image-image').val()
        };
    }
});
