define([
    'jquery',
    'mage/translate'
], function ($, $t) {
    'use strict';
    return function (config, element) {
            const buttons = $(element).find('.copy-btn');
            buttons.each(function () {
            const button = $(this);
            button.on('click', function () {
                const targetId = button.data('target');
                const codeElement = $('#' + targetId);
                const code = codeElement.length ? codeElement.text().trim() : '';

                if (navigator.clipboard && code) {
                    navigator.clipboard.writeText(code).then(() => {
                        button.text($t('Copied!'));
                        setTimeout(() => {
                            button.text($t('Copy Code'));
                        }, 1500);
                    });
                }
            });
        });
    };
});
