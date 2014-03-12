window.blockEditors.News = PageBlock.extend({
    category: null,
    perPage: null,

    edit: function (modalBody, blockContent) {
        self.category = $('<select></select>');
        self.category.addClass('select2');
        self.category.css('width', '100%');
        self.category.append($('<option value="0">All categories</option>'));

        $.getJSON('/'+window.adminUri+'/categories/category-list/news', function (data) {
            for (var i in data) {
                var option = $('<option></option>').attr('value', i).text(data[i]);

                if (blockContent && blockContent.category) {
                    if (blockContent.category == i) {
                        option.prop('selected', true);
                    }
                }

                self.category.append(option);
            }
        });


        self.perPage = $('<select></select>');
        self.perPage.css('width', '100%');
        self.perPage.append($('<option value="0">All</option>'));
        self.perPage.append($('<option value="5">5</option>'));
        self.perPage.append($('<option value="10">10</option>'));
        self.perPage.append($('<option value="15">15</option>'));
        self.perPage.append($('<option value="25">25</option>'));
        self.perPage.append($('<option value="50">50</option>'));

        modalBody.append('<h4>Select a category:</h4>');
        modalBody.append(self.category);
        modalBody.append('<h4>Items per page:</h4>');
        modalBody.append(self.perPage);
    },

    save: function () {
        return {category: self.category.val(), perPage: self.perPage.val()};
    }
});