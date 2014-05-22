window.blockEditors.PageCollection = PageBlock.extend({
    editor: null,
    pageList: null,

    edit: function (modalBody, blockContent) {
        var current = '';
        self.pageList = $('<ul style="list-style: none;padding-left:0px;"></ul>');

        var appendPage = function(id, data) {
            var item = $('<li style="display:block;position:relative;border:1px solid #ccc;padding:8px;margin-bottom:8px;"></li>').text(data).data('id', id);
            item.addClass(id);
            var btn = $('<button class="close" style="position:absolute;right:8px;">').text('×').on('click', function () {
                $(this).parent('li').remove();
            });
            item.append(btn);
            self.pageList.append(item);
        }

        $('.modal-footer button').attr('disabled', true);

        $.ajax({
            url: '/'+window.adminUri+'/page/metadata',
            data: {
                q: JSON.stringify(blockContent.pages)
            },
            type:'POST',
            success:function(data) {
                var pagesMeta = JSON.parse(data);
                $.each(pagesMeta.results, function(i, item) {
                    appendPage(pagesMeta.results[i].id, pagesMeta.results[i].text);
                });
                modalBody.append(pageList);

                self.editor = pagePicker('collection-page', 'Add a page', blockContent.pages);
                self.editor.find('#collection-page').on('change', function () {
                    appendPage($(this).val(), $(this).select2('data').text);
                });

                modalBody.append(self.editor);
                $('.modal-footer button').attr('disabled', false);
            }
        });
    },

    save: function () {

        var pages = [];

        self.pageList.find('li').each(function () {
            pages.push($(this).data('id'));
        });

        return {pages: pages};
    }
});
