/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

/**
 * Quick help service
 */
(function() {
    'use strict';

    var QuickHelpService = function($q, $http, $route, $timeout, $translate, Toast, Client, Connections, Messages, State, ShelterAPI) {
        /**
         * Fake information
         *
         * @type {Object}
         */
        var fake = {
            client: {
                id: 'fake_desktop',
                type: 'client',
                name: ''
            },
            message: {
                content: ''
            }
        };

        /**
         * Available tours
         *
         * @type {Object}
         */
        var tours = {
            plan: [],
            stream: [],
            streams: []
        };

        /**
         * Initial UI state
         *
         * @type {Object}
         */
        var state = {
            client: {
                real: false,
                chat: false,
                waiting: false,
                index: 1
            },
            message: {
                real: false,
                popup: false
            }
        };

        /**
         * List of names that is resolved to CSS selectors
         *
         * @type {Object}
         */
        var selectors = {
            // ALL
            MESSAGE_FEED_MESSAGE: '.message-feed__unit.tour',
            WAITING_LINE: '.side-block.waiting-list',
            WAITING_LINE_SORTING: '.side-block.waiting-list .side-block__title',
            HEADER_STATUS: '.header__status',
            POLICE_STATUS: '.police-status',
            ALARM_STATISTICS: '.status-bar',
            ADMINISTRATION_BUTTON: '.header__button.icons.-settings',

            // STREAM, PLAN
            SMALL_STREAM: '.streams:not(.ng-hide) .streams__small .streams__stream:not(.ng-hide):eq(0)',
            SMALL_EMPTY_STREAM: '.streams:not(.ng-hide) .streams__empty:not(.ng-hide):eq(0)',

            // STREAMS
            STREAM_LOCATION_BUTTON: '.streams > .streams__unit .streams__item .streams__stream:not(.ng-hide):eq(0) .streams__button.-location',
            STREAM_VOLUME_BUTTON: '.streams > .streams__unit .streams__item .streams__stream:not(.ng-hide):eq(0) .streams__button.-volume',
            STREAM_MESSAGE_BUTTON: '.streams > .streams__unit .streams__item .streams__stream:not(.ng-hide):eq(0) .streams__button.-messages',
            STREAM_CHAT_WINDOW: '.streams > .streams__unit .streams__item .streams__stream:not(.ng-hide):eq(0) .streams__chat',
            STREAM_ANSWER_BUTTON: '.streams > .streams__unit .streams__item .streams__stream:not(.ng-hide):eq(0) .streams__button.-answer',
            STREAM_MAXIMIZE_BUTTON: '.streams > .streams__unit .streams__item .streams__stream:not(.ng-hide):eq(0) .streams__video-box .-minimize-maximize',
            EMPTY_STREAM_WINDOW: '.streams > .streams__unit .streams__item .streams__empty:not(.ng-hide):eq(0)',
            SMALL_MAP: '.streams .streams__unit:last-child .streams__item',

            // STREAM
            LARGE_STREAM_VOLUME_BUTTON:'.button.icons.-volume:not(.ng-hide)',
            LARGE_STREAM_CHAT_WINDOW: '.-chat-wrapper',
            LARGE_STREAM_ANSWER_BUTTON: '.-name-block .-answer, .-name-block .-on-hold',
            STREAM_MINIMIZE_BUTTON: '.button.-minimizeMaximize',
            LARGE_STREAM_MAP: '.map',

            // PLAN
            SCHOOL_PLAN_MAP_TOOLS: '.map-tools',
            PUSH_NOTIFICATION_TAB: '#message-push',
            AUDIO_MESSAGE_TAB: '#message-audio',
            MESSAGE_HISTORY_TAB: '#message-history'
        };

        /**
         * ------------------------------------------------------------------
         * Client & fake client
         * ------------------------------------------------------------------
         */

        /**
         * Client
         *
         * @type {Client}
         */
        var client = null;

        /**
         * Finds active client
         *
         * @return {Client || null}
         */
        var findActiveClient = function() {
            var clients = Connections.getClients(),
                i = 0, l = clients.length;

            var client = null;
            for (i; i < l; i++) {
                var _client = clients[i];
                if ('client' === _client.profile.type && true === _client.position.inView) {
                    client = _client;
                    break;
                }
            }

            return client;
        };

        /**
         * Creates fake client
         *
         * @return {Client}
         */
        var createFakeClient = function() {
            var fakeClient = fake.client,
                client = new Client(fakeClient.id);

            Connections.addClient(client);

            // Update client
            client.profile.gcmId = fakeClient.id;
            client.profile.type = fakeClient.type;
            client.profile.name = fakeClient.name;

            client.position.index = 1;
            client.position.inView = true;

            client.timestamps.connected = Date.now();

            // Add to list
            State.addStream(1);

            return client;
        };

        /**
         * Removes fake client
         */
        var removeFakeClient = function() {
            var fakeClient = fake.client,
                clients = Connections.getClients(),
                i = 0, l = clients.length;

            for (i; i < l; i++) {
                var _client = clients[i];
                if (fakeClient.id === _client.profile.id) {
                    State.removeStream(1);
                    clients.splice(i, 1);
                    break;
                }
            }
        };

        /**
         * ------------------------------------------------------------------
         * Message & fake message
         * ------------------------------------------------------------------
         */

        var message = null;

        /**
         * Finds active client message
         *
         * @param {String} clientId
         *
         * @return {Object}
         */
        var findActiveMessage = function(clientId) {
            var message = null,
                messages = Messages.list,
                i = 0, l = messages.length;

            for (i; i < l; i++) {
                var _message = messages[i];
                if (clientId === _message.user.id) {
                    message = _message;
                    break;
                }
            }

            return message;
        };

        /**
         * Creates fake message
         *
         * @param {Client} client
         *
         * @return {Object}
         */
        var createFakeMessage = function(client) {
            var fakeMessage = fake.message,
                message = Messages.createMessage({
                    id: client.profile.id,
                    name: client.profile.name,
                    isShelter: false,
                    calledPolice: client.state.calledPolice,
                    timestamp: Date.now(),
                    message: fakeMessage.content
                });

            // Flag it as easier manipulation
            message.fake = true;

            // Add to lists
            Messages.pushMessage(message);
            client.messages.list.push(message);

            return message;
        };

        /**
         * Removes fake message
         *
         * @param {Client} client
         */
        var removeFakeMessage = function(client) {
            var messages = Messages.list,
                i = 0, l = messages.length;

            for (i; i < l; i++) {
                var _message = messages[i];
                if (undefined !== _message && true === _message.fake) {
                    Messages.removeMessage(i);
                }
            }

            Messages.save();
        };

        /**
         * Removes fake message from client model
         *
         * @param {Client} client
         */
        var removeFakeClientMessage = function(client) {
            var messages = client.messages.list,
                i = 0, l = messages.length;

            for (i; i < l; i++) {
                var _message = messages[i];
                if (undefined !== _message && true === _message.fake) {
                    messages.splice(i, 1);
                }
            }

            client.save();
        };

        /**
         * ------------------------------------------------------------------
         * Tour
         * ------------------------------------------------------------------
         */

        /**
         * Tour template
         *
         * @returns {String}
         */
        var tourTemplate = function() {
            var template = '';
            template += '<div class="popover tour">';
            template += '   <div class="arrow"></div>';
            template += '   <h3 class="popover__title popover-title"></h3>';
            template += '   <div class="popover__content popover-content"></div>';
            template += '   <div class="popover__nav popover-navigation">';
            template += '       <a class="popover__prev  popover__button button -default" data-role="prev">' + config.lang.quickhelp.tour_ui.prev + '</a>';
            template += '       <a class="popover__nextbtn popover__button button -submit" data-role="next">' + config.lang.quickhelp.tour_ui.next + '</a>';
            template += '   </div>';
            template += '   <a href="javascript:void(0);" class="popover__close" data-role="end">';
            template += '       <i class="crs"></i>';
            template += '   </a>';
            template += '</div>';
            return template;
        };

        /**
         * Executed before each step is shown
         *
         * @param {Object} tour
         */
        var tourShow = function(tour) {

        };

        /**
         * Executed after each step is shown
         *
         * @param {Object} tour
         */
        var tourShown = function(tour) {
            var curr = tour._current,
                steps = tour._options.steps,
                step = steps[curr];

            switch (step.element) {
                // Show popup on message
                case selectors['MESSAGE_FEED_MESSAGE']:
                    if (false === message.popupOpen) {
                        message.popupOpen = true;
                        state.message.popup = true;
                    }
                    break;

                // Open chat before it is visible
                case selectors['STREAM_MESSAGE_BUTTON']:
                    if (false === client.state.chatOpen) {
                        client.state.chatOpen = true;
                        state.client.chat = true;
                    }
                    break;

                // Move client to waiting line, but hold its position in stream list
                case selectors['SMALL_MAP']:
                    if (true === client.state.chatOpen) {
                        client.state.chatOpen = false;
                    }

                    state.client.index = client.position.index;
                    state.client.waiting = true;
                    client.hideFromView();
                    State.addStream(state.client.index);
                    break;
                default:
                    break;
            }
        };

        /**
         * Next tour step
         *
         * @param {Object} tour
         */
        var tourNext = function(tour) {

        };

        /**
         * Previous tour step
         *
         * @param {Object} tour
         */
        var tourPrev = function(tour) {
            var prev = tour._current - 1,
                steps = tour._options.steps;

            if (prev >= 0) {
                var step = steps[prev];
                switch (step.element) {
                    case selectors['EMPTY_STREAM_WINDOW']:
                        client.placeIntoView(state.client.index);
                        state.client.waiting = false;
                        state.client.index = 0;
                        client.state.chatOpen = true;
                        break;
                    default:
                        break;
                }
            }
        };

        /**
         * Executed when tour ends
         */
        var tourEnd = function() {
            // Remove message tour state
            message.tour = false;

            // Remove fake message
            if (false === state.message.real) {
                removeFakeMessage(client);
                removeFakeClientMessage(client);

                state.message.popup = false;
            }

            // Remove popup if opened by QH
            if (true === state.message.popup) {
                message.popupOpen = false;
                state.message.popup = false;
            }

            // Remove fake client
            if (false === state.client.real) {
                removeFakeClient();
                state.client.chat = false;
                state.client.waiting = false;
            }

            // Put client back to its place if it was moved
            if (true === state.client.waiting) {
                client.placeIntoView(state.client.index);
                state.client.waiting = false;
                state.client.index = 0;
            }

            // Close client chat if opened by QH
            if (true === state.client.chat) {
                client.state.chatOpen = false;
                state.client.chat = false;
            }

            // Reset the last bits
            client = null;
            message = null;
            state.message.real = false;
            state.client.real = false;

            // Remove classes
            $('body')
                .removeClass('tour-enabled')
                .children('.tourbootstrap-overlay')
                .removeClass('tourbootstrap-overlay--enabled');
        };

        /**
         * Gathers and creates tour steps
         */
        var tourSteps = function(steps) {
            // HTML template
            var template = tourTemplate();

            // Default configuration
            var defaultStep = {
                backdrop: true,
                backdropContainer: 'body',
                backdropPadding: true,
                template: template
            };

            var result = [],
                i = 0, l = steps.length;

            for (i; i < l; i++) {
                var step = steps[i];
                step.element = selectors[step.element];
                result.push(angular.extend(step, defaultStep));
            }

            return result;
        };

        /**
         * Initializes tour
         */
        var initTour = function(steps) {
            return new Tour({
                storage: false,
                backdrop: true,
                backdropContainer: 'body',
                backdropPadding: 0,
                onShown: tourShown,
                onShow: tourShow,
                onEnd: tourEnd,
                onNext: tourNext,
                onPrev: tourPrev,
                steps: tourSteps(steps)
            });
        };

        /**
         * ------------------------------------------------------------------
         * Service
         * ------------------------------------------------------------------
         */

        /**
         * Initialize
         * Right before the start of the tour
         */
        this.init = function() {
            var defer = $q.defer();

            $http.get('/api/shelter/quick-help/')
                .then(function(response) {
                    var data = response.data;

                    // Update fake info & tour steps
                    angular.merge(fake, data.fake);
                    angular.copy(data.view, tours);

                    defer.resolve();
                }, function() {
                    Toast.push('warning',
                        $translate.instant('toast.title.warning'),
                        $translate.instant('toast.contents.help.not_available'));

                    defer.reject();
                });

            return defer.promise;
        };

        /**
         * Starts tour
         */
        this.start = function() {
            // Current route and view
            var currentRoute = $route.current,
                currentView = currentRoute.active;

            // Disable everywhere except on stream and plan
            if ('stream' !== currentView && 'plan' !== currentView) {
                Toast.push('warning',
                    $translate.instant('toast.title.warning'),
                    $translate.instant('toast.contents.help.not_available'));

                return;
            }

            // Set current view to streams if in the main page
            if (undefined === currentRoute.params.clientId
                && 'stream' === currentView) {
                currentView = 'streams';
            }

            // Add required classes
            $('body')
                .addClass('tour-enabled')
                .children('.tourbootstrap-overlay')
                .addClass('tourbootstrap-overlay--enabled');

            // Close any open Select2 component
            var openSelect2 = $('.select2-container-active').length;
            if (0 < openSelect2) {
                angular.element('.select2-container-active').scope().$select.close(true);
            }

            // Find real or create fake client
            var _client = findActiveClient();
            if (null !== _client) {
                state.client.real = true;
                client = _client;
            } else {
                client = createFakeClient();
            }

            // Find real message
            var _message = null;
            if (true === state.client.real) {
                _message = findActiveMessage(client.profile.id);
                if (null !== _message) {
                    state.message.real = true;
                    message = _message;
                }
            }

            // Create fake message
            if (null === _message) {
                message = createFakeMessage(client);
            }

            // Mark message as tour message
            message.tour = true;

            // Start tour
            var steps = tours[currentView],
                tour = initTour(steps);

            // Wait for the DOM to be ready
            $timeout(function() {
                tour.init().start(true);
            }, 200);
        };

        /**
         * Ends tour
         */
        this.stop = function() {
            tourEnd();
        };

        // Return the service
        return this;
    };

    QuickHelpService.$inject = [
        '$q',
        '$http',
        '$route',
        '$timeout',
        '$translate',
        'Toast',
        'Client',
        'Connections',
        'Messages',
        'State',
        'ShelterAPI'
    ];
    angular.module('app.services').service('QuickHelpService', QuickHelpService);
})();