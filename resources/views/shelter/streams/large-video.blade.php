<div class="streams__video">
    <div class="chat__with">Chat with:</div>
    <div class="name-block">
        <span class="name-block__called"><span class="-nr" ng-show="client.state.calledPolice">112</span></span>
        <span class="name-block__title"><% client.profile.name %></span>
    </div>
    <div class="streams__video-box">
        <div class="streams__video-tag-block">
            <video class="streams__video-element" active="client.state.inLargeView" ng-src="<% client.stream.url | trusted %>" listen="client.state.muted" autoplay></video>
        </div>
        <span class="-time" time-ago="client.timestamps.connected"></span>

        <div class="button -minimizeMaximize" ng-click="client.minimizeMaximize()">
            <div class="icons -minimize-maximize">
                <span class="-icon"></span>
            </div>
            <span class="button__text">Minimize</span>
        </div>
    </div>
    <div class="streams__video-overlay"></div>
</div>