<h3>Alarm has been triggered. You need to go to the crisis center.</h3>

<p>Device type: {{ $type }}</p>
<p>Device ID: {{ $device_id }}</p>
<p>Police called: <?php echo $call_police == 1 ? 'Yes' : 'No'; ?></p>
<p>Alarm triggered on: <?php echo date("Y-m-d H:i:s"); ?></p>
<pre><?php print_r($_SERVER); ?></pre>