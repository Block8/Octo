window.blockEditors.PageCollection = PageBlock.extend({
    editor: null,
    pageList: null,

    edit: function (modalBody, blockContent) {

    },

    save: function () {

        var pages = [];

        self.pageList.find('li').each(function () {
            pages.push($(this).data('id'));
        });

        return {pages: pages};
    }
});
