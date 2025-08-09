<?php
require_once '../system/func.php';
require_once '../system/dbc.php';
require_once '../system/header.php';

auth(); // Закроем от неавторизированных
requestModer(); // Закроем для тех у кого есть запрос на модератора

// Получаем прогресс игрока по всем подземельям
$progress = $mc->query("SELECT dp.*, d.name, d.min_level, d.max_level, d.difficulty, d.icon 
                        FROM `dungeon_progress` dp 
                        LEFT JOIN `dungeons` d ON dp.dungeon_id = d.id 
                        WHERE dp.user_id = '" . $user['id'] . "' 
                        AND d.active = '1' 
                        ORDER BY d.difficulty ASC");

// Получаем все доступные подземелья
$all_dungeons = $mc->query("SELECT * FROM `dungeons` WHERE `active` = '1' ORDER BY `difficulty` ASC");

// Общая статистика игрока
$total_completions = $mc->query("SELECT SUM(completed_times) as total FROM `dungeon_progress` WHERE `user_id` = '" . $user['id'] . "'")->fetch_array(MYSQLI_ASSOC)['total'];
if (!$total_completions) $total_completions = 0;

$total_active_sessions = $mc->query("SELECT COUNT(*) as count FROM `dungeon_sessions` WHERE `user_id` = '" . $user['id'] . "' AND `completed` = '0'")->fetch_array(MYSQLI_ASSOC)['count'];

// Получаем лимит попыток в день
$max_attempts = 5; // По умолчанию
$attempts_setting = $mc->query("SELECT * FROM `settings` WHERE `key` = 'dungeon_max_attempts'")->fetch_array(MYSQLI_ASSOC);
if ($attempts_setting) {
    $max_attempts = intval($attempts_setting['value']);
}

// Получаем текущие попытки игрока
$current_attempts = $mc->query("SELECT * FROM `dungeon_attempts` WHERE `user_id` = '" . $user['id'] . "'")->fetch_array(MYSQLI_ASSOC);
if (!$current_attempts) {
    // Если записи нет, создаем новую
    $mc->query("INSERT INTO `dungeon_attempts` (`user_id`, `current_attempts`, `max_attempts`, `last_reset`) 
                VALUES ('" . $user['id'] . "', '0', '$max_attempts', '" . strtotime(date('Y-m-d')) . "')");
    $current_attempts = array(
        'current_attempts' => 0,
        'max_attempts' => $max_attempts,
        'last_reset' => strtotime(date('Y-m-d'))
    );
}

// Проверяем, нужно ли сбросить попытки (новый день)
$today_start = strtotime(date('Y-m-d'));
if ($current_attempts['last_reset'] < $today_start) {
    $mc->query("UPDATE `dungeon_attempts` SET `current_attempts` = '0', `last_reset` = '$today_start' WHERE `user_id` = '" . $user['id'] . "'");
    $current_attempts['current_attempts'] = 0;
    $current_attempts['last_reset'] = $today_start;
}

// Получаем топ игроков по прохождению подземелий
$top_players = $mc->query("SELECT u.id, u.name, u.lvl, u.side, SUM(dp.completed_times) as total_completed 
                          FROM `dungeon_progress` dp 
                          LEFT JOIN `users` u ON dp.user_id = u.id 
                          GROUP BY dp.user_id 
                          ORDER BY total_completed DESC 
                          LIMIT 10");

?>

<style>
.stats_container {
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
    background: rgba(20, 20, 30, 0.9);
    border-radius: 16px;
    color: #e0e0e0;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
}

.stats_header {
    background: linear-gradient(135deg, #4B0082, #8A2BE2, #9370DB);
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

.stats_header:before {
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

.stats_summary {
    background: rgba(40, 40, 60, 0.7);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 25px;
}

.stats_title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 15px;
    color: #FFF8DC;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    padding-bottom: 10px;
}

.stats_grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
}

.stat_card {
    background: rgba(30, 30, 40, 0.7);
    border-radius: 8px;
    padding: 15px;
    text-align: center;
}

.stat_value {
    font-size: 1.8rem;
    font-weight: bold;
    margin-bottom: 5px;
    color: #9370DB;
}

.stat_label {
    font-size: 0.9rem;
    color: #A9A9A9;
}

.progress_container {
    background: rgba(40, 40, 60, 0.7);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 25px;
}

.dungeon_list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}

.dungeon_card {
    background: rgba(30, 30, 40, 0.7);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.dungeon_card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
}

.dungeon_header {
    padding: 15px;
    position: relative;
    background-size: cover;
    background-position: center;
    height: 100px;
    display: flex;
    align-items: flex-end;
}

.dungeon_header:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0,0,0,0.8));
}

.dungeon_icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: 2px solid #9370DB;
    margin-right: 10px;
    position: relative;
    background: #000;
    overflow: hidden;
}

.dungeon_icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.dungeon_name {
    font-weight: bold;
    font-size: 1.1rem;
    color: #FFF;
    position: relative;
    text-shadow: 0 1px 3px rgba(0,0,0,0.8);
}

