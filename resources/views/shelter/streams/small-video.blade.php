<div class="streams__video">
    <div class="chat__with">Chat with:</div>
    <div class="name-block">
        <span class="name-block__called"><span class="-nr" ng-show="client.state.calledPolice">112</span></span>
        <span class="name-block__title"><% client.profile.name %></span>
    </div>
    <div class="streams__video-box">
        <div class="streams__video-tag-block">
            <video class="streams__video-element" ng-src="<% client.stream.url | trusted %>" listen="client.state.muted" autoplay></video>
        </div>

        <span class="-time">
            <span ng-show="client.state.battery !== 0 && (client.profile.device === 'ios' || client.profile.device === 'android')" >
                Battery: <% client.state.battery %><br /><br />
            </span>

            <span ng-show="client.state.battery == 0 && (client.profile.device === 'ios' || client.profile.device === 'android')">
                Battery: Loading<br /><br />
            </span>

            <span time-ago="client.timestamps.connected"></span>
        </span>

        <div class="icons -minimize-maximize" ng-click="client.minimizeMaximize()">
            <span class="-icon"></span>
        </div>
    </div>
    <div class="streams__video-overlay"></div>
</div>
