<!DOCTYPE html>
<html>
    <head>
        <title>Mobitva 2.0</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/style/disconnect.css?136.667677757" type="text/css">
        <script src="javascript/jquery-3.3.1.min.js"></script>
    </head>
    <center><h4><b style="color: #767676;">Соединение с сервером прервано</b></h4> <span style="color: #767676;">Переподсоединиться?</span></center>

    <style>
        body{
            font-family:Tahoma,Arial;
            font-size:16px ;
            margin:20px auto;
            padding-bottom:50px ;
            max-width:320px ;
            min-width:128px ;
        }
    </style>
    <script>
        var coeffFont = 3;
        //подгон высоты + размер шрифтов
        window.onresize = function () {
            resizer();
        };
        resizer();
        function resizer() {
            $(".sizeFooterH").css({
                fontSize: ((window.innerHeight / 100) * coeffFont) + "px"
            });
        }
        function close_window() {
            if (confirm("Вы действительно хотите покинуть игру ? Там же остались незавершенные дела !")) {
                var win = window.open("about:blank", "_self");
                win.close();
            }
        }
    </script>
    <div class="LButtonFlex">
        <button onclick="window.location = '/';" class="LButtonStyle L_index_0 startfooter menuleft sizeFooterH" style="background-color: #CDFF9A;color: #A2BB88;visibility: visible;">Да</button>
    </div>
    <div class="RButtonFlex">
        <button onclick="close_window();return false;" class="RButtonStyle R_index_0 menufooter startfooter menuright sizeFooterH" style="background-color: #CDFF9A;color: #A2BB88;visibility: visible;">Выход</button>
    </div>

    <div class="linefooter menufooter startfooter sizeFooterH" style="background-color: #CDFF9A;color: #A2BB88;visibility: visible;"><div class="timefooter"><?php echo date("H:i"); ?></div></div>
</html>