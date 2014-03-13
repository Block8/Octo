window.blockEditors.TextImageLink = PageBlock.extend({
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

        modalBody.append('<br /><h4>Enter a link:</h4><br />');
        self.linkEditor = $('<input name="link" type="text" class="col-xs-12" />');

        if (blockContent && blockContent.link) {
            self.linkEditor.val(blockContent.link);
        }

        modalBody.append(self.linkEditor);

        var currentImage = '';

        if (blockContent && blockContent.image) {
            currentImage = blockContent.image;
        }

        self.imageEditor = imagePicker('image-image', 'Choose an image', currentImage);
        modalBody.append('<br /><br /><h4>Select an image:</h4><br />');
        modalBody.append(self.imageEditor);
    },

    save: function () {
        return {
            content: self.textEditor.val(),
            image: self.imageEditor.find('#image-image').val(),
            link: self.linkEditor.val()
        };
    }
});
