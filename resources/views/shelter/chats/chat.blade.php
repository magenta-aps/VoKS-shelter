<div class="chat" ng-class="{'-active': client.state.chatOpen}">
    <div class="chat__wrapper" scroll-glue perfect-scrollbar>
        <div class="chat__conversation">
            <div class="chat__board">
                <div class="chat__item" ng-class="{
                '-user -triangle-tr': message.fromShelter,
                '-main -triangle-tl': !message.fromShelter
                }" ng-repeat="message in client.messages.list | orderBy: 'timestamp'">
                    <p class="chat__message"><% message.message %></p>
                    <div class="chat__time"><% message.timestamp | date:"h:mma" %></div>
                </div>
            </div>
        </div>
    </div>
    <div class="chat__input-block">
        <form ng-submit="content = client.sendChatMessage(content)" novalidate>
            <input type="text" class="chat__input-field" placeholder="{{ Lang::get('app.stream.placeholder') }}" ng-model="content" required />
        </form>
    </div>
</div>