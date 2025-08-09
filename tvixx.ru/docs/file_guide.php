<?php
require_once '../system/func.php';
auth();
access(3); // Файл доступен только администраторам

// Заголовок страницы
$title = "Документация по файлам проекта";
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <style>
        /* Стили для удобной навигации и отображения */
        :root {
            --main-bg-color: #f8f9fa;
            --header-bg-color: #343a40;
            --header-text-color: #fff;
            --nav-bg-color: #e9ecef;
            --section-title-bg: #007bff;
            --section-title-color: #fff;
            --card-bg-color: #fff;
            --card-border-color: #ddd;
            --card-header-bg: #f1f1f1;
            --link-color: #007bff;
            --code-bg-color: #f5f5f5;
            --table-header-bg: #e9ecef;
            --table-border-color: #dee2e6;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: var(--main-bg-color);
            color: #333;
        }
        
        header {
            background-color: var(--header-bg-color);
            color: var(--header-text-color);
            padding: 1rem;
            text-align: center;
        }
        
        .container {
            display: flex;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        nav {
            width: 250px;
            background-color: var(--nav-bg-color);
            padding: 1rem;
            position: sticky;
            top: 0;
            height: calc(100vh - 2rem);
            overflow-y: auto;
        }
        
        nav h3 {
            margin-top: 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #ccc;
        }
        
        nav ul {
            list-style-type: none;
            padding: 0;
        }
        
        nav li {
            margin-bottom: 5px;
        }
        
        nav a {
            text-decoration: none;
            color: var(--link-color);
            display: block;
            padding: 5px;
            border-radius: 3px;
        }
        
        nav a:hover {
            background-color: #dee2e6;
        }
        
        main {
            flex: 1;
            padding: 0 20px;
        }
        
        .section-title {
            background-color: var(--section-title-bg);
            color: var(--section-title-color);
            padding: 10px;
            border-radius: 5px;
            margin-top: 30px;
        }
        
        .card {
            background-color: var(--card-bg-color);
            border: 1px solid var(--card-border-color);
            border-radius: 5px;
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .card-header {
            background-color: var(--card-header-bg);
            padding: 10px 15px;
            border-bottom: 1px solid var(--card-border-color);
        }
        
        .card-body {
            padding: 15px;
        }
        
        .file-path {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        
        code {
            background-color: var(--code-bg-color);
            padding: 2px 4px;
            border-radius: 3px;
            font-family: 'Courier New', Courier, monospace;
        }
        
        pre {
            background-color: var(--code-bg-color);
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        th, td {
            border: 1px solid var(--table-border-color);
            padding: 8px 12px;
            text-align: left;
        }
        
        th {
            background-color: var(--table-header-bg);
        }
        
        /* Категории файлов */
        .category-core { border-left: 4px solid #007bff; }
        .category-clan { border-left: 4px solid #28a745; }
        .category-battle { border-left: 4px solid #dc3545; }
        .category-cron { border-left: 4px solid #fd7e14; }
        .category-functions { border-left: 4px solid #6f42c1; }
        
        .search-box {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        #top-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: var(--section-title-bg);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            text-align: center;
            line-height: 40px;
            cursor: pointer;
            display: none;
            z-index: 99;
        }
    </style>
</head>
<body>
    <header>
        <h1><?php echo $title; ?></h1>
    </header>
    
    <div class="container">
        <nav>
            <input type="text" class="search-box" id="searchInput" placeholder="Поиск файла...">
            <h3>Содержание</h3>
            <ul>
                <li><a href="#introduction">Введение</a></li>
                <li><a href="#system">Системные файлы</a></li>
                <li><a href="#clan">Клановая система</a></li>
                <li><a href="#huntb">Система боёв</a></li>
                <li><a href="#dungeons">Система подземелий</a></li>
                <li><a href="#mining">Система шахт</a></li>
                <li><a href="#main">Основные игровые файлы</a></li>
                <li><a href="#quests">Система квестов</a></li>
                <li><a href="#admin">Админ-панель</a></li>
                <li><a href="#api">API и интеграция</a></li>
                <li><a href="#system-files">Доп. системные файлы</a></li>
                <li><a href="#cron">Крон-задачи</a></li>
                <li><a href="#functions">Вспомогательные функции</a></li>
                <li><a href="#database">Структура базы данных</a></li>
                <li><a href="#additional">Дополнительные файлы</a></li>
                <li><a href="#monetary">Платежные системы</a></li>
                <li><a href="#registration-auth">Регистрация и авторизация</a></li>
                <li><a href="#action-files">Файлы действий</a></li>
                <li><a href="#visual-files">Визуальные компоненты и CSS</a></li>
                <li><a href="#directories">Дополнительные директории</a></li>
            </ul>
        </nav>
        
        <main>
            <section id="introduction">
                <h2 class="section-title">Введение</h2>
                <div class="card">
                    <div class="card-header">
                        <h3>О проекте</h3>
                    </div>
                    <div class="card-body">
                        <p>Данный документ содержит подробное описание файловой структуры проекта, описание основных файлов и их взаимосвязей. Документация предназначена для разработчиков и администраторов системы.</p>
                        
                        <p>Проект представляет собой браузерную MMORPG с системой кланов, боёв, экономикой и другими игровыми механиками.</p>
                    </div>
                </div>
            </section>
            
            <section id="system">
                <h2 class="section-title">Системные файлы</h2>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>system/func.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /system/func.php</div>
                        <p><strong>Назначение:</strong> Основной файл с базовыми функциями проекта</p>
                        
                        <p>Этот файл содержит большое количество базовых функций, используемых по всему проекту:</p>
                        <ul>
                            <li><code>auth()</code> - проверка авторизации пользователя</li>
                            <li><code>noauth()</code> - проверка отсутствия авторизации</li>
                            <li><code>access($access)</code> - проверка уровня доступа</li>
                            <li><code>message($text)</code> - вывод сообщения пользователю</li>
                            <li><code>message_yn($text, $btny, $btnn, $namea, $nameb)</code> - вывод сообщения с выбором Да/Нет</li>
                            <li><code>GetLevel($exp)</code> - получение уровня персонажа по опыту</li>
                            <li><code>hero1_add()</code> - добавление игрока в бой</li>
                            <li><code>bot_add()</code> - добавление бота в бой</li>
                            <li>и другие</li>
                        </ul>
                        
                        <p><strong>Взаимодействие:</strong> Файл подключается практически во всех PHP-файлах проекта через <code>require_once</code>.</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>system/dbc.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /system/dbc.php</div>
                        <p><strong>Назначение:</strong> Файл для работы с базой данных</p>
                        
                        <p>Содержит функции для подключения к базе данных и выполнения запросов.</p>
                        
                        <p><strong>Взаимодействие:</strong> Подключается в большинстве файлов для обеспечения доступа к базе данных</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>system/connect.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /system/connect.php</div>
                        <p><strong>Назначение:</strong> Файл с настройками соединения с базой данных</p>
                        
                        <p>Содержит параметры соединения с базой данных, создает соединение и устанавливает начальные настройки.</p>
                        
                        <p><strong>Взаимодействие:</strong> Подключается в system/func.php</p>
                    </div>
                </div>
            </section>
            
            <section id="clan">
                <h2 class="section-title">Клановая система</h2>
                
                <div class="card category-clan">
                    <div class="card-header">
                        <h3>clan/vladenia.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /clan/vladenia.php</div>
                        <p><strong>Назначение:</strong> Страница отображения владений клана</p>
                        
                        <p>Файл отображает список локаций, захваченных кланом игрока, включая информацию о времени следующего боя за землю, доходах с локации и защитном периоде.</p>
                        
                        <p><strong>Использует функции:</strong></p>
                        <ul>
                            <li><code>functions/date_functions.php</code> - форматирование даты следующего боя</li>
                            <li><code>functions/bablo.php</code> - форматирование отображения игровой валюты</li>
                        </ul>
                        
                        <p><strong>Работа с БД:</strong> Получает данные из таблицы <code>location</code> для отображения захваченных земель.</p>
                        
                        <p><strong>Взаимодействие:</strong> Используется в клановом разделе игры для просмотра захваченных территорий.</p>
                    </div>
                </div>
                
                <div class="card category-clan">
                    <div class="card-header">
                        <h3>clan/clan_all.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /clan/clan_all.php</div>
                        <p><strong>Назначение:</strong> Страница с информацией о клане</p>
                        
                        <p>Отображает основную информацию о клане, список участников, казну и прочие данные клана.</p>
                        
                        <p><strong>Взаимодействие:</strong> Используется при просмотре информации о клане и управлении им.</p>
                    </div>
                </div>
                
                <div class="card category-clan">
                    <div class="card-header">
                        <h3>clan/kazna.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /clan/kazna.php</div>
                        <p><strong>Назначение:</strong> Страница управления казной клана</p>
                        
                        <p>Позволяет просматривать состояние казны клана, вносить деньги в казну и тратить их.</p>
                        
                        <p><strong>Взаимодействие:</strong> Используется в управлении экономикой клана.</p>
                    </div>
                </div>
            </section>
            
            <section id="huntb">
                <h2 class="section-title">Система боёв</h2>
                
                <div class="card category-battle">
                    <div class="card-header">
                        <h3>huntb/zem/index.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /huntb/zem/index.php</div>
                        <p><strong>Назначение:</strong> Страница информации о земле и боях за неё</p>
                        
                        <p>Этот файл обрабатывает и отображает информацию о локации, владеющем клане, времени следующего боя, и, в зависимости от времени, показывает интерфейс регистрации на бой либо список зарегистрированных участников битвы.</p>
                        
                        <p><strong>Логика работы:</strong></p>
                        <ul>
                            <li>Определяет текущее время и сравнивает с временем боя (18:00 и 20:00)</li>
                            <li>Если время боевого периода (17:50-19:00 или 19:50-21:00) - показывает интерфейс регистрации и список участников</li>
                            <li>В обычное время - показывает информацию о земле и ее владельце</li>
                            <li>Определяет, какие земли клан атакует или обороняет</li>
                        </ul>
                        
                        <p><strong>Использует функции:</strong></p>
                        <ul>
                            <li><code>functions/date_functions.php</code> - форматирование даты следующего боя</li>
                            <li><code>functions/bablo.php</code> - форматирование отображения игровой валюты</li>
                        </ul>
                        
                        <p><strong>Работа с БД:</strong> Получает данные из таблиц <code>location</code>, <code>clan</code>, <code>huntb_list</code>, <code>users</code>.</p>
                        
                        <p><strong>Взаимодействие:</strong></p>
                        <ul>
                            <li><code>huntb/zem/add.php</code> - для регистрации на бой (параметр add=1800 или add=2000)</li>
                            <li><code>huntb/zem/remove.php</code> - для отмены регистрации на бой</li>
                        </ul>
                    </div>
                </div>
                
                <div class="card category-battle">
                    <div class="card-header">
                        <h3>huntb/zem/add.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /huntb/zem/add.php</div>
                        <p><strong>Назначение:</strong> Регистрация игрока на бой за землю</p>
                        
                        <p>Файл обрабатывает запрос на регистрацию игрока для участия в битве за землю. Принимает параметр add, определяющий тип битвы (1800 - отборочный тур, 2000 - финальная битва).</p>
                        
                        <p><strong>Работа с БД:</strong> Добавляет запись в таблицу <code>huntb_list</code> с информацией об игроке, типе боя и локации.</p>
                        
                        <p><strong>Взаимодействие:</strong> Вызывается из <code>huntb/zem/index.php</code> при нажатии на кнопку регистрации.</p>
                    </div>
                </div>
                
                <div class="card category-battle">
                    <div class="card-header">
                        <h3>huntb/zem/remove.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /huntb/zem/remove.php</div>
                        <p><strong>Назначение:</strong> Отмена регистрации игрока на бой за землю</p>
                        
                        <p>Файл обрабатывает запрос на отмену регистрации игрока для участия в битве за землю.</p>
                        
                        <p><strong>Работа с БД:</strong> Удаляет запись из таблицы <code>huntb_list</code> для текущего игрока.</p>
                        
                        <p><strong>Взаимодействие:</strong> Вызывается из <code>huntb/zem/index.php</code> при нажатии на кнопку отказа от участия.</p>
                    </div>
                </div>
                
                <div class="card category-battle">
                    <div class="card-header">
                        <h3>hunt/result.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /hunt/result.php</div>
                        <p><strong>Назначение:</strong> Обработка и отображение результатов боя</p>
                        
                        <p>Файл отвечает за обработку результатов боевых сражений - распределение награды, опыта, дропа предметов и отображение итогов для игрока.</p>
                        
                        <p><strong>Работа с БД:</strong> Получает данные из таблицы <code>resultbattle</code>, обрабатывает их и обновляет данные игрока.</p>
                        
                        <p><strong>Взаимодействие:</strong> Вызывается автоматически после завершения боя для отображения результатов.</p>
                    </div>
                </div>
                
                <div class="card category-battle">
                    <div class="card-header">
                        <h3>hunt/index.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /hunt/index.php</div>
                        <p><strong>Назначение:</strong> Главная страница охоты</p>
                        
                        <p>Отображает список доступных монстров для охоты, их характеристики и возможные награды. Позволяет игроку выбрать монстра для сражения.</p>
                        
                        <p><strong>Работа с БД:</strong> Получает данные из таблиц <code>hunt</code> (список монстров) и <code>users</code> (данные игрока).</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан с <code>hunt/attack.php</code> для начала боя с выбранным монстром.</p>
                    </div>
                </div>
                
                <div class="card category-battle">
                    <div class="card-header">
                        <h3>hunt/attack.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /hunt/attack.php</div>
                        <p><strong>Назначение:</strong> Инициация боя с монстром</p>
                        
                        <p>Обрабатывает начало сражения с выбранным монстром, создает запись о бое в базе данных и перенаправляет игрока на страницу боя.</p>
                        
                        <p><strong>Работа с БД:</strong> Создает записи в таблицах <code>battle</code> и обновляет статус игрока.</p>
                        
                        <p><strong>Взаимодействие:</strong> Вызывается из <code>hunt/index.php</code> и перенаправляет на <code>hunt/battle.php</code>.</p>
                    </div>
                </div>
                
                <div class="card category-battle">
                    <div class="card-header">
                        <h3>hunt/command.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /hunt/command.php</div>
                        <p><strong>Назначение:</strong> Обработка команд во время боя</p>
                        
                        <p>Обрабатывает действия игрока во время боя (атака, защита, использование навыков и т.д.), рассчитывает результаты и обновляет состояние боя.</p>
                        
                        <p><strong>Работа с БД:</strong> Обновляет данные в таблице <code>battle</code> и связанных таблицах.</p>
                        
                        <p><strong>Взаимодействие:</strong> Вызывается из <code>hunt/battle.php</code> и <code>hunt/tec.php</code>.</p>
                    </div>
                </div>
                
                <div class="card category-battle">
                    <div class="card-header">
                        <h3>hunt/battle.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /hunt/battle.php</div>
                        <p><strong>Назначение:</strong> Интерфейс боя</p>
                        
                        <p>Отображает игровой интерфейс боя, включая состояние игрока и противника, доступные действия и ход сражения.</p>
                        
                        <p><strong>Работа с БД:</strong> Получает данные о текущем состоянии боя из таблицы <code>battle</code>.</p>
                        
                        <p><strong>Взаимодействие:</strong> Взаимодействует с <code>hunt/command.php</code> для обработки действий игрока.</p>
                    </div>
                </div>
                
                <div class="card category-battle">
                    <div class="card-header">
                        <h3>battle/pvp.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /battle/pvp.php</div>
                        <p><strong>Назначение:</strong> Система PvP-сражений</p>
                        
                        <p>Обрабатывает бои между игроками (PvP), включая вызов на дуэль, принятие вызова и проведение боя.</p>
                        
                        <p><strong>Работа с БД:</strong> Работает с таблицами <code>duels</code> и <code>battle</code>.</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан с системой боев и профилями игроков.</p>
                    </div>
                </div>
                
                <div class="card category-battle">
                    <div class="card-header">
                        <h3>battle/func.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /battle/func.php</div>
                        <p><strong>Назначение:</strong> Вспомогательные функции для боевой системы</p>
                        
                        <p>Содержит специализированные функции для боевой системы, используемые в PvP и PvE боях.</p>
                        
                        <p><strong>Работа с БД:</strong> Вспомогательные запросы к боевым таблицам.</p>
                        
                        <p><strong>Взаимодействие:</strong> Используется в файлах <code>battle/pvp.php</code> и других боевых скриптах.</p>
                    </div>
                </div>
            </section>
            
            <section id="dungeons">
                <h2 class="section-title">Система подземелий</h2>
                
                <div class="card category-battle">
                    <div class="card-header">
                        <h3>dungeons/index.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /dungeons/index.php</div>
                        <p><strong>Назначение:</strong> Главная страница подземелий</p>
                        
                        <p>Отображает список доступных подземелий, их сложность, требования и награды. Позволяет игрокам выбрать подземелье для прохождения.</p>
                        
                        <p><strong>Работа с БД:</strong> Получает данные из таблиц <code>dungeons</code>, <code>dungeons_complete</code> и <code>users</code>.</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан с <code>dungeons/start.php</code> для начала прохождения подземелья.</p>
                    </div>
                </div>
                
                <div class="card category-battle">
                    <div class="card-header">
                        <h3>dungeons/start.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /dungeons/start.php</div>
                        <p><strong>Назначение:</strong> Начало прохождения подземелья</p>
                        
                        <p>Инициализирует прохождение выбранного подземелья, создает соответствующие записи в базе данных и подготавливает игрока к первому бою.</p>
                        
                        <p><strong>Работа с БД:</strong> Создает записи в таблицах <code>dungeons_progress</code> и <code>dungeons_battles</code>.</p>
                        
                        <p><strong>Взаимодействие:</strong> Вызывается из <code>dungeons/index.php</code> и перенаправляет на <code>dungeons/explore.php</code>.</p>
                    </div>
                </div>
                
                <div class="card category-battle">
                    <div class="card-header">
                        <h3>dungeons/explore.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /dungeons/explore.php</div>
                        <p><strong>Назначение:</strong> Исследование подземелья</p>
                        
                        <p>Позволяет игроку исследовать подземелье, обнаруживать встречи с монстрами, находить сокровища и продвигаться к финальному боссу.</p>
                        
                        <p><strong>Работа с БД:</strong> Работает с таблицами <code>dungeons_progress</code>, <code>dungeons_battles</code> и <code>dungeons_loot</code>.</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан с <code>dungeons/battle.php</code> для боев в подземелье и <code>dungeons/complete.php</code> для завершения подземелья.</p>
                    </div>
                </div>
                
                <div class="card category-battle">
                    <div class="card-header">
                        <h3>dungeons/battle.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /dungeons/battle.php</div>
                        <p><strong>Назначение:</strong> Бои в подземелье</p>
                        
                        <p>Обрабатывает боевые столкновения с монстрами в подземелье, включая босса. Имеет специальные механики для подземелий.</p>
                        
                        <p><strong>Работа с БД:</strong> Работает с таблицами <code>dungeons_battles</code> и <code>dungeons_monsters</code>.</p>
                        
                        <p><strong>Взаимодействие:</strong> Вызывается из <code>dungeons/explore.php</code> и после завершения боя возвращает на страницу исследования.</p>
                    </div>
                </div>
                
                <div class="card category-battle">
                    <div class="card-header">
                        <h3>dungeons/complete.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /dungeons/complete.php</div>
                        <p><strong>Назначение:</strong> Завершение подземелья</p>
                        
                        <p>Обрабатывает завершение подземелья (успешное или неудачное), распределяет награды и опыт, обновляет прогресс игрока.</p>
                        
                        <p><strong>Работа с БД:</strong> Обновляет таблицы <code>dungeons_complete</code>, <code>users</code> и другие связанные таблицы.</p>
                        
                        <p><strong>Взаимодействие:</strong> Вызывается из <code>dungeons/explore.php</code> после победы над боссом или при отступлении.</p>
                    </div>
                </div>
                
                <div class="card category-battle">
                    <div class="card-header">
                        <h3>dungeons/functions.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /dungeons/functions.php</div>
                        <p><strong>Назначение:</strong> Вспомогательные функции для системы подземелий</p>
                        
                        <p>Содержит специализированные функции для работы с подземельями, генерации монстров, расчета наград и других механик.</p>
                        
                        <p><strong>Работа с БД:</strong> Работает со всеми таблицами, связанными с подземельями.</p>
                        
                        <p><strong>Взаимодействие:</strong> Используется во всех файлах системы подземелий.</p>
                    </div>
                </div>
            </section>
            
            <section id="mining">
                <h2 class="section-title">Система шахт</h2>
                
                <div class="card category-functions">
                    <div class="card-header">
                        <h3>mine/index.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /mine/index.php</div>
                        <p><strong>Назначение:</strong> Интерфейс шахты</p>
                        
                        <p>Представляет интерфейс для добычи ресурсов в шахте через мини-игру. Позволяет игрокам добывать различные ресурсы для крафта и продажи.</p>
                        
                        <p><strong>Работа с БД:</strong> Работает с таблицами <code>mine_resources</code> и <code>users</code>.</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан с <code>mine/score.php</code> для обработки результатов добычи.</p>
                    </div>
                </div>
                
                <div class="card category-functions">
                    <div class="card-header">
                        <h3>mine/score.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /mine/score.php</div>
                        <p><strong>Назначение:</strong> Обработка результатов добычи в шахте</p>
                        
                        <p>Обрабатывает результаты добычи ресурсов в шахте, распределяет добытые ресурсы и начисляет опыт игроку.</p>
                        
                        <p><strong>Работа с БД:</strong> Обновляет таблицы <code>mine_resources</code>, <code>inventory</code> и <code>users</code>.</p>
                        
                        <p><strong>Взаимодействие:</strong> Вызывается из <code>mine/index.php</code> после завершения сеанса добычи.</p>
                    </div>
                </div>
            </section>

            <section id="main">
                <h2 class="section-title">Основные игровые файлы</h2>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>main.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /main.php</div>
                        <p><strong>Назначение:</strong> Главная страница игры</p>
                        
                        <p>Отображает главную страницу игры после авторизации с основными игровыми меню, новостями и статистикой игрока.</p>
                        
                        <p><strong>Работа с БД:</strong> Получает данные из таблиц <code>users</code>, <code>news</code> и других.</p>
                        
                        <p><strong>Взаимодействие:</strong> Центральная точка навигации по игре, связана со всеми основными разделами.</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>profile.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /profile.php</div>
                        <p><strong>Назначение:</strong> Профиль персонажа</p>
                        
                        <p>Отображает профиль персонажа с его характеристиками, экипировкой, достижениями и другой информацией. Позволяет настраивать персонажа.</p>
                        
                        <p><strong>Работа с БД:</strong> Получает данные из таблиц <code>users</code>, <code>userbag</code>, <code>achievements</code> и других.</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан с системой экипировки, навыков и другими персональными механиками.</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>shop.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /shop.php</div>
                        <p><strong>Назначение:</strong> Игровой магазин</p>
                        
                        <p>Интерфейс игрового магазина, где игроки могут покупать экипировку, зелья, материалы и другие предметы.</p>
                        
                        <p><strong>Работа с БД:</strong> Работает с таблицами <code>shop</code>, <code>userbag</code>, <code>users</code>.</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан с экономической системой и инвентарем игрока.</p>
                    </div>
                </div>

                <div class="card category-core">
                    <div class="card-header">
                        <h3>bank.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /bank.php</div>
                        <p><strong>Назначение:</strong> Банковская система</p>
                        
                        <p>Позволяет игрокам хранить деньги в банке, получать проценты, обменивать различные валюты и совершать другие банковские операции.</p>
                        
                        <p><strong>Работа с БД:</strong> Работает с таблицами <code>users</code>, <code>bank_accounts</code> и другими связанными таблицами.</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан с экономической системой игры.</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>chat.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /chat.php</div>
                        <p><strong>Назначение:</strong> Игровой чат</p>
                        
                        <p>Обеспечивает функциональность игрового чата, позволяет игрокам общаться в общем, клановом и других чатах.</p>
                        
                        <p><strong>Работа с БД:</strong> Работает с таблицами <code>chat</code>, <code>chat_rooms</code>, <code>users</code>.</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан с <code>system/chatread.php</code> и <code>system/chatwrite.php</code> для обработки сообщений.</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>mail.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /mail.php</div>
                        <p><strong>Назначение:</strong> Почтовая система</p>
                        
                        <p>Реализует внутриигровую почтовую систему, позволяет игрокам отправлять и получать личные сообщения, а также системные уведомления.</p>
                        
                        <p><strong>Работа с БД:</strong> Работает с таблицами <code>msg</code> и <code>users</code>.</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан с различными игровыми системами, отправляющими уведомления.</p>
                    </div>
                </div>
            </section>

            <section id="quests">
                <h2 class="section-title">Система квестов</h2>
                
                <div class="card category-functions">
                    <div class="card-header">
                        <h3>quests/index.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /quests/index.php</div>
                        <p><strong>Назначение:</strong> Главная страница квестов</p>
                        
                        <p>Отображает список доступных квестов для игрока, включая текущие, завершенные и доступные для взятия. Показывает информацию о требованиях и наградах.</p>
                        
                        <p><strong>Работа с БД:</strong> Получает данные из таблиц <code>quests</code>, <code>quests_users</code> и <code>users</code>.</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан с <code>quests/take.php</code> для взятия квестов и <code>quests/complete.php</code> для завершения квестов.</p>
                    </div>
                </div>
                
                <div class="card category-functions">
                    <div class="card-header">
                        <h3>quests/take.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /quests/take.php</div>
                        <p><strong>Назначение:</strong> Взятие квеста</p>
                        
                        <p>Обрабатывает взятие квеста игроком, проверяет требования и создает соответствующую запись в базе данных.</p>
                        
                        <p><strong>Работа с БД:</strong> Создает записи в таблице <code>quests_users</code> и обновляет другие связанные таблицы.</p>
                        
                        <p><strong>Взаимодействие:</strong> Вызывается из <code>quests/index.php</code> при взятии квеста.</p>
                    </div>
                </div>
                
                <div class="card category-functions">
                    <div class="card-header">
                        <h3>quests/complete.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /quests/complete.php</div>
                        <p><strong>Назначение:</strong> Завершение квеста</p>
                        
                        <p>Обрабатывает завершение квеста, проверяет выполнение условий, выдает награды и обновляет прогресс игрока.</p>
                        
                        <p><strong>Работа с БД:</strong> Обновляет таблицы <code>quests_users</code>, <code>users</code>, <code>inventory</code> и другие.</p>
                        
                        <p><strong>Взаимодействие:</strong> Вызывается при выполнении всех условий квеста либо из <code>quests/index.php</code>.</p>
                    </div>
                </div>
                
                <div class="card category-functions">
                    <div class="card-header">
                        <h3>functions/check_quests.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /functions/check_quests.php</div>
                        <p><strong>Назначение:</strong> Проверка прогресса квестов</p>
                        
                        <p>Содержит функции для проверки прогресса квестов и обновления состояния квестов при различных действиях игрока (убийство монстров, сбор предметов и т.д.).</p>
                        
                        <p><strong>Работа с БД:</strong> Работает с таблицами <code>quests_users</code>, <code>quests</code> и другими связанными таблицами.</p>
                        
                        <p><strong>Взаимодействие:</strong> Используется во многих файлах игры для обновления прогресса квестов при соответствующих действиях.</p>
                    </div>
                </div>
            </section>
            
            <section id="admin">
                <h2 class="section-title">Административная система</h2>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>admin/index.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /admin/index.php</div>
                        <p><strong>Назначение:</strong> Главная страница панели администратора</p>
                        
                        <p>Главная страница административного интерфейса с меню доступа к различным инструментам администрирования и модерации.</p>
                        
                        <p><strong>Работа с БД:</strong> Получает общую статистику из различных таблиц.</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан со всеми административными инструментами.</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>admin/users.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /admin/users.php</div>
                        <p><strong>Назначение:</strong> Управление пользователями</p>
                        
                        <p>Предоставляет интерфейс для управления пользователями, включая поиск, редактирование профилей, блокировку и другие действия.</p>
                        
                        <p><strong>Работа с БД:</strong> Работает с таблицей <code>users</code> и связанными таблицами.</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан с <code>admin/user_edit.php</code> для редактирования пользователей.</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>admin/items.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /admin/items.php</div>
                        <p><strong>Назначение:</strong> Управление предметами</p>
                        
                        <p>Позволяет администраторам управлять игровыми предметами, создавать новые, редактировать и удалять существующие.</p>
                        
                        <p><strong>Работа с БД:</strong> Работает с таблицами <code>shop</code>, <code>items</code> и связанными таблицами.</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан с <code>admin/item_edit.php</code> для редактирования предметов.</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>admin/monsters.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /admin/monsters.php</div>
                        <p><strong>Назначение:</strong> Управление монстрами</p>
                        
                        <p>Предоставляет интерфейс для управления монстрами в игре, включая создание, редактирование и настройку дропа.</p>
                        
                        <p><strong>Работа с БД:</strong> Работает с таблицами <code>hunt</code>, <code>monsters</code> и связанными таблицами.</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан с <code>admin/monster_edit.php</code> для редактирования монстров.</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>admin/logs.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /admin/logs.php</div>
                        <p><strong>Назначение:</strong> Система логов</p>
                        
                        <p>Отображает системные логи различных действий в игре, включая транзакции, бои, использование предметов и другие важные события.</p>
                        
                        <p><strong>Работа с БД:</strong> Получает данные из таблиц <code>logs</code>, <code>transaction_logs</code> и других логовых таблиц.</p>
                        
                        <p><strong>Взаимодействие:</strong> Используется для мониторинга и аудита игровых действий.</p>
                    </div>
                </div>
            </section>
            
            <section id="api">
                <h2 class="section-title">API и интеграция</h2>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>api/index.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /api/index.php</div>
                        <p><strong>Назначение:</strong> Главный файл API</p>
                        
                        <p>Основной файл API, обрабатывающий входящие запросы и маршрутизирующий их к соответствующим обработчикам.</p>
                        
                        <p><strong>Работа с БД:</strong> Зависит от конкретного запроса.</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан с различными файлами API для обработки конкретных типов запросов.</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>api/auth.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /api/auth.php</div>
                        <p><strong>Назначение:</strong> Аутентификация API</p>
                        
                        <p>Обрабатывает запросы на аутентификацию и авторизацию через API, выдает и проверяет токены доступа.</p>
                        
                        <p><strong>Работа с БД:</strong> Работает с таблицами <code>users</code>, <code>api_tokens</code> и связанными таблицами.</p>
                        
                        <p><strong>Взаимодействие:</strong> Используется другими API-методами для проверки аутентификации.</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>api/user.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /api/user.php</div>
                        <p><strong>Назначение:</strong> API для работы с пользователями</p>
                        
                        <p>Предоставляет API-методы для получения информации о пользователях, обновления профилей и выполнения других действий, связанных с пользователями.</p>
                        
                        <p><strong>Работа с БД:</strong> Работает с таблицей <code>users</code> и связанными таблицами.</p>
                        
                        <p><strong>Взаимодействие:</strong> Используется внешними системами для интеграции с пользовательскими данными.</p>
                    </div>
                </div>
            </section>
            
            <section id="system-files">
                <h2 class="section-title">Дополнительные системные файлы</h2>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>system/header.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /system/header.php</div>
                        <p><strong>Назначение:</strong> Шапка страниц</p>
                        
                        <p>Содержит HTML-код шапки страниц, включая навигационное меню, информацию о персонаже и общие элементы интерфейса.</p>
                        
                        <p><strong>Работа с БД:</strong> Получает базовую информацию о пользователе и системные настройки.</p>
                        
                        <p><strong>Взаимодействие:</strong> Включается в большинство страниц игры для отображения общего заголовка.</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>system/chatread.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /system/chatread.php</div>
                        <p><strong>Назначение:</strong> Чтение сообщений чата</p>
                        
                        <p>Обрабатывает запросы на получение сообщений чата, включая фильтрацию по каналам и обработку командных сообщений.</p>
                        
                        <p><strong>Работа с БД:</strong> Получает данные из таблицы <code>chat</code> и связанных таблиц.</p>
                        
                        <p><strong>Взаимодействие:</strong> Используется в <code>chat.php</code> и других файлах, отображающих чат.</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>system/chatwrite.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /system/chatwrite.php</div>
                        <p><strong>Назначение:</strong> Отправка сообщений в чат</p>
                        
                        <p>Обрабатывает отправку сообщений в чат, включая проверку на спам, фильтрацию контента и обработку команд.</p>
                        
                        <p><strong>Работа с БД:</strong> Записывает сообщения в таблицу <code>chat</code> и связанные таблицы.</p>
                        
                        <p><strong>Взаимодействие:</strong> Используется в <code>chat.php</code> и других файлах с возможностью отправки сообщений.</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>system/banned.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /system/banned.php</div>
                        <p><strong>Назначение:</strong> Система блокировок</p>
                        
                        <p>Обрабатывает проверку и отображение информации о блокировке аккаунта, если пользователь заблокирован.</p>
                        
                        <p><strong>Работа с БД:</strong> Получает информацию о блокировке из таблиц <code>ban</code> и <code>users</code>.</p>
                        
                        <p><strong>Взаимодействие:</strong> Вызывается системой авторизации при обнаружении блокировки.</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>system/time.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /system/time.php</div>
                        <p><strong>Назначение:</strong> Обработка времени</p>
                        
                        <p>Содержит функции для работы со временем, включая форматирование, расчет временных интервалов и управление игровым временем.</p>
                        
                        <p><strong>Работа с БД:</strong> Минимальная, в основном для получения системных настроек времени.</p>
                        
                        <p><strong>Взаимодействие:</strong> Используется во многих файлах для корректной работы со временем.</p>
                    </div>
                </div>
            </section>

            <div class="card">
                <div class="card-header">
                    <h3>Дополнительные таблицы</h3>
                </div>
                <div class="card-body">
                    <table>
                        <thead>
                            <tr>
                                <th>Таблица</th>
                                <th>Описание</th>
                                <th>Связанные файлы</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>shop</code></td>
                                <td>Информация о предметах в магазине</td>
                                <td>shop.php, functions/wesh_*.php</td>
                            </tr>
                            <tr>
                                <td><code>userbag</code></td>
                                <td>Инвентарь игроков</td>
                                <td>profile.php, shop.php, functions/wesh_*.php</td>
                            </tr>
                            <tr>
                                <td><code>hunt</code></td>
                                <td>Информация о монстрах для охоты</td>
                                <td>hunt/*.php</td>
                            </tr>
                            <tr>
                                <td><code>quests</code></td>
                                <td>Список квестов</td>
                                <td>quests/*.php</td>
                            </tr>
                            <tr>
                                <td><code>quests_users</code></td>
                                <td>Квесты, взятые игроками</td>
                                <td>quests/*.php, functions/check_quests.php</td>
                            </tr>
                            <tr>
                                <td><code>chat</code></td>
                                <td>Сообщения чата</td>
                                <td>chat.php, system/chat*.php</td>
                            </tr>
                            <tr>
                                <td><code>dungeons</code></td>
                                <td>Информация о подземельях</td>
                                <td>dungeons/*.php</td>
                            </tr>
                            <tr>
                                <td><code>dungeons_progress</code></td>
                                <td>Прогресс игроков в подземельях</td>
                                <td>dungeons/*.php</td>
                            </tr>
                            <tr>
                                <td><code>dungeons_monsters</code></td>
                                <td>Монстры в подземельях</td>
                                <td>dungeons/*.php</td>
                            </tr>
                            <tr>
                                <td><code>mine_resources</code></td>
                                <td>Ресурсы для добычи в шахте</td>
                                <td>mine/*.php</td>
                            </tr>
                            <tr>
                                <td><code>duels</code></td>
                                <td>Информация о дуэлях</td>
                                <td>battle/pvp.php</td>
                            </tr>
                            <tr>
                                <td><code>logs</code></td>
                                <td>Системные логи</td>
                                <td>admin/logs.php</td>
                            </tr>
                            <tr>
                                <td><code>settings</code></td>
                                <td>Настройки системы</td>
                                <td>admin/settings.php, system/*.php</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <section id="additional">
                <h2 class="section-title">Дополнительные файлы</h2>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>profile.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /profile.php</div>
                        <p><strong>Назначение:</strong> Страница профиля пользователя</p>
                        
                        <p>Отображает и позволяет редактировать профиль персонажа, его характеристики, снаряжение, навыки, достижения и другую персональную информацию.</p>
                        
                        <p><strong>Работа с БД:</strong> Получает и обновляет данные из таблиц <code>users</code>, <code>userbag</code>, <code>skills</code>, <code>achievements</code> и других.</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан с системой инвентаря, экипировки и другими персональными механиками.</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>equip.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /equip.php</div>
                        <p><strong>Назначение:</strong> Система экипировки</p>
                        
                        <p>Позволяет игрокам просматривать, надевать и снимать предметы экипировки. Отображает статистику предметов и их влияние на характеристики персонажа.</p>
                        
                        <p><strong>Работа с БД:</strong> Работает с таблицами <code>userbag</code>, <code>users</code>, <code>equip</code>.</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан с <code>profile.php</code> и системой предметов.</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>friends.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /friends.php</div>
                        <p><strong>Назначение:</strong> Система друзей</p>
                        
                        <p>Позволяет игрокам добавлять других игроков в друзья, просматривать список друзей, отправлять запросы на дружбу и управлять существующими дружескими связями.</p>
                        
                        <p><strong>Работа с БД:</strong> Работает с таблицами <code>friends</code>, <code>users</code>.</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан с профилями игроков и системой сообщений.</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>premium.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /premium.php</div>
                        <p><strong>Назначение:</strong> Система премиум-аккаунта</p>
                        
                        <p>Предоставляет информацию о премиум-возможностях, позволяет приобрести премиум-статус и активировать дополнительные функции для премиум-игроков.</p>
                        
                        <p><strong>Работа с БД:</strong> Работает с таблицами <code>users</code>, <code>premium</code>, <code>payments</code>.</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан с платежными системами и другими игровыми механиками.</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>top.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /top.php</div>
                        <p><strong>Назначение:</strong> Рейтинги игроков и кланов</p>
                        
                        <p>Отображает различные рейтинговые таблицы игроков и кланов по разным критериям (уровень, богатство, PvP и другие).</p>
                        
                        <p><strong>Работа с БД:</strong> Получает данные из таблиц <code>users</code>, <code>clan</code> и других для формирования рейтингов.</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан с профилями игроков и страницами кланов.</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>online.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /online.php</div>
                        <p><strong>Назначение:</strong> Список онлайн-игроков</p>
                        
                        <p>Отображает список игроков, находящихся онлайн, с возможностью фильтрации и сортировки по различным параметрам.</p>
                        
                        <p><strong>Работа с БД:</strong> Получает данные из таблицы <code>users</code> с фильтром по статусу онлайн.</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан с профилями игроков и системой статусов.</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>support.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /support.php</div>
                        <p><strong>Назначение:</strong> Система поддержки</p>
                        
                        <p>Позволяет игрокам создавать тикеты в службу поддержки, отслеживать статус обращений и общаться с администрацией по вопросам игры.</p>
                        
                        <p><strong>Работа с БД:</strong> Работает с таблицами <code>support_tickets</code>, <code>support_messages</code> и <code>users</code>.</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан с административными инструментами и системой уведомлений.</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>shop_heroes.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /shop_heroes.php</div>
                        <p><strong>Назначение:</strong> Магазин героев</p>
                        
                        <p>Специализированный магазин для покупки и улучшения героев, которые могут сопровождать игрока в боях и выполнять различные функции.</p>
                        
                        <p><strong>Работа с БД:</strong> Работает с таблицами <code>shop_heroes</code>, <code>heroes</code>, <code>users</code>.</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан с боевой системой и экономикой игры.</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>ref.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /ref.php</div>
                        <p><strong>Назначение:</strong> Реферальная система</p>
                        
                        <p>Позволяет игрокам приглашать новых игроков через реферальные ссылки и получать за это вознаграждение. Отображает статистику рефералов.</p>
                        
                        <p><strong>Работа с БД:</strong> Работает с таблицами <code>referrals</code>, <code>users</code>.</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан с системой регистрации и платежной системой.</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>gift.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /gift.php</div>
                        <p><strong>Назначение:</strong> Система подарков</p>
                        
                        <p>Позволяет игрокам отправлять и получать подарки в виде игровых предметов, ресурсов или валюты.</p>
                        
                        <p><strong>Работа с БД:</strong> Работает с таблицами <code>gifts</code>, <code>users</code>, <code>userbag</code>.</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан с системой инвентаря и социальными механиками игры.</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>help.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /help.php</div>
                        <p><strong>Назначение:</strong> Справочная система</p>
                        
                        <p>Предоставляет справочную информацию по игровым механикам, правилам, возможностям и интерфейсу для помощи игрокам.</p>
                        
                        <p><strong>Работа с БД:</strong> Получает данные из таблицы <code>help</code> и <code>help_categories</code>.</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан с различными игровыми системами через справочные материалы.</p>
                    </div>
                </div>
            </section>

            <section id="monetary">
                <h2 class="section-title">Платежные системы</h2>
                
                <div class="card category-functions">
                    <div class="card-header">
                        <h3>pay.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /pay.php</div>
                        <p><strong>Назначение:</strong> Основной файл системы платежей</p>
                        
                        <p>Предоставляет интерфейс для пополнения игрового счета различными способами, обрабатывает инициирование платежей через платежные шлюзы.</p>
                        
                        <p><strong>Работа с БД:</strong> Создает записи в таблицах <code>payments</code>, <code>payment_transactions</code> и обновляет данные пользователя.</p>
                        
                        <p><strong>Взаимодействие:</strong> Связан с различными платежными системами и обработчиками платежей.</p>
                    </div>
                </div>
                
                <div class="card category-functions">
                    <div class="card-header">
                        <h3>freekassa.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /freekassa.php</div>
                        <p><strong>Назначение:</strong> Интеграция с платежной системой FreeKassa</p>
                        
                        <p>Обрабатывает платежи через платежную систему FreeKassa, включая создание платежей и обработку уведомлений о результатах платежа.</p>
                        
                        <p><strong>Работа с БД:</strong> Обновляет записи в таблицах <code>payments</code>, <code>users</code> при успешном пополнении счета.</p>
                        
                        <p><strong>Взаимодействие:</strong> Вызывается из <code>pay.php</code> при выборе данного способа оплаты и из обработчиков уведомлений.</p>
                    </div>
                </div>
                
                <div class="card category-functions">
                    <div class="card-header">
                        <h3>robo1.php и robo2.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /robo1.php, /robo2.php</div>
                        <p><strong>Назначение:</strong> Интеграция с платежной системой Robokassa</p>
                        
                        <p>Обрабатывают платежи через Robokassa: robo1.php отвечает за инициацию платежа, а robo2.php - за обработку результатов платежа.</p>
                        
                        <p><strong>Работа с БД:</strong> Обновляют записи в таблицах <code>payments</code>, <code>users</code> при успешном пополнении счета.</p>
                        
                        <p><strong>Взаимодействие:</strong> Вызываются из <code>pay.php</code> при выборе данного способа оплаты и из обработчиков уведомлений.</p>
                    </div>
                </div>
            </section>

            <section id="registration-auth">
                <h2 class="section-title">Регистрация и авторизация</h2>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>index.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /index.php</div>
                        <p><strong>Назначение:</strong> Страница авторизации</p>
                        
                        <p>Основная страница входа в игру, позволяет пользователям авторизоваться, содержит ссылки на регистрацию и восстановление пароля.</p>
                        
                        <p><strong>Работа с БД:</strong> Проверяет учетные данные в таблице <code>users</code>, обновляет статусы и времена последнего входа.</p>
                        
                        <p><strong>Взаимодействие:</strong> Перенаправляет на <code>main.php</code> после успешной авторизации.</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>registration.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /registration.php</div>
                        <p><strong>Назначение:</strong> Регистрация нового пользователя</p>
                        
                        <p>Предоставляет форму для регистрации нового игрока, обрабатывает введенные данные, создает новый аккаунт и инициализирует базовые данные персонажа.</p>
                        
                        <p><strong>Работа с БД:</strong> Создает записи в таблицах <code>users</code>, <code>userbag</code> и других таблицах при регистрации.</p>
                        
                        <p><strong>Взаимодействие:</strong> Перенаправляет на <code>index.php</code> для входа после успешной регистрации.</p>
                    </div>
                </div>
                
                <div class="card category-core">
                    <div class="card-header">
                        <h3>out.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /out.php</div>
                        <p><strong>Назначение:</strong> Выход из аккаунта</p>
                        
                        <p>Обрабатывает выход пользователя из аккаунта, очищает сессию и перенаправляет на страницу входа.</p>
                        
                        <p><strong>Работа с БД:</strong> Обновляет статус пользователя в таблице <code>users</code>, устанавливая статус оффлайн.</p>
                        
                        <p><strong>Взаимодействие:</strong> Перенаправляет на <code>index.php</code> после выхода.</p>
                    </div>
                </div>
            </section>

            <section id="action-files">
                <h2 class="section-title">Файлы действий</h2>
                
                <div class="card category-functions">
                    <div class="card-header">
                        <h3>action.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /action.php</div>
                        <p><strong>Назначение:</strong> Обработчик игровых действий</p>
                        
                        <p>Обрабатывает различные игровые действия, перенаправляя запросы к соответствующим обработчикам в зависимости от параметра действия.</p>
                        
                        <p><strong>Работа с БД:</strong> Зависит от конкретного действия, работает с различными таблицами.</p>
                        
                        <p><strong>Взаимодействие:</strong> Вызывается из различных частей игры для выполнения действий.</p>
                    </div>
                </div>
                
                <div class="card category-functions">
                    <div class="card-header">
                        <h3>disconnect.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /disconnect.php</div>
                        <p><strong>Назначение:</strong> Обработка разрыва соединения</p>
                        
                        <p>Обрабатывает случаи разрыва соединения с игрой, корректно завершая сессию и сохраняя статус пользователя.</p>
                        
                        <p><strong>Работа с БД:</strong> Обновляет статус пользователя в таблице <code>users</code>, фиксирует время выхода.</p>
                        
                        <p><strong>Взаимодействие:</strong> Вызывается автоматически при некорректном завершении сессии.</p>
                    </div>
                </div>
                
                <div class="card category-functions">
                    <div class="card-header">
                        <h3>changeParams.php</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /changeParams.php</div>
                        <p><strong>Назначение:</strong> Изменение параметров персонажа</p>
                        
                        <p>Обрабатывает изменение различных параметров персонажа: характеристик, внешнего вида, настроек и других атрибутов.</p>
                        
                        <p><strong>Работа с БД:</strong> Обновляет данные в таблице <code>users</code> и других связанных таблицах.</p>
                        
                        <p><strong>Взаимодействие:</strong> Вызывается из <code>profile.php</code> и других страниц настройки персонажа.</p>
                    </div>
                </div>
            </section>

            <section id="visual-files">
                <h2 class="section-title">Визуальные компоненты и CSS</h2>
                
                <div class="card category-functions">
                    <div class="card-header">
                        <h3>style.css</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /style.css, /styles.css</div>
                        <p><strong>Назначение:</strong> Основные стили игры</p>
                        
                        <p>Содержат основные CSS-стили для оформления игровых страниц, включая цвета, размеры, позиционирование элементов и другие визуальные аспекты.</p>
                        
                        <p><strong>Взаимодействие:</strong> Подключаются в <code>header.php</code> и влияют на все страницы игры.</p>
                    </div>
                </div>
                
                <div class="card category-functions">
                    <div class="card-header">
                        <h3>equip-mobile.css</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /equip-mobile.css</div>
                        <p><strong>Назначение:</strong> Стили для мобильной версии экипировки</p>
                        
                        <p>Содержит специальные CSS-стили для адаптации интерфейса экипировки под мобильные устройства.</p>
                        
                        <p><strong>Взаимодействие:</strong> Подключается в <code>equip.php</code> и других файлах экипировки при обнаружении мобильного устройства.</p>
                    </div>
                </div>
                
                <div class="card category-functions">
                    <div class="card-header">
                        <h3>equip_styles.css</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /equip_styles.css</div>
                        <p><strong>Назначение:</strong> Стили системы экипировки</p>
                        
                        <p>Содержит специальные CSS-стили для интерфейса экипировки персонажа, включая слоты, визуализацию характеристик и другие элементы.</p>
                        
                        <p><strong>Взаимодействие:</strong> Подключается в <code>equip.php</code> и других файлах, связанных с экипировкой.</p>
                    </div>
                </div>
                
                <div class="card category-functions">
                    <div class="card-header">
                        <h3>scripts.js</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /scripts.js</div>
                        <p><strong>Назначение:</strong> Основные JavaScript-скрипты</p>
                        
                        <p>Содержит общие JavaScript-функции, используемые на различных страницах игры для интерактивности и динамического обновления контента.</p>
                        
                        <p><strong>Взаимодействие:</strong> Подключается в <code>header.php</code> и используется на большинстве страниц.</p>
                    </div>
                </div>
                
                <div class="card category-functions">
                    <div class="card-header">
                        <h3>equip-mobile.js</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /equip-mobile.js</div>
                        <p><strong>Назначение:</strong> JavaScript для мобильной версии экипировки</p>
                        
                        <p>Содержит специальные JavaScript-функции для адаптации интерфейса экипировки под мобильные устройства, включая жесты и специальные взаимодействия.</p>
                        
                        <p><strong>Взаимодействие:</strong> Подключается в <code>equip.php</code> при обнаружении мобильного устройства.</p>
                    </div>
                </div>
            </section>

            <section id="directories">
                <h2 class="section-title">Дополнительные директории</h2>
                
                <div class="card category-functions">
                    <div class="card-header">
                        <h3>js/ и javascript/</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /js/, /javascript/</div>
                        <p><strong>Назначение:</strong> JavaScript-библиотеки и скрипты</p>
                        
                        <p>Директории содержат JavaScript-библиотеки, скрипты и модули, используемые в различных частях игры для интерактивности и динамического обновления контента.</p>
                        
                        <p><strong>Взаимодействие:</strong> Скрипты подключаются на различных страницах в зависимости от требуемой функциональности.</p>
                    </div>
                </div>
                
                <div class="card category-functions">
                    <div class="card-header">
                        <h3>css/ и styles/</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /css/, /styles/</div>
                        <p><strong>Назначение:</strong> CSS-стили и темы</p>
                        
                        <p>Директории содержат файлы CSS-стилей для различных компонентов игры, темы оформления и специальные стили для отдельных страниц.</p>
                        
                        <p><strong>Взаимодействие:</strong> Стили подключаются в <code>header.php</code> или на конкретных страницах для специфического оформления.</p>
                    </div>
                </div>
                
                <div class="card category-functions">
                    <div class="card-header">
                        <h3>images/ и img/</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /images/, /img/</div>
                        <p><strong>Назначение:</strong> Изображения и графика</p>
                        
                        <p>Директории содержат графические файлы, используемые в игре: иконки, аватары, фоны, интерфейсные элементы, изображения предметов, монстров и других игровых объектов.</p>
                        
                        <p><strong>Взаимодействие:</strong> Изображения используются во всех частях игры для визуального оформления.</p>
                    </div>
                </div>
                
                <div class="card category-functions">
                    <div class="card-header">
                        <h3>sounds/</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /sounds/</div>
                        <p><strong>Назначение:</strong> Звуковые файлы</p>
                        
                        <p>Директория содержит звуковые файлы, используемые в игре: музыкальное сопровождение, звуковые эффекты для боев, действий и событий.</p>
                        
                        <p><strong>Взаимодействие:</strong> Звуки воспроизводятся JavaScript-кодом в соответствующих игровых ситуациях.</p>
                    </div>
                </div>
                
                <div class="card category-functions">
                    <div class="card-header">
                        <h3>logs/</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /logs/</div>
                        <p><strong>Назначение:</strong> Логи системы</p>
                        
                        <p>Директория содержит файлы логов различных действий в системе, ошибок и других событий для отладки и мониторинга.</p>
                        
                        <p><strong>Взаимодействие:</strong> Используется системными скриптами для записи логов и административными инструментами для их анализа.</p>
                    </div>
                </div>
                
                <div class="card category-functions">
                    <div class="card-header">
                        <h3>template/</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /template/</div>
                        <p><strong>Назначение:</strong> Шаблоны</p>
                        
                        <p>Директория содержит шаблоны страниц и компонентов, используемые для генерации HTML-кода с единым оформлением.</p>
                        
                        <p><strong>Взаимодействие:</strong> Шаблоны подключаются в PHP-файлах для формирования HTML-структуры страниц.</p>
                    </div>
                </div>
                
                <div class="card category-functions">
                    <div class="card-header">
                        <h3>modules/</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /modules/</div>
                        <p><strong>Назначение:</strong> Модули системы</p>
                        
                        <p>Директория содержит модульные компоненты системы, подключаемые в различных частях игры для обеспечения специфической функциональности.</p>
                        
                        <p><strong>Взаимодействие:</strong> Модули подключаются по необходимости в соответствующих файлах.</p>
                    </div>
                </div>
                
                <div class="card category-functions">
                    <div class="card-header">
                        <h3>animator/ и newanimator/</h3>
                    </div>
                    <div class="card-body">
                        <div class="file-path">Путь: /animator/, /newanimator/</div>
                        <p><strong>Назначение:</strong> Системы анимации</p>
                        
                        <p>Директории содержат файлы для анимации игровых объектов, боевых действий и других динамических элементов игры.</p>
                        
                        <p><strong>Взаимодействие:</strong> Используются в боевой системе и других частях игры, требующих анимации.</p>
                    </div>
                </div>
            </section>
        </main>
    </div>
    
    <button id="top-button" title="Наверх">↑</button>
    
    <script>
        // Скрипт для поиска по документации
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchQuery = this.value.toLowerCase();
            const fileCards = document.querySelectorAll('.card');
            
            fileCards.forEach(card => {
                const cardContent = card.textContent.toLowerCase();
                if (cardContent.includes(searchQuery)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
        
        // Кнопка прокрутки наверх
        const topButton = document.getElementById('top-button');
        
        window.onscroll = function() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                topButton.style.display = 'block';
            } else {
                topButton.style.display = 'none';
            }
        };
        
        topButton.addEventListener('click', function() {
            document.body.scrollTop = 0; // Для Safari
            document.documentElement.scrollTop = 0; // Для Chrome, Firefox, IE, Opera
        });
    </script>
</body>
</html>

<?php
$footval = "docs";
require_once '../system/foot/foot.php';
?> 