/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var waitingLineController = function($scope) {

        $scope.dropdown = {
            order: null
        };

        var options = config['order-options'];

        for (var i = 0; i < options.length; i++) {
            if (typeof options[i].default !== 'undefined') {
                $scope.dropdown.order = options[i];
            }
        }

        /** Position of the next element bellow viewport */
        $scope.elementBellowPos = 0;
        /** Position of the next element above viewport */
        $scope.elementAbovePos = 0;

        /** Function to determine if scroll to button should be shown; also saves the position of the next element  */
        $scope.showScrollTo = function(bellow) {
            var $elements = $('.button.icons.-answer.-active');
            if ($elements.length > 1) {
                for (var i = 0; i < $elements.length; i++) {
                    if (isElementHidden($($elements[i]), bellow)) {
                        saveElementPosition($($elements[i]), bellow);
                        return true;
                    }
                }
            } else if ($elements.length === 1) {
                if (isElementHidden($elements, bellow)) {
                    saveElementPosition($elements, bellow);
                    return true;
                }
            }
            return false;
        };
        /** function to scroll to the next element above */
        $scope.scrollToTop = function() {
            var $scrollElement = $('.waiting-list__lines');
            $scrollElement.scrollTop($scope.elementAbovePos);
        };

        /** function to scroll to the next element bellow */
        $scope.scrollToBottom = function() {
            var $scrollElement = $('.waiting-list__lines');
            $scrollElement.scrollTop($scope.elementBellowPos);
        };

        /** function to get the element position inside the waiting line element */
        function getElementPosition(el) {
            var $scrollElement = $('.waiting-list__lines');
            return el.position().top + $scrollElement.scrollTop();
        }

        /** function to save the next element's position in the waiting line */
        function saveElementPosition($element, bellow) {
            if (bellow) {
                $scope.elementBellowPos = getElementPosition($element);
            } else {
                $scope.elementAbovePos = getElementPosition($element);
            }
        }

        /** function to check if the element is above or bellow the viewport */
        function isElementHidden(el, bellow) {
            var $scrollElement = $('.waiting-list__lines');
            var docViewTop = $scrollElement.scrollTop();
            var docViewBottom = docViewTop + $scrollElement.height();
            if (bellow) {
                return (
                    el.position().top + docViewTop > docViewBottom
                );
            }
            else {
                return (
                    (el.position().top + el.height()) + docViewTop < docViewTop
                );
            }
        }
    };

    waitingLineController.$inject = ['$scope'];
    angular.module('app').controller('WaitingLineController', waitingLineController);
})();