.dungeon_body {
    padding: 15px;
}

.dungeon_stats {
    margin-top: 10px;
}

.dungeon_stat {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: 0.9rem;
}

.dungeon_stat_label {
    color: #A9A9A9;
}

.dungeon_stat_value {
    font-weight: bold;
    color: #e0e0e0;
}

.dungeon_progress {
    width: 100%;
    height: 8px;
    background: rgba(0, 0, 0, 0.3);
    border-radius: 4px;
    overflow: hidden;
    margin-top: 15px;
}

.dungeon_progress_bar {
    height: 100%;
    background: linear-gradient(to right, #9370DB, #8A2BE2);
    border-radius: 4px;
}

.dungeon_footer {
    padding: 15px;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.dungeon_difficulty {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: bold;
}

.difficulty-1 {
    background: rgba(50, 205, 50, 0.2);
    color: #90EE90;
}

.difficulty-2 {
    background: rgba(255, 215, 0, 0.2);
    color: #FFD700;
}

.difficulty-3 {
    background: rgba(255, 165, 0, 0.2);
    color: #FFA500;
}

.difficulty-4 {
    background: rgba(255, 69, 0, 0.2);
    color: #FF6347;
}

.difficulty-5 {
    background: rgba(178, 34, 34, 0.2);
    color: #FF6666;
}

.dungeon_action {
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    font-size: 0.85rem;
    font-weight: bold;
    background: #9370DB;
    color: white;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-block;
}

.dungeon_action:hover {
    background: #8A2BE2;
    transform: scale(1.05);
}

.dungeon_empty {
    text-align: center;
    padding: 20px;
    font-style: italic;
    color: #A9A9A9;
}

.top_players {
    background: rgba(40, 40, 60, 0.7);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 25px;
}

.player_table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.player_table th, .player_table td {
    padding: 10px 15px;
    text-align: left;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.player_table th {
    color: #FFF8DC;
    font-weight: 600;
}

.player_avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    overflow: hidden;
}

.player_avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.player_name {
    display: flex;
    align-items: center;
    gap: 10px;
}

.player_rank {
    font-weight: bold;
    font-size: 1.1rem;
}

.rank-1 {
    color: #FFD700;
}

.rank-2 {
    color: #C0C0C0;
}

.rank-3 {
    color: #CD7F32;
}

.attempts_info {
    background: rgba(40, 40, 60, 0.7);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.attempts_text {
    font-size: 1.1rem;
}

.attempts_value {
    font-weight: bold;
    color: #9370DB;
}

.attempts_reset {
    font-size: 0.9rem;
    color: #A9A9A9;
    margin-top: 5px;
}
</style>

<div class="stats_container">
    <div class="stats_header">Мои подземелья</div>
    
    <div class="attempts_info">
        <div>
            <span class="attempts_text">Доступно попыток сегодня: <span class="attempts_value"><?= ($max_attempts - $current_attempts['current_attempts']) ?>/<?= $max_attempts ?></span></span>
            <div class="attempts_reset">Попытки обновятся завтра в 00:00</div>
        </div>
        
        <a href="/dungeons/index.php" class="dungeon_action">Список подземелий</a>
    </div>
    
    <div class="stats_summary">
        <div class="stats_title">Общая статистика</div>
        <div class="stats_grid">
            <div class="stat_card">
                <div class="stat_value"><?= $progress->num_rows ?></div>
                <div class="stat_label">Исследовано подземелий</div>
            </div>
            
            <div class="stat_card">
                <div class="stat_value"><?= $total_completions ?></div>
                <div class="stat_label">Полных прохождений</div>
            </div>
            
            <div class="stat_card">
                <div class="stat_value"><?= $total_active_sessions ?></div>
                <div class="stat_label">Активных сессий</div>
            </div>
            
            <div class="stat_card">
                <div class="stat_value"><?= $all_dungeons->num_rows ?></div>
                <div class="stat_label">Всего подземелий</div>
            </div>
        </div>
    </div>
    
    <div class="progress_container">
        <div class="stats_title">Мой прогресс</div>
        
        <?php if ($progress->num_rows > 0): ?>
        <div class="dungeon_list">
            <?php while ($dungeon_progress = $progress->fetch_array(MYSQLI_ASSOC)): ?>
            <?php
            // Получаем данные о подземелье
            $dungeon = $mc->query("SELECT * FROM `dungeons` WHERE `id` = '" . $dungeon_progress['dungeon_id'] . "'")->fetch_array(MYSQLI_ASSOC);
            
            // Если есть активная сессия, получаем ее
            $active_session = $mc->query("SELECT * FROM `dungeon_sessions` WHERE `user_id` = '" . $user['id'] . "' AND `dungeon_id` = '" . $dungeon_progress['dungeon_id'] . "' AND `completed` = '0' ORDER BY `id` DESC LIMIT 1")->fetch_array(MYSQLI_ASSOC);
            
            // Определяем сложность текстом
            $difficulty_text = '';
            switch ($dungeon['difficulty']) {
                case 1: $difficulty_text = 'Легкая'; break;
                case 2: $difficulty_text = 'Нормальная'; break;
                case 3: $difficulty_text = 'Сложная'; break;
                case 4: $difficulty_text = 'Очень сложная'; break;
                case 5: $difficulty_text = 'Эпическая'; break;
                default: $difficulty_text = 'Неизвестно';
            }
            
            // Путь к фоновому изображению и иконке
            $bg_path = !empty($dungeon['background']) ? "../img/dungeons/backgrounds/" . $dungeon['background'] : "../img/dungeons/backgrounds/default.jpg";
            $icon_path = !empty($dungeon['icon']) ? "../img/dungeons/icons/" . $dungeon['icon'] : "../img/dungeons/icons/default.png";
            
            // Декодируем данные JSON
            $floors_data = json_decode($dungeon['floors_data'], true);
            
            // Вычисляем процент прогресса (если игрок достиг максимального уровня - 100%)
            $max_level = count($floors_data);
            $progress_percent = ($dungeon_progress['max_reached_level'] / $max_level) * 100;
            if ($dungeon_progress['max_reached_level'] >= $max_level) {
                $progress_percent = 100;
            }
            ?>
            <div class="dungeon_card">
                <div class="dungeon_header" style="background-image: url('<?= $bg_path ?>');">
                    <div class="dungeon_icon">
                        <img src="<?= $icon_path ?>" alt="<?= $dungeon['name'] ?>">
                    </div>
                    <div class="dungeon_name"><?= $dungeon['name'] ?></div>
                </div>
                
                <div class="dungeon_body">
                    <div class="dungeon_stats">
                        <div class="dungeon_stat">
                            <div class="dungeon_stat_label">Уровень игрока:</div>
                            <div class="dungeon_stat_value"><?= $dungeon['min_level'] ?>-<?= $dungeon['max_level'] ?></div>
                        </div>
                        
                        <div class="dungeon_stat">
                            <div class="dungeon_stat_label">Макс. достигнутый этаж:</div>
                            <div class="dungeon_stat_value"><?= $dungeon_progress['max_reached_level'] ?>/<?= $max_level ?></div>
                        </div>
                        
                        <div class="dungeon_stat">
                            <div class="dungeon_stat_label">Полных прохождений:</div>
                            <div class="dungeon_stat_value"><?= $dungeon_progress['completed_times'] ?></div>
                        </div>
                        
                        <div class="dungeon_stat">
                            <div class="dungeon_stat_label">Последнее прохождение:</div>
                            <div class="dungeon_stat_value">
                                <?= !empty($dungeon_progress['last_completed']) ? date('d.m.Y', $dungeon_progress['last_completed']) : 'Никогда' ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="dungeon_progress">
                        <div class="dungeon_progress_bar" style="width: <?= $progress_percent ?>%;"></div>
                    </div>
                </div>
                
                <div class="dungeon_footer">
                    <div class="dungeon_difficulty difficulty-<?= $dungeon['difficulty'] ?>"><?= $difficulty_text ?></div>
                    
                    <?php if ($active_session): ?>
                    <a href="/dungeons/explore.php?session=<?= $active_session['id'] ?>" class="dungeon_action">Продолжить</a>
                    <?php else: ?>
                    <a href="/dungeons/start.php?id=<?= $dungeon['id'] ?>" class="dungeon_action">Исследовать</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div class="dungeon_empty">Вы еще не исследовали ни одного подземелья. Посетите список подземелий, чтобы начать приключение!</div>
        <?php endif; ?>
    </div>
    
    <div class="top_players">
        <div class="stats_title">Лучшие исследователи</div>
        
        <table class="player_table">
            <thead>
                <tr>
                    <th>Ранг</th>
                    <th>Игрок</th>
                    <th>Уровень</th>
                    <th>Прохождений</th>
                </tr>
            </thead>
            <tbody>
                <?php $rank = 1; ?>
                <?php while ($player = $top_players->fetch_array(MYSQLI_ASSOC)): ?>
                <tr>
                    <td>
                        <div class="player_rank rank-<?= $rank <= 3 ? $rank : '' ?>">#<?= $rank ?></div>
                    </td>
                    <td>
                        <div class="player_name">
                            <div class="player_avatar">
                                <img src="../img/avatar/<?= $player['side'] ?>.png" alt="<?= $player['name'] ?>">
                            </div>
                            <?= $player['name'] ?>
                        </div>
                    </td>
                    <td><?= $player['lvl'] ?></td>
                    <td><?= $player['total_completed'] ?></td>
                </tr>
                <?php $rank++; ?>
                <?php endwhile; ?>
                
                <?php if ($top_players->num_rows == 0): ?>
                <tr>
                    <td colspan="4" class="dungeon_empty">Нет данных о прохождениях</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>