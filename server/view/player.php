<!DOCTYPE html>
<head>
    <title>SkyLive 0.85 beta</title>
    <meta name="viewport" content="width=device-width, user-scalable=yes" />
    <meta charset="utf8"/>
    <link href="https://unpkg.com/video.js@6.7.1/dist/video-js.css" rel="stylesheet">
    <script src="https://unpkg.com/video.js@6.7.1/dist/video.js"></script>
    <script src="https://unpkg.com/@videojs/http-streaming@0.9.0/dist/videojs-http-streaming.js"></script>

</head>
<body class="player">
    <div id="portal-switcher">
        <input id="check01" type="checkbox" name="menu" />
        <label for="check01" class="noselect" style="opacity: 0.7;">
            <span id="portal-switch-text-long">Change Skynet portal server ▼</span>
            <span id="portal-switch-text-short">Portal ▼</span>
        </label>
        <ul class="submenu" id="portal_list">
            <li id="loading_portals"><a href="#">Loading portals...</a></li>
        </ul>
    </div>
    <a href="<?= URL ?>" class="logo">SkyLive</a>
    <div class="player-container">
        <video-js id="my_video_1" class="vjs-default-skin vjs-16-9" controls preload="auto" width="70%">
            <source id="src" src="stream.m3u8?streamid=<?= htmlspecialchars($_GET['s']) ?>&portal=<?= $portal ?>" type="application/x-mpegURL">
        </video-js>
    </div><!--
--><div class="minnit-chat-container"><!--
    --><iframe id="chat" src="https://minnit.chat/SkyLive?embed&&nickname=" allowTransparency="true"></iframe><br><a href="https://minnit.chat/SkyLive" target="_blank">HTML5 Chatroom powered by Minnit Chat</a>
    </div>
    <script>
        var urlParams = new URLSearchParams(window.location.search);

        if (urlParams.has('stream') && urlParams.has('stream') != '') {
            var streamid = urlParams.get('stream'); // "edit"
        } else {
            var streamid = 'skylive'
        }
        if (urlParams.has('portal') && urlParams.has('portal') != '') {
            var portal = urlParams.get('portal'); // "edit"
        } else {
            var portal   = window.location.origin;
        }
        
        if (portal == null || portal == 'https://skylive.local' || portal == 'http://skylive.local' || portal == 'https://localhost' || portal == 'http://localhost') {
            portal = 'https://siasky.net'
        }
        console.log('Using portal: ' + portal)
        // $('#src').attr("src", "<?= URL ?>stream.php?portal=" + portal + "&streamid=" + streamid)


        var overrideNative = true;

        var player = videojs('my_video_1', {
            html5: {
                hls: {
                overrideNative: overrideNative
                },
                nativeVideoTracks: !overrideNative,
                nativeAudioTracks: !overrideNative,
                nativeTextTracks: !overrideNative,
                autoplay: true,
                muted: true
            }
        });
    </script>
        

    <button onclick="switchMode()" class="BT-OH-BR-R6-NF-FH-FP-PT" id="button">
        <canvas id="canvas"></canvas> 
        <hover></hover>
        <span>Toggle chat</span>
    </button>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

    <script>

    chatmode = 0

    function switchMode() {
        if (chatmode == 0) {
            $('body').addClass('chatmode')
            chatmode = 1
        } else {
            $('body').removeClass('chatmode')
            chatmode = 0
        }
    }

    // portal list
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("loading_portals").remove()
            let portals = JSON.parse(this.responseText);
            portals.forEach(portal => {
                url = "/player?s=<?= htmlspecialchars($_GET['s']) ?>&portal=" + portal.link
                document.getElementById("portal_list").innerHTML += '<li><a href="' + url + '">' + portal.name + '</a></li>'
            });
        }
    };
    xhttp.open("GET", "https://siastats.info/dbs/skynet_current.json", true);
    xhttp.send();


    // Chat button
        (function () {
    var lastTime = 0;
    var vendors = ['ms', 'moz', 'webkit', 'o'];
    for (var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
        window.requestAnimationFrame = window[vendors[x] + 'RequestAnimationFrame'];
        window.cancelAnimationFrame = window[vendors[x] + 'CancelAnimationFrame'] ||
        window[vendors[x] + 'CancelRequestAnimationFrame'];
    }

    if (!window.requestAnimationFrame)
    window.requestAnimationFrame = function (callback, element) {
        var currTime = new Date().getTime();
        var timeToCall = Math.max(0, 16 - (currTime - lastTime));
        var id = window.setTimeout(function () {callback(currTime + timeToCall);},
        timeToCall);
        lastTime = currTime + timeToCall;
        return id;
    };

    if (!window.cancelAnimationFrame)
    window.cancelAnimationFrame = function (id) {
        clearTimeout(id);
    };
    })();


    (function () {

    // Get the buttons.
    var startBtn = document.getElementById('button');
    /*var resetBtn = document.getElementById('resetBtn');*/
    // A variable to store the requestID.
    var requestID;
    // Canvas
    var canvas = document.getElementById('canvas');
    // 2d Drawing Context.
    var ctx = canvas.getContext('2d');

    // Variables to for the drawing position and object.
    var posX = 0;
    var W = 246;
    var H = 60;
    var circles = [];

    //Get canvas size
    canvas.width = 246;
    canvas.height = 60;

    // Animate.
    function animate() {
        requestID = requestAnimationFrame(animate);
        //Fill canvas with black color
        //ctx.globalCompositeOperation = "source-over";
        ctx.fillStyle = "rgba(0,0,0,0.15)";
        ctx.fillRect(0, 0, W, H);

        //Fill the canvas with circles
        for (var j = 0; j < circles.length; j++) {
        var c = circles[j];

        //Create the circles
        ctx.beginPath();
        ctx.arc(c.x, c.y, c.radius, 0, Math.PI * 2, false);
        ctx.fillStyle = "rgba(" + c.r + ", " + c.g + ", " + c.b + ", 0.5)";
        ctx.fill();

        c.x += c.vx;
        c.y += c.vy;
        c.radius -= .02;

        if (c.radius < 0)
        circles[j] = new create();
        }



    }

    //Random Circles creator
    function create() {

        //Place the circles at the center

        this.x = W / 2;
        this.y = H / 2;


        //Random radius between 2 and 6
        this.radius = 2 + Math.random() * 3;

        //Random velocities
        this.vx = -5 + Math.random() * 10;
        this.vy = -5 + Math.random() * 10;

        //Random colors
        this.r = Math.round(Math.random()) * 255;
        this.g = Math.round(Math.random()) * 255;
        this.b = Math.round(Math.random()) * 255;
    }

    for (var i = 0; i < 500; i++) {
        circles.push(new create());
    }

    // Event listener for the start button.
    startBtn.addEventListener('mouseover', function (e) {
        e.preventDefault();

        // Start the animation.
        requestID = requestAnimationFrame(animate);
    });


    // Event listener for the stop button.
    startBtn.addEventListener('mouseout', function (e) {
        e.preventDefault();

        // Stop the animation;
        cancelAnimationFrame(requestID);

        e.preventDefault();

        // Reset the X position to 0.
        posX = 0;

        // Clear the canvas.
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Draw the initial box on the canvas.
        // ctx.fillRect(posX, 0, canvas.width, canvas.height);

    });

    })();

    </script>
</body>