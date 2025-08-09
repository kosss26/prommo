<?php
require_once 'system/func.php';
require_once 'system/header.php';
auth();

// Начинаем буферизацию вывода
ob_start();

// Обработка обмена валюты
if (isset($_GET['obmen'], $_GET['money'])) {
    $amount = ceil((float)$_GET['money']);
    $money_bank = ($user['level'] * 10000 / 2) * $amount;
    
    if ($amount > 0 && $user['platinum'] >= $amount) {
        $success = $mc->query("UPDATE `users` SET 
            `platinum` = `platinum` - '$amount',
            `money` = `money` + '$money_bank' 
            WHERE `id` = '" . $user['id'] . "'");
            
        $message = $success ? "Успешно" : "Ошибка обмена";
        echo "<script>showContent('/main.php?msg=" . urlencode($message) . "');</script>";
        exit;
    }
    
    echo "<script>showContent('/main.php?msg=" . urlencode("Недостаточно платины") . "');</script>";
    exit;
}

// Обработка увеличения рюкзака
if (isset($_GET['msg_getv'])) {
    message_yn(
        urlencode('Вы точно желаете увеличить рюкзак на +4? <br> Это обойдется Вам в 32<img style="width: 15px;" src="/images/icons/plata.png">!'),
        '/bank.php?getv',
        '/bank.php',
        'Да',
        'Отмена'
    );
}

if (isset($_GET['getv'])) {
    if ($user['max_bag_count'] >= 112) {
        message("Рюкзак максимален! <br> Увеличение недоступно.");
        exit;
    }
    
    if ($user['platinum'] < 32) {
        message("Недостаточно средств!");
        exit;
    }
    
    $success = $mc->query("UPDATE `users` SET 
        `max_bag_count` = `max_bag_count` + 4,
        `platinum` = `platinum` - 32 
        WHERE `id` = '" . $user['id'] . "'");
        
    if ($success) {
        echo "<script>showContent('/bank.php?msg=" . urlencode("Рюкзак увеличен на +4") . "');</script>";
        exit;
    }
}
?>

<!-- Подключение современных библиотек -->
<script src="https://cdn.jsdelivr.net/npm/pixi.js@7.2.4/dist/pixi.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@lottiefiles/lottie-player@2.0.3/dist/lottie-player.min.js"></script>

<style>
/* Стиль для банка, приведенный в соответствие с дизайном главной страницы */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

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
}

.bank_container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    box-sizing: border-box;
    position: relative;
    font-family: 'Inter', sans-serif;
    color: var(--text);
    perspective: 1000px;
    background: var(--card-bg);
    border-radius: var(--radius);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.bank_header {
    background: linear-gradient(135deg, var(--accent), var(--accent-2));
    color: var(--text);
    padding: 18px;
    border-radius: var(--radius);
    text-align: center;
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 25px;
    border: 1px solid var(--glass-border);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
}

.bank_card {
    background: var(--glass-bg);
    backdrop-filter: blur(5px);
    border-radius: var(--radius);
    padding: 20px;
    margin-bottom: 25px;
    border: 1px solid var(--glass-border);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.bank_card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
}

.bank_balance {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: var(--glass-bg);
    border-radius: 10px;
    margin-bottom: 15px;
    border: 1px solid var(--glass-border);
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.bank_balance:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
    border-color: var(--accent);
    background: rgba(255,255,255,0.12);
}

.balance_item {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--text);
    font-weight: 500;
}

.currency_wrapper {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-left: 5px;
}

.currency_icon {
    width: 24px;
    height: 24px;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
    transition: all 0.2s ease;
}

.currency_icon:hover {
    transform: scale(1.2) rotate(5deg);
}

.bank_input {
    width: 100%;
    padding: 15px;
    border: 1px solid var(--glass-border);
    border-radius: 8px;
    font-size: 16px;
    margin: 15px 0;
    background: var(--glass-bg);
    color: var(--text);
    transition: all 0.3s ease;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
}

