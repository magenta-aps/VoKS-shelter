/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

(function() {
    'use strict';

    var rtcConnection = function(AVStream, SocketService) {
        var _e = function(e, m) {};
        var Connection = function(id, options, microphoneEnabled) {
            var self = this;

            this.id = id;

            this.streams = {
                local: microphoneEnabled ? AVStream.clone() : {},
                remote: null
            };

            this.options = {
                onStream: function() {},
                onClose: function() {}
            };

            this.options = angular.extend(this.options, options);

            this.connection = null;

            this.connect = function() {
                this.connection = new RTCPeerConnection(
                    config['ice-servers'],
                    config['media-constrains']
                );

                if (microphoneEnabled) {
                    this.connection.addStream(self.streams.local.getStream());
                }

                this.connection.onaddstream = function(event) {
                    self.streams.remote = event.stream;
                    self.options.onStream(event.stream);
                };

                this.connection.oniceconnectionstatechange = function() {
                    if ('disconnected' === self.connection.iceConnectionState
                        || 'failed' === self.connection.iceConnectionState) {
                        self.close();
                    }
                };

                this.connection.onremovestream = function() {
                    self.close();
                };

                this.connection.onclose = function() {
                    self.close();
                };

                this.connection.onicecandidate = function(e) {
                    if (!self.connection || !e || !e.candidate) {
                        return;
                    }

                    self.sendSDPData('CANDIDATE', e.candidate);
                };
            };

            this.connect();

            this.sendMessage = function(type, data) {
                SocketService.sendRaw({
                    type: type,
                    data: data,
                    dst: self.id,
                    src: config.id
                });
            };

            this.sendSDPData = function(type, data) {
                SocketService.sendRaw({
                    type: type,
                    payload: data,
                    dst: self.id,
                    src: config.id
                });
            };

            this.connectionActive = function() {
                if (!this.connection) {
                    return false;
                }

                return this.connection.signalingState !== 'closed';
            };

            this.close = function() {
                try {
                    if (typeof self.connection !== 'undefined') {
                        self.options.onClose();
                        self.connection.close();
                    }
                } catch (e) {
                    _e('Error closing connection:', e.message);
                }
            };

            this.getConnection = function() {
                return this.connection;
            };

            this.process = function(type, payload) {

                if (this.connection.signalingState === 'closed') {
                    this.connection = null;
                    this.connect();
                }

                switch (type) {
                    case 'CANDIDATE':
                        this.processIce(payload);
                        break;
                    case 'OFFER':
                        this.processOffer(payload);
                        break;
                    case 'ANSWER':
                        this.processAnswer(payload);
                        break;
                    default:
                        break;
                }
            };

            this.processOffer = function(offer) {

                this.connection.setRemoteDescription(new RTCSessionDescription(offer), function() {
                }, function(error) {
                    _e('set remote sdp', error);
                });


                this.connection.createAnswer(function(sdp) {
                    //set local SDP
                    self.connection.setLocalDescription(sdp, function() {

                    }, function(error) {
                        _e('set local sdp in create answer', error);
                    });

                    self.sendSDPData('ANSWER', sdp);
                }, function(error) {
                    _e('create answer', error);
                });
            };

            this.processAnswer = function(answer) {
                this.connection.setRemoteDescription(new RTCSessionDescription(answer), function() {
                }, function(error) {
                    _e('process answer', error);
                });
            };

            this.processIce = function(ice) {
                this.connection.addIceCandidate(new RTCIceCandidate(ice), function() {
                }, function(error) {
                    _e('add ice', error);
                });
            };

        };

        return Connection;
    };

    rtcConnection.$inject = ['AVStream', 'SocketService'];
    angular.module('app').factory('RTCConnection', rtcConnection);
})();