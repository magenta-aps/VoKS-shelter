/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    $(function() {
        $(document).on('click', '.help-block__faq-item .-title', function() {
            var block = $(this).closest('.help-block__faq-item');
            block.find('.-text').slideToggle(200, function() {
                block.toggleClass('-active');
            });
        });
    });
})();