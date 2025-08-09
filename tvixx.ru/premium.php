<?php
require_once('system/func.php');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Премиум-аккаунт</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        :root {
            --bg-grad-start: #111;
            --bg-grad-end: #1a1a1a;
            --accent: #f5c15d;
            --accent-2: #ff8452;
            --card-bg: rgba(255,255,255,0.05);
            --glass-bg: rgba(255,255,255,0.08);
            --glass-border: rgba(255,255,255,0.12);
            --text: #fff;
            --muted: #c2c2c2;
            --radius: 16px;
            --secondary-bg: rgba(255,255,255,0.03);
            --item-hover: rgba(255,255,255,0.15);
        }
        
        body {
            background: linear-gradient(135deg, var(--bg-grad-start), var(--bg-grad-end));
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 10px;
            min-height: 100vh;
            color: var(--text);
            box-sizing: border-box;
        }
        
        .premium-container {
            background: var(--card-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            padding: 25px;
            width: 90%;
            max-width: 400px;
            margin: 20px auto 70px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            position: relative;
        }
        
        .premium-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--accent), var(--accent-2));
            border-radius: var(--radius) var(--radius) 0 0;
        }
        
        .premium-header {
            font-size: 24px;
            font-weight: 700;
            color: var(--accent);
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--glass-border);
            position: relative;
        }
        
        .premium-benefits {
            margin: 20px 0;
            background: var(--secondary-bg);
            padding: 15px;
            border-radius: var(--radius);
            border: 1px solid var(--glass-border);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .premium-benefit {
            font-size: 15px;
            color: var(--text);
            margin: 12px 0;
            padding: 10px;
            border-bottom: 1px solid var(--glass-border);
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .premium-benefit:last-child {
            border-bottom: none;
        }
        
        .premium-benefit:hover {
            background: var(--item-hover);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        
        .premium-benefit b {
            color: var(--accent);
            font-weight: 700;
            margin-left: 5px;
        }
        
        .premium-highlight {
            color: var(--text);
            margin: 20px 0;
            padding: 15px;
            background: var(--secondary-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            text-align: center;
            font-size: 15px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .premium-highlight::before {
            content: '🏆';
            font-size: 20px;
            margin-right: 10px;
        }
        
        .premium-button {
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            color: #111;
            border: none;
            padding: 15px 20px;
            font-size: 16px;
            border-radius: 50px;
            cursor: pointer;
            display: block;
            margin: 25px auto;
            text-align: center;
            text-transform: uppercase;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 90%;
            max-width: 280px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }
        
        .premium-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.5s ease;
        }
        
        .premium-button:hover::before {
            left: 100%;
        }
        
        .premium-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.3);
        }
        
        .premium-icon {
            vertical-align: middle;
            margin-left: 5px;
            width: 20px;
            height: 20px;
            filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.1));
        }
        
        .premium-note {
            font-size: 14px;
            color: var(--muted);
            margin-top: 20px;
            padding: 12px;
            background: var(--secondary-bg);
            border-radius: var(--radius);
            text-align: center;
            border: 1px solid var(--glass-border);
        }

        @media screen and (max-width: 400px) {
            .premium-container {
                padding: 20px;
                width: 95%;
            }
            
            .premium-header {
                font-size: 20px;
            }
            
            .premium-benefit {
                font-size: 14px;
            }
            
            .premium-button {
                padding: 12px 15px;
                font-size: 14px;
            }
        }

        /* Стили для модального окна */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }

        .modal-window {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: var(--card-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            padding: 25px;
            width: 85%;
            max-width: 320px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            z-index: 1001;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .modal-header {
            color: var(--accent);
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--glass-border);
        }

        .modal-content {
            color: var(--text);
            font-size: 15px;
            line-height: 1.5;
            text-align: center;
            margin-bottom: 25px;
        }

        .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .modal-button {
            padding: 12px 20px;
            border-radius: 50px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .modal-confirm {
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            color: #111;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .modal-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }

        .modal-cancel {
            background: var(--secondary-bg);
            color: var(--text);
            border: 1px solid var(--glass-border);
        }

        .modal-cancel:hover {
            background: var(--item-hover);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="premium-container">
        <div class="premium-header">Премиум-аккаунт</div>
        <div class="premium-benefits">
            <div class="premium-benefit">⚔️ <b>+50%</b> получаемой славы</div>
            <div class="premium-benefit">📈 <b>+25%</b> получаемого опыта</div>
            <div class="premium-benefit">💰 <b>+25%</b> получаемого золота</div>
        </div>
        <div class="premium-highlight">
            Шанс получить награду за каждого побежденного игрока!
        </div>
        <div class="premium-note">
            ⚠️ Внимание: Премиум не влияет на бонусы, получаемые по заданиям.
        </div>
        <div class="premium-note">
            Для активации премиум-аккаунта на месяц необходимо заплатить 200 <img src="/images/icons/plata.png" class="premium-icon">
        </div>
        <button class="premium-button" onclick="showModal()">Активировать Премиум</button>
    </div>

    <!-- Добавляем HTML модального окна -->
    <div class="modal-overlay" id="confirmModal">
        <div class="modal-window">
            <div class="modal-header">Подтверждение покупки</div>
            <div class="modal-content">
                Вы действительно хотите активировать премиум-аккаунт за 200 <img src="/images/icons/plata.png" class="premium-icon">?
            </div>
            <div class="modal-buttons">
                <button class="modal-button modal-confirm" onclick="confirmPurchase()">Подтвердить</button>
                <button class="modal-button modal-cancel" onclick="closeModal()">Отмена</button>
            </div>
        </div>
    </div>

    <script>
        function showModal() {
            document.getElementById('confirmModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('confirmModal').style.display = 'none';
        }

        function confirmPurchase() {
            closeModal();
            showContent('shop.php?prem');
        }

        // Изменяем обработчик кнопки
        document.querySelector('.premium-button').onclick = function(e) {
            e.preventDefault();
            showModal();
        };

        // Закрытие по клику вне окна
        document.querySelector('.modal-overlay').onclick = function(e) {
            if (e.target === this) {
                closeModal();
            }
        };
    </script>
</body>
</html>
<?php
$footval = 'backtoshop';
require_once('system/foot/foot.php');
?>