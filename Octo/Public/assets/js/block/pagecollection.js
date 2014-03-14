window.blockEditors.PageCollection = PageBlock.extend({
    editor: null,
    pageList: null,

    edit: function (modalBody, blockContent) {
        var current = '';

        if (blockContent && blockContent.pages) {
            current = blockContent.pages;
        }

        self.pageList = $('<ul></ul>');
        modalBody.append(pageList);


        self.editor = pagePicker('collection-page', 'Add a page', current);
        self.editor.find('#collection-page').on('change', function () {

            var item = $('<li></li>').text($(this).select2('data').text).data('id', $(this).val());
            item.addClass($(this).val());

            var btn = $('<button class="btn btn-sm">').text('remove').on('click', function ()
            {
                $(this).parent('li').remove();
            });

            item.append(btn)
            pageList.append(item);

        });

        modalBody.append(self.editor);
    },

    save: function () {

        var pages = [];

        self.pageList.find('li').each(function () {
            pages.push($(this).data('id'));
        });

        return {pages: pages};
    }
});
