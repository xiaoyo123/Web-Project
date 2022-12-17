<!DOCTYPE html>
<html>

<head>
    <?php
    session_start();
    if (isset($_SESSION['username'])) 
        $player = $_SESSION['username'];
    else 
        header("location: login.php");
    if (isset($_GET['roomid'])) {
        $roomid = $_GET['roomid'];
        $db = new PDO('mysql: host=localhost; dbname=account', 'root', '801559');
        $sql = $db->prepare("SELECT player1, player2 FROM play WHERE roomid=?");
        $sql->execute(array($roomid));
        while($row = $sql->fetch(PDO::FETCH_OBJ) ){
            if ($row->player1 == "") 
                $UP = $db->exec("UPDATE play SET player1='$player' WHERE roomid=$roomid");
            else 
                $UP = $db->exec("UPDATE play SET player2='$player' WHERE roomid=$roomid");  
        }
    }
    ?> 
    <style>
        body {
            /* background-image: url("../background/war-6111531_960_7201.jpg"); */
            background-repeat: no-repeat;
            background-position: 50% 0%;
            background-size: 80%;
            margin: 0px;
            padding: 0px;
            border: none;
            user-select: none;
            /*position: relative;*/
        }
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
            pointer-events: none;
            left: 25%;
            background-color: aqua;
        }
        .hp {
            top: 0%;
            left: 0%;
            width: 100%;
            height: 10%;
            background-color: red;
        }
        .hands {
            width: 50%;
            height: 25%;
            left: 25%;
            position: absolute;
            bottom: 0%;
        }
        .card>img {
            width: 100%;
            height: auto;
            background-color: blue;
            pointer-events: none;
        }
        .card {
            position: relative;
            display: inline-block;
            width: 100px;
            height: 100px;
            background-image: url(../atk/2022_11_17_22_25_IMG_7586.PNG);
        }
        .on-desk {
            position: absolute;
            width: 20%;
        }
        .on-desk>img {
            width: 100%;
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
        }
        .mycard {
            position: absolute;
            top: 0%;
            left: 0%;
            width: 100%;
            height: 100%;
            z-index: 1;

        }
    </style>
    <script src="http://code.jquery.com/jquery-1.9.0rc1.js"></script>
    <script type="text/javascript">
        var move = false;
        var element;
        const now = [];
        var round, HP;
        var run = setInterval(function(){getCard()}, 5000);

        function getCard(){
            $.ajax({
                type: "POST",
                url: "getCardB.php",
                dataType: "json",
                data: {
                    rd: round
                },
                success: function(data){
                    console.log(data);
                },
                error: function(){
                    console.log("failed");
                }
            })
        }
        $(function () {
            var _x, _y;
            $(".card").mousedown(function (e) {
                element = $(this);
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
                console.log("click up", move);
                $(".moving").css({ display: "none" });
                //如果再桌子上
                var desk_x = $(".card-on-desk").offset().left;
                var desk_y = $(".card-on-desk").offset().top;
                var desk_w = $(".card-on-desk").width();
                var desk_h = $(".card-on-desk").height();
                if (element.offset().left > desk_x && element.offset().left < desk_x + desk_w && element.offset().top > desk_y && element.offset().top < desk_y + desk_h && move) {
                    now.push(element.children("img").attr("src"));
                    //把卡片放到桌子上 並疊在最上面
                    element.remove();
                    $(".mycard").append(element);
                    var card_x = ((Math.random() * 1000) % (50));
                    var card_y = ((Math.random() * 1000) % (10) + element.height() / 4);
                    var rotate = ((Math.random() * 1000) % (30) - 15);
                    //更改卡片類別
                    element.attr("class", "on-desk");
                    element.css({ "top": card_y, "left": card_x, "z-index": 1, "transform": "rotate(" + rotate + "deg)" });
                }
                else if (move) {
                    element.css({ "top": 0, "left": 0 });
                }
                move = false;
            })
            $(".card").dblclick(function () {
                var element = $(this);
                now.push(this.innerHTML.split('"')[1]);
                element.remove();
                $(".mycard").append(element);
                var card_x = ((Math.random() * 1000) % (50));
                var card_y = ((Math.random() * 1000) % (10) + element.height() / 4);
                var rotate = ((Math.random() * 1000) % (30) - 15);
                //更改卡片類別
                element.attr("class", "on-desk");
                element.css({ "top": card_y, "left": card_x, "z-index": 1, "transform": "rotate(" + rotate + "deg)" });
            });
        })
        function post(){
            console.log(now);
            $.ajax({
                type: "POST",
                url: "playB.php",
                dataType: "json",
                data: {
                    src: now,
                    rd: round,
                    HP: HP
                },
                success: function(data){
                    console.log(data);
                }
            })
        }
        function init(){
            round = 1;
            HP = 50;
            while(now.length > 0)
                now.pop();
        }
        window.addEventListener("load", init, "false");
    </script>
</head>

<body>

    <div class="rival" id="rival">
        <img src="">
        <div class="hp" id="rival-hp" name="rival-hp">50</div>
        <div class="rival-name" id="rival-name" name="rival-name">name</div>
    </div>
    <div class="ourside" id="ourside" name="ourside">
        <div class="name" id="name" name="name">name</div>
        <div class="hp" id="hp" name="hp">50</div>
        <img src="">
    </div>
    <div class="card-on-desk" id="card-on-desk" name="card-on-desk">
        <div class="rival-card" id="rival-card" name="rival-card"></div>
        <div class="mycard" id="mycard" name="mycard"></div>
        <div class="moving">移動到此處按下左鍵</div>
    </div>
    <div class="hands" id="hands" name="hands">
        <div class="card" id="card0">
            <img src="../atk/2022_11_17_22_25_IMG_7586.PNG">
        </div>
        <div class="card" id="card1">
            <img src="../atk/2022_11_18_15_18_IMG_7591.JPG">
        </div>
    </div>
    <input type="button" value="出牌" onclick="post()"></button>
</body>

</html>