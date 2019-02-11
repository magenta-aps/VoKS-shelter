/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var clientFactory = function($rootScope, $location, SocketService, Messages, ShelterAPI, State) {
        var Client = function(id) {
            var self = this;

            /**
             * Don't forget to clear localStorage after making in-code changes
             */

            this.profile = {
                id: id,
                type: 'pin',
                device: 'desktop',
                name: '',
                ip: '',
                gcmId: '',
                mac_address: ''
            };

            this.position = {
                x: 300 + Math.floor(Math.random() * 20) + 1,
                y: 300 + Math.floor(Math.random() * 20) + 1,
                inView: false,
                inLargeView: false,
                inQueue: false,
                index: 0,
                selected: false
            };

            this.state = {
                battery: 0,
                chatOpen: false,
                calling: false,
                talking: false,
                muted: false,
                calledPolice: false,
                highlightedInMap: false,
                global: State,
                canReceiveAudio: true,
                rtcActive: false,
                focused: false
            };

            this.timestamps = {
                triggeredAlarm: null,
                connected: null,//Date.now(),
                lastActive: Date.now()
            };

            this.stream = {
                url: '',
                object: {},
                shelter: {}
            };

            this.messages = {
                list: [],
                queue: [],
                unread: 0
            };

            this.connection = null;
            this.timestamp = null;

            self.preload();
        };

        Client.prototype.updateActivity = function() {
            this.timestamps.lastActive = Date.now();
        };

        /**
         *
         * @param position
         */
        Client.prototype.setPosition = function(position) {
            this.position = angular.extend(this.position, position);
        };

        /**
         *
         * @param profile
         */
        Client.prototype.setProfile = function(profile) {
            this.profile = angular.extend(this.profile, profile);
        };

        /**
         * Set RTC Connection linked to this client
         *
         * @param connection
         */
        Client.prototype.setConnection = function(connection) {
            connection.clientId = this.profile.id;
            this.connection = connection;
        };

        /**
         * Get RTC Connection linked to this client
         *
         */
        Client.prototype.getConnection = function() {
            return this.connection;
        };

        /**
         *
         */
        Client.prototype.createConnection = function() {
            var connection = this.getConnection();
            if (connection) {
                if (!connection.connectionActive()) {
                    this.sendMessage('PEER_CONNECTION', 1);
                }
            } else {
                this.sendMessage('PEER_CONNECTION', 1);
            }
        };

        Client.prototype.closeRtcConnection = function() {
            try {
                var connection = this.getConnection();
                connection.streams.local.pause();

                if (connection.connectionActive()) {
                    connection.close();
                }
            } catch (e) {

            }
        };

        /**
         *
         */
        Client.prototype.closeConnection = function() {
            this.sendMessage('PEER_CONNECTION', 0);
            this.sendMessage('VIDEO', 0);

            this.state.muted = true;
            this.state.talking = false;

            this.closeRtcConnection();
        };

        /**
         * Set the MediaStream object
         *
         * @param MediaStream stream
         */
        Client.prototype.setStream = function(stream) {
            this.stream.object = stream;
            this.stream.url = URL.createObjectURL(stream);

            //initially pause outgoing stream
            if (this.state.microphoneEnabled) {
                this.getConnection().streams.local.pause();
            }
        };

        /**
         * Mark unread messages
         *
         */
        Client.prototype.markUnread = function() {
            this.messages.unread++;
            this.save();
        };

        /**
         * Mark messages as read
         *
         */
        Client.prototype.markRead = function() {
            this.state.focused = true;
            this.messages.unread = 0;
            this.save();
        };

        /**
         * Blur
         */
        Client.prototype.blur = function() {
            this.state.focused = false;
        };


        /**
         * Send message through DataChannel
         *
         * @param type
         * @param data
         */
        Client.prototype.sendMessage = function(type, data) {
            SocketService.send(type, this.profile.id, config.id, data);
        };

        /**
         * Send chat message through DataChannel
         *
         * @param type
         * @param data
         * @return string message
         */
        Client.prototype.sendChatMessage = function(message) {
            if (message.length > 0) {
                this.sendMessage('MESSAGE', message);

                var model = Messages.createMessage({
                    id: this.profile.id,
                    isShelter: true,
                    message: message
                });

                this.messages.list.push(angular.copy(model));

                //push into localStorage and message queue
                Messages.pushShelterMessage(angular.copy(model));

                message = '';

                this.save();
            }

            return message;
        };

        /**
         * Open/Close chat
         *
         */
        Client.prototype.toggleChat = function() {
            this.state.chatOpen = !this.state.chatOpen;

            if (this.state.chatOpen) {
                this.markRead();
            }

            this.save();
        };

        /**
         *
         * @param permissions
         */
        Client.prototype.canReceiveAudio = function(permissions) {
            this.state.canReceiveAudio = permissions;
        };

        /**
         * Set initial position on connection
         *
         * @returns {client}
         */
        Client.prototype.setInitialPosition = function() {
            var position = State.getFreeStreamPosition(),
                inView = true;

            if (!position) {
                inView = false;
                position = 0;
            } else {
                State.addStream(position);
            }

            this.position.index = position;
            this.position.inView = inView;
            this.position.inQueue = !inView;

            if (inView) {
                this.sendMessage('PEER_CONNECTION', 1);
                this.sendMessage('VIDEO', 1);
            } else {
                this.state.muted = true;
                this.state.calling = false;
                this.state.talking = false;
            }

            return this;
        };

        /**
         *
         * @param position
         */
        Client.prototype.placeIntoView = function(position) {
            if (!State.streamsHaveRoom() || this.position.index) {
                return;
            }

            //if moving from waiting line, then autopick position
            if (!position) {
                position = State.getFreeStreamPosition();
            }

            State.addStream(position);

            this.position.index = position;
            this.position.inView = true;
            this.position.inQueue = false;

            // Selected (bigger) pin
            if (null === State.selected.marker) {
                State.selected.marker = this.profile.id;
            }

            if (State.isFirstStream()) {
                this.state.muted = false;
            }

            this.sendMessage('PEER_CONNECTION', 1);
            this.sendMessage('VIDEO', 1);

            this.save();
        };

        /**
         * Close the chat or remove client from view
         *
         */
        Client.prototype.hideFromView = function() {
            if (!this.state.chatOpen) {
                var position = this.position.index;

                if (this.position.inLargeView) {
                    State.closeSingleStream();
                    $location.path('/stream').replace();
                }

                this.position.index = 0;
                this.position.inView = false;
                this.position.inQueue = true;

                this.position.inLargeView = false;

                // Selected (bigger) pin
                if (this.profile.id === State.selected.marker) {
                    State.selected.marker = null;
                }

                State.removeStream(position);

                if (this.state.muted && !this.state.talking) {
                    this.closeConnection();
                } else {
                    this.sendMessage('VIDEO', 0);
                }
            } else {
                this.state.chatOpen = false;
            }

            this.save();
        };

        /**
         * Listen/Stop listening to the client
         *
         */
        Client.prototype.listen = function() {
            this.state.muted = !this.state.muted;

            //if video is in stream view then don't drop the connection
            if (this.position.inView) {
                var connection = this.getConnection();
                if (!connection || !connection.connectionActive()) {
                    this.sendMessage('PEER_CONNECTION', 1);
                }

                this.save();
                return;
            }

            // if shelter is listening and RTC Connection isn't active
            // should create the connection
            if (!this.state.muted) {
                this.createConnection();
            } else {
                // if client isn't talking or a call isn't being made
                // close the connection
                if (!(this.state.talking || this.state.calling)) {
                    this.closeConnection();
                }
            }

            this.save();
        };

        /**
         * Open/Close single stream view
         */
        Client.prototype.minimizeMaximize = function() {
            this.position.inLargeView = !this.position.inLargeView;
            if (this.position.inLargeView) {
                State.openSingleSream(this.profile.id);
            } else {
                State.closeSingleStream();
            }

            this.save();
        };

        Client.prototype.maximize = function() {
            State.openSingleStream(this.profile.id);
            this.position.inLargeView = true;
            this.save();
        };

        Client.prototype.minimize = function() {
            State.closeSingleStream();
            this.position.inLargeView = false;
            this.save();
        };

        /**
         * Answer/Hangup on client
         */
        Client.prototype.talk = function() {
            if (this.state.calling && this.state.canReceiveAudio) {
                if (this.state.talking) {
                    this.sendMessage('LISTENING', 0);
                    this.getConnection().streams.local.pause();
                    this.state.talking = false;
                } else {
                    this.sendMessage('LISTENING', 1);
                    this.getConnection().streams.local.resume();
                    this.state.talking = true;
                    this.state.muted = false;
                    this.createConnection();
                }
            }

            this.save();
        };

        Client.prototype.save = function() {
            localStorage.setItem('client_' + this.profile.id, angular.toJson(this));
        };

        /**
         * Loads client profile from local storage
         */
        Client.prototype.preload = function() {
            var client = JSON.parse(localStorage.getItem('client_' + this.profile.id));
            if (null !== client) {
                var fields = ['state', 'messages'],
                    i = 0, l = fields.length;

                for (i; i < l; i ++) {
                    var field = fields[i];
                    this[field] = angular.merge({}, this[field], client[field]);
                }
            }
        };

        /**
         * Called when the connection gets opened
         */
        Client.prototype.init = function() {
            var self = this;

            if(self.position.index > 0 && self.position.inView) {
                return;
            }

            // we need to preload the messages before the save() method call
            self.preload();

            //sets the connection start timestamp for the count up timer
            if (!self.timestamps.connected) {
                self.timestamps.connected = Date.now();
                self.save();
            }

            self.setInitialPosition();

            this.sendMessage('CALL_STATE', this.state.talking ? 1 : 0);

            self.state.muted = !State.isFirstStream();

            self.state.calling = false;
            self.state.talking = false;
            self.state.canReceiveAudio = true;

            State.hadStreams = true;
        };

        /**
         * Update client data
         * @returns {Promise}
         */
        Client.prototype.update = function(client) {
            this.setProfile(client.profile);
            this.setPosition(client.position);

            return this;
        };

        /**
         * Called when the connection gets closed
         *
         */
        Client.prototype.destroy = function() {
            if (this.position.inLargeView) {
                State.closeSingleStream();
            }

            // need to close RTC connection
            this.closeRtcConnection();

            State.removeStream(this.position.index);

            this.profile.type = 'pin';

            this.position.index = 0;
            this.position.inView = false;
            this.position.inQueue = false;
            this.position.inLargeView = false;

            this.save();
        };

        return Client;
    };

    clientFactory.$inject = ['$rootScope', '$location', 'SocketService', 'Messages', 'ShelterAPI', 'State'];
    angular.module('app').factory('Client', clientFactory);
})();