.bank_input:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(245, 193, 93, 0.2), inset 0 2px 4px rgba(0, 0, 0, 0.05);
    transform: translateY(-1px);
}

.bank_button {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, var(--accent), var(--accent-2));
    border: none;
    border-radius: 8px;
    color: var(--text);
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    margin: 10px 0;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
}

.bank_button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: all 0.5s ease;
}

.bank_button:hover:not(:disabled)::before {
    left: 100%;
}

.bank_button:hover:not(:disabled) {
    transform: translateY(-3px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
}

.bank_button:active:not(:disabled) {
    transform: translateY(1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.bank_button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.bank_button--green {
    background: linear-gradient(135deg, #6B8E23, #556B2F);
}

.bank_button--platinum {
    background: linear-gradient(135deg, #E5E4E2, #A9A9A9);
    color: #333;
}

.exchange_rate {
    text-align: center;
    padding: 15px;
    background: var(--glass-bg);
    border-radius: 8px;
    margin: 15px 0;
    color: var(--text);
    font-weight: 500;
    border: 1px solid var(--glass-border);
    position: relative;
    overflow: hidden;
}

.section_title {
    color: var(--text);
    margin: 0 0 20px 0;
    text-align: center;
    font-size: 18px;
    font-weight: bold;
    position: relative;
    padding-bottom: 10px;
}

.section_title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 2px;
    background: linear-gradient(to right, var(--accent), var(--accent-2));
}

#canvas-container {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    pointer-events: none;
    overflow: hidden;
}

/* Анимации */
@keyframes shimmer {
    0% { background-position: -100% 0; }
    100% { background-position: 100% 0; }
}

@keyframes float {
    0% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0); }
}

/* Мобильная адаптация */
@media (max-width: 480px) {
    .bank_container {
        padding: 10px;
    }
    
    .bank_header {
        font-size: 18px;
        padding: 15px;
    }
    
    .bank_card {
        padding: 15px;
    }
    
    .bank_balance {
        flex-direction: column;
        align-items: flex-start;
        padding: 12px;
    }
    
    .bank_button {
        padding: 12px;
        font-size: 14px;
    }
    
    .bank_input {
        padding: 12px;
        font-size: 14px;
    }
    
    .section_title {
        font-size: 16px;
    }
}

/* Стили для анимированных монет и визуализации платины */
.animated-coin {
    display: inline-block;
    margin: 0 4px;
    transform-origin: center;
    animation: float 2s ease-in-out infinite;
}

.platinum-visualization {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    padding: 10px;
    margin: 15px auto;
    background: var(--glass-bg);
    border-radius: 8px;
    border: 1px solid var(--glass-border);
    min-height: 45px;
}

.exchange-preview {
    background: var(--glass-bg);
    border-radius: 8px;
    padding: 10px;
    margin-top: 10px;
    border: 1px solid var(--glass-border);
    text-align: center;
}

.exchange-rate-title {
    margin-bottom: 8px;
    font-weight: bold;
    color: var(--accent);
}

.exchange-rate-value {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}

.exchange-input-container {
    position: relative;
}

.button-content {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.coin-icon, .bag-icon {
    width: 20px;
    height: 20px;
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
}

.coin-icon {
    background-image: url('/images/icons/plata.png');
}

.bag-icon {
    background-image: url('/images/icons/ruck.png');
}

.coin-container {
    display: flex;
    align-items: center;
    gap: 4px;
    background: var(--glass-bg);
    padding: 4px 8px;
    border-radius: 15px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Добавление глобальных стилей для согласования с main.php */
body {
    margin: 0;
    padding: 0;
    width: 100%;
    min-height: 100%;
    color: var(--text);
    font-family: 'Inter', Arial, sans-serif;
    background: linear-gradient(135deg, var(--bg-grad-start), var(--bg-grad-end));
}
</style>

<div class="bank_container">
    <div id="canvas-container"></div>
    <div class="bank_header">Банк</div>
    
    <div class="bank_card">
        <h3 class="section_title">Ваш баланс</h3>
        
        <div class="bank_balance" id="gold-balance">
            <div class="balance_item">
                <span>Игровая валюта:</span>
                <div class="currency_wrapper">
                    <?php foreach(['zoloto', 'serebro', 'med'] as $currency): ?>
                        <?php if (money($user['money'], $currency) != 0): ?>
                            <div class="coin-container" data-currency="<?= $currency ?>">
                                <img class="currency_icon" src="/images/icons/<?= $currency ?>.png" alt="<?= $currency ?>">
                                <span class="currency-amount"><?= money($user['money'], $currency) ?></span>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <div class="bank_balance" id="platinum-balance">
            <div class="balance_item">
                <span>Платина:</span>
                <div class="currency_wrapper">
                    <div class="coin-container" data-currency="plata">
                        <img class="currency_icon" src="/images/icons/plata.png" alt="platinum">
                        <span class="currency-amount"><?= $user['platinum'] ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!isset($_GET['kassa'])): ?>
        <?php if ($user['access'] != 999): ?>
            <div class="bank_card">
                <h3 class="section_title">Обмен валюты</h3>
                
                <div class="exchange_rate">
                    <?php $kurs = $user['level'] * 10000 / 2; ?>
                    <div class="exchange-rate-title">Текущий курс обмена:</div>
                    <div class="exchange-rate-value">
                        <img class="currency_icon" src="/images/icons/plata.png" alt="platinum">
                        <span>1</span> = 
                        <?php foreach(['zoloto', 'serebro'] as $currency): ?>
                            <?php if (money($kurs, $currency) != 0): ?>
                                <img class="currency_icon" src="/images/icons/<?= $currency ?>.png" alt="<?= $currency ?>">
                                <span><?= money($kurs, $currency) ?></span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="exchange-input-container">
                    <input type="number" 
                           name="money" 
                           id="money" 
                           class="bank_input"
                           placeholder="Введите количество платины для обмена"
                           min="0"
                           value="0">
                    
                    <div class="exchange-preview" id="exchange-preview">
                        <span>Вы получите:</span>
                        <div id="exchange-result"></div>
                    </div>
                </div>

                <button class="bank_button" 
                        id="exchange-button"
                        onclick="animateExchange(); setTimeout(() => showContent('/bank.php?obmen&' + $('#money').serialize()), 1000);">
                    <div class="button-content">
                        <span>Обменять валюту</span>
                    </div>
                </button>
            </div>

            <button class="bank_button bank_button--platinum" onclick="showContent('/bank.php?kassa=free')">
                <div class="button-content">
                    <i class="coin-icon"></i>
                    <span>Пополнить через Free-Kassa</span>
                </div>
            </button>
        <?php else: ?>
            <div class="bank_card">
                <div style="text-align: center; color: var(--accent); padding: 20px;">
                    <i class="fa fa-exclamation-triangle" style="font-size: 24px; margin-bottom: 10px;"></i>
                    <p>Обменник временно не работает</p>
                </div>
            </div>
        <?php endif; ?>

        <button class="bank_button bank_button--green" onclick="animateBagIncrease(); setTimeout(() => showContent('/bank.php?msg_getv'), 800);">
            <div class="button-content">
                <i class="bag-icon"></i>
                <span>Увеличить рюкзак (+4 слота)</span>
            </div>
        </button>
    <?php endif; ?>

    <?php if (isset($_GET['kassa']) && $_GET['kassa'] == 'free'): ?>
        <div class="bank_card">
            <h3 class="section_title">Покупка платины</h3>

            <div class="exchange_rate">
                <div class="exchange-rate-title">Курс:</div>
                <div class="exchange-rate-value">
                    <img class="currency_icon" src="/images/icons/plata.png" alt="platinum">
                    <span>1</span> = <span id="crsbnk">0</span> ₽
                </div>
            </div>

            <div class="exchange-input-container">
                <input type="number" 
                       name="platkassa" 
                       id="platkassa" 
                       class="bank_input"
                       oninput="bankkassa(); updatePlatinumAnimation();" 
                       placeholder="Введите количество платины"
                       min="0"
                       value="0">
                
                <div class="platinum-visualization" id="platinum-visualization">
                    <!-- Platinum coins will be dynamically generated here -->
                </div>
            </div>

            <div class="exchange_rate" id="rubkassa">
                <span>0 ₽</span>
            </div>

            <button class="bank_button bank_button--platinum" 
                    id="buy-platinum-button"
                    onclick="animatePlatinumPurchase(); setTimeout(() => showContent('/freekassa.php?pay=' + Math.round(Number(platkassa.value)) * Number(cursbank)), 1200);">
                <div class="button-content">
                    <span>Купить платину</span>
                </div>
            </button>
        </div>
    <?php endif; ?>
</div>

<script>
// Базовые переменные
var cursbank = 1.5;
document.getElementById('crsbnk').innerHTML = cursbank;

// PixiJS инициализация для фоновых эффектов
let app;
let coins = [];
let particles = [];

function initPixiApp() {
    // Создаем приложение PixiJS
    app = new PIXI.Application({
        width: document.querySelector('.bank_container').offsetWidth,
        height: window.innerHeight,
        transparent: true,
        antialias: true
    });
    
    document.getElementById('canvas-container').appendChild(app.view);
    
    // Создаем монеты для фона
    createCoins();
    
    // Добавляем резайз
    window.addEventListener('resize', () => {
        app.renderer.resize(
            document.querySelector('.bank_container').offsetWidth,
            window.innerHeight
        );
    });
    
    // Добавляем анимацию
    app.ticker.add(() => {
        animateCoins();
        animateParticles();
    });
}

function createCoins() {
    // Создаем текстуры для монет
    const goldTexture = PIXI.Texture.from('/images/icons/zoloto.png');
    const silverTexture = PIXI.Texture.from('/images/icons/serebro.png');
    const bronzeTexture = PIXI.Texture.from('/images/icons/med.png');
    const platinumTexture = PIXI.Texture.from('/images/icons/plata.png');
    
    const textures = [goldTexture, silverTexture, bronzeTexture, platinumTexture];
    
    // Добавляем 25-30 монет на заднем фоне
    for (let i = 0; i < 30; i++) {
        const randomTexture = textures[Math.floor(Math.random() * textures.length)];
        const coin = new PIXI.Sprite(randomTexture);
        
        // Случайное положение и размер
        coin.x = Math.random() * app.screen.width;
        coin.y = Math.random() * app.screen.height;
        coin.alpha = Math.random() * 0.3 + 0.05;
        coin.scale.set(Math.random() * 0.5 + 0.3);
        
        // Случайное вращение и скорость
        coin.rotation = Math.random() * Math.PI * 2;
        coin.speed = Math.random() * 0.5 + 0.2;
        coin.rotationSpeed = (Math.random() - 0.5) * 0.05;
        
        app.stage.addChild(coin);
        coins.push(coin);
    }
}

function animateCoins() {
    // Анимируем монеты падающими и вращающимися
    coins.forEach(coin => {
        coin.y += coin.speed;
        coin.rotation += coin.rotationSpeed;
        
        // Если монета вышла за пределы экрана, возвращаем её наверх
        if (coin.y > app.screen.height) {
            coin.y = -coin.height;
            coin.x = Math.random() * app.screen.width;
        }
    });
}

function createParticle(x, y, color) {
    const particle = new PIXI.Graphics();
    particle.beginFill(color);
    particle.drawCircle(0, 0, Math.random() * 3 + 1);
    particle.endFill();
    
    particle.x = x;
    particle.y = y;
    particle.alpha = Math.random() * 0.5 + 0.5;
    particle.speed = {
        x: (Math.random() - 0.5) * 3,
        y: (Math.random() - 0.5) * 3
    };
    particle.lifetime = Math.random() * 60 + 30; // Frames
    
    app.stage.addChild(particle);
    particles.push(particle);
}

function animateParticles() {
    // Анимируем частицы и удаляем истекшие
    for (let i = particles.length - 1; i >= 0; i--) {
        const particle = particles[i];
        
        particle.x += particle.speed.x;
        particle.y += particle.speed.y;
        particle.alpha -= 0.01;
        particle.lifetime--;
        
        if (particle.lifetime <= 0 || particle.alpha <= 0) {
            app.stage.removeChild(particle);
            particles.splice(i, 1);
        }
    }
}

function createCoinBurst(x, y, amount, coinType) {
    // Определяем цвет в зависимости от типа монеты
    let color;
    switch(coinType) {
        case 'zoloto': color = 0xFFD700; break;
        case 'serebro': color = 0xC0C0C0; break;
        case 'med': color = 0xCD7F32; break;
        case 'plata': case 'platinum': color = 0xE5E4E2; break;
        default: color = 0xFFD700; break;
    }
    
    // Создаем частицы
    for (let i = 0; i < amount; i++) {
        createParticle(x, y, color);
    }
}

// Функция для обновления отображения предварительного просмотра обмена
function updateExchangePreview() {
    const amount = parseInt(document.getElementById('money').value) || 0;
    const kurs = <?= $user['level'] * 10000 / 2 ?>; // Берем курс из PHP
    const result = kurs * amount;
    
    let previewHTML = '';
    <?php foreach(['zoloto', 'serebro'] as $currency): ?>
        if (money(result, '<?= $currency ?>') != 0) {
            previewHTML += `<img class="currency_icon" src="/images/icons/<?= $currency ?>.png" alt="<?= $currency ?>"> ${money(result, '<?= $currency ?>')} `;
        }
    <?php endforeach; ?>
    
    document.getElementById('exchange-result').innerHTML = previewHTML;
}

// Функция для анимации кнопки обмена
function animateExchange() {
    const button = document.getElementById('exchange-button');
    const amount = parseInt(document.getElementById('money').value) || 0;
    
    if (amount <= 0) return;
    
    // Анимируем кнопку
    gsap.to(button, {
        scale: 0.95,
        duration: 0.2,
        yoyo: true,
        repeat: 1
    });
    
    // Создаем эффект монет
    const buttonRect = button.getBoundingClientRect();
    createCoinBurst(
        buttonRect.left + buttonRect.width / 2, 
        buttonRect.top + buttonRect.height / 2,
        20 + amount * 2,
        'zoloto'
    );
}

// Функция для банковского калькулятора
function bankkassa() {
    var value = Number(document.getElementById('platkassa').value);
    if (value > -1) {
        document.getElementById('rubkassa').innerHTML = 
            Math.round(value * cursbank) + ' ₽';
    } else {
        document.getElementById('platkassa').value = 0;
    }
}

// Функция для обновления визуализации платины
function updatePlatinumAnimation() {
    const amount = parseInt(document.getElementById('platkassa').value) || 0;
    const container = document.getElementById('platinum-visualization');
    
    // Очищаем контейнер
    container.innerHTML = '';
    
    // Визуализируем только если количество разумное
    if (amount > 0 && amount <= 50) {
        // Создаем анимированные иконки платины
        for (let i = 0; i < Math.min(amount, 15); i++) {
            const coinDiv = document.createElement('div');
            coinDiv.className = 'animated-coin';
            coinDiv.style.animationDelay = `${i * 0.1}s`;
            
            const img = document.createElement('img');
            img.src = '/images/icons/plata.png';
            img.alt = 'platinum';
            img.className = 'currency_icon';
            
            coinDiv.appendChild(img);
            container.appendChild(coinDiv);
        }
    }
}

// Функция для анимации покупки платины
function animatePlatinumPurchase() {
    const button = document.getElementById('buy-platinum-button');
    const amount = parseInt(document.getElementById('platkassa').value) || 0;
    
    if (amount <= 0) return;
    
    // Анимируем кнопку
    gsap.to(button, {
        scale: 0.95,
        duration: 0.2,
        yoyo: true,
        repeat: 1
    });
    
    // Создаем эффект монет
    const buttonRect = button.getBoundingClientRect();
    createCoinBurst(
        buttonRect.left + buttonRect.width / 2, 
        buttonRect.top + buttonRect.height / 2,
        20 + amount,
        'plata'
    );
}

// Функция для анимации увеличения рюкзака
function animateBagIncrease() {
    const button = document.querySelector('.bank_button--green');
    
    // Анимируем кнопку
    gsap.to(button, {
        scale: 0.95,
        duration: 0.2,
        yoyo: true,
        repeat: 1
    });
}

// Анимации для балансов
function animateBalances() {
    const goldBalance = document.getElementById('gold-balance');
    const platinumBalance = document.getElementById('platinum-balance');
    
    // Анимация для золотого баланса
    gsap.from(goldBalance, {
        y: 20,
        opacity: 0,
        duration: 0.8,
        ease: 'power2.out'
    });
    
    // Анимация для платиного баланса
    gsap.from(platinumBalance, {
        y: 20,
        opacity: 0,
        duration: 0.8,
        delay: 0.2,
        ease: 'power2.out'
    });
    
    // Анимация монет
    gsap.to('.coin-container', {
        y: '-5px',
        duration: 1.5,
        yoyo: true,
        repeat: -1,
        ease: 'sine.inOut',
        stagger: 0.1
    });
}

// Добавляем эффекты при наведении на монеты
document.querySelectorAll('.currency_icon').forEach(icon => {
    icon.addEventListener('mouseover', function() {
        const rect = this.getBoundingClientRect();
        const coinType = this.closest('.coin-container')?.dataset?.currency || 'zoloto';
        
        createCoinBurst(
            rect.left + rect.width / 2,
            rect.top + rect.height / 2,
            10,
            coinType
        );
    });
});

// Подключаем обработчики событий и инициализируем анимации
document.addEventListener('DOMContentLoaded', function() {
    // Инициализируем PixiJS
    initPixiApp();
    
    // Анимируем баланс
    animateBalances();
    
    // Обработчик для предпросмотра обмена
    if (document.getElementById('money')) {
        document.getElementById('money').addEventListener('input', updateExchangePreview);
        updateExchangePreview(); // Инициализируем при загрузке
    }
    
    // Парралакс эффект для карточек
    document.querySelectorAll('.bank_card').forEach(card => {
        card.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const moveX = (x - centerX) / 20;
            const moveY = (y - centerY) / 20;
            
            gsap.to(this, {
                rotationY: moveX,
                rotationX: -moveY,
                transformPerspective: 1000,
                duration: 0.4,
                ease: 'power2.out'
            });
        });
        
        card.addEventListener('mouseleave', function() {
            gsap.to(this, {
                rotationY: 0,
                rotationX: 0,
                duration: 0.6,
                ease: 'power2.out'
            });
        });
    });
});

// Добавляем функцию для преобразования суммы в игровую валюту
function money(sum, type) {
    if (type == 'zoloto') {
        return Math.floor(sum / 10000);
    } else if (type == 'serebro') {
        return Math.floor((sum % 10000) / 100);
    } else if (type == 'med') {
        return Math.floor(sum % 100);
    }
    return 0;
}
</script>

<?php
$footval = "bank";
require_once ('system/foot/foot.php');
ob_end_flush();
?>