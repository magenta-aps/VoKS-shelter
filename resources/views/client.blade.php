<!DOCTYPE html>
<html>
<head>
    <title><?php echo $_GET['user']; ?></title>
    <style type="text/css" media="screen">
        video {
            outline: 1px solid #000;
            max-width: 320px;
            max-height: 240px;
            display: inline-block;
        }
        .call {
            color: red;
        }
        .active {
            color: green;
        }
    </style>
</head>
<body>
<h1>Client -------------------- <a href="#" class="call" id="call">Call shelter</a></h1>
<button id="connect">Connect</button><br>
<video id="local-video" autoplay="autoplay"></video>

<div id="peer-videos">

</div>

<div id="messages"></div>

<div class="">
    <form method="post" id="form">
        <input type="text" id="message-input" placeholder="message.."/>
        <input type="submit" value="Send" />
    </form>
</div>

<a href="#" id="notify">Notify</a>
<a href="#" id="mac">Mac</a>

<script>var user = '<?php echo $_GET['user']; ?>_browser';</script>
<script>var shelterId = '<?php echo !empty($_GET['shelter_id']) ? $_GET['shelter_id'] : 1; ?>';</script>
<script>var ssl = false;</script>
<script src="/js/client/adapter.js"></script>
<script src="/js/client/jquery.js"></script>
<script src="/js/client/client.js"></script>
</body>
</html>