<?php
require_once '../../system/func.php';
require_once '../../system/dbc.php';
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Уровень доступа
if (isset($user) && $user['access'] > 2) {
    $zvanieArrAll = $mc->query("SELECT * FROM `slava` ORDER BY `slava`.`slava` ASC")->fetch_all(MYSQLI_ASSOC);
    $allloc = $mc->query("SELECT * FROM `location`")->fetch_all(MYSQLI_ASSOC);
    $allquest = $mc->query("SELECT * FROM `quests`")->fetch_all(MYSQLI_ASSOC);

    $arrqueestsonlock = [];
    foreach ($allquest as $quest) {
        $arrqueestsonlock['loc' . $quest['locId']][] = $quest;
    }

    $alllocNew = [];
    foreach ($allloc as $loc) {
        $alllocNew[$loc['id']] = $loc;
    }

    // Индексируем массив квестов по локациям
    $tempArr = [];
    foreach ($arrqueestsonlock as $locQuests) {
        $tempArr[] = $locQuests;
    }
    $arrqueestsonlock = $tempArr;


    $arrIco = [
        0 => "",
        1 => "<img height='19' src='/img/icon/icogood.png' width='19' alt='Норм.' title='Нормасцы'>",
        2 => "<img height='19' src='/img/icon/icoevil.png' width='19' alt='Шейв.' title='Шейване'>"
    ];
    $arrIcoAlt = [
        0 => "",
        1 => "Н->",
        2 => "Ш->"
    ];

    $pers_img_arr = [
        "",
        "../img/qestpers/GOL_app_quest-merchant.png",
        "../img/qestpers/GOL_app_quest-warrior.png",
        "../img/qestpers/GOL_app_quest-drunkard.png",
        "../img/qestpers/GOL_app_quest-farrier.png",
        "../img/qestpers/GOL_app_quest-girl.png",
        "../img/qestpers/GOL_app_quest-skeleton.png",
        "../img/qestpers/GOL_app_quest-spy.png"
    ];

    // Обработка AJAX запроса для поиска
    if (isset($_POST['action']) && $_POST['action'] == 'search_items') {
        header('Content-Type: application/json;charset=utf-8');

        $term = $mc->real_escape_string(urldecode($_POST['term'] ?? ''));
        $type = $_POST['type'] ?? 'shop'; // 'shop' | 'monsters'
        $results = [];

        if ($term !== '') {

            // --- универсальная выборка без риска для имен полей ---
            if ($type === 'shop') {
                $query = "SELECT * FROM `shop` WHERE `name` LIKE '%$term%' LIMIT 20";
            } else { // monsters
                $query = "SELECT * FROM `monsters` WHERE `name` LIKE '%$term%' LIMIT 20";
            }

            if ($db_results = $mc->query($query)) {
                while ($row = $db_results->fetch_assoc()) {

                    // определяем, в каком столбце лежит картинка
                    $imgField = '';
                    foreach (['images', 'image', 'img', 'icon'] as $fld) {
                        if (!empty($row[$fld])) {
                            $imgField = $row[$fld];
                            break;
                        }
                    }

                    $results[] = [
                        'id'   => $row['id'],
                        'name' => $row['name'],
                        'img'  => ($type === 'shop'
                                   ? '/img/shop/'  . $imgField . '.png'
                                   : '/img/mobs/'  . $imgField . '.png'),
                        'type' => $type
                    ];
                }
            }
        }

        echo json_encode($results, JSON_UNESCAPED_UNICODE);
        exit;
    }

    ?>
    <style>
        /* Общие стили */
        .quest-admin-container {
            font-family: 'Montserrat', sans-serif;
            padding: 15px;
            max-width: 900px;
            margin: auto;
            color: #e0e0e0; /* Светлый текст для темного фона */
        }

        .quest-card, .element-card {
            background-color: rgba(0, 0, 0, 0.4);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }

        .quest-card h2, .quest-card h3, .element-card h3 {
            color: #f0c060; /* Золотистый цвет для заголовков */
            margin-top: 0;
            margin-bottom: 15px;
            border-bottom: 1px solid rgba(240, 192, 96, 0.5);
            padding-bottom: 5px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #c0c0c0; /* Серый для меток */
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 8px 10px;
            margin-bottom: 10px;
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            color: #e0e0e0;
            box-sizing: border-box; /* Учитывает padding и border в ширине */
            font-family: inherit;
            font-size: 14px;
        }
        input[type="number"] {
             -moz-appearance: textfield; /* Firefox */
        }
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0; /* Safari and Chrome */
        }

        textarea {
            min-height: 80px;
            resize: vertical;
        }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='5' viewBox='0 0 10 5'%3E%3Cpath fill='%23c0c0c0' d='M0 0l5 5 5-5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            padding-right: 30px; /* Место для стрелки */
        }
         select option {
            background-color: #333; /* Темный фон для опций */
            color: #e0e0e0;
        }

        .button_alt_01, .search-button, .remove-item-btn, .add-item-btn {
            background: linear-gradient(to bottom, #f0c060, #c08030); /* Золотистый градиент */
            border: none;
            color: #201508; /* Темный текст */
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-align: center;
            transition: background 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.4);
            display: inline-block;
            margin: 5px 0;
        }

        .button_alt_01:hover, .search-button:hover, .remove-item-btn:hover, .add-item-btn:hover {
            background: linear-gradient(to bottom, #ffdb8a, #d99740);
            box-shadow: 0 2px 5px rgba(0,0,0,0.5);
        }

        .buttdelete {
             background: linear-gradient(to bottom, #d9534f, #c9302c); /* Красный градиент */
             color: white;
        }
        .buttdelete:hover {
             background: linear-gradient(to bottom, #e76864, #d64742);
        }

        details {
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            margin-bottom: 15px;
            background-color: rgba(0, 0, 0, 0.2);
        }

        summary {
            padding: 10px;
            cursor: pointer;
            font-weight: bold;
            color: #f0c060;
            background-color: rgba(240, 192, 96, 0.1);
            border-radius: 5px 5px 0 0;
            outline: none; /* Убрать стандартный фокус */
        }
        details[open] summary {
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
             border-radius: 5px 5px 0 0;
        }


        .details-content {
            padding: 15px;
        }

        /* Стили для списка квестов */
        .quest-list details {
             background-color: rgba(0, 0, 0, 0.3);
        }
        .quest-list summary {
            background-color: rgba(240, 192, 96, 0.15);
            color: #f5d080; /* Светлее золотой */
        }
        .quest-list a {
            color: #80caff; /* Голубой для ссылок */
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .quest-list a:hover {
            color: #aaddff;
        }
        .quest-list .quest-comment {
            color: #aaa;
            font-size: 0.9em;
            margin-left: 5px;
        }

        /* Стили для формы создания/редактирования */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }

        .input-group {
            display: flex;
            align-items: stretch; /* Кнопка и поле одной высоты */
            margin-bottom: 10px;
        }
        .input-group input[type="text"], .input-group textarea {
             flex-grow: 1; /* Поле занимает доступное место */
             margin-bottom: 0;
             border-top-right-radius: 0;
             border-bottom-right-radius: 0;
        }
        .input-group .search-button {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            padding: 8px 12px;
             margin-left: -1px; /* Убрать двойную границу */
        }
        .input-group textarea {
            resize: none; /* Убрать изменение размера в группе */
        }

        .selected-items-display {
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            padding: 10px;
            margin-top: 5px;
            min-height: 40px; /* Чтобы было видно, даже если пусто */
            font-size: 0.9em;
            color: #ccc;
        }
         .selected-item {
            display: inline-flex; /* Для центрирования и кнопки */
            align-items: center;
            background-color: rgba(0, 0, 0, 0.3);
            padding: 3px 8px;
            border-radius: 15px;
            margin: 3px;
            font-size: 0.9em;
         }
        .selected-item img {
            width: 16px;
            height: 16px;
            margin-right: 5px;
            vertical-align: middle;
        }
        .selected-item span {
            margin-right: 5px;
        }
         .remove-item-btn {
            background: #dc3545; /* Красный */
            color: white;
            border: none;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            font-size: 10px;
            line-height: 16px; /* Центрировать крестик */
            text-align: center;
            cursor: pointer;
            padding: 0;
            margin-left: 5px;
         }
         .remove-item-btn:hover {
              background: #c82333;
         }

        /* Стили для элементов (частей квеста) */
        .elements {
            border: 1px dashed rgba(255, 255, 255, 0.3);
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            position: relative; /* Для позиционирования кнопок */
        }
        .element-controls {
            text-align: center;
            margin-bottom: 15px;
            font-size: 24px;
            color: #f0c060;
        }
        .element-controls .counts {
             font-weight: bold;
             margin: 0 15px;
        }
        .element-controls .control-arrow {
            cursor: pointer;
            transition: color 0.3s ease;
        }
        .element-controls .control-arrow:hover {
            color: #ffd700; /* Ярче золото */
        }
        .delete-element-btn-container {
            text-align: center;
            margin-top: 20px;
        }

        /* Стили для иконок персов */
        .pers-icon-selector img {
            width: 60px; /* Уменьшил размер */
            height: 60px;
            border-radius: 8px;
            background-color: rgba(128, 128, 128, 0.5);
            border: 2px solid transparent;
            cursor: pointer;
            margin: 5px;
            transition: border-color 0.3s ease, transform 0.2s ease;
        }
        .pers-icon-selector img:hover {
            border-color: #f0c060;
            transform: scale(1.1);
        }
         .pers-icon-selector img.selected-icon {
              border-color: #ff6347; /* Выделение выбранной иконки */
              transform: scale(1.05);
         }


        /* Модальное окно */
        .modal {
            display: none;
            position: fixed;
            z-index: 1001;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.8);
        }

        .modal-content {
            background-color: #2c2c2c; /* Темный фон модалки */
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 10px;
            color: #e0e0e0;
        }

        .modal-close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .modal-close:hover,
        .modal-close:focus {
            color: #fff;
            text-decoration: none;
        }

        #search-results {
            max-height: 300px;
            overflow-y: auto;
            margin-top: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            padding: 10px;
        }
         .search-result-item {
            display: flex;
            align-items: center;
            padding: 8px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            cursor: pointer;
            transition: background-color 0.3s ease;
         }
         .search-result-item:last-child {
            border-bottom: none;
         }
         .search-result-item:hover {
             background-color: rgba(255, 255, 255, 0.1);
         }
        .search-result-item img {
            width: 30px;
            height: 30px;
            margin-right: 10px;
            border-radius: 4px;
        }
        .search-result-item span {
            flex-grow: 1;
        }
        .search-result-item input[type="number"] {
            width: 60px;
            margin-left: 10px;
            padding: 4px;
            font-size: 13px;
        }

        /* Стили для скрытых элементов */
        .hidden {
            display: none;
        }

        /* Мелкие утилиты */
        .text-center { text-align: center; }
        .mb-1 { margin-bottom: 0.5rem; }
        .mb-2 { margin-bottom: 1rem; }
        .mt-2 { margin-top: 1rem; }

    </style>
    <div class="quest-admin-container">
    <?php

    // Вывод списка всех квестов
    if (empty($_GET) || (isset($_GET['action']) && $_GET['action'] == 'list')) {
        ?>
        <div class="quest-card">
            <h2>Управление квестами</h2>
             <div class="text-center mb-2">
                <button type="button" class="button_alt_01" onclick="showContent('/admin/quest/quest.php?action=addNew');">Создать новый квест</button>
            </div>
             <div class="text-center mb-2">
                 <button type="button" class="button_alt_01 buttdelete" onclick="showContent('/admin/quest/quest.php?action=sbros');">Сброс активных квестов</button>
             </div>
        </div>

         <div class="quest-card quest-list">
            <h3>Список существующих квестов</h3>
            <?php
            if (!empty($arrqueestsonlock)) {
                foreach ($arrqueestsonlock as $questsInLoc) {
                    $locId = $questsInLoc[0]['locId'];
                    $locName = isset($alllocNew[$locId]) ? htmlspecialchars(urldecode($alllocNew[$locId]['Name'])) : "Неизвестная локация";
                    ?>
                    <details>
                        <summary><?= $locName; ?></summary>
                        <div class="details-content" style="padding-left: 20px;">
                            <?php
                            foreach ($questsInLoc as $quest) {
                                $questName = htmlspecialchars(urldecode($quest['name']));
                                $comment = urldecode($quest['comment']);
                                ?>
                                <?= $quest['id']; ?>.
                                <?= $arrIco[$quest['rasa']]; ?>
                                <a href="#" onclick="showContent('/admin/quest/quest.php?action=edit&id=<?= $quest['id']; ?>'); return false;">
                                    <?= $questName; ?>
                                </a>
                                <?php if (!empty($comment)): ?>
                                    <span class="quest-comment">// <?= htmlspecialchars($comment); ?></span>
                                <?php endif; ?>
                                <br>
                            <?php } ?>
                        </div>
                    </details>
                    <?php
                }
            } else {
                echo "<p>Квесты не найдены.</p>";
            }
            ?>
         </div>
        <?php
    } else {
        // Отображение формы создания/редактирования или страницы сброса

        // Скрытый селект для JS (выбор квеста)
        ?>
        <code class="questSelectHidden hidden">
            <option class="opthidden" value='0'>квест не выбран</option>
            <?php foreach ($arrqueestsonlock as $questsInLoc): ?>
                <?php $locName = isset($alllocNew[$questsInLoc[0]['locId']]) ? htmlspecialchars(urldecode($alllocNew[$questsInLoc[0]['locId']]['Name'])) : "Неизвестная локация"; ?>
                <optgroup class="optghidden" label="<?= $locName; ?>">
                    <?php foreach ($questsInLoc as $quest): ?>
                        <option class="opthidden" value='<?= $quest['id']; ?>'>
                            <?= $arrIcoAlt[$quest['rasa']]; ?> <?= htmlspecialchars(urldecode($quest['name'])); ?> <?= !empty(urldecode($quest['comment'])) ? "//" . htmlspecialchars(urldecode($quest['comment'])) : ""; ?>
                        </option>
                    <?php endforeach; ?>
                </optgroup>
            <?php endforeach; ?>
        </code>

        <!-- Модальное окно для сообщений -->
        <div class="modal msg" id="messageModal">
             <div class="modal-content" style="max-width: 300px; text-align: center;">
                 <span class="modal-close" onclick="$('#messageModal').hide()">&times;</span>
                 <div class="text_msg" style="margin-bottom: 15px;"></div>
                 <button class="button_alt_01" onclick="$('#messageModal').hide()">Ок</button>
            </div>
        </div>

        <!-- Модальное окно для поиска -->
        <div id="searchModal" class="modal">
            <div class="modal-content">
                <span class="modal-close" onclick="closeSearchModal()">&times;</span>
                <h3 id="searchModalTitle">Поиск</h3>
                <input type="text" id="searchInput" placeholder="Введите название..." onkeyup="performSearch()">
                 <p><small id="searchHint"></small></p> {/* Подсказка для формата */}
                <div id="search-results">Загрузка...</div>
                <div class="text-center mt-2">
                    <button id="addSelectedItemsBtn" class="button_alt_01 add-item-btn" onclick="addSelectedItems()">Добавить выбранное</button>
                     <button class="button_alt_01 buttdelete" onclick="closeSearchModal()">Отмена</button>
                </div>
            </div>
        </div>

        <!-- Шаблон для части квеста -->
        <div id="elementsTemplate" class="hidden">
            <div class="element-card elements">
                 <div class="element-controls">
                    <span class="control-arrow" onclick="up1($(this));">▲</span>
                    <span class="counts">-- Часть X --</span>
                    <span class="control-arrow" onclick="down1($(this));">▼</span>
                </div>

                 <input type="hidden" name="elements[][element_order]" class="element-order-input" value=""> {/* Скрытое поле для порядка */}

                 <div class="form-grid">
                    <div>
                        <label>Автозапуск этой части:</label>
                        <select name='elements[][auto_start_c]'>
                            <option value='0'>Нет</option>
                            <option value='1'>Да</option>
                        </select>
                    </div>
                    <div>
                        <label>Тип окна сообщения:</label>
                        <select name='elements[][type_c]'>
                            <option value='0'>Персонаж, Согласиться, Отказаться</option>
                            <option value='1'>Персонаж, Согласиться</option>
                            <option value='2'>Персонаж, ОК</option>
                            <option value='3'>Окно, Согласиться, Отказаться</option>
                            <option value='4'>Окно, Согласиться</option>
                            <option value='5'>Окно, ОК</option>
                        </select>
                    </div>
                 </div>

                 <div>
                     <label>Иконка персонажа:</label>
                     <input class='img_id hidden' type='number' name='elements[][img_id]' value='0'>
                     <div class="pers-icon-selector">
                         <img src="/img/znak.png" alt="Нет" onclick="selectPersIcon($(this), 0)"> {/* Добавил иконку "Нет" */}
                         <?php for ($i = 1; $i < count($pers_img_arr); $i++) { ?>
                             <img src="<?= $pers_img_arr[$i]; ?>" alt="Pers <?= $i; ?>" onclick="selectPersIcon($(this), <?= $i; ?>)">
                         <?php } ?>
                    </div>
                 </div>

                 <div>
                    <label>Текст сообщения:</label>
                    <small>Теги: <code>time %time%</code>, <code>duels %duels%</code>, <code>drop&id %drop0%</code>, <code>shop&id %shop0%</code></small>
                    <textarea name='elements[][msg_text]' type='text'></textarea>
                 </div>

                <div class="form-grid">
                    <div>
                         <label>Время на выполнение части (ДД:ЧЧ:ММ:СС):</label>
                         <input name='elements[][time_ce]' type='text' value="00:00:00:00" placeholder="ДД:ЧЧ:ММ:СС">
                     </div>
                     <div>
                         <label>Действие при успехе:</label>
                         <select name='elements[][type_if]'>
                            <option value='0'>Действие отсутствует</option>
                            <option value='1'>Завершить квест</option>
                            <option value='2'>Запустить новый квест (этот завершить)</option>
                            <option value='3'>Перейти к след. части</option>
                            <option value='4'>Перейти к след. части (скрыть если завершен)</option>
                            <option value='5'>Перейти к след. части (скрыть если активен)</option>
                         </select>
                     </div>
                     <div>
                         <label>Запустить новый квест (если выбрано выше):</label>
                         <select name='elements[][new_quest]' class="questSelect" myValue="0"></select>
                     </div>
                     <div>
                         <label>Отправиться в локацию:</label>
                         <select name='elements[][gotolocid]'>
                              <option value='0'>Не отправлять</option> {/* Добавил опцию */}
                             <?php foreach ($allloc as $loc): ?>
                                 <option value='<?= $loc['id']; ?>'><?= htmlspecialchars(urldecode($loc['Name'])); ?></option>
                             <?php endforeach; ?>
                         </select>
                     </div>
                </div>

                 <details>
                    <summary>Условия и Действия</summary>
                    <div class="details-content">
                        <div class="form-grid">
                            <div>
                                <label>Запуск боя с монстрами:</label>
                                <select name='elements[][autobattle]'>
                                    <option value='0'>Не запускать</option>
                                    <option value='1'>Сразу при активации части (пока не выбьют нужное)</option>
                                    <option value='2'>При подтверждении (всегда)</option>
                                    <option value='3'>При подтверждении (пока не выбьют нужное)</option>
                                    <option value='4'>Сразу при активации части (всегда)</option>
                                </select>
                            </div>
                            <div>
                                 <label>Монстры для автобоя (JSON: [id, id, ...]):</label>
                                 <div class="input-group">
                                     <input name='elements[][mob_battle]' type='text' value='[]' class="json-input">
                                     <button type="button" class="search-button" onclick="openSearchModal($(this), 'monsters', 'array')">Поиск</button>
                                 </div>
                                 <div class="selected-items-display"></div>
                             </div>
                             <div>
                                 <label>Победить героев (кол-во):</label>
                                 <input name='elements[][herowin_c]' type='number' value='0'>
                             </div>
                        </div>

                        <details>
                             <summary>Поиск бандитов</summary>
                             <div class="details-content form-grid">
                                 <div>
                                     <label>Группы монстров (JSON: [[id,...],[id,...],...]):</label>
                                     <div class="input-group">
                                         <textarea name='elements[][banda_battle]' rows="2" class="json-input">[]</textarea>
                                         {/* Поиск пока не реализован для этого сложного формата */}
                                     </div>
                                     {/* <div class="selected-items-display"></div> */}
                                 </div>
                                 <div>
                                     <label>Локации для поиска (JSON: [id, id, ...]):</label>
                                     <div class="input-group">
                                         <input name='elements[][banda_battle_location]' type='text' value='[]' class="json-input">
                                         {/* Можно добавить поиск локаций, если нужно */}
                                     </div>
                                     {/* <div class="selected-items-display"></div> */}
                                 </div>
                             </div>
                         </details>

                         <details>
                             <summary>Дроп с конкретных монстров</summary>
                             <div class="details-content">
                                <label>Формат: [[mob_id, [[item_id, боев_до_дропа], ...], [min_gold, max_gold], [min_plat, max_plat]], ...]</label>
                                <textarea name='elements[][mob_idandvesh]' rows="3" type='text'>[]</textarea><br>
                                <small>Пример: <code>[[2, [[778,100]], [10, 20], [0, 1]]]</code></small>
                             </div>
                         </details>

                         <details>
                             <summary>Требование: Выбить предметы</summary>
                             <div class="details-content">
                                 <label>Предметы (JSON: [[id, шт], ...]):</label>
                                 <div class="input-group">
                                      <input name='elements[][drop_vesh]' type='text' value='[]' class="json-input">
                                      <button type="button" class="search-button" onclick="openSearchModal($(this), 'shop', 'array_pairs')">Поиск</button>
                                 </div>
                                 <div class="selected-items-display"></div>
                             </div>
                         </details>

                         <details>
                             <summary>Требование: Купить предметы</summary>
                             <div class="details-content">
                                  <label>Предметы (JSON: [[id, шт], ...]):</label>
                                  <div class="input-group">
                                      <input name='elements[][buy_vesh]' type='text' value='[]' class="json-input">
                                      <button type="button" class="search-button" onclick="openSearchModal($(this), 'shop', 'array_pairs')">Поиск</button>
                                  </div>
                                  <div class="selected-items-display"></div>
                              </div>
                         </details>

                         <details>
                            <summary>Забрать у игрока</summary>
                             <div class="details-content form-grid">
                                 <div>
                                     <label>Предметы (JSON: [[id, шт],...]):</label>
                                      <div class="input-group">
                                         <input name='elements[][delpv]' type='text' value='[]' class="json-input">
                                         <button type="button" class="search-button" onclick="openSearchModal($(this), 'shop', 'array_pairs')">Поиск</button>
                                     </div>
                                     <div class="selected-items-display"></div>
                                 </div>
                                 <div><label>Опыт:</label><input name='elements[][delpexp]' type='number' value='0'></div>
                                 <div><label>Слава:</label><input name='elements[][delpslava]' type='number' value='0'></div>
                                 <div><label>Выносливость tec:</label><input name='elements[][delpvinos_t]' type='number' value='0'></div>
                                 <div><label>Выносливость max:</label><input name='elements[][delpvinos_m]' type='number' value='0'></div>
                                 <div><label>platinum:</label><input name='elements[][delpplatinum]' type='number' value='0'></div>
                                 <div><label>деньги юники:</label><input name='elements[][delpmed]' type='number' value='0'></div>
                                 <div><label>победы монстры:</label><input name='elements[][delppobedmonser]' type='number' value='0'></div>
                                 <div><label>победы igroki:</label><input name='elements[][delppobedigroki]' type='number' value='0'></div>
                             </div>
                         </details>

                         <details>
                            <summary>Выдать игроку</summary>
                            <div class="details-content">
                                <div class="form-grid">
                                    <div>
                                        <label>Предметы (JSON: [[id, шт],...]):</label>
                                        <div class="input-group">
                                            <input name='elements[][addpv]' type='text' value='[]' class="json-input">
                                            <button type="button" class="search-button" onclick="openSearchModal($(this), 'shop', 'array_pairs')">Поиск</button>
                                        </div>
                                         <div class="selected-items-display"></div>
                                    </div>
                                    <div><label>Опыт:</label><input name='elements[][addpexp]' type='number' value='0'></div>
                                    <div><label>Слава:</label><input name='elements[][addpslava]' type='number' value='0'></div>
                                    <div><label>Выносливость tec:</label><input name='elements[][addpvinos_t]' type='number' value='0'></div>
                                    <div><label>Выносливость max:</label><input name='elements[][addpvinos_m]' type='number' value='0'></div>
                                    <div><label>platinum:</label><input name='elements[][addpplatinum]' type='number' value='0'></div>
                                    <div><label>деньги юники:</label><input name='elements[][addpmed]' type='number' value='0'></div>
                                    <div><label>победы монстры:</label><input name='elements[][addppobedmonser]' type='number' value='0'></div>
                                    <div><label>победы igroki:</label><input name='elements[][addppobedigroki]' type='number' value='0'></div>
                                </div>
                                <hr style="margin: 15px 0; border-color: rgba(255,255,255,0.2);">
                                <div class="form-grid">
                                      <div>
                                         <label>Рандомные вещи (JSON: [[id, шт],...]):</label>
                                         <div class="input-group">
                                             <input name='elements[][addprv]' type='text' value='[]' class="json-input">
                                             <button type="button" class="search-button" onclick="openSearchModal($(this), 'shop', 'array_pairs')">Поиск</button>
                                         </div>
                                         <div class="selected-items-display"></div>
                                     </div>
                                     <div>
                                        <label>Выдать не более N случайных вещей (0=все):</label>
                                        <input name='elements[][addprnv]' type='number' value='0'>
                                     </div>
                                </div>
                            </div>
                         </details>

                    </div> {/* .details-content */}
                 </details> {/* Условия и Действия */}


                 <details>
                     <summary>Действия при ПРОВАЛЕ (по времени)</summary>
                     <div class="details-content">
                          <div>
                             <label>Иконка персонажа (провал):</label>
                             <input class='proval_img_id hidden' type='number' name='elements[][proval_img_id]' value='0'>
                             <div class="pers-icon-selector">
                                  <img src="/img/znak.png" alt="Нет" onclick="selectPersIcon($(this), 0, 'proval_img_id')">
                                  <?php for ($i = 1; $i < count($pers_img_arr); $i++) { ?>
                                     <img src="<?= $pers_img_arr[$i]; ?>" alt="Pers <?= $i; ?>" onclick="selectPersIcon($(this), <?= $i; ?>, 'proval_img_id')">
                                 <?php } ?>
                            </div>
                         </div>
                         <div class="form-grid">
                              <div>
                                 <label>Тип окна (провал):</label>
                                 <select name='elements[][proval_type_c]'>
                                    <option value='0'>Персонаж, Согласиться, Отказаться</option>
                                    <option value='1'>Персонаж, Согласиться</option>
                                    <option value='2'>Персонаж, ОК</option>
                                    <option value='3'>Окно, Согласиться, Отказаться</option>
                                    <option value='4'>Окно, Согласиться</option>
                                    <option value='5'>Окно, ОК</option>
                                </select>
                             </div>
                             <div>
                                 <label>Действие (провал):</label>
                                 <select name='elements[][proval_type_if]'>
                                     <option value='0'>Действие отсутствует</option>
                                     <option value='1'>Завершить квест</option>
                                     <option value='2'>Запустить новый квест (этот завершить)</option>
                                     <option value='3'>Перейти к след. части</option>
                                     <option value='4'>Перейти к след. части (скрыть если завершен)</option>
                                     <option value='5'>Перейти к след. части (скрыть если активен)</option>
                                 </select>
                             </div>
                              <div>
                                 <label>Запустить новый квест (провал):</label>
                                 <select name='elements[][proval_new_quest]' class="questSelect" myValue="0"></select>
                             </div>
                         </div>
                         <div>
                             <label>Текст сообщения (провал):</label>
                             <textarea name='elements[][proval_msg_text]' type='text'></textarea>
                         </div>

                         <details>
                            <summary>Забрать у игрока (провал)</summary>
                             <div class="details-content form-grid">
                                 <div>
                                     <label>Предметы (JSON: [[id, шт],...]):</label>
                                     <div class="input-group">
                                         <input name='elements[][proval_delpv]' type='text' value='[]' class="json-input">
                                         <button type="button" class="search-button" onclick="openSearchModal($(this), 'shop', 'array_pairs')">Поиск</button>
                                     </div>
                                     <div class="selected-items-display"></div>
                                 </div>
                                 <div><label>Опыт:</label><input name='elements[][proval_delpexp]' type='number' value='0'></div>
                                 <div><label>Слава:</label><input name='elements[][proval_delpslava]' type='number' value='0'></div>
                                 <div><label>Выносливость tec:</label><input name='elements[][proval_delpvinos_t]' type='number' value='0'></div>
                                 <div><label>Выносливость max:</label><input name='elements[][proval_delpvinos_m]' type='number' value='0'></div>
                                 <div><label>platinum:</label><input name='elements[][proval_delpplatinum]' type='number' value='0'></div>
                                 <div><label>деньги юники:</label><input name='elements[][proval_delpmed]' type='number' value='0'></div>
                                 <div><label>победы монстры:</label><input name='elements[][proval_delppobedmonser]' type='number' value='0'></div>
                                 <div><label>победы igroki:</label><input name='elements[][proval_delppobedigroki]' type='number' value='0'></div>
                            </div>
                        </details>
                         <details>
                            <summary>Выдать игроку (провал)</summary>
                            <div class="details-content">
                                <div class="form-grid">
                                    <div>
                                        <label>Предметы (JSON: [[id, шт],...]):</label>
                                        <div class="input-group">
                                            <input name='elements[][proval_addpv]' type='text' value='[]' class="json-input">
                                             <button type="button" class="search-button" onclick="openSearchModal($(this), 'shop', 'array_pairs')">Поиск</button>
                                        </div>
                                        <div class="selected-items-display"></div>
                                    </div>
                                    <div><label>Опыт:</label><input name='elements[][proval_addpexp]' type='number' value='0'></div>
                                    <div><label>Слава:</label><input name='elements[][proval_addpslava]' type='number' value='0'></div>
                                    <div><label>Выносливость tec:</label><input name='elements[][proval_addpvinos_t]' type='number' value='0'></div>
                                    <div><label>Выносливость max:</label><input name='elements[][proval_addpvinos_m]' type='number' value='0'></div>
                                    <div><label>platinum:</label><input name='elements[][proval_addpplatinum]' type='number' value='0'></div>
                                    <div><label>деньги юники:</label><input name='elements[][proval_addpmed]' type='number' value='0'></div>
                                    <div><label>победы монстры:</label><input name='elements[][proval_addppobedmonser]' type='number' value='0'></div>
                                    <div><label>победы igroki:</label><input name='elements[][proval_addppobedigroki]' type='number' value='0'></div>
                                </div>
                                <hr style="margin: 15px 0; border-color: rgba(255,255,255,0.2);">
                                <div class="form-grid">
                                    <div>
                                        <label>Рандомные вещи (JSON: [[id, шт],...]):</label>
                                         <div class="input-group">
                                             <input name='elements[][proval_addprv]' type='text' value='[]' class="json-input">
                                              <button type="button" class="search-button" onclick="openSearchModal($(this), 'shop', 'array_pairs')">Поиск</button>
                                         </div>
                                         <div class="selected-items-display"></div>
                                    </div>
                                    <div>
                                        <label>Выдать не более N случайных вещей (0=все):</label>
                                        <input name='elements[][proval_addprnv]' type='number' value='0'>
                                    </div>
                                </div>
                            </div>
                         </details>
                    </div>
                 </details> {/* Провал */}

                 <details>
                     <summary>Действия при ОТКАЗЕ</summary>
                     <div class="details-content">
                          <div>
                             <label>Иконка персонажа (отказ):</label>
                             <input class='otkaz_img_id hidden' type='number' name='elements[][otkaz_img_id]' value='0'>
                             <div class="pers-icon-selector">
                                  <img src="/img/znak.png" alt="Нет" onclick="selectPersIcon($(this), 0, 'otkaz_img_id')">
                                  <?php for ($i = 1; $i < count($pers_img_arr); $i++) { ?>
                                     <img src="<?= $pers_img_arr[$i]; ?>" alt="Pers <?= $i; ?>" onclick="selectPersIcon($(this), <?= $i; ?>, 'otkaz_img_id')">
                                 <?php } ?>
                            </div>
                         </div>
                           <div class="form-grid">
                              <div>
                                 <label>Тип окна (отказ):</label>
                                 <select name='elements[][otkaz_type_c]'>
                                    <option value='0'>Персонаж, Согласиться, Отказаться</option>
                                    <option value='1'>Персонаж, Согласиться</option>
                                    <option value='2'>Персонаж, ОК</option>
                                    <option value='3'>Окно, Согласиться, Отказаться</option>
                                    <option value='4'>Окно, Согласиться</option>
                                    <option value='5'>Окно, ОК</option>
                                </select>
                             </div>
                             <div>
                                 <label>Действие (отказ):</label>
                                 <select name='elements[][otkaz_type_if]'>
                                     <option value='0'>Действие отсутствует</option>
                                     <option value='1'>Завершить квест</option>
                                     <option value='2'>Запустить новый квест (этот завершить)</option>
                                     <option value='3'>Перейти к след. части</option>
                                     <option value='4'>Перейти к след. части (скрыть если завершен)</option>
                                     <option value='5'>Перейти к след. части (скрыть если активен)</option>
                                 </select>
                             </div>
                              <div>
                                 <label>Запустить новый квест (отказ):</label>
                                 <select name='elements[][otkaz_new_quest]' class="questSelect" myValue="0"></select>
                             </div>
                         </div>
                           <div>
                             <label>Текст сообщения (отказ):</label>
                             <textarea name='elements[][otkaz_msg_text]' type='text'></textarea>
                         </div>

                         <details>
                             <summary>Забрать у игрока (отказ)</summary>
                              <div class="details-content form-grid">
                                 <div>
                                     <label>Предметы (JSON: [[id, шт],...]):</label>
                                     <div class="input-group">
                                         <input name='elements[][otkaz_delpv]' type='text' value='[]' class="json-input">
                                         <button type="button" class="search-button" onclick="openSearchModal($(this), 'shop', 'array_pairs')">Поиск</button>
                                     </div>
                                     <div class="selected-items-display"></div>
                                 </div>
                                 <div><label>Опыт:</label><input name='elements[][otkaz_delpexp]' type='number' value='0'></div>
                                 <div><label>Слава:</label><input name='elements[][otkaz_delpslava]' type='number' value='0'></div>
                                 <div><label>Выносливость tec:</label><input name='elements[][otkaz_delpvinos_t]' type='number' value='0'></div>
                                 <div><label>Выносливость max:</label><input name='elements[][otkaz_delpvinos_m]' type='number' value='0'></div>
                                 <div><label>platinum:</label><input name='elements[][otkaz_delpplatinum]' type='number' value='0'></div>
                                 <div><label>деньги юники:</label><input name='elements[][otkaz_delpmed]' type='number' value='0'></div>
                                 <div><label>победы монстры:</label><input name='elements[][otkaz_delppobedmonser]' type='number' value='0'></div>
                                 <div><label>победы igroki:</label><input name='elements[][otkaz_delppobedigroki]' type='number' value='0'></div>
                             </div>
                        </details>
                         <details>
                             <summary>Выдать игроку (отказ)</summary>
                              <div class="details-content">
                                <div class="form-grid">
                                    <div>
                                        <label>Предметы (JSON: [[id, шт],...]):</label>
                                         <div class="input-group">
                                             <input name='elements[][otkaz_addpv]' type='text' value='[]' class="json-input">
                                              <button type="button" class="search-button" onclick="openSearchModal($(this), 'shop', 'array_pairs')">Поиск</button>
                                         </div>
                                         <div class="selected-items-display"></div>
                                    </div>
                                    <div><label>Опыт:</label><input name='elements[][otkaz_addpexp]' type='number' value='0'></div>
                                    <div><label>Слава:</label><input name='elements[][otkaz_addpslava]' type='number' value='0'></div>
                                    <div><label>Выносливость tec:</label><input name='elements[][otkaz_addpvinos_t]' type='number' value='0'></div>
                                    <div><label>Выносливость max:</label><input name='elements[][otkaz_addpvinos_m]' type='number' value='0'></div>
                                    <div><label>platinum:</label><input name='elements[][otkaz_addpplatinum]' type='number' value='0'></div>
                                    <div><label>деньги юники:</label><input name='elements[][otkaz_addpmed]' type='number' value='0'></div>
                                    <div><label>победы монстры:</label><input name='elements[][otkaz_addppobedmonser]' type='number' value='0'></div>
                                    <div><label>победы igroki:</label><input name='elements[][otkaz_addppobedigroki]' type='number' value='0'></div>
                                </div>
                                <hr style="margin: 15px 0; border-color: rgba(255,255,255,0.2);">
                                 <div class="form-grid">
                                     <div>
                                         <label>Рандомные вещи (JSON: [[id, шт],...]):</label>
                                          <div class="input-group">
                                              <input name='elements[][otkaz_addprv]' type='text' value='[]' class="json-input">
                                              <button type="button" class="search-button" onclick="openSearchModal($(this), 'shop', 'array_pairs')">Поиск</button>
                                          </div>
                                          <div class="selected-items-display"></div>
                                     </div>
                                     <div>
                                         <label>Выдать не более N случайных вещей (0=все):</label>
                                         <input name='elements[][otkaz_addprnv]' type='number' value='0'>
                                     </div>
                                 </div>
                            </div>
                        </details>
                    </div>
                 </details> {/* Отказ */}

                 <div class='delete-element-btn-container'>
                    <button class='button_alt_01 buttdelete' type='button' onclick="removeElement($(this))">Удалить эту часть</button>
                </div>
            </div>
        </div>

        <script>
            console.log("Script start. jQuery type:", typeof $); // <<< ДОБАВИТЬ ЭТУ СТРОКУ

            // --- Глобальные переменные для поиска ---
            let currentSearchTargetInput;
            let currentSearchType; // 'shop' or 'monsters'
            let currentSearchFormat; // 'array', 'array_pairs'
            let selectedItems = []; // Хранит выбранные элементы для текущего поиска

            // --- Функции управления частями квеста ---
            function up1(e) {
                 var element = e.closest(".elements");
                 var prev = element.prev(".elements");
                 if (prev.length > 0) {
                     element.insertBefore(prev);
                     renamecounts();
                 }
             }

             function down1(e) {
                 var element = e.closest(".elements");
                 var next = element.next(".elements");
                  if (next.length > 0) {
                     element.insertAfter(next);
                     renamecounts();
                 }
            }

             function renamecounts() {
                 $("#questElementsContainer .elements").each(function(index) {
                    $(this).find(".counts").text("-- Часть " + (index + 1) + " --");
                     $(this).find(".element-order-input").val(index + 1); // Обновляем порядок
                });
             }

            function addElement() {
                console.log("addElement function called."); // Отладка
                
                // Получаем шаблон напрямую из HTML
                let html = `
                <div class="element-card elements">
                    <div class="element-controls">
                        <span class="control-arrow" onclick="up1($(this));">▲</span>
                        <span class="counts">-- Часть X --</span>
                        <span class="control-arrow" onclick="down1($(this));">▼</span>
                    </div>

                    <input type="hidden" name="elements[][element_order]" class="element-order-input" value="">

                    <div class="form-grid">
                        <div>
                            <label>Автозапуск этой части:</label>
                            <select name='elements[][auto_start_c]'>
                                <option value='0'>Нет</option>
                                <option value='1'>Да</option>
                            </select>
                        </div>
                        <div>
                            <label>Тип окна сообщения:</label>
                            <select name='elements[][type_c]'>
                                <option value='0'>Персонаж, Согласиться, Отказаться</option>
                                <option value='1'>Персонаж, Согласиться</option>
                                <option value='2'>Персонаж, ОК</option>
                                <option value='3'>Окно, Согласиться, Отказаться</option>
                                <option value='4'>Окно, Согласиться</option>
                                <option value='5'>Окно, ОК</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label>Иконка персонажа:</label>
                        <input class='img_id hidden' type='number' name='elements[][img_id]' value='0'>
                        <div class="pers-icon-selector">
                            <img src="/img/znak.png" alt="Нет" onclick="selectPersIcon($(this), 0)"> {/* Добавил иконку "Нет" */}
                            <?php for ($i = 1; $i < count($pers_img_arr); $i++) { ?>
                                <img src="<?= $pers_img_arr[$i]; ?>" alt="Pers <?= $i; ?>" onclick="selectPersIcon($(this), <?= $i; ?>)">
                            <?php } ?>
                        </div>
                    </div>

                    <div>
                        <label>Текст сообщения:</label>
                        <small>Теги: <code>time %time%</code>, <code>duels %duels%</code>, <code>drop&id %drop0%</code>, <code>shop&id %shop0%</code></small>
                        <textarea name='elements[][msg_text]' type='text'></textarea>
                    </div>

                    <div class="form-grid">
                        <div>
                            <label>Время на выполнение части (ДД:ЧЧ:ММ:СС):</label>
                            <input name='elements[][time_ce]' type='text' value="00:00:00:00" placeholder="ДД:ЧЧ:ММ:СС">
                        </div>
                        <div>
                            <label>Действие при успехе:</label>
                            <select name='elements[][type_if]'>
                                <option value='0'>Действие отсутствует</option>
                                <option value='1'>Завершить квест</option>
                                <option value='2'>Запустить новый квест (этот завершить)</option>
                                <option value='3'>Перейти к след. части</option>
                                <option value='4'>Перейти к след. части (скрыть если завершен)</option>
                                <option value='5'>Перейти к след. части (скрыть если активен)</option>
                            </select>
                        </div>
                        <div>
                            <label>Запустить новый квест (если выбрано выше):</label>
                            <select name='elements[][new_quest]' class="questSelect" myValue="0"></select>
                        </div>
                        <div>
                            <label>Отправиться в локацию:</label>
                            <select name='elements[][gotolocid]'>
                                <option value='0'>Не отправлять</option>
                                <?php foreach ($allloc as $loc): ?>
                                    <option value='<?= $loc['id']; ?>'><?= htmlspecialchars(urldecode($loc['Name'])); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class='delete-element-btn-container'>
                        <button class='button_alt_01 buttdelete' type='button' onclick="removeElement($(this))">Удалить эту часть</button>
                    </div>
                </div>`;
                
                // Находим контейнер
                var container = $("#questElementsContainer");
                if (!container.length) {
                    console.error("Container #questElementsContainer not found!");
                    alert("Ошибка: Контейнер для частей квеста не найден.");
                    return;
                }
                
                // Добавляем HTML в контейнер
                container.append(html);
                
                // Обновляем нумерацию частей
                renamecounts();
                
                // Инициализируем нужные компоненты
                var newElement = container.children().last();
                initQuestSelects(newElement);
                
                console.log("Element added successfully!");
            }

             function removeElement(button) {
                if (confirm("Вы уверены, что хотите удалить эту часть квеста?")) {
                    button.closest('.elements').remove();
                     renamecounts();
                 }
            }

             // --- Функции для селекта квестов ---
             function initQuestSelects(container) {
                 var options = $(".questSelectHidden").html();
                 $(container).find('.questSelect').each(function() {
                    // Очистить старые опции перед добавлением новых
                     $(this).html(options);
                     var myValue = $(this).attr('myValue') || '0';
                     // Проверяем существование опции перед установкой значения
                     if ($(this).find("option[value='" + myValue + "']").length > 0) {
                         $(this).val(myValue);
                     } else {
                         $(this).val('0'); // Ставим значение по умолчанию, если сохраненное не найдено
                     }
                 });
                  recolorselect($(container).find('select')); // Применить цвет к новым селектам
             }

            function getsetstyle() {
                $('.questSelectHidden').find('.opthidden').each(function() {
                     var text = $(this).text();
                     var styleMatch = text.match(/style="([^"]*)"/);
                    var style = styleMatch ? styleMatch[1] : 'color: #e0e0e0;'; // Цвет по умолчанию для админки
                    $(this).attr("style", style);
                     $(this).html(text.replace(/<style[^>]*>.*<\/style>/gm, '').replace(/style="[^"]*"/, '').trim());
                 });
                 $('.questSelectHidden').find('.optghidden').each(function() {
                     var label = $(this).attr("label");
                     var styleMatch = label.match(/style="([^"]*)"/);
                    var style = styleMatch ? styleMatch[1] : '';
                    $(this).attr("style", style);
                     $(this).attr("label", label.replace(/<style[^>]*>.*<\/style>/gm, '').replace(/style="[^"]*"/, '').trim());
                });
             }

            function recolorselect(selector = 'select') {
                 $(selector).each(function() {
                     try {
                        var selectedOption = $('option:selected', this);
                         // Используем data-атрибут или стиль по умолчанию
                         var color = selectedOption.data('color') || '#e0e0e0'; // Светлый цвет по умолчанию
                         $(this).css('color', color);
                     } catch (e) {
                         $(this).css('color', '#e0e0e0'); // Цвет по умолчанию при ошибке
                     }
                 });
             }

            // --- Функции для выбора иконок персонажей ---
             function selectPersIcon(imgElement, iconId, inputClassName = 'img_id') {
                var container = imgElement.closest('div'); // Найти родительский div
                // Обновить скрытое поле ввода
                container.siblings('.' + inputClassName).val(iconId);
                // Убрать класс 'selected-icon' со всех иконок в этом контейнере
                container.find('img').removeClass('selected-icon');
                // Добавить класс 'selected-icon' к выбранной иконке
                 imgElement.addClass('selected-icon');
             }

            function initPersIconSelectors(container) {
                $(container).find('.pers-icon-selector').each(function(){
                     var selectorDiv = $(this);
                     var inputField;
                     // Определяем связанное поле ввода по классу
                    if (selectorDiv.siblings('.img_id').length) {
                        inputField = selectorDiv.siblings('.img_id');
                    } else if (selectorDiv.siblings('.proval_img_id').length) {
                        inputField = selectorDiv.siblings('.proval_img_id');
                    } else if (selectorDiv.siblings('.otkaz_img_id').length) {
                        inputField = selectorDiv.siblings('.otkaz_img_id');
                    }

                     if(inputField) {
                         var selectedId = inputField.val();
                         selectorDiv.find('img').removeClass('selected-icon');
                         // Найти иконку по ID (через onclick атрибут или data-атрибут, если добавить)
                         // Простой вариант: искать по значению в onclick
                        selectorDiv.find('img').filter(function() {
                             var onclickAttr = $(this).attr('onclick');
                             if (onclickAttr) {
                                 // Ищем числовое значение ID в строке onclick
                                 var match = onclickAttr.match(/,\s*(\d+)\s*(?:,|\))/);
                                 return match && match[1] == selectedId;
                             }
                            return false;
                         }).addClass('selected-icon');
                     }
                 });
            }


            // --- Функции поиска предметов/мобов ---
             function openSearchModal(button, type, format) { // <<< Изменен первый аргумент
                // Находим поле ввода ОТНОСИТЕЛЬНО кнопки
                currentSearchTargetInput = button.closest('.input-group').find('.json-input'); // <<< Изменен поиск input
                if (!currentSearchTargetInput.length) {
                    console.error("Could not find target input for search button.");
                    msg("Ошибка: Не найдено поле ввода для поиска.");
                    return;
                }

                currentSearchType = type;
                 currentSearchFormat = format;
                selectedItems = []; // Очистить предыдущий выбор

                 $('#searchModalTitle').text('Поиск ' + (type === 'shop' ? 'предметов' : 'монстров'));
                $('#searchInput').val('');
                $('#search-results').html('Введите название для поиска...');

                 // Установить подсказку для формата
                 let hint = '';
                 if (format === 'array') {
                     hint = 'Результат будет в формате: [id, id, ...]';
                     $('#addSelectedItemsBtn').text('Добавить ID');
                 } else if (format === 'array_pairs') {
                     hint = 'Укажите количество. Результат: [[id, кол-во], [id, кол-во], ...]';
                     $('#addSelectedItemsBtn').text('Добавить предметы с количеством');
                 }
                 $('#searchHint').text(hint);


                 $('#searchModal').show();
                 $('#searchInput').focus();
             }

             function closeSearchModal() {
                 $('#searchModal').hide();
                 currentSearchTargetInput = null;
             }

             let searchTimeout;
             function performSearch() {
                 clearTimeout(searchTimeout);
                 searchTimeout = setTimeout(() => {
                     const term = $('#searchInput').val();
                     if (term.length < 2) {
                         $('#search-results').html('Введите минимум 2 символа...');
                         return;
                     }

                     $('#search-results').html('Идет поиск...');

                     $.ajax({
                         type: "POST",
                         url: "/admin/quest/quest.php", // Отправляем на этот же скрипт
                         data: {
                             action: 'search_items',
                             term: rfc3986EncodeURIComponent(term),
                             type: currentSearchType
                         },
                         dataType: "json",
                         success: function(results) {
                             displaySearchResults(results);
                         },
                         error: function() {
                             $('#search-results').html('Ошибка поиска.');
                         }
                     });
                 }, 300); // Задержка перед отправкой запроса
             }

            function displaySearchResults(results) {
                 const resultsContainer = $('#search-results');
                 resultsContainer.empty();
                 if (results.length === 0) {
                     resultsContainer.html('Ничего не найдено.');
                     return;
                 }

                 results.forEach(item => {
                     const isSelected = selectedItems.some(sel => sel.id === item.id);
                     const itemDiv = $(`
                         <div class="search-result-item ${isSelected ? 'selected' : ''}" data-id="${item.id}" data-name="${item.name}" data-img="${item.img}" onclick="toggleSelectItem(${item.id}, '${item.name}', '${item.img}', $(this))">
                             <img src="${item.img}" alt="">
                             <span>${item.name} (ID: ${item.id})</span>
                             ${currentSearchFormat === 'array_pairs' ? '<input type="number" min="1" value="1" class="item-quantity" placeholder="Кол-во" onclick="event.stopPropagation();">': ''}
                         </div>
                     `);
                     resultsContainer.append(itemDiv);
                 });
             }

             function toggleSelectItem(id, name, img, element) {
                 const index = selectedItems.findIndex(item => item.id === id);
                 const quantityInput = element.find('.item-quantity');
                 const quantity = currentSearchFormat === 'array_pairs' ? (parseInt(quantityInput.val()) || 1) : 1;

                 if (index > -1) {
                     // Элемент уже выбран — обновляем количество (если нужно) и/или снимаем выделение
                     if (currentSearchFormat === 'array_pairs') {
                         selectedItems[index].quantity = quantity;
                     }
                     // Повторный клик снимает выделение
                     selectedItems.splice(index, 1);
                     element.removeClass('selected');
                     if (quantityInput.length) quantityInput.prop('disabled', true);
                 } else {
                     // Добавляем новый элемент в выбор
                     selectedItems.push({ id, name, img, quantity });
                     element.addClass('selected');
                     if (quantityInput.length) quantityInput.prop('disabled', false);
                 }
             }

            function addSelectedItems() {
                 if (!currentSearchTargetInput) return;

                 let jsonString = '[]';
                 if (selectedItems.length > 0) {
                      // Обновляем количество перед генерацией JSON
                     $('#search-results .search-result-item.selected').each(function() {
                          const id = $(this).data('id');
                          const index = selectedItems.findIndex(item => item.id === id);
                          if (index > -1 && currentSearchFormat === 'array_pairs') {
                              const quantityInput = $(this).find('.item-quantity');
                              selectedItems[index].quantity = parseInt(quantityInput.val()) || 1;
                          }
                      });


                     if (currentSearchFormat === 'array') {
                         const ids = selectedItems.map(item => item.id);
                         jsonString = JSON.stringify(ids);
                     } else if (currentSearchFormat === 'array_pairs') {
                         const pairs = selectedItems.map(item => [item.id, item.quantity]);
                         jsonString = JSON.stringify(pairs);
                     }
                 }

                 currentSearchTargetInput.val(jsonString);
                updateSelectedItemsDisplay(currentSearchTargetInput); // Обновить визуальное отображение
                 closeSearchModal();
             }

            // --- Функции отображения выбранных элементов под полем ввода ---
             function updateSelectedItemsDisplay(inputElement) {
                 const displayContainer = inputElement.closest('.input-group').next('.selected-items-display');
                 if (!displayContainer.length) return; // Если нет контейнера, выходим

                 displayContainer.empty();
                 const jsonString = inputElement.val();
                 let items = [];

                 try {
                     items = JSON.parse(jsonString);
                     if (!Array.isArray(items)) items = [];
                 } catch (e) {
                     items = []; // Если невалидный JSON, считаем пустым
                 }

                 if (items.length === 0) {
                      displayContainer.html('<small>Предметы не выбраны</small>');
                     return;
                 }

                 // Определяем формат (массив ID или массив пар [ID, кол-во])
                 let isPairFormat = Array.isArray(items[0]);

                 items.forEach(itemData => {
                     let id, quantity;
                     if (isPairFormat) {
                         id = itemData[0];
                         quantity = itemData[1];
                     } else {
                         id = itemData;
                         quantity = null; // Нет количества для простого массива ID
                     }

                     // Пытаемся найти имя и картинку (если они были сохранены ранее или можно запросить)
                     // Для упрощения пока показываем только ID и кол-во
                     // TODO: Можно добавить AJAX запрос для получения имени/картинки по ID, если нужно

                    const itemSpan = $(`
                         <span class="selected-item" data-id="${id}">
                             ID: ${id} ${quantity !== null ? '(' + quantity + ' шт.)' : ''}
                             <button type="button" class="remove-item-btn" onclick="removeItemFromInput(${id}, $(this))">&times;</button>
                         </span>
                     `);
                     displayContainer.append(itemSpan);
                 });
             }

            function removeItemFromInput(idToRemove, buttonElement) {
                 const inputElement = buttonElement.closest('.selected-items-display').prev('.input-group').find('.json-input');
                 if (!inputElement.length) return;

                 const jsonString = inputElement.val();
                 let items = [];
                 try {
                     items = JSON.parse(jsonString);
                     if (!Array.isArray(items)) items = [];
                 } catch (e) {
                     items = [];
                 }

                 let isPairFormat = items.length > 0 && Array.isArray(items[0]);

                 const filteredItems = items.filter(itemData => {
                     if (isPairFormat) {
                         return itemData[0] !== idToRemove;
                     } else {
                         return itemData !== idToRemove;
                     }
                 });

                 inputElement.val(JSON.stringify(filteredItems));
                 updateSelectedItemsDisplay(inputElement); // Обновить отображение
             }

            // Инициализация отображения для всех существующих полей при загрузке
             function initSearchDisplays(container = document) {
                $(container).find('.json-input').each(function() {
                     updateSelectedItemsDisplay($(this));
                 });
             }

            // --- Функции сохранения ---
             function create() {
                 try {
                     // Безопасная сериализация формы: используем FormSerializer, если доступен,
                     // иначе применяем упрощённый fallback-метод.
                     var formData;
                     if (typeof FormSerializer !== 'undefined' && typeof FormSerializer.serializeObject === 'function') {
                         $.fn.serializeObject = FormSerializer.serializeObject;
                         formData = $("#questForm").serializeObject();
                     } else if (typeof $.fn.serializeObject === 'function') {
                         formData = $("#questForm").serializeObject();
                     } else {
                         // Fallback: превращаем serializeArray в объект
                         formData = $("#questForm").serializeArray().reduce(function(obj, field) {
                             if (obj[field.name] !== undefined) {
                                 if (!Array.isArray(obj[field.name])) obj[field.name] = [obj[field.name]];
                                 obj[field.name].push(field.value || '');
                             } else {
                                 obj[field.name] = field.value || '';
                             }
                             return obj;
                         }, {});
                     }

                     // Преобразование времени перезапуска
                     var timeR = formData.time_r || "00:00:00:00";
                     var timeR_parts = timeR.split(":");
                     formData.time_r = ((((((Number(timeR_parts[0]) * 24) + Number(timeR_parts[1])) * 60) + Number(timeR_parts[2])) * 60) + Number(timeR_parts[3]));
                      if(timeR === "00:00:00:-1") formData.time_r = -1; // Обработка одноразового квеста


                     // Кодирование строковых полей квеста
                     formData.name = rfc3986EncodeURIComponent(formData.name || '');
                     formData.comment = rfc3986EncodeURIComponent(formData.comment || '');
                    // JSON поля кодировать не нужно, если они уже строки JSON
                    // formData.predmet = rfc3986EncodeURIComponent(formData.predmet || '[]'); // Не кодируем JSON
                    // formData.predmet_none = rfc3986EncodeURIComponent(formData.predmet_none || '[]'); // Не кодируем JSON


                     // Обработка частей квеста (elements)
                     if (formData.elements && Array.isArray(formData.elements)) {
                         formData.elements.forEach(function(element, index) {
                            // Преобразование времени части
                            var timeCE = element.time_ce || "00:00:00:00";
                             var timeCE_parts = timeCE.split(":");
                            element.time_ce = ((((((Number(timeCE_parts[0]) * 24) + Number(timeCE_parts[1])) * 60) + Number(timeCE_parts[2])) * 60) + Number(timeCE_parts[3]));

                             // Кодирование текстовых полей части
                             element.msg_text = rfc3986EncodeURIComponent(element.msg_text || '');
                             element.proval_msg_text = rfc3986EncodeURIComponent(element.proval_msg_text || '');
                             element.otkaz_msg_text = rfc3986EncodeURIComponent(element.otkaz_msg_text || '');

                             // JSON поля кодировать не нужно
                            /*
                             element.mob_battle = rfc3986EncodeURIComponent(element.mob_battle || '[]');
                             element.banda_battle = rfc3986EncodeURIComponent(element.banda_battle || '[]');
                             element.banda_battle_location = rfc3986EncodeURIComponent(element.banda_battle_location || '[]');
                             element.delpv = rfc3986EncodeURIComponent(element.delpv || '[]');
                             element.addpv = rfc3986EncodeURIComponent(element.addpv || '[]');
                             element.addprv = rfc3986EncodeURIComponent(element.addprv || '[]');
                             element.mob_idandvesh = rfc3986EncodeURIComponent(element.mob_idandvesh || '[]');
                             element.drop_vesh = rfc3986EncodeURIComponent(element.drop_vesh || '[]');
                             element.buy_vesh = rfc3986EncodeURIComponent(element.buy_vesh || '[]');
                             element.proval_delpv = rfc3986EncodeURIComponent(element.proval_delpv || '[]');
                             element.proval_addpv = rfc3986EncodeURIComponent(element.proval_addpv || '[]');
                             element.proval_addprv = rfc3986EncodeURIComponent(element.proval_addprv || '[]');
                             element.otkaz_delpv = rfc3986EncodeURIComponent(element.otkaz_delpv || '[]');
                             element.otkaz_addpv = rfc3986EncodeURIComponent(element.otkaz_addpv || '[]');
                             element.otkaz_addprv = rfc3986EncodeURIComponent(element.otkaz_addprv || '[]');
                             */
                         });
                     } else {
                         // Если частей нет, отправляем пустой массив
                        formData.elements = [];
                     }
                     console.log("Sending data:", formData); // Для отладки
                     saveQuest(formData);

                 } catch (e) {
                     console.error("Error in create function:", e);
                    msg("Ошибка при подготовке данных к отправке: " + e.message);
                 }
             }

            function saveQuest(dataToSend) {
                 // Показываем индикатор загрузки
                 $("body").prepend("<div class='loading-overlay' style='position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 1002; display: flex; justify-content: center; align-items: center;'><img src='" + imgLoading.src + "' alt='loading...' style='width: 50px; height: 50px;'></div>");

                 $.ajax({
                     type: "POST",
                     url: "/admin/quest/quest_l.php", // URL обработчика сохранения
                     data: {
                         strdata: JSON.stringify(dataToSend) // Отправляем как JSON строку
                     },
                     dataType: "json",
                     success: function(response) {
                         $(".loading-overlay").remove(); // Убираем загрузку
                         if (response.otvet == 1) {
                             $('#questIdInput').val(response.new_id); // Обновляем ID в форме
                             msg("Квест успешно создан/обновлен (ID: " + response.new_id + ")");
                             // Можно добавить переход к списку или обновление страницы
                             // showContent('/admin/quest/quest.php?action=list');
                         } else if (response.otvet == 2) {
                            msg("Квест успешно обновлен");
                             // Можно добавить переход к списку или обновление страницы
                             // showContent('/admin/quest/quest.php?action=list');
                         } else {
                            msg("Ошибка сохранения: " + (response.error || "Неизвестная ошибка сервера."));
                             console.error("Save error response:", response);
                         }
                     },
                     error: function(xhr, status, error) {
                         $(".loading-overlay").remove(); // Убираем загрузку
                         msg("Ошибка соединения. Данные не сохранены. " + error);
                         console.error("AJAX error:", status, error, xhr.responseText);
                     }
                 });
             }

            function msg(text) {
                 $('.text_msg').html(text);
                 $('#messageModal').show();
             }

            function rfc3986EncodeURIComponent(str) {
                 return encodeURIComponent(str).replace(/[!'()*]/g, function(c) {
                    return '%' + c.charCodeAt(0).toString(16).toUpperCase();
                 });
            }

            // --- Инициализация при загрузке ---
             $(document).ready(function() {
                console.log("Document ready. jQuery type:", typeof $);

                 getsetstyle();
                 initQuestSelects(document);
                 initSearchDisplays(document);
                 initPersIconSelectors(document);
                 renamecounts();

                // Перекрасить все select при загрузке и изменении
                recolorselect();
                $(document).on('change', 'select', function() {
                     recolorselect($(this));
                 });

                 // Инициализация FormSerializer
                 if (typeof FormSerializer === 'undefined') {
                    console.warn("FormSerializer library not found.");
                    // Полифил на случай отсутствия плагина
                    if (typeof $.fn.serializeObject !== 'function') {
                        $.fn.serializeObject = function() {
                            return this.serializeArray().reduce(function(obj, field) {
                                if (obj[field.name] !== undefined) {
                                    if (!Array.isArray(obj[field.name])) obj[field.name] = [obj[field.name]];
                                    obj[field.name].push(field.value || '');
                                } else {
                                    obj[field.name] = field.value || '';
                                }
                                return obj;
                            }, {});
                        };
                    }
                 } else {
                      $.fn.serializeObject = FormSerializer.serializeObject;
                 }

                 // Обработчик кнопки "Добавить часть квеста" через делегирование
                 $(document).on('click', '#addQuestPartBtn', function(e) {
                    e.preventDefault();
                    console.log("Add Quest Part button clicked via document delegation.");
                    addElement();
                    return false;
                 });

                 // --- ДОБАВИТЬ ЭТОТ ОБРАБОТЧИК ---
                 // Обработчик кнопок "Поиск"
                 $(document).on('click', '.js-search-button', function() {
                    var $button = $(this);
                    var searchType = $button.data('search-type');
                    var searchFormat = $button.data('search-format');
                    console.log("Search button clicked. Type:", searchType, "Format:", searchFormat); // Отладка
                    openSearchModal($button, searchType, searchFormat); // Вызываем глобальную функцию
                 });
                 // --- КОНЕЦ ДОБАВЛЕННОГО ОБРАБОТЧИКА ---

                 // Дополнительная инициализация для редактора
                 $(document).ready(function() {
                     initQuestSelects($('#questForm'));
                     initSearchDisplays($('#questForm'));
                     initPersIconSelectors($('#questForm'));
                     renamecounts();
                 });

                 // Привязываем обработчик к кнопке напрямую при загрузке страницы
                 $("#addQuestPartBtn").click(function(e) {
                     e.preventDefault(); // Предотвращаем возможное поведение по умолчанию
                     console.log("Add Quest Part button clicked directly from ready.");
                     addElement();
                     return false; // Для надежности
                 });

             });

            function addElementSimple() {
                console.log("addElementSimple function called");
                
                // Самый простой шаблон без PHP-кода
                var simpleHtml = `
                <div class="element-card elements">
                    <div class="element-controls">
                        <span class="control-arrow" onclick="up1($(this));">▲</span>
                        <span class="counts">-- Новая часть --</span>
                        <span class="control-arrow" onclick="down1($(this));">▼</span>
                    </div>
                    
                    <input type="hidden" name="elements[][element_order]" class="element-order-input" value="">
                    
                    <div class="form-grid">
                        <div>
                            <label>Автозапуск этой части:</label>
                            <select name='elements[][auto_start_c]'>
                                <option value='0'>Нет</option>
                                <option value='1'>Да</option>
                            </select>
                        </div>
                        <div>
                            <label>Тип окна сообщения:</label>
                            <select name='elements[][type_c]'>
                                <option value='0'>Персонаж, Согласиться, Отказаться</option>
                                <option value='1'>Персонаж, Согласиться</option>
                                <option value='2'>Персонаж, ОК</option>
                                <option value='3'>Окно, Согласиться, Отказаться</option>
                                <option value='4'>Окно, Согласиться</option>
                                <option value='5'>Окно, ОК</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label>Иконка персонажа:</label>
                        <input class='img_id' type='number' name='elements[][img_id]' value='0'>
                    </div>
                    
                    <div>
                        <label>Текст сообщения:</label>
                        <textarea name='elements[][msg_text]' type='text'></textarea>
                    </div>
                    
                    <div class="form-grid">
                        <div>
                            <label>Время на выполнение части (ДД:ЧЧ:ММ:СС):</label>
                            <input name='elements[][time_ce]' type='text' value="00:00:00:00">
                        </div>
                        <div>
                            <label>Действие при успехе:</label>
                            <select name='elements[][type_if]'>
                                <option value='0'>Действие отсутствует</option>
                                <option value='1'>Завершить квест</option>
                                <option value='2'>Запустить новый квест (этот завершить)</option>
                                <option value='3'>Перейти к след. части</option>
                                <option value='4'>Перейти к след. части (скрыть если завершен)</option>
                                <option value='5'>Перейти к след. части (скрыть если активен)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class='delete-element-btn-container'>
                        <button class='button_alt_01 buttdelete' type='button' onclick="removeElement($(this))">Удалить эту часть</button>
                    </div>
                </div>`;
                
                $("#questElementsContainer").append(simpleHtml);
                renamecounts();
                alert("Часть квеста добавлена");
            }
            
            // ... остальной код ...

         // Основной скрипт
         $(document).ready(function() {
             console.log("Document ready fired");
             
             // Принудительная привязка обработчика к простой кнопке
             $("a.button_alt_01").on("click", function(e) {
                 console.log("Simple button clicked");
             });
             
             // Попытка прямого назначения обработчика кнопке добавления
             var addBtn = document.getElementById("addQuestPartBtn");
             if (addBtn) {
                 console.log("Button found, attaching direct event");
                 addBtn.addEventListener("click", function(e) {
                     e.preventDefault();
                     console.log("Button clicked via direct addEventListener");
                     addElementSimple(); // Используем упрощённую функцию
                     return false;
                 });
             } else {
                 console.error("Button not found in DOM");
             }
         });

        </script>
        <?php

        // Форма создания нового квеста
        if (isset($_GET['action']) && $_GET['action'] == 'addNew') {
            $quest_this = null; // Убедимся, что данных нет
            ?>
             <div class="quest-card">
                 <h2>Создание нового квеста</h2>
                 <form id="questForm">
                     <input id='questIdInput' name='id' type='hidden' value='' > <!-- Скрытое поле ID -->

                    <div class="quest-card">
                        <h3>Основные параметры</h3>
                        <div class="form-grid">
                            <div>
                                <label for="questName">Название:</label>
                                <input id="questName" name='name' type='text' value="" required>
                            </div>
                             <div>
                                 <label for="questComment">Комментарий (для админки):</label>
                                 <input id="questComment" name='comment' type='text' value="">
                             </div>
                             <div>
                                 <label for="questLoc">Локация:</label>
                                 <select id="questLoc" name='locId' required>
                                      <option value="">-- Выберите локацию --</option>
                                     <?php foreach ($allloc as $loc): ?>
                                         <option value='<?= $loc['id']; ?>'><?= htmlspecialchars(urldecode($loc['Name'])); ?></option>
                                     <?php endforeach; ?>
                                 </select>
                             </div>
                             <div>
                                 <label for="questRasa">Фракция (Раса):</label>
                                 <select id="questRasa" name='rasa'>
                                     <option value='0'>Любая</option>
                                     <option value='1'>Нормасцы</option>
                                     <option value='2'>Шейване</option>
                                 </select>
                             </div>
                            <div>
                                <label for="questTimeR">Время перезапуска (ДД:ЧЧ:ММ:СС):</label>
                                <input id="questTimeR" name='time_r' type='text' value="00:00:00:00" placeholder="ДД:ЧЧ:ММ:СС или 00:00:00:-1 для одноразового">
                                 <small><code>00:00:00:-1</code> - одноразовый</small>
                            </div>
                            <div>
                                 <label for="questAutoStart">Автозапуск квеста при доступности:</label>
                                 <select id="questAutoStart" name='auto_start'>
                                    <option value='0'>Нет</option>
                                    <option value='1'>Да</option>
                                </select>
                             </div>
                        </div>
                    </div>

                    <div class="quest-card">
                         <h3>Условия доступности</h3>
                        <div class="form-grid">
                             <div>
                                 <label>Уровень (от/до):</label>
                                 <div style="display: flex; gap: 10px;">
                                     <input name='level_min' type='number' value="1" style='flex: 1;' placeholder="От">
                                     <input name='level_max' type='number' value="999" style='flex: 1;' placeholder="До">
                                 </div>
                             </div>
                             <div>
                                 <label>Доступен после квеста:</label>
                                 <select name='pred_quest' class="questSelect" myValue="0"></select>
                             </div>
                             <div>
                                 <label>Недоступен при активном/завершенном квесте:</label>
                                 <select name='quest_not' class="questSelect" myValue="0"></select>
                             </div>
                            <div>
                                 <label>Доступен при звании:</label>
                                 <select name='zvanie'>
                                      <option value=''>Любое</option>
                                     <?php foreach ($zvanieArrAll as $zvanie): ?>
                                          <option value='<?= htmlspecialchars($zvanie['name']); ?>'><?= htmlspecialchars($zvanie['name']); ?></option>
                                     <?php endforeach; ?>
                                 </select>
                            </div>
                            <div>
                                 <label>Требуется наличие предметов (JSON):</label>
                                 <div class="input-group">
                                      <input name='predmet' type='text' value='[]' class="json-input">
                                     <button type="button" class="search-button" onclick="openSearchModal($(this), 'shop', 'array_pairs')">Поиск</button>
                                 </div>
                                 <div class="selected-items-display"></div>
                                 <small>Формат: [[id, шт], [id, шт]]</small>
                             </div>
                             <div>
                                 <label>Требуется отсутствие предметов (JSON):</label>
                                 <div class="input-group">
                                      <input name='predmet_none' type='text' value='[]' class="json-input">
                                     <button type="button" class="search-button" onclick="openSearchModal($(this), 'shop', 'array_pairs')">Поиск</button>
                                 </div>
                                 <div class="selected-items-display"></div>
                                 <small>Формат: [[id, шт], [id, шт]]</small>
                             </div>
                        </div>
                        <details>
                            <summary>Дополнительные требования по параметрам</summary>
                            <div class="details-content form-grid">
                                <div><label>Здоровье (минимум):</label><input name='health' type='number' value='0'></div>
                                <div><label>Урон (минимум):</label><input name='strength' type='number' value='0'></div>
                                <div><label>Точность (минимум):</label><input name='toch' type='number' value='0'></div>
                                <div><label>Броня (минимум):</label><input name='bron' type='number' value='0'></div>
                                <div><label>Уворот (минимум):</label><input name='lov' type='number' value='0'></div>
                                <div><label>Оглушение (минимум):</label><input name='kd' type='number' value='0'></div>
                                <div><label>Блок (минимум):</label><input name='block' type='number' value='0'></div>
                                <div><label>Уровень (минимум):</label><input name='level' type='number' value='0'></div>
                                <div><label>Опыт (минимум):</label><input name='exp' type='number' value='0'></div>
                                <div><label>Слава (минимум):</label><input name='slava' type='number' value='0'></div>
                                <div><label>Выносливость тек. (минимум):</label><input name='vinos_t' type='number' value='0'></div>
                                <div><label>Выносливость макс. (минимум):</label><input name='vinos_m' type='number' value='0'></div>
                                <div><label>Рейтинг турнира (минимум):</label><input name='tur_reit' type='number' value='0'></div>
                                <div><label>Репутация + (минимум):</label><input name='rep_p' type='number' value='0'></div>
                                <div><label>Репутация - (максимум):</label><input name='rep_m' type='number' value='0'></div>
                                <div><label>Платина (минимум):</label><input name='platinum' type='number' value='0'></div>
                                <div><label>Юники (минимум):</label><input name='med' type='number' value='0'></div>
                                <div><label>Победы монстры (минимум):</label><input name='pobedmonser' type='number' value='0'></div>
                                <div><label>Победы игроки (минимум):</label><input name='pobedigroki' type='number' value='0'></div>
                            </div>
                        </details>
                    </div>

                     <div class="quest-card">
                         <h3>Части квеста</h3>
                         <div id="questElementsContainer">
                             <!-- Сюда будут добавляться части квеста -->
                         </div>
                         <div class='text-center mt-2'>
                            <button id="addQuestPartBtn" class='button_alt_01 add-item-btn' type='button'>Добавить часть квеста</button>
                            <button id="addQuestPartBtnAlt" class='button_alt_01 add-item-btn' type='button' style="display:none;">Альтернативная кнопка добавления</button>
                            <a href="javascript:void(0);" onclick="addElementSimple();" class="button_alt_01" style="text-decoration: none; display: inline-block; margin-left: 10px;">+ Добавить часть (простой способ)</a>
                        </div>
                     </div>

                     <div class='text-center mt-2'>
                         <button onclick="create();" class='button_alt_01' style='padding: 12px 25px; font-size: 16px;' type='button'>Сохранить квест</button>
                         <button onclick="showContent('/admin/quest/quest.php?action=list');" class='button_alt_01 buttdelete' type='button'>Отмена</button>
                     </div>
                 </form>
            </div>
            <?php
        }

        // Форма редактирования существующего квеста
        elseif (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id']) && $_GET['id'] != "") {
             $quest_id = intval($_GET['id']);
             $quest_this = $mc->query("SELECT * FROM `quests` WHERE `id` = '$quest_id'")->fetch_assoc();

             if ($quest_this) {
                 $arrel = $mc->query("SELECT * FROM `quests_count` WHERE `id_quests` = '$quest_id' ORDER BY `count` ASC")->fetch_all(MYSQLI_ASSOC);
                ?>
                 <div class="quest-card">
                    <h2>Редактирование квеста #<?= $quest_this['id']; ?>: <?= htmlspecialchars(urldecode($quest_this['name'])); ?></h2>
                    <form id="questForm">
                        <input id='questIdInput' name='id' type='hidden' value='<?= $quest_this['id']; ?>'>

                        <div class="quest-card">
                             <h3>Основные параметры</h3>
                             <div class="form-grid">
                                 <div>
                                     <label for="questName">Название:</label>
                                     <input id="questName" name='name' type='text' value="<?= htmlspecialchars(urldecode($quest_this['name'])); ?>" required>
                                 </div>
                                 <div>
                                     <label for="questComment">Комментарий (для админки):</label>
                                     <input id="questComment" name='comment' type='text' value="<?= htmlspecialchars(urldecode($quest_this['comment'])); ?>">
                                 </div>
                                 <div>
                                     <label for="questLoc">Локация:</label>
                                     <select id="questLoc" name='locId' required>
                                         <?php foreach ($allloc as $loc): ?>
                                             <option value='<?= $loc['id']; ?>' <?= $quest_this['locId'] == $loc['id'] ? 'selected' : ''; ?>><?= htmlspecialchars(urldecode($loc['Name'])); ?></option>
                                         <?php endforeach; ?>
                                     </select>
                                 </div>
                                 <div>
                                     <label for="questRasa">Фракция (Раса):</label>
                                     <select id="questRasa" name='rasa'>
                                         <option value='0' <?= $quest_this['rasa'] == 0 ? 'selected' : ''; ?>>Любая</option>
                                         <option value='1' <?= $quest_this['rasa'] == 1 ? 'selected' : ''; ?>>Нормасцы</option>
                                         <option value='2' <?= $quest_this['rasa'] == 2 ? 'selected' : ''; ?>>Шейване</option>
                                     </select>
                                 </div>
                                 <div>
                                    <label for="questTimeR">Время перезапуска (ДД:ЧЧ:ММ:СС):</label>
                                    <?php
                                        $time_r_val = "00:00:00:00";
                                        if ($quest_this['time_r'] == -1) {
                                             $time_r_val = "00:00:00:-1";
                                        } elseif ($quest_this['time_r'] > 0) {
                                             $time_r_val = sprintf("%02d:%02d:%02d:%02d", floor(($quest_this['time_r'] / 3600) / 24), floor(($quest_this['time_r'] / 3600) % 24), floor(($quest_this['time_r'] % 3600) / 60), floor(($quest_this['time_r'] % 3600) % 60));
                                        }
                                     ?>
                                     <input id="questTimeR" name='time_r' type='text' value="<?= $time_r_val; ?>" placeholder="ДД:ЧЧ:ММ:СС или 00:00:00:-1">
                                     <small><code>00:00:00:-1</code> - одноразовый</small>
                                 </div>
                                <div>
                                     <label for="questAutoStart">Автозапуск квеста при доступности:</label>
                                     <select id="questAutoStart" name='auto_start'>
                                        <option value='0' <?= $quest_this['auto_start'] == 0 ? 'selected' : ''; ?>>Нет</option>
                                        <option value='1' <?= $quest_this['auto_start'] == 1 ? 'selected' : ''; ?>>Да</option>
                                    </select>
                                 </div>
                             </div>
                         </div>

                         <div class="quest-card">
                             <h3>Условия доступности</h3>
                             <div class="form-grid">
                                <div>
                                     <label>Уровень (от/до):</label>
                                     <div style="display: flex; gap: 10px;">
                                         <input name='level_min' type='number' value="<?= $quest_this['level_min']; ?>" style='flex: 1;' placeholder="От">
                                         <input name='level_max' type='number' value="<?= $quest_this['level_max']; ?>" style='flex: 1;' placeholder="До">
                                     </div>
                                 </div>
                                <div>
                                     <label>Доступен после квеста:</label>
                                     <select name='pred_quest' class="questSelect" myValue="<?= $quest_this['pred_quest']; ?>"></select>
                                 </div>
                                 <div>
                                     <label>Недоступен при активном/завершенном квесте:</label>
                                     <select name='quest_not' class="questSelect" myValue="<?= $quest_this['quest_not']; ?>"></select>
                                 </div>
                                <div>
                                     <label>Доступен при звании:</label>
                                     <select name='zvanie'>
                                          <option value='' <?= empty($quest_this['zvanie']) ? 'selected' : ''; ?>>Любое</option>
                                         <?php foreach ($zvanieArrAll as $zvanie): ?>
                                              <option value='<?= htmlspecialchars($zvanie['name']); ?>' <?= $quest_this['zvanie'] == $zvanie['name'] ? 'selected' : ''; ?>><?= htmlspecialchars($zvanie['name']); ?></option>
                                         <?php endforeach; ?>
                                     </select>
                                </div>
                                 <div>
                                     <label>Требуется наличие предметов (JSON):</label>
                                     <div class="input-group">
                                         <input name='predmet' type='text' value="<?= htmlspecialchars($quest_this['predmet'] ?: '[]') ?>" class="json-input">
                                         <button type="button" class="search-button" onclick="openSearchModal($(this), 'shop', 'array_pairs')">Поиск</button>
                                     </div>
                                     <div class="selected-items-display"></div>
                                     <small>Формат: [[id, шт], [id, шт]]</small>
                                 </div>
                                 <div>
                                     <label>Требуется отсутствие предметов (JSON):</label>
                                      <div class="input-group">
                                          <input name='predmet_none' type='text' value="<?= htmlspecialchars($quest_this['predmet_none'] ?: '[]') ?>" class="json-input">
                                          <button type="button" class="search-button" onclick="openSearchModal($(this), 'shop', 'array_pairs')">Поиск</button>
                                      </div>
                                      <div class="selected-items-display"></div>
                                      <small>Формат: [[id, шт], [id, шт]]</small>
                                  </div>
                             </div>
                             <details>
                                 <summary>Дополнительные требования по параметрам</summary>
                                 <div class="details-content form-grid">
                                     <?php
                                         $params = ['health', 'strength', 'toch', 'bron', 'lov', 'kd', 'block', 'level', 'exp', 'slava', 'vinos_t', 'vinos_m', 'tur_reit', 'rep_p', 'rep_m', 'platinum', 'med', 'pobedmonser', 'pobedigroki'];
                                         $labels = ['Здоровье (мин):', 'Урон (мин):', 'Точность (мин):', 'Броня (мин):', 'Уворот (мин):', 'Оглушение (мин):', 'Блок (мин):', 'Уровень (мин):', 'Опыт (мин):', 'Слава (мин):', 'Выносл. тек. (мин):', 'Выносл. макс. (мин):', 'Рейтинг тур. (мин):', 'Репутация + (мин):', 'Репутация - (макс):', 'Платина (мин):', 'Юники (мин):', 'Победы монстры (мин):', 'Победы игроки (мин):'];
                                         foreach ($params as $index => $param) {
                                             echo "<div><label>" . $labels[$index] . "</label><input name='$param' type='number' value='" . ($quest_this[$param] ?? 0) . "'></div>";
                                         }
                                     ?>
                                 </div>
                             </details>
                         </div>

                         <div class="quest-card">
                             <h3>Части квеста</h3>
                             <div id="questElementsContainer">
                                 <?php foreach ($arrel as $el_index => $el_data):
                                      // Установка данных для текущего элемента
                                       $el_data['addpv'] = $el_data['addpv'] ?: '[]';
                                       $el_data['addprv'] = $el_data['addprv'] ?: '[]';
                                       $el_data['delpv'] = $el_data['delpv'] ?: '[]';
                                       $el_data['mob_battle'] = $el_data['mob_battle'] ?: '[]';
                                       $el_data['banda_battle'] = $el_data['banda_battle'] ?: '[]';
                                       $el_data['banda_battle_location'] = $el_data['banda_battle_location'] ?: '[]';
                                       $el_data['mob_idandvesh'] = $el_data['mob_idandvesh'] ?: '[]';
                                       $el_data['drop_vesh'] = $el_data['drop_vesh'] ?: '[]';
                                       $el_data['buy_vesh'] = $el_data['buy_vesh'] ?: '[]';
                                       // Провал
                                       $el_data['proval_addpv'] = $el_data['proval_addpv'] ?: '[]';
                                       $el_data['proval_addprv'] = $el_data['proval_addprv'] ?: '[]';
                                       $el_data['proval_delpv'] = $el_data['proval_delpv'] ?: '[]';
                                       // Отказ
                                       $el_data['otkaz_addpv'] = $el_data['otkaz_addpv'] ?: '[]';
                                       $el_data['otkaz_addprv'] = $el_data['otkaz_addprv'] ?: '[]';
                                       $el_data['otkaz_delpv'] = $el_data['otkaz_delpv'] ?: '[]';

                                       // Время части
                                       $time_ce_val = "00:00:00:00";
                                        if ($el_data['time_ce'] > 0) {
                                             $time_ce_val = sprintf("%02d:%02d:%02d:%02d", floor(($el_data['time_ce'] / 3600) / 24), floor(($el_data['time_ce'] / 3600) % 24), floor(($el_data['time_ce'] % 3600) / 60), floor(($el_data['time_ce'] % 3600) % 60));
                                        }
                                 ?>
                                    <div class="element-card elements">
                                         <div class="element-controls">
                                             <span class="control-arrow" onclick="up1($(this));">▲</span>
                                             <span class="counts">-- Часть <?= $el_index + 1; ?> --</span>
                                             <span class="control-arrow" onclick="down1($(this));">▼</span>
                                         </div>
                                          <input type="hidden" name="elements[<?= $el_index; ?>][element_order]" class="element-order-input" value="<?= $el_index + 1; ?>">

                                        <div class="form-grid">
                                             <div>
                                                 <label>Автозапуск этой части:</label>
                                                  <select name='elements[<?= $el_index; ?>][auto_start_c]'>
                                                     <option value='0' <?= $el_data['auto_start_c'] == 0 ? 'selected' : ''; ?>>Нет</option>
                                                     <option value='1' <?= $el_data['auto_start_c'] == 1 ? 'selected' : ''; ?>>Да</option>
                                                 </select>
                                             </div>
                                             <div>
                                                 <label>Тип окна сообщения:</label>
                                                  <select name='elements[<?= $el_index; ?>][type_c]'>
                                                      <option value='0' <?= $el_data['type_c'] == 0 ? 'selected' : ''; ?>>Персонаж, Согласиться, Отказаться</option>
                                                      <option value='1' <?= $el_data['type_c'] == 1 ? 'selected' : ''; ?>>Персонаж, Согласиться</option>
                                                      <option value='2' <?= $el_data['type_c'] == 2 ? 'selected' : ''; ?>>Персонаж, ОК</option>
                                                      <option value='3' <?= $el_data['type_c'] == 3 ? 'selected' : ''; ?>>Окно, Согласиться, Отказаться</option>
                                                      <option value='4' <?= $el_data['type_c'] == 4 ? 'selected' : ''; ?>>Окно, Согласиться</option>
                                                      <option value='5' <?= $el_data['type_c'] == 5 ? 'selected' : ''; ?>>Окно, ОК</option>
                                                  </select>
                                             </div>
                                         </div>

                                        <div>
                                             <label>Иконка персонажа:</label>
                                             <input class='img_id hidden' type='number' name='elements[<?= $el_index; ?>][img_id]' value='<?= $el_data['img_id']; ?>'>
                                             <div class="pers-icon-selector">
                                                 <img src="/img/znak.png" alt="Нет" onclick="selectPersIcon($(this), 0)">
                                                 <?php for ($i = 1; $i < count($pers_img_arr); $i++) { ?>
                                                     <img src="<?= $pers_img_arr[$i]; ?>" alt="Pers <?= $i; ?>" onclick="selectPersIcon($(this), <?= $i; ?>)" class="<?= $el_data['img_id'] == $i ? 'selected-icon' : '' ?>">
                                                 <?php } ?>
                                            </div>
                                         </div>

                                         <div>
                                            <label>Текст сообщения:</label>
                                             <small>Теги: <code>time %time%</code>, <code>duels %duels%</code>, <code>drop&id %drop0%</code>, <code>shop&id %shop0%</code></small>
                                             <textarea name='elements[<?= $el_index; ?>][msg_text]' type='text'><?= htmlspecialchars(urldecode($el_data['msg_text'])); ?></textarea>
                                         </div>

                                         <div class="form-grid">
                                             <div>
                                                 <label>Время на выполнение части (ДД:ЧЧ:ММ:СС):</label>
                                                 <input name='elements[<?= $el_index; ?>][time_ce]' type='text' value="<?= $time_ce_val; ?>" placeholder="ДД:ЧЧ:ММ:СС">
                                             </div>
                                             <div>
                                                 <label>Действие при успехе:</label>
                                                 <select name='elements[<?= $el_index; ?>][type_if]'>
                                                    <option value='0' <?= $el_data['type_if'] == 0 ? 'selected' : ''; ?>>Действие отсутствует</option>
                                                    <option value='1' <?= $el_data['type_if'] == 1 ? 'selected' : ''; ?>>Завершить квест</option>
                                                    <option value='2' <?= $el_data['type_if'] == 2 ? 'selected' : ''; ?>>Запустить новый квест (этот завершить)</option>
                                                    <option value='3' <?= $el_data['type_if'] == 3 ? 'selected' : ''; ?>>Перейти к след. части</option>
                                                     <option value='4' <?= $el_data['type_if'] == 4 ? 'selected' : ''; ?>>Перейти к след. части (скрыть если завершен)</option>
                                                     <option value='5' <?= $el_data['type_if'] == 5 ? 'selected' : ''; ?>>Перейти к след. части (скрыть если активен)</option>
                                                 </select>
                                             </div>
                                             <div>
                                                 <label>Запустить новый квест (если выбрано выше):</label>
                                                 <select name='elements[<?= $el_index; ?>][new_quest]' class="questSelect" myValue="<?= $el_data['new_quest']; ?>"></select>
                                             </div>
                                             <div>
                                                 <label>Отправиться в локацию:</label>
                                                  <select name='elements[<?= $el_index; ?>][gotolocid]'>
                                                      <option value='0' <?= $el_data['gotolocid'] == 0 ? 'selected' : ''; ?>>Не отправлять</option>
                                                      <?php foreach ($allloc as $loc): ?>
                                                         <option value='<?= $loc['id']; ?>' <?= $el_data['gotolocid'] == $loc['id'] ? 'selected' : ''; ?>><?= htmlspecialchars(urldecode($loc['Name'])); ?></option>
                                                     <?php endforeach; ?>
                                                 </select>
                                             </div>
                                        </div>

                                        <details>
                                             <summary>Условия и Действия</summary>
                                             <div class="details-content">
                                                  <div class="form-grid">
                                                     <div>
                                                         <label>Запуск боя с монстрами:</label>
                                                         <select name='elements[<?= $el_index; ?>][autobattle]'>
                                                             <option value='0' <?= $el_data['autobattle'] == 0 ? 'selected' : ''; ?>>Не запускать</option>
                                                             <option value='1' <?= $el_data['autobattle'] == 1 ? 'selected' : ''; ?>>Сразу при активации части (пока не выбьют нужное)</option>
                                                             <option value='2' <?= $el_data['autobattle'] == 2 ? 'selected' : ''; ?>>При подтверждении (всегда)</option>
                                                             <option value='3' <?= $el_data['autobattle'] == 3 ? 'selected' : ''; ?>>При подтверждении (пока не выбьют нужное)</option>
                                                             <option value='4' <?= $el_data['autobattle'] == 4 ? 'selected' : ''; ?>>Сразу при активации части (всегда)</option>
                                                         </select>
                                                     </div>
                                                     <div>
                                                         <label>Монстры для автобоя (JSON: [id, id, ...]):</label>
                                                         <div class="input-group">
                                                             <input name='elements[<?= $el_index; ?>][mob_battle]' type='text' value='<?= htmlspecialchars(urldecode($el_data['mob_battle'])); ?>' class="json-input">
                                                             <button type="button" class="search-button" onclick="openSearchModal($(this), 'monsters', 'array')">Поиск</button>
                                                         </div>
                                                         <div class="selected-items-display"></div>
                                                     </div>
                                                     <div>
                                                         <label>Победить героев (кол-во):</label>
                                                         <input name='elements[<?= $el_index; ?>][herowin_c]' type='number' value='<?= $el_data['herowin_c']; ?>'>
                                                     </div>
                                                </div>

                                                 <details>
                                                     <summary>Поиск бандитов</summary>
                                                     <div class="details-content form-grid">
                                                         <div>
                                                             <label>Группы монстров (JSON: [[id,...],[id,...],...]):</label>
                                                             <div class="input-group">
                                                                 <textarea name='elements[<?= $el_index; ?>][banda_battle]' rows="2" class="json-input"><?= htmlspecialchars(urldecode($el_data['banda_battle'])); ?></textarea>
                                                             </div>
                                                          </div>
                                                         <div>
                                                             <label>Локации для поиска (JSON: [id, id, ...]):</label>
                                                             <div class="input-group">
                                                                 <input name='elements[<?= $el_index; ?>][banda_battle_location]' type='text' value='<?= htmlspecialchars(urldecode($el_data['banda_battle_location'])); ?>' class="json-input">
                                                             </div>
                                                          </div>
                                                     </div>
                                                 </details>

                                                 <details>
                                                     <summary>Дроп с конкретных монстров</summary>
                                                     <div class="details-content">
                                                         <label>Формат: [[mob_id, [[item_id, боев_до_дропа], ...], [min_gold, max_gold], [min_plat, max_plat]], ...]</label>
                                                         <textarea name='elements[<?= $el_index; ?>][mob_idandvesh]' rows="3" type='text'><?= htmlspecialchars(urldecode($el_data['mob_idandvesh'])); ?></textarea>
                                                      </div>
                                                 </details>

                                                 <details>
                                                     <summary>Требование: Выбить предметы</summary>
                                                     <div class="details-content">
                                                         <label>Предметы (JSON: [[id, шт], ...]):</label>
                                                         <div class="input-group">
                                                             <input name='elements[<?= $el_index; ?>][drop_vesh]' type='text' value='<?= htmlspecialchars(urldecode($el_data['drop_vesh'])); ?>' class="json-input">
                                                             <button type="button" class="search-button" onclick="openSearchModal($(this), 'shop', 'array_pairs')">Поиск</button>
                                                         </div>
                                                         <div class="selected-items-display"></div>
                                                     </div>
                                                 </details>

                                                 <details>
                                                     <summary>Требование: Купить предметы</summary>
                                                     <div class="details-content">
                                                         <label>Предметы (JSON: [[id, шт], ...]):</label>
                                                         <div class="input-group">
                                                             <input name='elements[<?= $el_index; ?>][buy_vesh]' type='text' value='<?= htmlspecialchars(urldecode($el_data['buy_vesh'])); ?>' class="json-input">
                                                             <button type="button" class="search-button" onclick="openSearchModal($(this), 'shop', 'array_pairs')">Поиск</button>
                                                         </div>
                                                         <div class="selected-items-display"></div>
                                                      </div>
                                                 </details>

                                                 <details>
                                                     <summary>Забрать у игрока</summary>
                                                     <div class="details-content form-grid">
                                                         <div>
                                                             <label>Предметы (JSON: [[id, шт],...]):</label>
                                                             <div class="input-group">
                                                                 <input name='elements[<?= $el_index; ?>][delpv]' type='text' value='<?= htmlspecialchars(urldecode($el_data['delpv'])); ?>' class="json-input">
                                                                  <button type="button" class="search-button" onclick="openSearchModal($(this), 'shop', 'array_pairs')">Поиск</button>
                                                             </div>
                                                             <div class="selected-items-display"></div>
                                                         </div>
                                                          <?php
                                                             $delParams = ['delpexp' => 'Опыт:', 'delpslava' => 'Слава:', 'delpvinos_t' => 'Выносл. (тек.):', 'delpvinos_m' => 'Выносл. (макс.):', 'delpplatinum' => 'Платина:', 'delpmed' => 'Юники:', 'delppobedmonser' => 'Победы (монстры):', 'delppobedigroki' => 'Победы (игроки):'];
                                                             foreach ($delParams as $key => $label) {
                                                                 echo "<div><label>$label</label><input name='elements[$el_index][$key]' type='number' value='" . ($el_data[$key] ?? 0) . "'></div>";
                                                             }
                                                          ?>
                                                     </div>
                                                 </details>

                                                 <details>
                                                     <summary>Выдать игроку</summary>
                                                     <div class="details-content">
                                                         <div class="form-grid">
                                                              <div>
                                                                 <label>Предметы (JSON: [[id, шт],...]):</label>
                                                                 <div class="input-group">
                                                                     <input name='elements[<?= $el_index; ?>][addpv]' type='text' value='<?= htmlspecialchars(urldecode($el_data['addpv'])); ?>' class="json-input">
                                                                      <button type="button" class="search-button" onclick="openSearchModal($(this), 'shop', 'array_pairs')">Поиск</button>
                                                                 </div>
                                                                 <div class="selected-items-display"></div>
                                                             </div>
                                                              <?php
                                                                 $addParams = ['addpexp' => 'Опыт:', 'addpslava' => 'Слава:', 'addpvinos_t' => 'Выносл. (тек.):', 'addpvinos_m' => 'Выносл. (макс.):', 'addpplatinum' => 'Платина:', 'addpmed' => 'Юники:', 'addppobedmonser' => 'Победы (монстры):', 'addppobedigroki' => 'Победы (игроки):'];
                                                                 foreach ($addParams as $key => $label) {
                                                                     echo "<div><label>$label</label><input name='elements[$el_index][$key]' type='number' value='" . ($el_data[$key] ?? 0) . "'></div>";
                                                                 }
                                                              ?>
                                                         </div>
                                                          <hr style="margin: 15px 0; border-color: rgba(255,255,255,0.2);">
                                                         <div class="form-grid">
                                                              <div>
                                                                 <label>Рандомные вещи (JSON: [[id, шт],...]):</label>
                                                                  <div class="input-group">
                                                                      <input name='elements[<?= $el_index; ?>][addprv]' type='text' value='<?= htmlspecialchars(urldecode($el_data['addprv'])); ?>' class="json-input">
                                                                      <button type="button" class="search-button" onclick="openSearchModal($(this), 'shop', 'array_pairs')">Поиск</button>
                                                                  </div>
                                                                  <div class="selected-items-display"></div>
                                                              </div>
                                                             <div>
                                                                  <label>Выдать не более N случайных вещей (0=все):</label>
                                                                  <input name='elements[<?= $el_index; ?>][addprnv]' type='number' value='<?= $el_data['addprnv']; ?>'>
                                                              </div>
                                                         </div>
                                                     </div>
                                                 </details>
                                             </div> {/* .details-content */}
                                         </details> {/* Условия и Действия */}

                                         <details>
                                              <summary>Действия при ПРОВАЛЕ (по времени)</summary>
                                             <div class="details-content">
                                                 <div>
                                                      <label>Иконка персонажа (провал):</label>
                                                      <input class='proval_img_id hidden' type='number' name='elements[<?= $el_index; ?>][proval_img_id]' value='<?= $el_data['proval_img_id']; ?>'>
                                                      <div class="pers-icon-selector">
                                                         <img src="/img/znak.png" alt="Нет" onclick="selectPersIcon($(this), 0, 'proval_img_id')">
                                                         <?php for ($i = 1; $i < count($pers_img_arr); $i++) { ?>
                                                             <img src="<?= $pers_img_arr[$i]; ?>" alt="Pers <?= $i; ?>" onclick="selectPersIcon($(this), <?= $i; ?>, 'proval_img_id')" class="<?= $el_data['proval_img_id'] == $i ? 'selected-icon' : '' ?>">
                                                         <?php } ?>
                                                    </div>
                                                 </div>
                                                  <div class="form-grid">
                                                      <div>
                                                          <label>Тип окна (провал):</label>
                                                          <select name='elements[<?= $el_index; ?>][proval_type_c]'>
                                                             <option value='0' <?= $el_data['proval_type_c'] == 0 ? 'selected' : ''; ?>>Персонаж, Согласиться, Отказаться</option>
                                                             <option value='1' <?= $el_data['proval_type_c'] == 1 ? 'selected' : ''; ?>>Персонаж, Согласиться</option>
                                                             <option value='2' <?= $el_data['proval_type_c'] == 2 ? 'selected' : ''; ?>>Персонаж, ОК</option>
                                                             <option value='3' <?= $el_data['proval_type_c'] == 3 ? 'selected' : ''; ?>>Окно, Согласиться, Отказаться</option>
                                                             <option value='4' <?= $el_data['proval_type_c'] == 4 ? 'selected' : ''; ?>>Окно, Согласиться</option>
                                                             <option value='5' <?= $el_data['proval_type_c'] == 5 ? 'selected' : ''; ?>>Окно, ОК</option>
                                                         </select>
                                                     </div>
                                                      <div>
                                                          <label>Действие (провал):</label>
                                                          <select name='elements[<?= $el_index; ?>][proval_type_if]'>
                                                              <option value='0' <?= $el_data['proval_type_if'] == 0 ? 'selected' : ''; ?>>Действие отсутствует</option>
                                                              <option value='1' <?= $el_data['proval_type_if'] == 1 ? 'selected' : ''; ?>>Завершить квест</option>
                                                              <option value='2' <?= $el_data['proval_type_if'] == 2 ? 'selected' : ''; ?>>Запустить новый квест (этот завершить)</option>
                                                              <option value='3' <?= $el_data['proval_type_if'] == 3 ? 'selected' : ''; ?>>Перейти к след. части</option>
                                                               <option value='4' <?= $el_data['proval_type_if'] == 4 ? 'selected' : ''; ?>>Перейти к след. части (скрыть если завершен)</option>
                                                               <option value='5' <?= $el_data['proval_type_if'] == 5 ? 'selected' : ''; ?>>Перейти к след. части (скрыть если активен)</option>
                                                          </select>
                                                      </div>
                                                      <div>
                                                          <label>Запустить новый квест (провал):</label>
                                                          <select name='elements[<?= $el_index; ?>][proval_new_quest]' class="questSelect" myValue="<?= $el_data['proval_new_quest']; ?>"></select>
                                                      </div>
                                                  </div>
                                                  <div>
                                                      <label>Текст сообщения (провал):</label>
                                                      <textarea name='elements[<?= $el_index; ?>][proval_msg_text]' type='text'><?= htmlspecialchars(urldecode($el_data['proval_msg_text'])); ?></textarea>
                                                  </div>
                                                 <details>
                                                     <summary>Забрать у игрока (провал)</summary>
                                                     <div class="details-content form-grid">
                                                         <div>
                                                             <label>Предметы (JSON: [[id, шт],...]):</label>
                                                             <div class="input-group">
                                                                  <input name='elements[<?= $el_index; ?>][proval_delpv]' type='text' value='<?= htmlspecialchars(urldecode($el_data['proval_delpv'])); ?>' class="json-input">
                                                                  <button type="button" class="search-button" onclick="openSearchModal($(this), 'shop', 'array_pairs')">Поиск</button>
                                                              </div>
                                                              <div class="selected-items-display"></div>
                                                         </div>
                                                         <?php
                                                             $provalDelParams = ['proval_delpexp' => 'Опыт:', 'proval_delpslava' => 'Слава:', 'proval_delpvinos_t' => 'Выносл. (тек.):', 'proval_delpvinos_m' => 'Выносл. (макс.):', 'proval_delpplatinum' => 'Платина:', 'proval_delpmed' => 'Юники:', 'proval_delppobedmonser' => 'Победы (монстры):', 'proval_delppobedigroki' => 'Победы (игроки):'];
                                                             foreach ($provalDelParams as $key => $label) {
                                                                 echo "<div><label>$label</label><input name='elements[$el_index][$key]' type='number' value='" . ($el_data[$key] ?? 0) . "'></div>";
                                                             }
                                                         ?>
                                                     </div>
                                                 </details>
                                                  <details>
                                                     <summary>Выдать игроку (провал)</summary>
                                                     <div class="details-content">
                                                         <div class="form-grid">
                                                             <div>
                                                                 <label>Предметы (JSON: [[id, шт],...]):</label>
                                                                  <div class="input-group">
                                                                      <input name='elements[<?= $el_index; ?>][proval_addpv]' type='text' value='<?= htmlspecialchars(urldecode($el_data['proval_addpv'])); ?>' class="json-input">
                                                                      <button type="button" class="search-button" onclick="openSearchModal($(this), 'shop', 'array_pairs')">Поиск</button>
                                                                  </div>
                                                                  <div class="selected-items-display"></div>
                                                              </div>
                                                             <?php
                                                                 $provalAddParams = ['proval_addpexp' => 'Опыт:', 'proval_addpslava' => 'Слава:', 'proval_addpvinos_t' => 'Выносл. (тек.):', 'proval_addpvinos_m' => 'Выносл. (макс.):', 'proval_addpplatinum' => 'Платина:', 'proval_addpmed' => 'Юники:', 'proval_addppobedmonser' => 'Победы (монстры):', 'proval_addppobedigroki' => 'Победы (игроки):'];
                                                                 foreach ($provalAddParams as $key => $label) {
                                                                     echo "<div><label>$label</label><input name='elements[$el_index][$key]' type='number' value='" . ($el_data[$key] ?? 0) . "'></div>";
                                                                 }
                                                             ?>
                                                         </div>
                                                         <hr style="margin: 15px 0; border-color: rgba(255,255,255,0.2);">
                                                         <div class="form-grid">
                                                             <div>
                                                                 <label>Рандомные вещи (JSON: [[id, шт],...]):</label>
                                                                  <div class="input-group">
                                                                      <input name='elements[<?= $el_index; ?>][proval_addprv]' type='text' value='<?= htmlspecialchars(urldecode($el_data['proval_addprv'])); ?>' class="json-input">
                                                                       <button type="button" class="search-button" onclick="openSearchModal($(this), 'shop', 'array_pairs')">Поиск</button>
                                                                  </div>
                                                                  <div class="selected-items-display"></div>
                                                              </div>
                                                              <div>
                                                                  <label>Выдать не более N случайных вещей (0=все):</label>
                                                                  <input name='elements[<?= $el_index; ?>][proval_addprnv]' type='number' value='<?= $el_data['proval_addprnv']; ?>'>
                                                              </div>
                                                         </div>
                                                     </div>
                                                  </details>
                                             </div>
                                         </details> {/* Провал */}

                                        <details>
                                              <summary>Действия при ОТКАЗЕ</summary>
                                             <div class="details-content">
                                                  <div>
                                                      <label>Иконка персонажа (отказ):</label>
                                                      <input class='otkaz_img_id hidden' type='number' name='elements[<?= $el_index; ?>][otkaz_img_id]' value='<?= $el_data['otkaz_img_id']; ?>'>
                                                      <div class="pers-icon-selector">
                                                          <img src="/img/znak.png" alt="Нет" onclick="selectPersIcon($(this), 0, 'otkaz_img_id')">
                                                          <?php for ($i = 1; $i < count($pers_img_arr); $i++) { ?>
                                                             <img src="<?= $pers_img_arr[$i]; ?>" alt="Pers <?= $i; ?>" onclick="selectPersIcon($(this), <?= $i; ?>, 'otkaz_img_id')" class="<?= $el_data['otkaz_img_id'] == $i ? 'selected-icon' : '' ?>">
                                                         <?php } ?>
                                                    </div>
                                                  </div>
                                                  <div class="form-grid">
                                                      <div>
                                                          <label>Тип окна (отказ):</label>
                                                          <select name='elements[<?= $el_index; ?>][otkaz_type_c]'>
                                                              <option value='0' <?= $el_data['otkaz_type_c'] == 0 ? 'selected' : ''; ?>>Персонаж, Согласиться, Отказаться</option>
                                                              <option value='1' <?= $el_data['otkaz_type_c'] == 1 ? 'selected' : ''; ?>>Персонаж, Согласиться</option>
                                                              <option value='2' <?= $el_data['otkaz_type_c'] == 2 ? 'selected' : ''; ?>>Персонаж, ОК</option>
                                                              <option value='3' <?= $el_data['otkaz_type_c'] == 3 ? 'selected' : ''; ?>>Окно, Согласиться, Отказаться</option>
                                                              <option value='4' <?= $el_data['otkaz_type_c'] == 4 ? 'selected' : ''; ?>>Окно, Согласиться</option>
                                                              <option value='5' <?= $el_data['otkaz_type_c'] == 5 ? 'selected' : ''; ?>>Окно, ОК</option>
                                                          </select>
                                                      </div>
                                                      <div>
                                                          <label>Действие (отказ):</label>
                                                          <select name='elements[<?= $el_index; ?>][otkaz_type_if]'>
                                                              <option value='0' <?= $el_data['otkaz_type_if'] == 0 ? 'selected' : ''; ?>>Действие отсутствует</option>
                                                              <option value='1' <?= $el_data['otkaz_type_if'] == 1 ? 'selected' : ''; ?>>Завершить квест</option>
                                                              <option value='2' <?= $el_data['otkaz_type_if'] == 2 ? 'selected' : ''; ?>>Запустить новый квест (этот завершить)</option>
                                                              <option value='3' <?= $el_data['otkaz_type_if'] == 3 ? 'selected' : ''; ?>>Перейти к след. части</option>
                                                               <option value='4' <?= $el_data['otkaz_type_if'] == 4 ? 'selected' : ''; ?>>Перейти к след. части (скрыть если завершен)</option>
                                                               <option value='5' <?= $el_data['otkaz_type_if'] == 5 ? 'selected' : ''; ?>>Перейти к след. части (скрыть если активен)</option>
                                                          </select>
                                                      </div>
                                                      <div>
                                                          <label>Запустить новый квест (отказ):</label>
                                                          <select name='elements[<?= $el_index; ?>][otkaz_new_quest]' class="questSelect" myValue="<?= $el_data['otkaz_new_quest']; ?>"></select>
                                                      </div>
                                                  </div>
                                                  <div>
                                                      <label>Текст сообщения (отказ):</label>
                                                      <textarea name='elements[<?= $el_index; ?>][otkaz_msg_text]' type='text'><?= htmlspecialchars(urldecode($el_data['otkaz_msg_text'])); ?></textarea>
                                                  </div>
                                                  <details>
                                                      <summary>Забрать у игрока (отказ)</summary>
                                                      <div class="details-content form-grid">
                                                           <div>
                                                              <label>Предметы (JSON: [[id, шт],...]):</label>
                                                               <div class="input-group">
                                                                   <input name='elements[<?= $el_index; ?>][otkaz_delpv]' type='text' value='<?= htmlspecialchars(urldecode($el_data['otkaz_delpv'])); ?>' class="json-input">
                                                                   <button type="button" class="search-button" onclick="openSearchModal($(this), 'shop', 'array_pairs')">Поиск</button>
                                                               </div>
                                                               <div class="selected-items-display"></div>
                                                          </div>
                                                          <?php
                                                              $otkazDelParams = ['otkaz_delpexp' => 'Опыт:', 'otkaz_delpslava' => 'Слава:', 'otkaz_delpvinos_t' => 'Выносл. (тек.):', 'otkaz_delpvinos_m' => 'Выносл. (макс.):', 'otkaz_delpplatinum' => 'Платина:', 'otkaz_delpmed' => 'Юники:', 'otkaz_delppobedmonser' => 'Победы (монстры):', 'otkaz_delppobedigroki' => 'Победы (игроки):'];
                                                              foreach ($otkazDelParams as $key => $label) {
                                                                  echo "<div><label>$label</label><input name='elements[$el_index][$key]' type='number' value='" . ($el_data[$key] ?? 0) . "'></div>";
                                                              }
                                                          ?>
                                                      </div>
                                                  </details>
                                                  <details>
                                                      <summary>Выдать игроку (отказ)</summary>
                                                      <div class="details-content">
                                                         <div class="form-grid">
                                                             <div>
                                                                 <label>Предметы (JSON: [[id, шт],...]):</label>
                                                                  <div class="input-group">
                                                                      <input name='elements[<?= $el_index; ?>][otkaz_addpv]' type='text' value='<?= htmlspecialchars(urldecode($el_data['otkaz_addpv'])); ?>' class="json-input">
                                                                      <button type="button" class="search-button" onclick="openSearchModal($(this), 'shop', 'array_pairs')">Поиск</button>
                                                                  </div>
                                                                  <div class="selected-items-display"></div>
                                                              </div>
                                                             <?php
                                                                 $otkazAddParams = ['otkaz_addpexp' => 'Опыт:', 'otkaz_addpslava' => 'Слава:', 'otkaz_addpvinos_t' => 'Выносл. (тек.):', 'otkaz_addpvinos_m' => 'Выносл. (макс.):', 'otkaz_addpplatinum' => 'Платина:', 'otkaz_addpmed' => 'Юники:', 'otkaz_addppobedmonser' => 'Победы (монстры):', 'otkaz_addppobedigroki' => 'Победы (игроки):'];
                                                                 foreach ($otkazAddParams as $key => $label) {
                                                                     echo "<div><label>$label</label><input name='elements[$el_index][$key]' type='number' value='" . ($el_data[$key] ?? 0) . "'></div>";
                                                                 }
                                                             ?>
                                                         </div>
                                                          <hr style="margin: 15px 0; border-color: rgba(255,255,255,0.2);">
                                                         <div class="form-grid">
                                                             <div>
                                                                 <label>Рандомные вещи (JSON: [[id, шт],...]):</label>
                                                                  <div class="input-group">
                                                                      <input name='elements[<?= $el_index; ?>][otkaz_addprv]' type='text' value='<?= htmlspecialchars(urldecode($el_data['otkaz_addprv'])); ?>' class="json-input">
                                                                      <button type="button" class="search-button" onclick="openSearchModal($(this), 'shop', 'array_pairs')">Поиск</button>
                                                                  </div>
                                                                  <div class="selected-items-display"></div>
                                                              </div>
                                                              <div>
                                                                  <label>Выдать не более N случайных вещей (0=все):</label>
                                                                  <input name='elements[<?= $el_index; ?>][otkaz_addprnv]' type='number' value='<?= $el_data['otkaz_addprnv']; ?>'>
                                                              </div>
                                                         </div>
                                                      </div>
                                                  </details>
                                             </div>
                                         </details> {/* Отказ */}


                                         <div class='delete-element-btn-container'>
                                            <button class='button_alt_01 buttdelete' type='button' onclick="removeElement($(this))">Удалить эту часть</button>
                                        </div>
                                     </div>
                                 <?php endforeach; ?>
                              </div>
                             <div class='text-center mt-2'>
                                 <button id="addQuestPartBtn" class='button_alt_01 add-item-btn' type='button'>Добавить часть квеста</button>
                                 <button id="addQuestPartBtnAlt" class='button_alt_01 add-item-btn' type='button' style="display:none;">Альтернативная кнопка добавления</button>
                                 <a href="javascript:void(0);" onclick="addElementSimple();" class="button_alt_01" style="text-decoration: none; display: inline-block; margin-left: 10px;">+ Добавить часть (простой способ)</a>
                             </div>
                         </div>

                         <div class='text-center mt-2'>
                             <button onclick="create();" class='button_alt_01' style='padding: 12px 25px; font-size: 16px;' type='button'>Сохранить изменения</button>
                             <button onclick="showContent('/admin/quest/quest.php?action=list');" class='button_alt_01 buttdelete' type='button'>Отмена</button>
                         </div>
                     </form>
                </div>
                 <script>
                     // Дополнительная инициализация для редактора
                     $(document).ready(function() {
                         initQuestSelects($('#questForm'));
                         initSearchDisplays($('#questForm'));
                         initPersIconSelectors($('#questForm'));
                         renamecounts();
                     });
                 </script>
                 <?php
             } else {
                  echo "<div class='quest-card'><p style='color: red;'>Ошибка: Квест с ID $quest_id не найден.</p>";
                  echo "<button onclick=\"showContent('/admin/quest/quest.php?action=list');\" class='button_alt_01' type='button'>К списку квестов</button></div>";
             }
        }

        // Страница сброса квестов
        elseif (isset($_GET['action']) && $_GET['action'] == 'sbros') {
            require_once './sbros.php'; // Подключаем файл сброса
        }

        // Если действие не распознано
        else {
            echo "<div class='quest-card'><p style='color: orange;'>Неизвестное действие.</p>";
            echo "<button onclick=\"showContent('/admin/quest/quest.php?action=list');\" class='button_alt_01' type='button'>К списку квестов</button></div>";
        }

    } // конец else для !empty($_GET)

    ?>
    </div> <!-- .quest-admin-container -->
    <?php

} else {
    // Если нет доступа
    echo "<div style='text-align: center; padding: 20px; color: red; font-weight: bold;'>Доступ запрещен!</div>";
}

// --- Вспомогательные функции PHP ---
// function indexFirstIndexArr($arr) { // Больше не нужна в таком виде
//     $arr2 = [];
//     foreach ($arr as $key => $value) {
//         $arr2[] = $value;
//     }
//     return $arr2;
// }

function json_decode_nice($json) {
     // Простая реализация, т.к. основная валидация должна быть на клиенте/сервере при сохранении
     $decoded = json_decode($json, true);
     return (json_last_error() === JSON_ERROR_NONE) ? $decoded : null;
}

// Подключаем футер
$footval = 'adminadmin'; // Устанавливаем значение для футера
require_once '../../system/foot/foot.php'; // Подключаем футер
?>
