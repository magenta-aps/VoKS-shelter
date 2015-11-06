/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var toastFactory = function() {
        var Toaster = function() {

            var placeholder = $(".toasts");

            if (placeholder.length === 0) {
                $("body").prepend('<div class="toasts"></div>');
                placeholder = $(".toasts");
            }

            this.push = function(type, title, message) {
                var element = $("<div />");
                element.html(
                    '<div class="toast -' + type + ' icons -close">' +
                    '<h3 class="toast__title">' + title + '</h3>' +
                    '<p class="toast__text">' + (message ? message : '') + '</p>' +
                    '<span class="-icon"></span>' +
                    '</div>'
                );

                element.find('span').on('click', function() {
                    element.fadeOut(300, function() {
                        $(this).remove();
                    });
                });

                setTimeout(function() {
                    element.fadeOut(300, function() {
                        $(this).remove();
                    });
                }, 10000);

                placeholder.append(element.hide());
                element.fadeIn(300);
            };
        };

        return new Toaster();
    };

    toastFactory.$inject = [];
    angular.module('toasts', []).factory('Toast', toastFactory);
})();