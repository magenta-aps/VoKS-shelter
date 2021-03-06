
/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    /**
     * Angular app module
     * @type Array
     */
    var dependencies = [
        'pascalprecht.translate',

        'toasts',
        'ui.select',
        'ui.tinymce',
        'xeditable'
    ];
    angular.module('system', dependencies);

    /**
     * Various angular config
     *
     * Configure:
     * $provide
     * $interpolateProvider
     * $sceProvider
     */
    var ngConfig = function($interpolateProvider, $sceProvider) {
        $interpolateProvider.startSymbol('<%').endSymbol('%>');
        $sceProvider.enabled(false);
    };

    ngConfig.$inject = ['$interpolateProvider', '$sceProvider'];
    angular.module('system').config(ngConfig);

    /**
     * Translations
     * $translateProvider
     */
    var angularTranslate = function($translateProvider) {
        // Language and it's translations
        // Point "translations" to a proper translation JSON here
        var language = config.locale,
            translations = config.lang;

        // Escaping strategy
        $translateProvider.useSanitizeValueStrategy('escape');

        // Set translations
        $translateProvider
            .translations(language, translations)
            .preferredLanguage(language);
    };

    angularTranslate.$inject = ['$translateProvider'];
    angular.module('system').config(angularTranslate);

    /**
     * Everything else
     */
    angular
        .module('system')
        .config(['uiSelectConfig', function(uiSelectConfig) {
            uiSelectConfig.theme = 'select2';
        }])
        .run(['editableOptions', 'editableThemes', function(editableOptions, editableThemes) {
            editableThemes.bs3.errorTpl= '<div class="error__text error__message" ng-show="$error" ng-bind="$error"></div>';
            editableThemes.bs3.inputClass= "<% $error ? 'textarea-block__input-text error__block' : 'textarea-block__input-text' %>";

            editableOptions.theme = 'bs3';
        }])
        .filter('html', ['$sce', function($sce) {
            return function(text) {
                return $sce.trustAsHtml(text);
            };
        }])
        .directive('toggleClass', function() {
            return {
                restrict: 'A',
                link: function (scope, element, attrs) {
                    element.bind('click', function () {
                        element.parent().toggleClass('-active');
                    });
                }
            };
        });
})();