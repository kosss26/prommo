<?php
require_once '../system/func.php';
require_once '../system/dbc.php';

// Защита от перенаправления на disconnect.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) && isset($_COOKIE['id'])) {
    $_SESSION['user_id'] = $_COOKIE['id'];
}

require_once '../system/header.php';
require_once 'functions.php';

auth(); // Закроем от неавторизированных
admin(); // Только для админов

// Надежная проверка авторизации
checkDungeonAuth();

// Обработка действий
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    switch ($action) {
        case 'create':
            // Создание нового подземелья
            if (isset($_POST['submit'])) {
                $name = $mc->real_escape_string($_POST['name']);
                $description = $mc->real_escape_string($_POST['description']);
                $min_level = intval($_POST['min_level']);
                $max_level = intval($_POST['max_level']);
                $difficulty = intval($_POST['difficulty']);
                $floors = intval($_POST['floors']);
                
                // Проверка загрузки файлов
                $icon = '';
                $background = '';
                
                // Обработка иконки
                if (isset($_FILES['icon']) && $_FILES['icon']['error'] == 0) {
                    $allowed = array('jpg', 'jpeg', 'png', 'gif');
                    $filename = $_FILES['icon']['name'];
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    
                    if (in_array($ext, $allowed)) {
                        $new_filename = 'dungeon_icon_' . time() . '.' . $ext;
                        $upload_path = '../img/dungeons/icons/';
                        
                        if (!file_exists($upload_path)) {
                            mkdir($upload_path, 0777, true);
                        }
                        
                        if (move_uploaded_file($_FILES['icon']['tmp_name'], $upload_path . $new_filename)) {
                            $icon = $new_filename;
                        }
                    }
                }
                
                // Обработка фона
                if (isset($_FILES['background']) && $_FILES['background']['error'] == 0) {
                    $allowed = array('jpg', 'jpeg', 'png', 'gif');
                    $filename = $_FILES['background']['name'];
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    
                    if (in_array($ext, $allowed)) {
                        $new_filename = 'dungeon_bg_' . time() . '.' . $ext;
                        $upload_path = '../img/dungeons/backgrounds/';
                        
                        if (!file_exists($upload_path)) {
                            mkdir($upload_path, 0777, true);
                        }
                        
                        if (move_uploaded_file($_FILES['background']['tmp_name'], $upload_path . $new_filename)) {
                            $background = $new_filename;
                        }
                    }
                }
                
                // Создаем массив данных для этажей подземелья
                $floors_data = array();
                for ($i = 1; $i <= $floors; $i++) {
                    $floors_data[$i] = array(
                        'rooms' => rand(3, 8),        // Случайное количество комнат (от 3 до 8)
                        'monsters' => rand(1, 3),     // Монстров на комнату (от 1 до 3)
                        'treasure_chance' => 10 + ($i * 5), // Шанс на сокровище (увеличивается с уровнем)
                        'boss_level' => ($i == $floors) // Босс на последнем уровне
                    );
                }
                
                // Создаем массив данных для наград
                $rewards_data = array(
                    'gold_base' => 100 + ($difficulty * 50), // Базовое золото за прохождение
                    'exp_base' => 50 + ($difficulty * 25),  // Базовый опыт за прохождение
                    'item_chance' => 10 + ($difficulty * 5), // Шанс на предмет (зависит от сложности)
                    'special_rewards' => array() // Специальные награды (не используется в базовой версии)
                );
                
                // Сохраняем данные в формате JSON
                $floors_json = $mc->real_escape_string(json_encode($floors_data));
                $rewards_json = $mc->real_escape_string(json_encode($rewards_data));
                
                // Добавляем подземелье в базу данных
                $mc->query("INSERT INTO `dungeons` 
                            (`name`, `description`, `min_level`, `max_level`, `difficulty`, 
                             `icon`, `background`, `floors_data`, `rewards_data`, `added_date`, `active`) 
                           VALUES 
                            ('$name', '$description', '$min_level', '$max_level', '$difficulty', 
                             '$icon', '$background', '$floors_json', '$rewards_json', '" . time() . "', '1')");
                
                if ($mc->affected_rows > 0) {
                    $success = "Подземелье \"$name\" успешно создано!";
                } else {
                    $error = "Ошибка при создании подземелья: " . $mc->error;
                }
            }
            
            // Выводим форму для создания
            include 'admin_create.php';
            break;
            
        case 'edit':
            // Редактирование подземелья
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                ?><script>/*nextshowcontemt*/showContent("/dungeons/admin.php");</script><?php
                exit(0);
            }
            
            $dungeon_id = intval($_GET['id']);
            $dungeon = $mc->query("SELECT * FROM `dungeons` WHERE `id` = '$dungeon_id'")->fetch_array(MYSQLI_ASSOC);
            
            if (!$dungeon) {
                ?><script>/*nextshowcontemt*/showContent("/dungeons/admin.php");</script><?php
                exit(0);
            }
            
            // Обработка формы редактирования
            if (isset($_POST['submit'])) {
                $name = $mc->real_escape_string($_POST['name']);
                $description = $mc->real_escape_string($_POST['description']);
                $min_level = intval($_POST['min_level']);
                $max_level = intval($_POST['max_level']);
                $difficulty = intval($_POST['difficulty']);
                $active = isset($_POST['active']) ? 1 : 0;
                
                // Сохраняем текущие значения, если новые не загружены
                $icon = $dungeon['icon'];
                $background = $dungeon['background'];
                
                // Обработка иконки
                if (isset($_FILES['icon']) && $_FILES['icon']['error'] == 0) {
                    $allowed = array('jpg', 'jpeg', 'png', 'gif');
                    $filename = $_FILES['icon']['name'];
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    
                    if (in_array($ext, $allowed)) {
                        $new_filename = 'dungeon_icon_' . time() . '.' . $ext;
                        $upload_path = '../img/dungeons/icons/';
                        
                        if (!file_exists($upload_path)) {
                            mkdir($upload_path, 0777, true);
                        }
                        
                        if (move_uploaded_file($_FILES['icon']['tmp_name'], $upload_path . $new_filename)) {
                            // Удаляем старую иконку
                            if (!empty($dungeon['icon']) && file_exists($upload_path . $dungeon['icon'])) {
                                unlink($upload_path . $dungeon['icon']);
                            }
                            $icon = $new_filename;
                        }
                    }
                }
                
                // Обработка фона
                if (isset($_FILES['background']) && $_FILES['background']['error'] == 0) {
                    $allowed = array('jpg', 'jpeg', 'png', 'gif');
                    $filename = $_FILES['background']['name'];
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    
                    if (in_array($ext, $allowed)) {
                        $new_filename = 'dungeon_bg_' . time() . '.' . $ext;
                        $upload_path = '../img/dungeons/backgrounds/';
                        
                        if (!file_exists($upload_path)) {
                            mkdir($upload_path, 0777, true);
                        }
                        
                        if (move_uploaded_file($_FILES['background']['tmp_name'], $upload_path . $new_filename)) {
                            // Удаляем старый фон
                            if (!empty($dungeon['background']) && file_exists($upload_path . $dungeon['background'])) {
                                unlink($upload_path . $dungeon['background']);
                            }
                            $background = $new_filename;
                        }
                    }
                }
                
                // Обновляем данные подземелья
                $mc->query("UPDATE `dungeons` SET 
                           `name` = '$name', 
                           `description` = '$description', 
                           `min_level` = '$min_level', 
                           `max_level` = '$max_level', 
                           `difficulty` = '$difficulty', 
                           `icon` = '$icon', 
                           `background` = '$background', 
                           `active` = '$active' 
                           WHERE `id` = '$dungeon_id'");
                
                if ($mc->affected_rows >= 0) {
                    $success = "Подземелье \"$name\" успешно обновлено!";
                    // Обновляем данные для отображения
                    $dungeon = $mc->query("SELECT * FROM `dungeons` WHERE `id` = '$dungeon_id'")->fetch_array(MYSQLI_ASSOC);
                } else {
                    $error = "Ошибка при обновлении подземелья: " . $mc->error;
                }
            }
            
            // Выводим форму для редактирования
            include 'admin_edit.php';
            break;
            
        case 'delete':
            // Удаление подземелья
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                ?><script>/*nextshowcontemt*/showContent("/dungeons/admin.php");</script><?php
                exit(0);
            }
            
            $dungeon_id = intval($_GET['id']);
            
            // Проверяем, есть ли активные сессии для этого подземелья
            $active_sessions = $mc->query("SELECT COUNT(*) as count FROM `dungeon_sessions` WHERE `dungeon_id` = '$dungeon_id' AND `completed` = '0'")->fetch_array(MYSQLI_ASSOC);
            
            if ($active_sessions['count'] > 0) {
                $error = "Невозможно удалить подземелье, так как имеются активные сессии исследования!";
            } else {
                // Получаем информацию о подземелье для удаления файлов
                $dungeon = $mc->query("SELECT * FROM `dungeons` WHERE `id` = '$dungeon_id'")->fetch_array(MYSQLI_ASSOC);
                
                if ($dungeon) {
                    // Удаляем файлы изображений
                    if (!empty($dungeon['icon']) && file_exists('../img/dungeons/icons/' . $dungeon['icon'])) {
                        unlink('../img/dungeons/icons/' . $dungeon['icon']);
                    }
                    
                    if (!empty($dungeon['background']) && file_exists('../img/dungeons/backgrounds/' . $dungeon['background'])) {
                        unlink('../img/dungeons/backgrounds/' . $dungeon['background']);
                    }
                    
                    // Удаляем записи из базы данных
                    $mc->query("DELETE FROM `dungeons` WHERE `id` = '$dungeon_id'");
                    $mc->query("DELETE FROM `dungeon_progress` WHERE `dungeon_id` = '$dungeon_id'");
                    $mc->query("DELETE FROM `dungeon_sessions` WHERE `dungeon_id` = '$dungeon_id'");
                    
                    $success = "Подземелье успешно удалено!";
                } else {
                    $error = "Подземелье не найдено!";
                }
            }
            
            // Перенаправляем на главную страницу админки
            ?><script>/*nextshowcontemt*/showContent("/dungeons/admin.php");</script><?php
            exit(0);
            break;
            
        case 'attempts':
            // Управление попытками подземелий
            if (isset($_POST['submit'])) {
                $default_attempts = intval($_POST['default_attempts']);
                
                // Обновляем настройки максимального количества попыток
                $mc->query("UPDATE `dungeon_settings` SET `value` = '$default_attempts' WHERE `key` = 'default_attempts'");
                
                // Если настройки еще не существуют, добавляем их
                if ($mc->affected_rows == 0) {
                    $mc->query("INSERT INTO `dungeon_settings` (`key`, `value`, `description`) 
                                VALUES ('default_attempts', '$default_attempts', 'Количество попыток подземелий по умолчанию')");
                }
                
                if (isset($_POST['reset_all']) && $_POST['reset_all'] == 1) {
                    // Сбрасываем все текущие попытки до максимума
                    $mc->query("UPDATE `dungeon_attempts` SET `current_attempts` = '$default_attempts', `max_attempts` = '$default_attempts'");
                    $reset_message = "Попытки всех игроков сброшены до максимального значения.";
                } else {
                    // Обновляем только максимальное количество попыток
                    $mc->query("UPDATE `dungeon_attempts` SET `max_attempts` = '$default_attempts'");
                }
                
                $success = "Настройки попыток обновлены успешно!";
            }
            
            // Получаем текущие настройки попыток
            $attempts_settings = $mc->query("SELECT * FROM `dungeon_settings` WHERE `key` = 'default_attempts'")->fetch_array(MYSQLI_ASSOC);
            $default_attempts = $attempts_settings ? intval($attempts_settings['value']) : 3;
            
            // Получаем статистику по попыткам игроков
            $attempts_stats = $mc->query("SELECT 
                                         COUNT(*) as total_players,
                                         SUM(current_attempts) as total_current_attempts,
                                         AVG(current_attempts) as avg_current_attempts
                                         FROM `dungeon_attempts`")->fetch_array(MYSQLI_ASSOC);
            
            // Выводим форму управления попытками
?>
<style>
.attempts_form {
    background: rgba(40, 40, 60, 0.7);
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
}

.attempts_form .form_group {
    margin-bottom: 15px;
}

.attempts_form label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #FFF8DC;
}

.attempts_form input[type="number"] {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    background: rgba(30, 30, 40, 0.7);
    color: #e0e0e0;
    font-size: 1rem;
}

.attempts_form .checkbox_group {
    display: flex;
    align-items: center;
    margin-top: 15px;
}

.attempts_form .checkbox_group input[type="checkbox"] {
    margin-right: 10px;
}

.attempts_stats {
    background: rgba(40, 40, 60, 0.7);
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
}

.attempts_stats .stat_row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.attempts_stats .stat_label {
    font-weight: bold;
    color: #A9A9A9;
}

.attempts_stats .stat_value {
    color: #FFD700;
}
</style>

<div class="admin_container">
    <div class="admin_header">Управление попытками подземелий</div>
    
    <?php if (isset($success)): ?>
    <div class="admin_message success"><?= $success ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
    <div class="admin_message error"><?= $error ?></div>
    <?php endif; ?>
    
    <?php if (isset($reset_message)): ?>
    <div class="admin_message success"><?= $reset_message ?></div>
    <?php endif; ?>
    
    <div class="attempts_form">
        <form method="post" action="/dungeons/admin.php?action=attempts">
            <div class="form_group">
                <label for="default_attempts">Количество попыток по умолчанию:</label>
                <input type="number" id="default_attempts" name="default_attempts" value="<?= $default_attempts ?>" min="1" max="100" required>
                <p class="hint">Это значение будет установлено для всех новых игроков и как максимальное для существующих.</p>
            </div>
            
            <div class="form_group">
                <div class="checkbox_group">
                    <input type="checkbox" id="reset_all" name="reset_all" value="1">
                    <label for="reset_all">Сбросить текущие попытки всех игроков до максимума</label>
                </div>
            </div>
            
            <button type="submit" name="submit" class="admin_btn create">Сохранить настройки</button>
        </form>
    </div>
    
    <div class="attempts_stats">
        <h3>Статистика попыток</h3>
        
        <div class="stat_row">
            <span class="stat_label">Всего игроков с попытками:</span>
            <span class="stat_value"><?= number_format($attempts_stats['total_players']) ?></span>
        </div>
        
        <div class="stat_row">
            <span class="stat_label">Суммарно доступных попыток:</span>
            <span class="stat_value"><?= number_format($attempts_stats['total_current_attempts']) ?></span>
        </div>
        
        <div class="stat_row">
            <span class="stat_label">В среднем попыток на игрока:</span>
            <span class="stat_value"><?= number_format($attempts_stats['avg_current_attempts'], 1) ?></span>
        </div>
    </div>
    
    <div class="admin_toolbar">
        <a href="/dungeons/admin.php" class="admin_btn create">Вернуться к списку подземелий</a>
    </div>
</div>
<?php
            break;
            
        default:
            // Неизвестное действие, перенаправляем на главную
            ?><script>/*nextshowcontemt*/showContent("/dungeons/admin.php");</script><?php
            exit(0);
    }
} else {
    // Главная страница админки
    // Получаем список всех подземелий
    $dungeons = $mc->query("SELECT * FROM `dungeons` ORDER BY `id` DESC");
?>

<style>
.admin_container {
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
    background: rgba(20, 20, 30, 0.9);
    border-radius: 16px;
    color: #e0e0e0;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
}

.admin_header {
    background: linear-gradient(135deg, #483D8B, #4B0082, #800080);
    color: #FFF8DC;
    padding: 18px 20px;
    border-radius: 12px;
    text-align: center;
    font-size: 1.4rem;
    font-weight: 600;
    margin-bottom: 25px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    box-shadow: 0 6px 12px rgba(0,0,0,0.2);
    position: relative;
    overflow: hidden;
}

.admin_header:before {
    content: '';
    position: absolute;
    top: 0;
    left: -50%;
    width: 200%;
    height: 2px;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.admin_toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.admin_btn {
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    color: white;
    text-decoration: none;
    display: inline-block;
}

.admin_btn.create {
    background: #228B22;
}

.admin_btn.create:hover {
    background: #32CD32;
    transform: scale(1.05);
}

.admin_btn.attempts {
    background: #4169E1;
}

.admin_btn.attempts:hover {
    background: #6495ED;
}

.admin_table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background: rgba(40, 40, 60, 0.7);
    border-radius: 8px;
    overflow: hidden;
}

.admin_table th, .admin_table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.admin_table th {
    background: rgba(60, 60, 80, 0.7);
    color: #FFF8DC;
    font-weight: bold;
}

.admin_table tr:hover {
    background: rgba(60, 60, 80, 0.5);
}

.admin_table .status {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 12px;
    font-size: 0.85rem;
}

.admin_table .active {
    background: rgba(50, 205, 50, 0.3);
    color: #90EE90;
}

.admin_table .inactive {
    background: rgba(220, 20, 60, 0.3);
    color: #FFA07A;
}

.action_btns {
    display: flex;
    gap: 10px;
}

.action_btn {
    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.2s ease;
    color: white;
    text-decoration: none;
}

.action_btn.edit {
    background: #4169E1;
}

.action_btn.edit:hover {
    background: #6495ED;
}

.action_btn.delete {
    background: #B22222;
}

.action_btn.delete:hover {
    background: #FF0000;
}

.admin_message {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 8px;
}

.admin_message.success {
    background: rgba(50, 205, 50, 0.2);
    border: 1px solid rgba(50, 205, 50, 0.5);
    color: #90EE90;
}

.admin_message.error {
    background: rgba(220, 20, 60, 0.2);
    border: 1px solid rgba(220, 20, 60, 0.5);
    color: #FFA07A;
}

.dungeon_empty {
    text-align: center;
    padding: 20px;
    font-style: italic;
    color: #A9A9A9;
}

.stats_block {
    background: rgba(40, 40, 60, 0.7);
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
}

.stats_title {
    font-weight: bold;
    margin-bottom: 10px;
    color: #FFF8DC;
}

.stats_grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
}

.stat_card {
    background: rgba(60, 60, 80, 0.7);
    border-radius: 8px;
    padding: 12px;
    text-align: center;
}

.stat_value {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 5px;
    color: #FFD700;
}

.stat_label {
    font-size: 0.9rem;
    color: #A9A9A9;
}
</style>

<div class="admin_container">
    <div class="admin_header">Админ-панель: Управление подземельями</div>
    
    <?php if (isset($success)): ?>
    <div class="admin_message success"><?= $success ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
    <div class="admin_message error"><?= $error ?></div>
    <?php endif; ?>
    
    <div class="admin_toolbar">
        <a href="/dungeons/admin.php?action=create" class="admin_btn create">
            <i class="fas fa-plus"></i> Создать подземелье
        </a>
        <a href="/dungeons/admin.php?action=attempts" class="admin_btn attempts">
            <i class="fas fa-redo"></i> Управление попытками
        </a>
    </div>
    
    <div class="stats_block">
        <div class="stats_title">Статистика системы подземелий</div>
        <div class="stats_grid">
            <?php
            // Общее количество подземелий
            $total_dungeons = $mc->query("SELECT COUNT(*) as count FROM `dungeons`")->fetch_array(MYSQLI_ASSOC)['count'];
            
            // Активные сессии
            $active_sessions = $mc->query("SELECT COUNT(*) as count FROM `dungeon_sessions` WHERE `completed` = '0'")->fetch_array(MYSQLI_ASSOC)['count'];
            
            // Завершенные сессии
            $completed_sessions = $mc->query("SELECT COUNT(*) as count FROM `dungeon_sessions` WHERE `completed` = '1'")->fetch_array(MYSQLI_ASSOC)['count'];
            
            // Уникальные пользователи
            $unique_users = $mc->query("SELECT COUNT(DISTINCT `user_id`) as count FROM `dungeon_progress`")->fetch_array(MYSQLI_ASSOC)['count'];
            ?>
            
            <div class="stat_card">
                <div class="stat_value"><?= $total_dungeons ?></div>
                <div class="stat_label">Подземелий создано</div>
            </div>
            
            <div class="stat_card">
                <div class="stat_value"><?= $active_sessions ?></div>
                <div class="stat_label">Активных сессий</div>
            </div>
            
            <div class="stat_card">
                <div class="stat_value"><?= $completed_sessions ?></div>
                <div class="stat_label">Завершено исследований</div>
            </div>
            
            <div class="stat_card">
                <div class="stat_value"><?= $unique_users ?></div>
                <div class="stat_label">Игроков исследовало</div>
            </div>
        </div>
    </div>
    
    <div class="admin_toolbar">
        <div class="admin_title">Список подземелий</div>
        <a href="/dungeons/admin.php?action=create" class="admin_btn create">Создать подземелье</a>
    </div>
    
    <?php if ($dungeons->num_rows > 0): ?>
    <table class="admin_table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Уровень</th>
                <th>Сложность</th>
                <th>Статус</th>
                <th>Дата создания</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($dungeon = $dungeons->fetch_array(MYSQLI_ASSOC)): ?>
            <tr>
                <td><?= $dungeon['id'] ?></td>
                <td><?= $dungeon['name'] ?></td>
                <td><?= $dungeon['min_level'] ?> - <?= $dungeon['max_level'] ?></td>
                <td>
                    <?php
                    switch ($dungeon['difficulty']) {
                        case 1: echo 'Легкая'; break;
                        case 2: echo 'Нормальная'; break;
                        case 3: echo 'Сложная'; break;
                        case 4: echo 'Очень сложная'; break;
                        case 5: echo 'Эпическая'; break;
                        default: echo 'Неизвестно';
                    }
                    ?>
                </td>
                <td>
                    <span class="status <?= $dungeon['active'] ? 'active' : 'inactive' ?>">
                        <?= $dungeon['active'] ? 'Активно' : 'Отключено' ?>
                    </span>
                </td>
                <td><?= date('d.m.Y H:i', $dungeon['added_date']) ?></td>
                <td>
                    <div class="action_btns">
                        <a href="/dungeons/admin.php?action=edit&id=<?= $dungeon['id'] ?>" class="action_btn edit">Редактировать</a>
                        <a href="/dungeons/admin.php?action=delete&id=<?= $dungeon['id'] ?>" class="action_btn delete" onclick="return confirm('Вы уверены, что хотите удалить это подземелье?');">Удалить</a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <div class="dungeon_empty">Подземелья еще не созданы. Создайте первое подземелье, нажав на кнопку выше.</div>
    <?php endif; ?>
</div>

<?php
}
?> 