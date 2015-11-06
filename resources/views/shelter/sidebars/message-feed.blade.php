<div class="side-block message-feed" ng-controller="MessageFeedController">
    <div class="side-block__title">
        <div class="side-block__text"> {{ Lang::get('message-feed.label') }} </div>
    </div>

    <div class="side-block__container">
        <div class="side-block__wrapper" scroll-glue perfect-scrollbar>
            <div class="message-feed__messages chat">
                <div class="message-feed__wrapper">

                    <div class="message-feed__unit -quick-help chat__item -main -triangle-tl" ng-repeat="message in messages | orderBy:'timestamp'" ng-class="{'tour': true === message.tour}">

                        <div class="chat__name name-block" ng-class="{'-inactive': !users[message.user.id]}">
                            <span class="name-block__called" ng-show="users[message.user.id].state.calledPolice || message.calledPolice"><span class="-nr">112</span></span>
                            <span class="name-block__title" ng-click="togglePopup(message)"><% message.user.name %></span>

                            <div class="popup -triangle-bc" ng-class="{'-active': message.popupOpen === true}">
                                <div class="popup__button icons -messages"
                                     ng-click="users[message.user.id].toggleChat()"
                                     ng-class="{'-active': users[message.user.id].state.chatOpen}">
                                    <span class="-icon"></span>
                                </div>
                                <div class="popup__button icons -volume"
                                     ng-click="users[message.user.id].listen()"
                                     ng-class="{'-active': !users[message.user.id].state.muted }">
                                    <span class="-icon"></span>
                                </div>
                                <div class="popup__button icons -video2"
                                     ng-click="users[message.user.id].placeIntoView()" ng-class="{'-active': users[message.user.id].position.inView}">
                                    <span class="-icon"></span>
                                </div>
                            </div>
                        </div>

                        <p class="chat__message"><% message.message %></p>
                        <div class="chat__time"><% message.timestamp | date:"h:mma" %></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>