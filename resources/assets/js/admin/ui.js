/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    $(function() {
        var baseName = function(str) {
            var base;
            if (str.lastIndexOf('\\') > str.lastIndexOf('/')) {
                base = String(str).substring(str.lastIndexOf('\\') + 1);
            }
            else {
                base = String(str).substring(str.lastIndexOf('/') + 1);
            }
            return base;
        };

        $('.js__chosen-select').select2();

        $("#file-input").on('change', function() {
            $("#selected-file").html(baseName($(this).val()));
        });

        $("#file-input2").on('change', function() {
            $("#selected-file2").html(baseName($(this).val()));
        });

        $(".submit-form").click(function(e) {
            e.preventDefault();
            $("#" + $(this).data('form')).submit();
        });
    });
})();