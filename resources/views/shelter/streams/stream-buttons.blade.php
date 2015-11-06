<div class="icons -close-stream" ng-click="client.hideFromView()">
    <span class="-text" ng-show="!client.state.chatOpen">Close stream</span>
    <span class="-text" ng-show="client.state.chatOpen">Close chat</span>
    <span class="-icon"></span>
</div>
<div class="streams__buttons">
    <div class="streams__button icons -location" ng-class="{'-active': client.profile.id === client.state.global.selected.pin}" ng-click="client.centerInMap()">
        <span class="-icon"></span>
    </div>
    <div class="streams__button icons -volume" ng-class="{'-active': !client.state.muted}" ng-click="client.listen()">
        <span class="-icon"></span>
    </div>
    <div class="streams__button icons -messages" ng-class="{'-active': client.state.chatOpen}" ng-click="client.toggleChat()">
        <span class="-icon"><span class='-count' ng-show="client.messages.unread > 0"><% client.messages.unread %></span></span>
        <span class="-text">Messages</span>
    </div>
    <div class="streams__button icons -answer" ng-class="{
                '-active': client.state.calling,
                '-inactive': !client.state.calling,
                '-on-hold': client.state.calling && client.state.talking
                }" ng-click="client.talk()">
        <span class="-icon"></span>
        <span class="-text" ng-show="!client.state.calling || !client.state.talking">Answer</span>
        <span class="-text" ng-show="(client.state.calling && client.state.talking)">On hold</span>
    </div>
</div>