(function() {
    'use strict';

    angular
        .module('xeditable')
        .directive('editableTinymce', ['editableDirectiveFactory', function(editableDirectiveFactory) {
            return editableDirectiveFactory({
                directiveName: 'editableTinymce',
                inputTpl: '<textarea ui-tinymce="options"></textarea>',
                render: function() {
                    this.scope.options = {
                        menubar: false,
                        min_width: '100%',
                        plugins : '',
                        statusbar: false,
                        toolbar1: "bold italic underline | bullist numlist",
                        toolbar_items_size : 'small'
                    };

                    // Render
                    this.parent.render.call(this);
                }
            });
        }]);

})();