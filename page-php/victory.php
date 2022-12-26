<!DOCTYPE html>
<!-- 玩家獲勝頁面 -->
<html>
<head>
    <title>歐勒の趟</title>
    <meta charset="utf-8">
    <title>Game Lobby</title>
    <style>
        body {
            background-color: #f0f0f0;
        }
        #container {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            background-color: #f0f0f0;
        }
        #container > div {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }
        #container > div > h1 {
            font-size: 3em;
            margin: 0;
        }
        #container > div > p {
            font-size: 1.5em;
            margin: 0;
        }
        #container > div > button {
            font-size: 1.5em;
            margin: 0;
        }
    </style>
</head>
<body>
    <div id="container">
        <div>
            <form action="lobby.php">
                <h1>Victory!</h1>
                <p>Click the button to start a new game.</p>
                <button id="newGame">New Game</button>
            </form>
        </div>
    </div>
    <!-- <script src="https://www.gstatic.com/firebasejs/4.1.3/firebase.js"></script> -->

    <script>
   
    </script>

</html>