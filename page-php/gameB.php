<!DOCTYPE html>
<html>

<head>
    <title>歐勒の趟</title>
    <?php
    session_start();
    if (isset($_SESSION['username'])) 
        $player = $_SESSION['username'];
    else 
        header("location: login.php");
    ?> 
    <style>
        body {
            overflow: hidden;
        }

        /*total 高度最大化 左右置中*/

        .total {
            position: absolute;
            top: 0%;
            left: 0%;
            max-width: 960px;
            max-height: 639px;
            z-index: 1;
        }

        .background {
            position: absolute;
            pointer-events: none;
            height: 100%;
            top: 0%;
            left: 0%;
            background-color: rgba(0, 0, 0, 0.5);

        }
/*
        @media (max-width: 960px) {
            .total {
                height: 100%;
                width: 100%;
            }

            .background {
                width: 100%;
                height: auto;
            }
        }

        /*
        @media (max-height: 500px) {
            .background {
                width: 100%;
                height: auto;
            }
        }*/

        .rival {
            width: 10%;
            position: absolute;
            top: 10%;
            right: 10%;
            text-align: center;
        }

        .ourside {
            width: 10%;
            position: absolute;
            bottom: 10%;
            left: 10%;
            text-align: center;
        }

        .card-on-desk {
            width: 50%;
            height: 50%;
            position: absolute;
            bottom: 25%;
            left: 25%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .hp {
            top: 0%;
            left: 0%;
            width: 100%;
            height: 10%;
            background-color: red;
        }


        .hands {
            display: flex;
            flex-wrap: nowrap;
            width: 50%;
            height: 25%;
            left: 25%;
            position: absolute;
            bottom: 0%;
        }

        .card>img, .N>img {
            width: 100%;
            height: auto;
            background-color: blue;
            pointer-events: none;
        }

        .card, .N {
            box-sizing: border-box;
            position: relative;

            width: 12.5%;
            height: auto;
        }

        .on-desk {
            display: flex;
            flex-wrap: wrap;
            height: 50%;
            width: auto;
            position: relative;
        }

        .on-desk>img {
            height: 100%;
        }

        .moving {
            display: none;
            border: 5px;
            border-style: solid;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0%;
            left: 0%;
            pointer-events: none;
            box-sizing: border-box;
            color: #fff;
            z-index: 1;
        }

        .mycard {
            position: absolute;
            display: flex;
            flex-wrap: wrap;
            top: 0%;
            left: 0%;
            width: 50%;
            height: 100%;
            z-index: 2;
        }

        .rival-card {
            position: absolute;
            display: flex;
            flex-wrap: wrap;
            top: 0%;
            right: 0%;
            width: 50%;
            height: 100%;
            z-index: 2;
        }

        .post {
            position: absolute;
            top: 45%;
            left: 80%;
            width: 10%;
            height: 10%;
            z-index: 1;
            background-color: rgba(0, 0, 0, 0.5);
            text-align: center;
            color: rgb(255, 255, 255);
            line-height: 65px;
            font-size: 35px;
            cursor: pointer;
        }
        .surrender{
            position: absolute;
            top: 65%;
            left: 80%;
            width: 10%;
            height: 10%;
            z-index: 1;
            background-color: rgba(0, 0, 0, 0.5);
            text-align: center;
            color: #fff;
            line-height: 65px;
            font-size: 35px;
            cursor: pointer;
        }
        .atk{
            position: absolute;
            top: 85%;
            left: 80%;
            width: 10%;
            height: 10%;
            z-index: 1;
            background-color: rgba(0, 0, 0, 0.5);
            text-align: center;
            color: #fff;
            line-height: 65px;
            font-size: 35px;
        }
        .display {
            position: absolute;
            top: 10%;
            left: 0%;
            width: 25%;
            height: 25%;
            display: block;
            z-index: 2;
        }

        .display>img {
            width: 100%;
            height: auto;
        }

        .hp_block {
            background-color: #FF7D7D;
        }
    </style>
    <script src="http://code.jquery.com/jquery-1.9.0rc1.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script type="text/javascript">
        var move = false;
        var element;
        var nowx = 0, nowy = 0;
        $(function () {
            var _x, _y;
            $(".card").mousedown(function (e) {
                element = $(this);
                var className = element.attr("class");
                if (className != "card")
                    return;
                _x = e.pageX - parseInt($(this).css("left"));
                _y = e.pageY - parseInt($(this).css("top"));
                move = true;
            })
            $(document).mousemove(function (e) {
                if (move) {
                    var x = e.pageX - _x;
                    var y = e.pageY - _y;
                    $(element).css({ "top": y, "left": x });
                    $(".moving").css({ display: "block" });
                }
            }).mouseup(function () {
                // console.log("click up", move);
                $(".moving").css({ display: "none" });
                //如果再桌子上
                var desk_x = $(".card-on-desk").offset().left;
                var desk_y = $(".card-on-desk").offset().top;
                var desk_w = $(".card-on-desk").width();
                var desk_h = $(".card-on-desk").height();
                if (element.offset().left > desk_x && element.offset().left < desk_x + desk_w && element.offset().top > desk_y && element.offset().top < desk_y + desk_h && move) {
                    now.push(parseInt(element.children("img").attr("id")));
                    //把卡片放到桌子上 並疊在最上面
                    var tmp = element.clone();
                    element.css({ "top": 0, "left": 0 });
                    $(".mycard").append(tmp);
                    element.children("img").remove();
                    tmp.attr("class", "on-desk");
                    tmp.css({ "top": 0, "left": 0, "z-index": 1/*, "transform": "rotate(" + rotate + "deg)"*/ });
                    move = false;
                    var id = element.attr("id");
                    var hand = $("#hands");
                    for (var i = id.substr(4, 1); i <= 9; i++) {
                        var nowC = hand.children("#card" + i);
                        var next = hand.children("#card" + (parseInt(i) + 1));
                        nowC[0].innerHTML = next[0].innerHTML;
                        nowC[0].className = next[0].className;
                        nowC[0].type = next[0].type;
                        // console.log(nowC);
                    }
                    Padding();
                }
                else if (move) {
                    element.css({ "top": 0, "left": 0 });
                    move = false;
                }
            })
            $(".card").dblclick(function () {
                var element = $(this);
                var className = element.attr("class");
                if (className != "card")
                    return;
                now.push(parseInt(element.children("img").attr("id")));
                var tmp = element.clone();
                element.css({ "top": 0, "left": 0 });
                $(".mycard").append(tmp);
                element.children("img").remove();
                nowx = nowx + element.height() / 4;
                nowy = nowy + element.width() / 4;
                tmp.attr("class", "on-desk");
                tmp.css({ "top": 0, "left": 0, "z-index": 1/*, "transform": "rotate(" + rotate + "deg)"*/ });
                move = false;
                var id = element.attr("id");
                var hand = $("#hands");
                for (var i = id.substr(4, 1); i <= 9; i++) {
                    var nowC = hand.children("#card" + i);
                    var next = hand.children("#card" + (parseInt(i) + 1));
                    nowC[0].innerHTML = next[0].innerHTML;
                    nowC[0].className = next[0].className;
                    nowC[0].type = next[0].type;
                    // console.log(nowC);
                }
                Padding();
            });
            /*$(".card,.on-desk").mouseover(function () {
                var obj = $(this);
                var tmp = obj.clone();
                var img = tmp.children("img")[0];
                $(img).mouseover(function () {
                    $(".display").css({ "display": "none" });
                })
                tmp.css({ "display": "none" });
                $(".display").html("");
                $(".display").append(img);
                $(".display").css({ "display": "block" });
            })
            $(".card,.on-desk").mouseout(function () {
                $(".display").css({ "display": "none" });
            })*/
            $(".rival-card, .mycard, .hands").delegate(".card, .on-desk, .N", "mouseover", function () {
                // console.log("mouseover");
                var obj = $(this);
                var tmp = obj.clone();
                var img = tmp.children("img")[0];
                $(img).mouseover(function () {
                    $(".display").css({ "display": "none" });
                })
                tmp.css({ "display": "none" });
                $(".display").html("");
                $(".display").append(img);
                $(".display").css({ "display": "block" });
            })
            $(".rival-card, .mycard, .hands").delegate(".card, .on-desk, .N", "mouseout", function () {
                $(".display").css({ "display": "none" });
            })
            $(".post").mouseover(function () {
                $(this).css({ "width": "12%", "height": "12%", "top": "44%", "left": "79%" });
            }).mouseout(function () {
                $(this).css({ "width": "10%", "height": "10%", "top": "45%", "left": "80%" });
            }).mousedown(function () {
                $(this).css({ "width": "12%", "height": "12%", "top": "44%", "left": "79%", "background-color": "rgba(0,0,0,1)" });
            }).mouseup(function () {
                $(this).css({ "width": "12%", "height": "12%", "top": "44%", "left": "79%", "background-color": "rgba(0,0,0,0.5)" });
            })
            $(".surrender").mouseover(function () {
                $(this).css({ "width": "12%", "height": "12%", "top": "64%", "left": "79%" });
            }).mouseout(function () {
                $(this).css({ "width": "10%", "height": "10%", "top": "65%", "left": "80%" });
            }).mousedown(function () {
                $(this).css({ "width": "12%", "height": "12%", "top": "64%", "left": "79%", "background-color": "rgba(0,0,0,1)" });
            }).mouseup(function () {
                $(this).css({ "width": "12%", "height": "12%", "top": "64%", "left": "79%", "background-color": "rgba(0,0,0,0.5)" });
            })
        })

        var now = [];
        var round, HP, atk;
        var run;
        function Padding(){
            if(atk == 1){
                for(var i = 0; i < 8; i++){
                    var nowC = document.getElementById("card" + i);
                    if(nowC.type == 2){
                        nowC.className = "N";
                    }
                    else{
                        nowC.className = "card";
                    }
                }
            }
            else if(atk == 0){
                for(var i = 0; i < 8; i++){
                    var nowC = document.getElementById("card" + i);
                    if(nowC.type == 1)
                        nowC.className = "N";
                    else
                        nowC.className = "card";
                }
            }
        }
        function init(){
            round = 1;
            HP = 50;
            while(now.length > 0)
                now.pop();
            $.ajax({
                type: "POST",
                url: "start.php",
                dataType: "json",
                success: function(data){
                    atk = (data[0]) ^ 1;
                    if(atk == 1)
                        document.getElementById("atk").innerHTML = "攻方";
                    else{
                        document.getElementById("atk").innerHTML = "防方";
                        run = setInterval(function(){getCard()}, 5000);
                    }
                    for(var i = 0; i < data.length - 1; i++){
                        var nowC = document.getElementById("card" + i);
                        nowC.type = data[i + 1][2];
                        // console.log(now.type);
                        nowC.innerHTML = "<img id='"+ data[i + 1][0] + "' src='" + data[i + 1][1] + "'>";
                    }
                    Padding();
                }
            })
            
        }
        
        function Check_Round(){
            $.ajax({
                type: "POST",
                url: "solve.php",
                dataType: "json",
                data: {
                    rd: round
                },
                success: function(data){
                    round++;
                    document.getElementById("mycard").innerHTML = "";
                    document.getElementById("rival-card").innerHTML = "";
                    while(now.length > 0)
                        now.pop();
                    
                    var cnt = 0;
                    var id = 1;
                    for(var i = 0; i < 8; i++){
                        var nowC = document.getElementById("card" + i);
                        if(nowC.innerHTML == "" && (cnt < 4 || id == 1)){
                            nowC.type = data[id][2];
                            nowC.innerHTML = "<img id='"+ data[id][0] + "' src='" + data[id][1] + "'>";
                            id++, cnt++;
                        }
                        else cnt++;
                    }
                    // console.log("rival",data[5][0]);
                    // console.log(data[5][1]);
                    document.getElementById("hp").innerHTML = data[5][1];
                    document.getElementById("rival-hp").innerHTML = data[5][0];
                    $("#hp").css("width", parseInt(data[5][1]) * 2 + "%");
                    $("#rival-hp").css("width", parseInt(data[5][0]) * 2 + "%");
                    if(data[5][1] <= 0){
                        setTimeout(function(){
                            $.ajax({
                                type: "POST",
                                url: "init.php",
                                dataType: "json",
                                success: function(data){
                                    window.location.href = "defeat.php";
                                }
                            })
                        }, 3000);
                    }
                    if(data[5][0] <= 0){
                        setTimeout(function(){
                            $.ajax({
                                type: "POST",
                                url: "init.php",
                                dataType: "json",
                                success: function(data){
                                    window.location.href = "victory.php";
                                }
                            })
                        }, 3000);
                    }
                    atk = atk ^ 1;
                    if(atk == 1){
                        document.getElementById("atk").innerHTML = "攻方";
                    }
                    else{
                        document.getElementById("atk").innerHTML = "防方";
                        run = setInterval(function() { getCard()}, 5000);
                    }
                    document.getElementById("rival-eff").innerHTML = data[5][2];
                    document.getElementById("eff").innerHTML = data[5][3];
                    Padding();
                },
                error: function(jqXHR){
                    // console.log(0);
                }
            })
        }

        function getCard(){
            $.ajax({
                type: "POST",
                url: "getCardB.php",
                dataType: "json",
                data: {
                    rd: round
                },
                success: function(data){
                    // console.log(round, data);
                    var rival = document.getElementById("rival-card");
                    if(data[0][0] == 1){
                        rival.innerHTML = "<h1>PASS!</h1>";
                        clearInterval(run);
                        if(atk == 1)
                            setTimeout(function() {Check_Round()}, 3000);
                    }
                    else if(data[0][1] == 1){
                        rival.innerHTML = "<h1>對手投降!</h1>";
                        clearInterval(run);
                        setTimeout(function() {
                            $.ajax({
                                type: "POST",
                                url: "init.php",
                                dataType: "json",
                                success: function(data){
                                    window.location.href = "victory.php";
                                }
                            })
                        }, 3000);
                    }
                    else{
                        for(var i = 1; i < data.length; i++){
                            rival.innerHTML += "<div class='on-desk'><img id='"+ data[i][0] + "' src='" + data[i][1] + "'></div>";
                        }
                        if(data.length > 1){
                            clearInterval(run); 
                            if(atk == 1){
                                setTimeout(function() { Check_Round()}, 3000);
                            }
                        }
                    }
                },
                error: function(){
                    // console.log("failed");
                }
            })
        }
        
        function post(){
            if(now.length == 0) now.push(0);
            $.ajax({
                type: "POST",
                url: "playB.php",
                dataType: "json",
                data: {
                    id: now,
                    rd: round,
                    HP: HP,
                    atk: atk,
                    len: now.length
                },
                success: function(data){
                    if(atk == 1)
                        run = setInterval(function(){getCard()}, 5000);
                    else{
                        setTimeout(function (){ Check_Round() }, 3000);
                    }
                },
                error: function(jqXHR){
                    // console.log("failed");
                }
            })
        }
        
        function surrender(){
            $.ajax({
                type: "POST",
                url: "Bsurrender.php",
                dataType: "json",
                data: {
                    rd: round,
                },
                success: function(data){
                    window.location.href = "defeat.php";
                }
            })
        }
        setInterval(function () {
            var wh = $(window).height();
            var ww = $(window).width();
            var th = $(".total").height();
            var tw = $(".total").width();
            if (ww / wh > (960 / 639)) {
                $(".total").css({ "width": wh * (960 / 639) });
                $(".total").css({ "height": wh });
            }
            else {
                $(".total").css({ "width": ww });
                $(".total").css({ "height": ww * (639 / 960) });
            }

            //$(".total").css({ "height": tw * (639 / 960) });
            var bh = $(".post").height();
            var bh1 = $(".surrender").height();
            var bh2 = $(".atk").height();
            $(".post").css({ "line-height": bh + "px", "font-size": bh - 40 * ((639 / 960)) + "px", "color": "#fff" });
            $(".surrender ").css({ "line-height": bh1 + "px", "font-size": bh1 - 40 * ((639 / 960)) + "px", "color": "#fff" });
            $(".atk").css({ "line-height": bh2 + "px", "font-size": bh2 - 40 * ((639 / 960)) + "px", "color": "#fff"  });
            var deskchild = $(".mycard").children();
            if (deskchild.length > 3) {
                deskchild.css({
                    "width": "33%"
                });
            }
            var deskchild2 = $(".rival-card").children();
            if (deskchild2.length > 3) {
                deskchild2.css({
                    "width": "33%"
                });
            }
            //sh-40*($(".total").height() / 4- 20)/100 
            $(".hp").css({ "font-size": $(".hp").height() * 0.8 + "px" });
            $(".rival-name").css({ "font-size": $(".rival-name").height() * 0.8 + "px" });
            $(".name").css({ "font-size": $(".name").height() * 0.8 + "px" });
            $(".eff").css({ "font-size": $(".name").height() * 0.8 + "px" });
        }, 10);

         var t1 = 3e3;//如果是轮询，这个时间必须大于音频的长度。如果是webscoket，应该设置一个状态play，避免重复播放，如下：
        var play = false;
        function PLAYBGM(){
            var audio = document.getElementById('bgm');
            if(play){
                return false;
            }
            audio.currentTime = 0;//设置播放的音频的起始时间
            audio.volume = 0.5;//设置音频的声音大小
            audio.muted = false;//关闭静音状态
            play = true;

        }
        setInterval(function(){
            PLAYBGM();//假装在轮询服务器，或者从websocket拉取数据
            console.log(play);
        },t1);
    </script>
</head>

<body onload = init()>
    <div class="total">

        <img class="background" src="../background/war-6111531_960_7201.jpg">
        <div class="display"></div>
        <div class="rival" id="rival">
            <img src="">
            <div class="hp_block">
                <div class="hp" id="rival-hp" name="rival-hp">50</div>
            </div>
            <div class="rival-name" id="rival-name" name="rival-name">name</div>
            <!-- 效果在這 -->
            <div class="eff" id="rival-eff" name="eff">0</div>
        </div>
        <div class="ourside" id="ourside" name="ourside">
            <!-- 效果在這 -->
            <div class="eff" id="eff" name="eff">0</div>
            <div class="name" id="name" name="name">name</div>
            <div class="hp_block">
                <div class="hp" id="hp" name="hp">50</div>
            </div>
            <img src="">
        </div>
        <div class="card-on-desk" id="card-on-desk" name="card-on-desk">
            <div class="rival-card" id="rival-card" name="rival-card"></div>
            <div class="mycard" id="mycard" name="mycard"></div>
            <div class="moving">移動到此處按下左鍵</div>
        </div>
        <div class="hands" id="hands" name="hands">
            <div class="card" id="card0"></div>
            <div class="card" id="card1"></div>
            <div class="card" id="card2"></div>
            <div class="card" id="card3"></div>
            <div class="card" id="card4"></div>
            <div class="card" id="card5"></div>
            <div class="card" id="card6"></div>
            <div class="card" id="card7"></div>
        </div>
        <div class="post" onclick="post()">出牌</div>
        <div class="atk" id="atk"></div>
        <div class="surrender" onclick="surrender()">投降</div>
    </div>
    <audio id="bgm"  muted autoplay loop>
            <source src="BGM.mp3" type="audio/mpeg">
            Your browser does not support the audio element.
    </audio>
</body>

</html>