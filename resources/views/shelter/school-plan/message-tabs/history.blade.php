<div class="tab_4 tabs__content -history">
    <div class="-history__content" perfect-scrollbar>
        <table cellpadding="0" cellspacing="0" border="1" class="-history">
            <thead>
            <tr>
                <th>Time</th>
                <th>Message</th>
                <th>Type</th>
                <th>Result</th>
            </tr>
            <tr class="-border">
                <th colspan="4"></th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="item in history">
                <td><% item.time %></td>
                <td><% item.message %></td>
                <td><% item.type %></td>
                <td>Got It<br /><strong><% item.result %></strong></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>