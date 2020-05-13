<!DOCTYPE html>
<head>
    <title>SkyLive 0.85 beta</title>
    <meta name="viewport" content="width=device-width, user-scalable=yes" />
    <meta charset="utf8"/>
    <link href="https://unpkg.com/video.js@6.7.1/dist/video-js.css" rel="stylesheet">
    <script src="https://unpkg.com/video.js@6.7.1/dist/video.js"></script>
    <script src="https://unpkg.com/@videojs/http-streaming@0.9.0/dist/videojs-http-streaming.js"></script>

    <style>
        body {
            background: #191d23; /* Old browsers */
            background: -moz-radial-gradient(center, ellipse cover,  #101a2c  0%, #000000 100%); /* FF3.6+ */
            background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,#101a2c ), color-stop(100%,#000000)); /* Chrome,Safari4+ */
            background: -webkit-radial-gradient(center, ellipse cover,  #101a2c  0%,#000000 100%); /* Chrome10+,Safari5.1+ */
            background: -o-radial-gradient(center, ellipse cover,  #101a2c  0%,#000000 100%); /* Opera 12+ */
            background: -ms-radial-gradient(center, ellipse cover,  #101a2c  0%,#000000 100%); /* IE10+ */
            background: radial-gradient(ellipse at center,  #101a2c  0%,#000000 100%); /* W3C */
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#101a2c ', endColorstr='#000000',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
            text-align:center;
            margin: 0;
            color: #FFF;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
        }
        video {
            width: 100%;
            display: inline-block;
        }

        #player2 {
            display: none;
        }

        #playerinfo {
            display: none;
        }

        b {
            font-size: 120%;
        }

        #button {
            margin-top: 5px;
            color: #FFF;
        }
        
        a[href='https://minnit.chat/SkyLive'] {
            display: none;
        }

        [class*="BT"]{width:250px;display:block;position:relative;padding:0;border-color:rgba(255,255,255,0.4);margin:0 0 10px;line-height:6px;border-style:solid;left:50%;margin-left:-125px;height:60px;}
        [class*="BT"] hover{position:absolute;z-index:5;width:246px;margin-left:-370px;  transition: all 0.3s ease-out 0s;    background: -moz-linear-gradient(45deg,  rgba(255,255,255,0) 0%, rgba(135,135,135,0.38) 50%, rgba(255,255,255,0) 100%); /* FF3.6+ */background: -webkit-gradient(linear, left bottom, right top, color-stop(0%,rgba(255,255,255,0)), color-stop(50%,rgba(135,135,135,0.38)), color-stop(100%,rgba(255,255,255,0))); /* Chrome,Safari4+ */background: -webkit-linear-gradient(45deg,  rgba(255,255,255,0) 0%,rgba(135,135,135,0.38) 50%,rgba(255,255,255,0) 100%); /* Chrome10+,Safari5.1+ */background: -o-linear-gradient(45deg,  rgba(255,255,255,0) 0%,rgba(135,135,135,0.38) 50%,rgba(255,255,255,0) 100%); /* Opera 11.10+ */background: -ms-linear-gradient(45deg,  rgba(255,255,255,0) 0%,rgba(135,135,135,0.38) 50%,rgba(255,255,255,0) 100%); /* IE10+ */background: linear-gradient(45deg,  rgba(255,255,255,0) 0%,rgba(135,135,135,0.38) 50%,rgba(255,255,255,0) 100%); /* W3C */filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00ffffff', endColorstr='#00ffffff',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */  height:60px;margin-top:-30px;}
        [class*="OH"]{overflow:hidden;}
        [class*="BR"]{border-width:2px;}
        [class*="R6"]{border-radius:6px;}
        [class*="NF"]{background:transparent;}
        [class*="BT"]:hover hover{  margin-left:123px;}
        [class*="TU"]{text-transform:uppercase;}
        [class*="PT"]{cursor:pointer;}
        [class*="BT"] span{  position:absolute;  width:200px;  margin-left:-100px;  z-index:3;}
        canvas{margin: 0;padding: 0;display:block;position:absolute;margin-top:-30px;}



        .player-container {
            width: 100%;
            display: inline-block
        }


        
        #portal-switch-text-long {
            display: none;
        }
        #portal-switch-text-short {
            display: block;
        }
        

        @media (min-width: 900px) {
            .player-container {
                width: 80%;
            }
            .chatmode #chat {
                width: 20% !important;
            }
            #portal-switch-text-long {
                display: block;
            }
            #portal-switch-text-short {
                display: none;
            }
        }

        #chat {
            display: none;
            border: none;
            height: 100vh;
        }

        /* CHAT MODE */
        .chatmode #chat {
            display: inline-block !important;
            width: 80%;
        }

        .logo {
            color: #FFF !important;
            font-size: 140%;
            text-decoration: none !important;
            font-weight: bold;
            padding: 20px;
            text-align: center;
            display: block;
        }

        .chatmode .logo, .chatmode #portal-switcher {
            display: none;
        }

        .noselect {
        -webkit-touch-callout: none; /* iOS Safari */
            -webkit-user-select: none; /* Safari */
            -khtml-user-select: none; /* Konqueror HTML */
            -moz-user-select: none; /* Old versions of Firefox */
                -ms-user-select: none; /* Internet Explorer/Edge */
                    user-select: none; /* Non-prefixed version, currently
                                        supported by Chrome, Opera and Firefox */
        }

        /* portal switch dropdown */
        #portal-switcher {
            padding: 20px;
            position: absolute;
            right: 0;
        }

        /*hide the inputs/checkmarks and submenu*/
        #portal-switcher input, #portal-switcher ul.submenu {
            display: none;
            position: absolute;
            z-index: 100;
            padding: 0px;
            margin: 0px;
            background: #101a2c;
            list-style-type: none;
            right: 5px;
        }

        #portal-switcher ul.submenu li {
            padding: 10px 0px;
        }

        #portal-switcher ul.submenu li:hover {
            background: #090e18;
        }

        #portal-switcher ul.submenu a {
            color: #FFF;
            text-decoration: none;
            padding: 10px 20px;
        }

        /*position the label*/
        #portal-switcher label {
            position: relative;
            display: block;
            cursor: pointer;
        }

        /*show the submenu when input is checked*/
        #portal-switcher input:checked~ul.submenu {
            display: block;
        }
    </style>
</head>
<body>
    <div class="player-container">
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
        <video-js id="my_video_1" class="vjs-default-skin vjs-16-9" controls preload="auto" width="70%">
            <source id="src" src="stream?streamid=<?= htmlspecialchars($_GET['stream']) ?>" type="application/x-mpegURL">
        </video-js>
    </div><!--
    --><iframe id="chat" src="https://minnit.chat/SkyLive?embed&&nickname=" allowTransparency="true"></iframe><br><a href="https://minnit.chat/SkyLive" target="_blank">HTML5 Chatroom powered by Minnit Chat</a>
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
                url = portal.link + window.location.pathname + window.location.search
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