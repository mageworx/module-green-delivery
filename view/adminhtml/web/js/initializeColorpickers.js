require(['jquery', 'domReady!'], function ($) {
    'use strict';

    $('.colpicker').colpick(
        {
            layout: 'rgbhex',
            onChange: function (hsb, hex, rgb, el) {
                $(el).val('#' + hex);
            },
            onSubmit: function (hsb, hex, rgb, el) {
                var colpickerId = this.selector.parent().attr('id');
                $('#' + colpickerId).hide();
            }
        }
    );
});
