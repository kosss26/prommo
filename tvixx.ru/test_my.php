<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <script src="/javascript/jquery-3.3.1.min.js?136.123" type="text/javascript"></script>
    <style>
        .menu-table {
            position: absolute;
            bottom: 0;
            width: 40%;
        }
        
        .menu-item {
            padding: 15px;
            text-align: center;
            height: 50px;
            cursor: pointer;
        }
        
        .left-menu { left: 0; }
        .right-menu { right: 0; }
        
        .time-display {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            height: 50px;
            background-color: #FFD700;
        }
        
        .hidden {
            display: none;
        }
        
        .loading {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>
<body>
    <table class="time-display">
        <tr><td class="time">00:00</td></tr>
    </table>

    <table class="menu-table left-menu">
        <tr><td onclick="showContent('');toggleMenu('left');" class="lm_punct menu-item hidden" style="background-color: blue;">punct2</td></tr>
        <tr><td onclick="showContent('');toggleMenu('left');" class="lm_punct menu-item hidden" style="background-color: green;">punct1</td></tr>
        <tr><td class="menu-item" onclick="toggleMenu('left');" style="background-color: red;">lmenu</td></tr>
    </table>

    <table class="menu-table right-menu">
        <tr><td onclick="showContent('');toggleMenu('right');" class="rm_punct menu-item hidden" style="background-color: blue;">punct2</td></tr>
        <tr><td onclick="showContent('');toggleMenu('right');" class="rm_punct menu-item hidden" style="background-color: green;">punct1</td></tr>
        <tr><td class="menu-item" onclick="toggleMenu('right');" style="background-color: red;">rmenu</td></tr>
    </table>

    <script>
        function toggleMenu(side) {
            if (side === 'left') {
                $('.rm_punct').addClass('hidden');
                $('.lm_punct').toggleClass('hidden');
            } else {
                $('.lm_punct').addClass('hidden');
                $('.rm_punct').toggleClass('hidden');
            }
        }

        function showContent(link) {
            const loadingImg = $('<img>', {
                class: 'loading',
                src: '/img/loading.gif',
                alt: 'loading'
            });
            
            $('body').append(loadingImg);

            $.ajax({
                type: "POST",
                url: link,
                dataType: "text",
                data: { glbool: 1 },
                success: function(data) {
                    updateTime();
                    loadingImg.remove();
                },
                error: function() {
                    $(".time").html("error");
                    loadingImg.remove();
                }
            });
        }

        function updateTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            $(".time").html(`${hours}:${minutes}:${seconds}`);
        }
    </script>
</body>
</html>