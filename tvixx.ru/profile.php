<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/bablo.php';
require_once ('system/dbc.php');
require_once ('system/func.php');

function age_times($secs) {
    $bit = array(
        ' year' => floor($secs / 31556926),
        ' day' => $secs / 86400 % 365,
        ' hour' => $secs / 3600 % 24,
        ' minute' => $secs / 60 % 60,
        ' second' => $secs % 60
    );
    
    $years = 0;
    $days = 0;
    $hours = 0;
    $ret = [];
    
    foreach ($bit as $k => $v) {
        $str = (string) $v;
        $str = strlen($str) == 1 ? "0" . $str : $str;
        
        // Года
        if ($v > 0 && $k == ' year') {
            if ((int) $str{strlen($str) - 1 - 1} > 4 || (int) $str{strlen($str) - 1} == 0 || 
                ((int) $str{strlen($str) - 2} > 0 && (int) $str{strlen($str) - 2} < 2 && strlen($str) > 1)) {
                $years = $v;
                $ret[] = $v . ' лет ';
            } elseif ((int) $str{strlen($str) - 1} > 1 && (int) $str{strlen($str) - 1} < 5) {
                $years = $v;
                $ret[] = $v . ' года ';
            } elseif ((int) $str{strlen($str) - 1} == 1) {
                $years = $v;
                $ret[] = $v . ' год ';
            }
        }
        
        // Дни
        if ($v > 0 && $k == ' day') {
            if ((int) $str{strlen($str) - 1} > 4 || (int) $str{strlen($str) - 1} == 0 || 
                ((int) $str{strlen($str) - 2} > 0 && (int) $str{strlen($str) - 2} < 2 && strlen($str) > 1)) {
                $days = $v;
                $ret[] = $v . ' дней ';
            } elseif ((int) $str{strlen($str) - 1} > 1 && (int) $str{strlen($str) - 1} < 5) {
                $days = $v;
                $ret[] = $v . ' дня ';
            } elseif ((int) $str{strlen($str) - 1} == 1) {
                $days = $v;
                $ret[] = $v . ' день ';
            }
        }
        
        // Часы
        if ($v > 0 && $k == ' hour' && $years == 0) {
            if ($v > 4 && $v < 21) {
                $hours = $v;
                $ret[] = $v . ' часов ';
            } elseif (($v > 1 && $v < 5) || $v > 21) {
                $hours = $v;
                $ret[] = $v . ' часа ';
            } elseif ((int) $str{strlen($str) - 1} == 1) {
                $hours = $v;
                $ret[] = $v . ' час ';
            }
        }
        
        // Минуты
        if ($v > 0 && $k == ' minute' && $years == 0 && $days == 0) {
            $ret[] = $v . ' мин. ';
        }
        
        // Секунды
        if ($v > 0 && $k == ' second' && $years == 0 && $days == 0 && $hours == 0) {
            $ret[] = $v . ' сек ';
        }
    }
    return join(' ', $ret);
}

setTotem();
health_rechange();
$footval = "profile";
auth(); // Закроем от неавторизированых
# Настройки #
$animations = 2;
$id = $user['id'];
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $_GET['id'] = $id;
}
$result64 = $mc->query("SELECT * FROM `users` WHERE `id` = '$id'");
$profile = $result64->fetch_array(MYSQLI_ASSOC);
$arrstat = get_user_stats();
/*
  Здоровье:
  Урон:
  Точность:
  Броня:
  Уворот:
  Оглушение:
  Блок:
 * 
 */
//получаем параметры одетых бонусных шмоток 
$arr = [];
$arr['health'] = 0; //Здоровье
$arr['strength'] = 0; //урон
$arr['toch'] = 0; //точность
$arr['bron'] = 0; //броня
$arr['lov'] = 0; //уворот
$arr['kd'] = 0; //оглушение
$arr['block'] = 0; //блок
$arr['all'] = 0; //все статы
//пересчет параметров игрока
//получаем список только бонусных предметов (id_punct > 9) с боевым флагом
if ($myrow221 = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '$id' AND `id_punct` > '9' AND `BattleFlag` = '1'")->fetch_all(MYSQLI_ASSOC)) {
//перебираем параметры вещей
    for ($i = 0; $i < count($myrow221); $i++) {
//read thing
        if ($result1 = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $myrow221[$i]['id_shop'] . "'")) {
//thing to arr par
            $infoshop = $result1->fetch_array(MYSQLI_ASSOC);
            $arr['health'] += $infoshop['health'];
            $arr['strength'] += $infoshop['strength'];
            $arr['toch'] += $infoshop['toch'];
            $arr['lov'] += $infoshop['lov'];
            $arr['kd'] += $infoshop['kd'];
            $arr['block'] += $infoshop['block'];
            $arr['bron'] += $infoshop['bron'];
        }
    }
    $arr['all'] += $arr['health'] + $arr['strength'] + $arr['toch'] + $arr['lov'] + $arr['kd'] + $arr['block'] + $arr['bron'];
}

if ($myrow456 = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '$id' && `id_punct`='1' && `dress`='1'")->fetch_array(MYSQLI_ASSOC)) {
//получаем параметры вещей
    if ($myrow2123 = $mc->query("SELECT * FROM `shop` WHERE `id` = '" . $myrow456['id_shop'] . "'")->fetch_array(MYSQLI_ASSOC)) {
//переводим в иконку оружия
        if ($myrow2123['id_punct'] == 1) {
            $userWeapon = $myrow2123['id_image'];
        } else {
            $userWeapon = 0;
        }
    } else {
        $userWeapon = 0;
    }
} else {
    $userWeapon = 0;
}

# Ошибки #
if ($profile == 0) {
    error('Данный пользователь не найден');
    require_once ('system/foot/foot.php');
    exit(0);
}
# Информация #
?>
<style>
    /* Общие стили для всех блоков */
    .block-container {
        background: linear-gradient(to bottom, rgba(51, 41, 28, 0.9), rgba(51, 41, 28, 0.7));
        border: 1px solid rgba(255,215,0,0.3);
        border-radius: 8px;
        padding: 15px;
        margin: 15px auto;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }

    .block-header {
        background: linear-gradient(to bottom, #ffd700, #ffa500);
        color: #000;
        padding: 8px 15px;
        border-radius: 15px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 15px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        text-shadow: 0 1px 1px rgba(255,255,255,0.5);
    }

    /* Обновляем стили для статистики */
    .profile-stats {
        background: linear-gradient(to bottom, rgba(51, 41, 28, 0.9), rgba(51, 41, 28, 0.7));
        border: 1px solid rgba(255,215,0,0.3);
    }

    /* Обновляем стили для админ-панели */
    .admin-panel {
        background: linear-gradient(to bottom, rgba(51, 41, 28, 0.9), rgba(51, 41, 28, 0.7));
        border: 1px solid rgba(255,107,107,0.3);
    }

    .admin-header {
        background: linear-gradient(to bottom, #ff6b6b, #ff4757);
        color: #fff;
        text-shadow: 0 1px 1px rgba(0,0,0,0.5);
    }

    /* Обновляем стили для инвентаря */
    .admin-inventory {
        background: linear-gradient(to bottom, rgba(51, 41, 28, 0.9), rgba(51, 41, 28, 0.7));
        border: 1px solid rgba(255,215,0,0.3);
    }

    .inventory-item {
        background: rgba(0,0,0,0.2);
        border-radius: 4px;
        margin-bottom: 5px;
        transition: all 0.2s;
    }

    .inventory-item:hover {
        background: rgba(0,0,0,0.3);
        transform: translateX(5px);
    }

    /* Обновляем стили для кнопок действий */
    .action-btn {
        background: linear-gradient(to bottom, rgba(255,215,0,0.2), rgba(255,215,0,0.1));
        border: 1px solid rgba(255,215,0,0.3);
        transition: all 0.2s;
    }

    .action-btn:hover {
        background: linear-gradient(to bottom, rgba(255,215,0,0.3), rgba(255,215,0,0.2));
        transform: translateY(-2px);
    }

    .action-btn:active {
        transform: translateY(0);
    }

    /* Обновляем стили для формы добавления предметов */
    .add-item-form input {
        background: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0.2));
        border: 1px solid rgba(255,215,0,0.3);
        transition: all 0.2s;
    }

    .add-item-form input:focus {
        background: rgba(0,0,0,0.4);
        border-color: rgba(255,215,0,0.5);
        outline: none;
    }

    /* Стили для результатов поиска */
    .search-result {
        background: rgba(0,0,0,0.2);
        padding: 10px;
        border-radius: 4px;
        margin-bottom: 5px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s;
    }

    .search-result:hover {
        background: rgba(0,0,0,0.3);
    }

    /* Обновляем стили для клановых действий */
    .clan-actions .action-btn {
        background: linear-gradient(to bottom, rgba(70,130,180,0.2), rgba(70,130,180,0.1));
        border: 1px solid rgba(70,130,180,0.3);
    }

    .clan-actions .action-btn:hover {
        background: linear-gradient(to bottom, rgba(70,130,180,0.3), rgba(70,130,180,0.2));
    }

    .colonleft{
        width: 150px;
        padding-left: 6;
    }
    .colonright{
        width: 130px;
        padding-right: 6;
        word-break: break-all;
    }
    .profile-view {
        max-width: 600px;
        margin: 0 auto;
        background: transparent;
        border-radius: 8px;
        padding: 15px;
    }

    .profile-header {
        background: linear-gradient(to bottom, rgba(51, 41, 28, 0.9), rgba(51, 41, 28, 0.7));
        border: 1px solid rgba(255,215,0,0.3);
        border-radius: 8px;
        padding: 15px;
        margin: 0 auto 20px;
        max-width: 80%;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }

    .player-info {
        display: inline-flex;
        align-items: center;
        background: linear-gradient(to bottom, #ffd700, #ffa500);
        padding: 6px 15px;
        border-radius: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .player-name {
        color: #000;
        font-size: 20px;
        font-weight: bold;
        text-shadow: 0 1px 1px rgba(255,255,255,0.5);
        margin: 0 5px;
        vertical-align: middle;
    }

    .player-level {
        color: #000;  /* Меняем с #aaa на #000 */
        margin-left: 10px;
        padding-left: 10px;
        border-left: 2px solid rgba(0,0,0,0.2);
        text-shadow: 0 1px 1px rgba(255,255,255,0.5);
    }

    .rank-star {
        width: 20px;
        height: 20px;
        vertical-align: middle;
        margin-right: 5px;
        filter: drop-shadow(0 2px 2px rgba(0,0,0,0.3));
    }

    .character-preview {
        margin: 20px 0;
        background: transparent;
        border-radius: 8px;
        padding: 10px;
    }

    .admin-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 10px;
        margin-bottom: 20px;
    }

    .admin-btn {
        background: rgba(255,107,107,0.2);
        color: #fff;
        padding: 8px 12px;
        border-radius: 4px;
        cursor: pointer;
        text-align: center;
        transition: background 0.2s;
    }

    .admin-btn:hover {
        background: rgba(255,107,107,0.3);
    }

    .inventory-header, .add-item-header {
        color: #ffd700;
        font-size: 16px;
        margin: 15px 0 10px;
        padding-bottom: 5px;
        border-bottom: 1px solid rgba(255,215,0,0.3);
    }

    .item-action {
        color: #ffd700;
        cursor: pointer;
        font-size: 12px;
        padding: 2px 6px;
        border-radius: 3px;
        background: rgba(255,215,0,0.1);
    }

    .item-action.delete {
        color: #ff6b6b;
        background: rgba(255,107,107,0.1);
    }

    .item-action:hover {
        background: rgba(255,215,0,0.2);
    }

    .item-action.delete:hover {
        background: rgba(255,107,107,0.2);
    }

    .add-item-form {
        display: grid;
        grid-template-columns: 80px 1fr auto;
        gap: 10px;
        margin-bottom: 15px;
    }

    .stats-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    .stats-table tr {
        background: rgba(0,0,0,0.2);
        transition: background 0.2s;
    }

    .stats-table tr:hover {
        background: rgba(0,0,0,0.3);
    }

    .stats-table td {
        padding: 10px 15px;
    }

    .stat-label {
        color: #ffd700;
        font-size: 14px;
        font-weight: 500;
        width: 40%;
    }

    .stat-value {
        text-align: right;
        color: #fff;
    }

    .value-main {
        font-size: 16px;
        margin-bottom: 2px;
    }

    .value-sub {
        color: rgba(255,255,255,0.7);
        font-size: 14px;
    }

    .stat-value a {
        color: #ffd700;
        text-decoration: none;
        transition: color 0.2s;
    }

    .stat-value a:hover {
        color: #fff;
    }

    /* Дополнительные стили для блока снаряжения */
    .stats-table img {
        vertical-align: middle;
        margin-right: 8px;
        filter: drop-shadow(0 2px 2px rgba(0,0,0,0.3));
    }

    .stats-table .stat-value {
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }

    .stats-table .value-sub {
        min-width: 30px;
        text-align: right;
    }

    .equipment-preview {
        margin-bottom: 15px;
    }

    .equipment-btn {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 12px;
        background: linear-gradient(to bottom, #ffd700, #ffa500);
        border-radius: 8px;
        color: #000;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .equipment-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .equipment-icon {
        background: rgba(0,0,0,0.1);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .equipment-icon i {
        font-size: 20px;
        color: #000;
    }

    .equipment-text {
        flex: 1;
    }

    .equipment-title {
        font-weight: bold;
        font-size: 16px;
        margin-bottom: 2px;
    }

    .equipment-stats {
        font-size: 14px;
        opacity: 0.8;
    }

    .view-equipment {
        text-align: center;
        margin: 15px 0;
    }

    .view-equipment-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: linear-gradient(to bottom, #ffd700, #ffa500);
        border: 1px solid #663300;
        border-radius: 6px;
        color: #663300;
        cursor: pointer;
        transition: all 0.2s;
        font-weight: bold;
    }

    .view-equipment-btn:hover {
        background: linear-gradient(to bottom, #ffd700, #ff8c00);
        transform: translateY(-1px);
    }

    .view-equipment-btn:active {
        transform: translateY(0);
    }

    .view-equipment-btn i {
        color: #663300;
    }

    .empty-slot {
        color: #666;
        font-style: italic;
    }

    .item-normal { color: #663300; }
    
    .equipment-item {
        display: flex;
        align-items: center;
        padding: 8px 15px;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .item-name span {
        font-weight: 500;
    }

    .empty-slot {
        color: #666;
        font-style: italic;
    }

    .item-elite { color: #FFD700; }  /* желтый */
    .item-damage { color: #00FF00; } /* зеленый */
    .item-armor { color: #FF0000; }  /* красный */
    .item-dodge { color: #4169E1; }  /* синий */

    .equipment-view {
        max-width: 600px;
        margin: 20px auto;
    }

    .equipment-list {
        display: grid;
        gap: 10px;
        padding: 15px;
    }

    .equipment-item {
        display: flex;
        align-items: center;
        padding: 8px 15px;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .equipment-item:hover {
        background: rgba(0,0,0,0.1);
        transform: translateX(5px);
    }

    .item-slot {
        width: 100px;
        color: #663300;
        font-size: 14px;
        font-weight: 500;
    }

    .item-name {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
        color: #663300;
    }

    .item-name span {
        font-weight: 500;
    }

    .empty-slot {
        color: #666;
        font-style: italic;
    }

    .back-to-profile {
        text-align: center;
        margin-top: 20px;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: linear-gradient(to bottom, #ffd700, #ffa500);
        border: 1px solid #663300;
        border-radius: 6px;
        color: #663300;
        cursor: pointer;
        transition: all 0.2s;
        font-weight: bold;
        text-decoration: none;
    }

    .action-btn:hover {
        background: linear-gradient(to bottom, #ffd700, #ff8c00);
        transform: translateY(-1px);
    }

    .action-btn:active {
        transform: translateY(0);
    }

    .item-normal { color: #663300; }

    .character-stats-container {
        background: linear-gradient(to bottom, rgba(139, 69, 19, 0.1), rgba(139, 69, 19, 0.05));
        border: 1px solid rgba(139, 69, 19, 0.2);
        border-radius: 12px;
        padding: 20px;
        margin: 15px auto;
        max-width: 800px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .secret-moves {
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(139, 69, 19, 0.2);
    }

    .section-title {
        color: #8B4513;
        font-size: 1.2em;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .moves-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .move-sequence {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 6px;
    }

    .move-sequence img {
        width: 24px;
        height: 24px;
    }

    .move-separator {
        color: #8B4513;
        font-weight: bold;
    }

    .main-stats {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .premium-status {
        color: #9b59b6;
        font-weight: bold;
        font-size: 1.1em;
    }

    .stats-row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .stat-group {
        flex: 1;
        min-width: 250px;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        margin-bottom: 8px;
        transition: all 0.2s ease;
    }

    .stat-item:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-1px);
    }

    .stat-icon {
        width: 24px;
        height: 24px;
        filter: drop-shadow(0 2px 2px rgba(0,0,0,0.1));
    }

    .stat-label {
        color: #8B4513;
        font-weight: 500;
        min-width: 100px;
    }

    .stat-value {
        color: #4A2601;
        font-weight: 600;
    }

    .stat-value-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .stat-subvalue {
        font-size: 0.9em;
        color: #666;
    }

    .progress-bar {
        height: 6px;
        background: rgba(139, 69, 19, 0.1);
        border-radius: 3px;
        overflow: hidden;
        margin-top: 5px;
        position: relative;
    }

    .progress {
        height: 100%;
        background: linear-gradient(to right, #8B4513, #D2691E);
        border-radius: 3px;
        transition: width 0.3s ease;
    }

    .progress-text {
        position: absolute;
        right: 5px;
        top: -18px;
        font-size: 0.8em;
        color: #666;
    }

    .race-good { color: #3498db; }
    .race-evil { color: #e74c3c; }

    .combat-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 10px;
        padding: 15px;
        background: rgba(139, 69, 19, 0.05);
        border-radius: 8px;
    }

    .bonus-link {
        color: #8B4513;
        text-decoration: underline;
        cursor: pointer;
        font-weight: bold;
    }

    .bonus-link:hover {
        color: #D2691E;
    }

    .additional-stats {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid rgba(139, 69, 19, 0.2);
    }

    @media (max-width: 768px) {
        .stats-row {
            flex-direction: column;
        }

        .stat-group {
            min-width: 100%;
        }

        .combat-stats {
            grid-template-columns: 1fr;
        }
    }

    .status-bar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to bottom, rgba(44, 62, 80, 0.95), rgba(52, 73, 94, 0.92));
        padding: 8px 0;
        z-index: 1000;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .status-bar__container {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
        gap: 20px;
    }

    .status-bar__item {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .status-bar__icon {
        width: 20px;
        height: 20px;
        object-fit: contain;
    }

    .status-bar__value {
        color: #fff;
        font-weight: 500;
        font-size: 14px;
    }

    .status-bar__currency-group {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-left: auto;
    }

    .status-bar__item--level .status-bar__value { color: #3498db; }
    .status-bar__item--platinum .status-bar__value { color: #e74c3c; }
    .status-bar__item--gold .status-bar__value { color: #f1c40f; }
    .status-bar__item--silver .status-bar__value { color: #bdc3c7; }
    .status-bar__item--copper .status-bar__value { color: #e67e22; }

    @media (max-width: 768px) {
        .status-bar__container {
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .status-bar__currency-group {
            width: 100%;
            justify-content: space-between;
            margin-left: 0;
        }
    }

    /* Добавляем стили для прогресс-бара в альтернативном виде профиля */
    .profile-progress-bar {
        width: 100%;
        height: 14px;
        background: rgba(80, 80, 100, 0.3);
        border-radius: 7px;
        margin-top: 5px;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(102, 51, 0, 0.3);
    }
    
    .profile-progress {
        height: 100%;
        background: linear-gradient(to right, #3498db, #2980b9);
        border-radius: 6px;
        transition: width 0.3s ease;
    }
    
    .profile-progress-text {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 10px;
        font-weight: bold;
        text-shadow: 0px 0px 2px rgba(0, 0, 0, 0.7);
    }

    /* Улучшаем стили прогресс-бара в основном представлении профиля */
    .stats-table .profile-progress-bar {
        height: 18px;
        background: rgba(80, 80, 100, 0.4);
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(102, 51, 0, 0.4);
    }
    
    .stats-table .profile-progress {
        background: linear-gradient(to right, #3498db, #2980b9);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    }
    
    .stats-table .profile-progress-text {
        font-size: 12px;
        font-weight: bold;
        text-shadow: 0px 1px 2px rgba(0, 0, 0, 0.9);
    }
</style>
<script>
    MyLib.loaded1 = 0;
//setTimeFoot();

    if (<?= $profile['id']; ?> !== 0) {
        profile();
    }
    function profile() {
        var MiniCanvas = $("mobitva:eq(-1)").find("#MiniCanvas")[0];
        var ctxMiniCanvas = MiniCanvas.getContext("2d");
        var buffMiniCanvas = document.createElement("canvas");
        var ctxbuffMiniCanvas = buffMiniCanvas.getContext("2d");
        var requestAnimationFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
        var myReq;
        var weaponData = [];
        var imageweapon;
        var spriteData = [];
        var spriteImage = [];
        var Pico = <?= $profile['side']; ?>;
        var Panimation = 0;
        var Pweapon = <?= $userWeapon; ?>;
        var Panimationcount = 0;
        var PposX = -180;
        var PposY = -130;
        MiniCanvas.width = buffMiniCanvas.width = 320;
        MiniCanvas.height = buffMiniCanvas.height = 150;
        var dressData = [];
        var imagedress;
        var PDress = 0;
        $.ajax({
            url: "./json/dress/dress.json?129",
            dataType: "json",
            success: function (data) {
                dressData = JSON.parse(JSON.stringify(data));
                imagedress = new Image();
                imagedress.src = dressData.img;
            }
        });
        $.ajax({
            url: "./json/weapon/weapon_new.json?139.1114",
            dataType: "json",
            success: function (a) {
                weaponData = JSON.parse(JSON.stringify(a));
                imageweapon = new Image;
                imageweapon.src = weaponData.img;
            }
        });
        $.ajax({
            url: "./json/Player/animation.json?129",
            dataType: "json",
            success: function (a) {
                spriteData = JSON.parse(JSON.stringify(a));
                for (a = 0; a < spriteData.img.length; a++)
                    spriteImage[a] = new Image, spriteImage[a].src = spriteData.img[a];
            }
        });
        function render() {
            MiniCanvas.width = MiniCanvas.width;
            try {
                ctxMiniCanvas.drawImage(buffMiniCanvas,
                        0,
                        0,
                        MiniCanvas.width,
                        MiniCanvas.height,
                        0,
                        0,
                        buffMiniCanvas.width,
                        buffMiniCanvas.height
                        );
            } catch (e) {
            }
            MyLib.intervaltimer.push(setTimeout(function () {
                myReq = requestAnimationFrame(render);
            }, 1000 / 10));
        }
        myReq = requestAnimationFrame(render);
//fffaaackkk
        MyLib.intervaltimer.push(setInterval(function () {
            buffMiniCanvas.width = buffMiniCanvas.width;
            try {
                if (Panimationcount >= spriteData[Pico][Panimation].length) {
                    Panimationcount = 0;
                }
                for (var a = 0; a < spriteData[Pico][Panimation][Panimationcount].length; a++) {
                    var type = parseInt(spriteData[Pico][Panimation][Panimationcount][a][9]);
                    var typeStr = spriteData[Pico][Panimation][Panimationcount][a][9];
                    if (type === -1) {
                        ctxbuffMiniCanvas.save();
                        ctxbuffMiniCanvas.translate(Math.round(buffMiniCanvas.width / 2 + spriteData[Pico][Panimation][Panimationcount][a][4] ) + PposX, Math.round(spriteData[Pico][Panimation][Panimationcount][a][5] + spriteData[Pico][Panimation][Panimationcount][a][7] / 2) + PposY);
                        ctxbuffMiniCanvas.rotate(spriteData[Pico][Panimation][Panimationcount][a][8] * Math.PI / 180);
                        ctxbuffMiniCanvas.drawImage(
                                imageweapon,
                                weaponData.imgC[Pweapon][0],
                                weaponData.imgC[Pweapon][1],
                                weaponData.imgC[Pweapon][2],
                                weaponData.imgC[Pweapon][3],
                                -weaponData.imgC[Pweapon][4],
                                -weaponData.imgC[Pweapon][5],
                                weaponData.imgC[Pweapon][2],
                                weaponData.imgC[Pweapon][3]
                                );
                        ctxbuffMiniCanvas.restore();
                    } else if (type > 99) {
                        ctxbuffMiniCanvas.save();
                        ctxbuffMiniCanvas.translate(Math.round(buffMiniCanvas.width / 2 + spriteData[Pico][Panimation][Panimationcount][a][4] + spriteData[Pico][Panimation][Panimationcount][a][6] / 2) + PposX, Math.round(spriteData[Pico][Panimation][Panimationcount][a][5] + spriteData[Pico][Panimation][Panimationcount][a][7] / 2) + PposY);
                        ctxbuffMiniCanvas.rotate(spriteData[Pico][Panimation][Panimationcount][a][8] * Math.PI / 180);
                        ctxbuffMiniCanvas.drawImage(
                                imagedress,
                                dressData[typeStr][Pico][PDress][0],
                                dressData[typeStr][Pico][PDress][1],
                                dressData[typeStr][Pico][PDress][2],
                                dressData[typeStr][Pico][PDress][3],
                                Math.round(-dressData[typeStr][Pico][PDress][2] / 2),
                                Math.round(-dressData[typeStr][Pico][PDress][3] / 2),
                                dressData[typeStr][Pico][PDress][2],
                                dressData[typeStr][Pico][PDress][3]
                                );
                        ctxbuffMiniCanvas.restore();
                    } else {
                        ctxbuffMiniCanvas.save();
                        ctxbuffMiniCanvas.translate(Math.round(buffMiniCanvas.width / 2 + spriteData[Pico][Panimation][Panimationcount][a][4] + spriteData[Pico][Panimation][Panimationcount][a][6] / 2) + PposX, Math.round(spriteData[Pico][Panimation][Panimationcount][a][5] + spriteData[Pico][Panimation][Panimationcount][a][7] / 2) + PposY);
                        ctxbuffMiniCanvas.rotate(spriteData[Pico][Panimation][Panimationcount][a][8] * Math.PI / 180);
                        ctxbuffMiniCanvas.drawImage(
                                spriteImage[Pico],
                                spriteData[Pico][Panimation][Panimationcount][a][0],
                                spriteData[Pico][Panimation][Panimationcount][a][1],
                                spriteData[Pico][Panimation][Panimationcount][a][2],
                                spriteData[Pico][Panimation][Panimationcount][a][3],
                                Math.round(-spriteData[Pico][Panimation][Panimationcount][a][6] / 2),
                                Math.round(-spriteData[Pico][Panimation][Panimationcount][a][7] / 2),
                                spriteData[Pico][Panimation][Panimationcount][a][6],
                                spriteData[Pico][Panimation][Panimationcount][a][7]
                                );
                        ctxbuffMiniCanvas.restore();
                    }
                }
            } catch (e) {

            }
            Panimationcount++;
        }, 200));
    }
</script>
<?php
if (isset($user['id']) && isset($_GET['id']) && $user['id'] == $_GET['id'] && !isset($_GET['bonus'])) {
    if (isset($user['id'])) {
        ?>
        <div style="position: relative;top: -5px;z-index:99999">
            <table style="margin: auto;border-spacing: 0;height: 40px;max-width: 100%;">
                <tr>
                    <td class="lth_01l" style="width: 18px;"></td>
                    <td class="lth_01c">
                        <div style="font-size: 14px;font-family: font-family:Anfisa;">
                            <img style="width: 16px;" src="/img/img23.png"><?= $user['level']; ?>
                            <img style="width: 18px;" src="/images/icons/hp.png">
                            <?php
                            //Не съежали деньги
                            $dengi = 0;
                            ?>

                            <?php if ($user['platinum'] != 0) { ?>
                                <img style="width: 15px;" src="/images/icons/plata.png">
                                <?php
                                echo $user['platinum'];
                                $dengi++;
                                ?>
                            <?php } ?>

                            <?php if (money($user['money'], 'zoloto') != 0) { ?>
                                <img style="width: 15px;" src="/images/icons/zoloto.png">
                                <?php
                                echo money($user['money'], 'zoloto');
                                $dengi++;
                                ?>
                            <?php } ?>

                            <?php if (money($user['money'], 'serebro') != 0) { ?>
                                <img style="width: 15px;" src="/images/icons/serebro.png">
                                <?php
                                echo money($user['money'], 'serebro');
                                $dengi++;
                                ?>
                            <?php } ?>

                            <?php if (money($user['money'], 'med') != 0) { ?>
                                <img style="width: 15px;" src="/images/icons/med.png">
                                <?= money($user['money'], 'med'); ?>
                            <?php } ?>
                        </div>
                    </td>
                    <td class="lth_01r" style="width: 18px;"></td> 
                </tr>
            </table>
        </div>
    <?php } ?>
    <center>
        <div>
            <canvas id="MiniCanvas"></canvas>
        </div>
    </center>

<?php
// Инициализация переменных
$maxuron = $arrstat['strength'] + $profile['level'];
$maxtoch = $arrstat['toch'] + $profile['level'];
$maxlov = $arrstat['lov'] + $profile['level'];
$maxkd = $arrstat['kd'] + $profile['level'];

// Получаем репутацию
$rep_num = $profile['rep_p'] - $profile['rep_m'];
$rep_name = "Неизвестный";
if ($reparr = $mc->query("SELECT * FROM `reputation` WHERE `rep` <= '" . $rep_num . "' ORDER BY `rep` DESC LIMIT 1")->fetch_array(MYSQLI_ASSOC)) {
    $rep_name = $reparr['name'];
}

// Получаем звание
$slavamin = $mc->query("SELECT * FROM `slava` WHERE `slava` <= '" . $profile['slava'] . "' && `lvl` <= '" . $profile['level'] . "' ORDER BY `slava` DESC LIMIT 1")->fetch_array(MYSQLI_ASSOC);
$slavamax = $mc->query("SELECT * FROM `slava` WHERE `slava` > '" . $profile['slava'] . "' && `lvl` <= '" . $profile['level'] . "' ORDER BY `slava` ASC LIMIT 1")->fetch_array(MYSQLI_ASSOC);

if (!isset($slavamax['slava'])) {
    $slavamax['slava'] = "100";
}

// Отладка тайных приёмов
echo "<!-- Debug superudar: " . $profile['superudar'] . " -->";

// Получаем тайные приёмы персонажа
$arrsu = [];
if (!empty($profile['superudar'])) {
    $arrsu = explode(",", $profile['superudar']);
    // Удаляем пустые элементы
    $arrsu = array_filter($arrsu, function($value) {
        return !empty($value);
    });
    
    // Отладка массива приёмов
    echo "<!-- Debug arrsu: " . print_r($arrsu, true) . " -->";
}

// Получаем описания тайных приёмов
$superudar_desc = [];
if (!empty($arrsu)) {
    foreach ($arrsu as $combo) {
        $result = $mc->query("SELECT * FROM `superudar` WHERE `combo` = '" . $combo . "'");
        // Отладка SQL запроса
        echo "<!-- Debug SQL: SELECT * FROM `superudar` WHERE `combo` = '" . $combo . "' -->";
        
        if ($result && $row = $result->fetch_array(MYSQLI_ASSOC)) {
            $superudar_desc[$combo] = $row['name'];
            // Отладка результата
            echo "<!-- Debug combo " . $combo . ": " . $row['name'] . " -->";
        }
    }
}
?>

<div class="character-stats-container">
    <!-- Тайные приёмы -->
    <?php 
    if (!empty($arrsu)) { 
        echo "<!-- Debug: есть приёмы -->";
    ?>
        <div class="secret-moves">
            <div class="section-title">Тайные приёмы</div>
            <div class="moves-grid">
                <?php 
                foreach ($arrsu as $combo) {
                    echo '<div class="move-sequence">';
                    // Показываем комбинацию
                    for ($i = 0; $i < strlen($combo) - 1; $i++) {
                        echo '<img src="/images/super/' . $combo[$i] . 'su.png" alt="' . $combo[$i] . '">';
                        echo '<span class="move-separator">→</span>';
                    }
                    echo '<img src="/images/super/' . $combo[strlen($combo) - 1] . 'su.png" alt="' . $combo[strlen($combo) - 1] . '">';
                    
                    // Показываем название приёма, если есть
                    if (isset($superudar_desc[$combo])) {
                        echo '<span class="move-name">' . $superudar_desc[$combo] . '</span>';
                    }
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    <?php 
    } else {
        echo "<!-- Debug: нет приёмов -->";
    }
    ?>

    <!-- Основная информация -->
    <div class="main-stats">
        <div class="stats-row premium">
            <?php if ($profile['prem'] == '1') { ?>
                <span class="premium-status">Премиум <?= age_times($profile['prem_t'] - time()) ?></span>
            <?php } ?>
        </div>

        <div class="stats-row">
            <div class="stat-group">
                <div class="stat-item">
                    <span class="stat-label">Имя:</span>
                    <span class="stat-value"><?= $profile['name'] ?></span>
                </div>

                <div class="stat-item">
                    <span class="stat-label">Народ:</span>
                    <span class="stat-value <?= ($profile['side'] == 2 || $profile['side'] == 3) ? 'race-good' : 'race-evil' ?>">
                        <?= ($profile['side'] == 2 || $profile['side'] == 3) ? 'Нармасцы' : 'Шейване' ?>
                    </span>
                </div>
            </div>

            <div class="stat-group">
                <div class="stat-item">
                    <span class="stat-label">Опыт:</span>
                    <div class="stat-value-group">
                        <?php
                        // Получаем информацию о опыте для следующего уровня
                        $current_level = $profile['level'];
                        $next_level_info = $mc->query("SELECT * FROM `exp` WHERE `lvl` = '" . ($current_level + 1) . "'")->fetch_array(MYSQLI_ASSOC);
                        
                        if ($next_level_info) {
                            $next_level_exp = $next_level_info['exp'];
                            $current_level_info = $mc->query("SELECT * FROM `exp` WHERE `lvl` = '" . $current_level . "'")->fetch_array(MYSQLI_ASSOC);
                            $current_level_exp = $current_level_info ? $current_level_info['exp'] : 0;
                            
                            // Рассчитываем прогресс в процентах
                            $exp_needed = $next_level_exp - $current_level_exp;
                            $exp_progress = $profile['exp'] - $current_level_exp;
                            $percent = ($exp_progress / $exp_needed) * 100;
                            
                            // Ограничиваем значение от 0 до 100
                            $percent = max(0, min(100, $percent));
                        ?>
                        <div class="profile-progress-bar">
                            <div class="profile-progress" style="width: <?= $percent ?>%"></div>
                            <span class="profile-progress-text"><?= $exp_progress ?>/<?= $exp_needed ?></span>
                        </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="stat-item">
                    <span class="stat-label">Репутация:</span>
                    <div class="stat-value-group">
                        <span class="stat-value"><?= $rep_name ?></span>
                        <span class="stat-subvalue"><?= $profile['rep_p'] ?>/<?= $profile['rep_m'] ?></span>
                    </div>
                </div>

                <div class="stat-item">
                    <span class="stat-label">Звание:</span>
                    <div class="stat-value-group">
                        <span class="stat-value"><?= $slavamin['name'] ?></span>
                        <div class="profile-progress-bar">
                            <div class="profile-progress" style="width: <?= ($profile['slava'] / $slavamax['slava']) * 100 ?>%"></div>
                            <span class="profile-progress-text"><?= $profile['slava'] ?>/<?= $slavamax['slava'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="combat-stats">
            <div class="stat-item">
                <img src="/images/icons/hp.png" alt="Здоровье" class="stat-icon">
                <span class="stat-label">Здоровье:</span>
                <span class="stat-value"><?= $arrstat['max_health'] ?></span>
            </div>

            <div class="stat-item">
                <img src="/images/icons/power.jpg" alt="Урон" class="stat-icon">
                <span class="stat-label">Урон:</span>
                <span class="stat-value"><?= $arrstat['strength'] ?>...<?= $maxuron ?></span>
            </div>

            <div class="stat-item">
                <img src="/images/icons/toch.png" alt="Точность" class="stat-icon">
                <span class="stat-label">Точность:</span>
                <span class="stat-value"><?= $arrstat['toch'] ?>...<?= $maxtoch ?></span>
            </div>

            <div class="stat-item">
                <img src="/images/icons/bron.png" alt="Броня" class="stat-icon">
                <span class="stat-label">Броня:</span>
                <span class="stat-value"><?= $arrstat['bron'] ?></span>
            </div>

            <div class="stat-item">
                <img src="/images/icons/img235.png" alt="Уворот" class="stat-icon">
                <span class="stat-label">Уворот:</span>
                <span class="stat-value"><?= $arrstat['lov'] ?> ... <?= $maxlov ?></span>
            </div>

            <div class="stat-item">
                <img src="/images/icons/kd.png" alt="Оглушение" class="stat-icon">
                <span class="stat-label">Оглушение:</span>
                <span class="stat-value"><?= $arrstat['kd'] ?> ... <?= $maxkd ?></span>
            </div>

            <div class="stat-item">
                <img src="/images/icons/shit.png" alt="Блок" class="stat-icon">
                <span class="stat-label">Блок:</span>
                <span class="stat-value"><?= $arrstat['block'] ?></span>
            </div>
        </div>

        <div class="additional-stats">
            <div class="stat-item">
                <span class="stat-label">Бонусы:</span>
                <a class="bonus-link" onclick="showContent('/profile.php?bonus')">
                    <?= $arr['all'] ?>(<?= $arr['all'] ?>)
                </a>
            </div>

            <div class="stat-item">
                <span class="stat-label">Победы (монстры):</span>
                <span class="stat-value"><?= $profile['pobedmonser'] ?>/<?= $profile['losemonser'] ?></span>
            </div>

            <div class="stat-item">
                <span class="stat-label">Победы (игроки):</span>
                <span class="stat-value"><?= $profile['pobedigroki'] ?></span>
            </div>

            <div class="stat-item">
                <span class="stat-label">Возраст в игре:</span>
                <span class="stat-value"><?= age_times(time() - $profile['registr']) ?></span>
            </div>
        </div>
    </div>
</div>

<?php
} else {
    if ($profile['id'] == '464' || $profile['id'] == '42') {
        ?>
        <span style="font-size:16px">Mobitva2</span>
        <?php
    } else {
        if (isset($user['level']) && $user['level'] < 2) {
            ?>
            <script>showContent("/main.php?msg=" + decodeURI("Недоступно до 2 уровня"));</script>
            <?php
        }
        if (!$id == 0 && !isset($_GET['bonus'])) {
            // Получаем звание игрока
            $slavamin = $mc->query("SELECT * FROM `slava` WHERE `slava` <= '" . $profile['slava'] . "' && `lvl` <= '" . $profile['level'] . "' ORDER BY `slava` DESC LIMIT 1")->fetch_array(MYSQLI_ASSOC);
            $slavamax = $mc->query("SELECT * FROM `slava` WHERE `slava` > '" . $profile['slava'] . "' && `lvl` <= '" . $profile['level'] . "' ORDER BY `slava` ASC LIMIT 1")->fetch_array(MYSQLI_ASSOC);
            
            if (!isset($slavamax['slava'])) {
                $slavamax['slava'] = "100";
            }

            // Получаем репутацию
            $rep_num = $profile['rep_p'] - $profile['rep_m'];
            $rep_name = "Неизвестный";
            if ($reparr = $mc->query("SELECT * FROM `reputation` WHERE `rep` <= '" . $rep_num . "' ORDER BY `rep` DESC LIMIT 1")->fetch_array(MYSQLI_ASSOC)) {
                $rep_name = $reparr['name'];
            }

            // Получаем информацию о друзьях
            $myfriend = $mc->query("SELECT COUNT(*) FROM `friends` WHERE (`id_user`='" . $user['id'] . "' AND `id_user2` = '" . $profile['id'] . "') OR (`id_user` = '" . $profile['id'] . "' AND `id_user2` = '" . $user['id'] . "')")->fetch_array(MYSQLI_ASSOC);
            $myfriends = $mc->query("SELECT `red`,COUNT(0) FROM `friends` WHERE (`id_user` = '" . $user['id'] . "' AND `id_user2` = '" . $profile['id'] . "') OR (`id_user` = '" . $profile['id'] . "' AND `id_user2` = '" . $user['id'] . "')")->fetch_array(MYSQLI_ASSOC);

            // Определяем сторону
            $res = '';
            $resS = '';
            if ($user['side'] == 0 || $user['side'] == 1) {
                $res = 'Sh';
            } else if ($user['side'] == 2 || $user['side'] == 3) {
                $res = 'No';
            }
            if ($profile['side'] == 0 || $profile['side'] == 1) {
                $resS = 'Sh';
            } else if ($profile['side'] == 2 || $profile['side'] == 3) {
                $resS = 'No';
            }

            $star = "";
            if ($profile['access'] == 1) {
                $star = "<img class='rank-star' src='/img/icon/star.png' alt='*'>";
            } else if ($profile['access'] == 2) {
                $star = "<img class='rank-star' src='/img/icon/star2.png' alt='*'>";
            } else if ($profile['access'] > 2) {
                $star = "<img class='rank-star' src='/img/icon/star3.png' alt='*'>";
            }

            // Добавляем проверку параметра equipment
            if (isset($_GET['equipment'])) {
                // Определяем массивы стилей
                $colorStyle = array("black", "green", "blue", "red", "yellow");
                $textStyle = array("", "Урон", "Уворот", "Броня", "Элита");
                
                ?>
                <div class="equipment-view">
                    <div class="block-header">Снаряжение <?= $profile['name'] ?></div>
                    
                    <div class="equipment-list">
                        <?php
                        // Создаем массив всех возможных слотов в нужном порядке
                        $slots = [
                            1 => "Оружие",
                            2 => "Щит",
                            3 => "Шлем",
                            4 => "Перчатки",
                            5 => "Доспех",
                            6 => "Сапоги",
                            7 => "Амулет",
                            8 => "Кольцо",
                            9 => "Пояс"
                        ];

                        // Получаем надетые вещи
                        $equipped_items = $mc->query("SELECT userbag.*, shop.name, shop.id_punct, shop.stil 
                            FROM `userbag` 
                            LEFT JOIN `shop` ON userbag.id_shop = shop.id 
                            WHERE `id_user` = '$id' AND `dress` = '1' 
                            ORDER BY shop.id_punct ASC");

                        if ($equipped_items) {
                            $equipped_items = $equipped_items->fetch_all(MYSQLI_ASSOC);
                        } else {
                            $equipped_items = [];
                        }

                        // Создаем ассоциативный массив надетых предметов
                        $equipped = [];
                        foreach ($equipped_items as $item) {
                            $equipped[$item['id_punct']] = $item;
                        }

                        // Выводим все слоты
                        foreach ($slots as $slot_id => $slot_name) {
                            ?>
                            <div class="equipment-item">
                                <div class="item-slot"><?= $slot_name ?></div>
                                <div class="item-name">
                                    <?php if (isset($equipped[$slot_id])) { 
                                        $item = $equipped[$slot_id];
                                        if (isset($item['stil']) && $item['stil'] > 0 && $item['stil'] < 5) {
                                            $style_color = $colorStyle[$item['stil']];
                                            ?>
                                            <span style="color: <?= $style_color ?>; font-weight: bold;">
                                                <?= $item['name'] ?>
                                            </span>
                                        <?php } else { ?>
                                            <span class="item-normal"><?= $item['name'] ?></span>
                                        <?php }
                                    } else { ?>
                                        <span class="empty-slot">пусто</span>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    
                    <div class="back-to-profile">
                        <a class="action-btn" onclick="showContent('/profile.php?id=<?= $profile['id'] ?>')">
                            <i class="fa fa-arrow-left"></i> Вернуться к профилю
                        </a>
                    </div>
                </div>

                <style>
                    .equipment-view {
                        max-width: 600px;
                        margin: 20px auto;
                    }

                    .block-header {
                        color: #663300;
                        font-size: 18px;
                        text-align: center;
                        margin-bottom: 20px;
                        padding: 10px;
                    }

                    .equipment-list {
                        display: grid;
                        gap: 10px;
                        padding: 15px;
                    }

                    .equipment-item {
                        display: flex;
                        align-items: center;
                        padding: 8px 15px;
                        transition: transform 0.2s;
                    }

                    .equipment-item:hover {
                        transform: translateX(5px);
                    }

                    .item-slot {
                        width: 100px;
                        color: #663300;
                        font-size: 14px;
                        font-weight: 500;
                    }

                    .item-name {
                        display: flex;
                        align-items: center;
                        gap: 10px;
                        flex: 1;
                        color: #663300;
                    }

                    .empty-slot {
                        color: #666;
                        font-style: italic;
                    }

                    .item-normal { 
                        color: #663300; 
                    }

                    .back-to-profile {
                        text-align: center;
                        margin-top: 20px;
                    }

                    .action-btn {
                        display: inline-flex;
                        align-items: center;
                        gap: 8px;
                        padding: 8px 16px;
                        background: linear-gradient(to bottom, #ffd700, #ffa500);
                        border: 1px solid #663300;
                        border-radius: 6px;
                        color: #663300;
                        cursor: pointer;
                        transition: all 0.2s;
                        font-weight: bold;
                        text-decoration: none;
                    }

                    .action-btn:hover {
                        background: linear-gradient(to bottom, #ffd700, #ff8c00);
                        transform: translateY(-1px);
                    }

                    .action-btn:active {
                        transform: translateY(0);
                    }
                </style>
                <?php
                // Прерываем выполнение остального кода профиля
                require_once ('system/foot/foot.php');
                exit();
            }
            ?>
            
            <div class="profile-view">
                <!-- Имя и уровень -->
                <div class="profile-header">
                    <div class="player-info">
                        <?= $star ?>
                        <span class="player-name"><?= $profile['name'] ?></span>
                        <span class="player-level"><?= $profile['level'] ?></span>
                    </div>
                </div>

                <!-- Канвас с персонажем -->
                <center>
                    <div class="character-preview">
                        <canvas id="MiniCanvas"></canvas>
                    </div>
                </center>

                <!-- Кнопка просмотра снаряжения -->
                <?php if (isset($user['id']) && $user['id'] != $profile['id']) { ?>
                    <div class="view-equipment">
                        <a class="view-equipment-btn" onclick="showContent('/profile.php?id=<?= $profile['id'] ?>&equipment')">
                            <i class="fa fa-shield-alt"></i> Просмотреть снаряжение
                        </a>
                    </div>
                <?php } ?>

                <!-- Основная информация -->
                <div class="profile-stats block-container">
                    <div class="block-header">Информация</div>
                    <table class="stats-table">
                        <tr>
                            <td class="stat-label">Опыт:</td>
                            <td class="stat-value">
                                <?php
                                // Получаем информацию о опыте для следующего уровня
                                $current_level = $profile['level'];
                                $next_level_info = $mc->query("SELECT * FROM `exp` WHERE `lvl` = '" . ($current_level + 1) . "'")->fetch_array(MYSQLI_ASSOC);
                                
                                if ($next_level_info) {
                                    $next_level_exp = $next_level_info['exp'];
                                    $current_level_info = $mc->query("SELECT * FROM `exp` WHERE `lvl` = '" . $current_level . "'")->fetch_array(MYSQLI_ASSOC);
                                    $current_level_exp = $current_level_info ? $current_level_info['exp'] : 0;
                                    
                                    // Рассчитываем прогресс в процентах
                                    $exp_needed = $next_level_exp - $current_level_exp;
                                    $exp_progress = $profile['exp'] - $current_level_exp;
                                    $percent = ($exp_progress / $exp_needed) * 100;
                                    
                                    // Ограничиваем значение от 0 до 100
                                    $percent = max(0, min(100, $percent));
                                ?>
                                <div class="profile-progress-bar">
                                    <div class="profile-progress" style="width: <?= $percent ?>%"></div>
                                    <span class="profile-progress-text"><?= $exp_progress ?>/<?= $exp_needed ?></span>
                                </div>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="stat-label">Репутация:</td>
                            <td class="stat-value">
                                <div class="value-main"><?= $rep_name ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="stat-label">Звание:</td>
                            <td class="stat-value">
                                <div class="value-main"><?= $slavamin['name'] ?></div>
                            </td>
                        </tr>
                        <?php if (isset($clan['name'])) { ?>
                            <tr>
                                <td class="stat-label">Клан:</td>
                                <td class="stat-value">
                                    <a onclick="showContent('/clan/clan_all.php?see_clan=<?= $profile['id_clan'] ?>')"><?= $clan['name'] ?></a>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td class="stat-label">Победы (монстры):</td>
                            <td class="stat-value">
                                <div class="value-sub"><?= $profile['pobedmonser'] ?>/<?= $profile['losemonser'] ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="stat-label">Победы (игроки):</td>
                            <td class="stat-value">
                                <div class="value-sub"><?= $profile['pobedigroki'] ?></div>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Действия с профилем -->
                <?php if (isset($user['id']) && $user['id'] != $profile['id']) { ?>
                    <div class="profile-actions">
                        <a class="action-btn" onclick="showContent('/mail.php?id_2=<?= $profile['id'] ?>')">
                            <i class="fa fa-envelope"></i> сообщение
                        </a>

                        <?php if ($myfriends['COUNT(0)'] == 0) { ?>
                            <a class="action-btn" onclick="showContent('friends.php?addfriends=<?= $profile['id'] ?>')">
                                <i class="fa fa-user-plus"></i> добавить в друзья
                            </a>
                        <?php } else { ?>
                            <?php if ($myfriends['red'] == 0) { ?>
                                <a class="action-btn" onclick="showContent('/friends.php?dellfriends=<?= $profile['id'] ?>')">
                                    <i class="fa fa-user-times"></i> удалить из друзей
                                </a>
                            <?php } ?>
                        <?php } ?>

                        <a class="action-btn" onclick="showContent('/shop_gift.php?menu&id=<?= $profile['id'] ?>')">
                            <i class="fa fa-gift"></i> сделать подарок
                        </a>

                        <a class="action-btn" onclick="showContent('/history_name.php?id=<?= $profile['id'] ?>')">
                            <i class="fa fa-history"></i> история ников
                        </a>
                    </div>
                <?php } ?>

                <!-- Оставляем весь существующий код для клановых функций -->
                <?php 
                // Проверяем, что просматриваемый профиль в том же клане
                $same_clan = ($user['id_clan'] == $profile['id_clan'] && $user['id_clan'] != 0);
                
                if ($same_clan && $user['des'] > 1 && $user['id'] != $profile['id']) { ?>
                    <div class="clan-actions">
                        <?php if ($user['des'] > 1) { 
                            if ($profile['des'] < 1) { ?>
                                <a class="action-btn" onclick="showContent('/clan/chempadd.php?chempadd=<?= $profile['id'] ?>')">
                                    Назначить чемпионом
                                </a>
                            <?php } else if ($profile['des'] == 1) { ?>
                                <a class="action-btn" onclick="showContent('/clan/chempdel.php?chempdel=<?= $profile['id'] ?>')">
                                    Снять чемпиона
                                </a>
                            <?php }
                        } ?>

                        <?php if ($user['des'] > 2) {
                            if ($profile['des'] < 2) { ?>
                                <a class="action-btn" onclick="showContent('/clan/desadd.php?desadd=<?= $profile['id'] ?>')">
                                    Назначить десятником
                                </a>
                            <?php } else if ($profile['des'] == 2) { ?>
                                <a class="action-btn" onclick="showContent('/clan/desdel.php?desdel=<?= $profile['id'] ?>')">
                                    Снять десятника
                                </a>
                            <?php }
                        } ?>

                        <?php if ($user['des'] > 2) { ?>
                            <a class="action-btn" onclick="showContent('/clan/wignat.php?wignat=<?= $profile['id'] ?>')">
                                Выгнать из клана
                            </a>
                        <?php } ?>
                    </div>
                <?php } else if ($user['des'] > 1 && $profile['id_clan'] == 0 && $myfriend['COUNT(*)'] == 1 && $res == $resS) { ?>
                    <a class="action-btn" onclick="showContent('/clan/priglas.php?priglas=<?= $profile['id'] ?>')">
                        Пригласить в клан
                    </a>
                <?php } ?>
            </div>

            <style>
                .profile-view {
                    max-width: 600px;
                    margin: 0 auto;
                    background: transparent;
                    border-radius: 8px;
                    padding: 15px;
                }

                .profile-header {
                    text-align: center;
                    margin-bottom: 15px;
                    color: #ffd700;
                    font-size: 20px;
                    padding: 10px;
                }

                .rank-star {
                    width: 20px;
                    height: 20px;
                    vertical-align: middle;
                    margin-right: 5px;
                }

                .player-level {
                    color: #000;  /* Меняем с #aaa на #000 */
                    margin-left: 10px;
                    padding-left: 10px;
                    border-left: 2px solid rgba(0,0,0,0.2);
                    text-shadow: 0 1px 1px rgba(255,255,255,0.5);
                }

                .character-preview {
                    margin: 20px 0;
                    background: transparent;
                    border-radius: 8px;
                    padding: 10px;
                }

                .profile-stats {
                    background: rgba(0,0,0,0.1);
                    border-radius: 8px;
                    padding: 10px;
                    margin: 15px 0;
                }

                .profile-actions, .clan-actions {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
                    gap: 10px;
                    margin-top: 15px;
                }

                .action-btn {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                    padding: 8px 12px;
                    background: rgba(255,215,0,0.2);
                    border-radius: 4px;
                    color: #fff;
                    text-decoration: none;
                    cursor: pointer;
                    transition: background 0.2s;
                }

                .action-btn:hover {
                    background: rgba(255,215,0,0.3);
                }

                .action-btn img {
                    width: 16px;
                    height: 16px;
                }
            </style>

            <!-- Добавляем Font Awesome для иконок -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

            <?php if ($user['access'] > 2) { ?>
                <div class="admin-panel">
                    <div class="admin-header">Панель администратора</div>
                    
                    <div class="admin-actions">
                        <a class="admin-btn" onclick="showContent('/admin/admin.php?id=<?= $profile['id'] ?>')">
                            Изменить персонажа
                        </a>
                        
                        <?php if ($profile['game_ban'] == 0) { ?>
                            <a class="admin-btn" onclick="showContent('/admin/game_ban.php?ban&id=<?= $profile['id'] ?>')">
                                Заблокировать
                            </a>
                        <?php } else { ?>
                            <a class="admin-btn" onclick="showContent('/admin/game_ban.php?upban&id=<?= $profile['id'] ?>')">
                                Разблокировать
                            </a>
                        <?php } ?>
                    </div>

                    <!-- Список вещей персонажа -->
                    <div class="admin-inventory">
                        <div class="inventory-header">Инвентарь персонажа</div>
                        <?php
                        $persequip3 = $mc->query("SELECT * FROM `userbag` WHERE `id_user`='" . $profile['id'] . "' ORDER BY `userbag`.`id_punct` ASC, `id` ASC");
                        while ($persequip2 = $persequip3->fetch_array(MYSQLI_ASSOC)) {
                            $namedress2 = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $persequip2['id_shop'] . "'")->fetch_array(MYSQLI_ASSOC);
                        ?>
                            <div class="inventory-item">
                                <?php if ($persequip2['dress'] == "1") { ?>
                                    <b><?= $namedress2["name"] ?></b>
                                    <a class="item-action" onclick="showContent('/profile/<?= $profile['id'] ?>/1?weshEdit=h&ids=<?= $persequip2["id"] ?>')">Снять</a>
                                <?php } else if ($persequip2['dress'] == "0") { ?>
                                    <?= $namedress2["name"] ?>
                                    <a class="item-action" onclick="showContent('/profile/<?= $profile['id'] ?>/1?weshEdit=s&ids=<?= $persequip2["id"] ?>')">Одеть</a>
                                <?php } ?>
                                <a class="item-action delete" onclick="showContent('/profile/<?= $profile['id'] ?>/1?weshEdit=d&ids=<?= $persequip2["id"] ?>')">Удалить</a>
                            </div>
                        <?php } ?>
                    </div>

                    <!-- Форма добавления предметов -->
                    <div class="admin-add-item">
                        <div class="add-item-header">Добавить предмет</div>
                        <div class="add-item-form">
                            <input class="id_dress" type="number" value="0" placeholder="ID предмета">
                            <input class="name_dress" type="text" onkeyup="searchdress(this.value)" placeholder="Название предмета">
                            <button onclick="add();" class="admin-btn">Добавить</button>
                        </div>
                        <div class="search"></div>
                    </div>
                </div>

                <style>
                    /* Добавляем стили для админ-панели */
                    .admin-panel {
                        max-width: 600px;
                        margin: 20px auto;
                        background: transparent;
                        border-radius: 8px;
                        padding: 15px;
                    }

                    .admin-header {
                        color: #ff6b6b;
                        font-size: 18px;
                        padding: 10px;
                        text-align: center;
                        border-bottom: 1px solid rgba(255,255,255,0.1);
                        margin-bottom: 15px;
                    }

                    .admin-actions {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
                        gap: 10px;
                        margin-bottom: 20px;
                    }

                    .admin-btn {
                        background: rgba(255,107,107,0.2);
                        color: #fff;
                        padding: 8px 12px;
                        border-radius: 4px;
                        cursor: pointer;
                        text-align: center;
                        transition: background 0.2s;
                    }

                    .admin-btn:hover {
                        background: rgba(255,107,107,0.3);
                    }

                    .inventory-header, .add-item-header {
                        color: #ffd700;
                        font-size: 16px;
                        margin: 15px 0 10px;
                        padding-bottom: 5px;
                        border-bottom: 1px solid rgba(255,215,0,0.3);
                    }

                    .inventory-item {
                        display: flex;
                        align-items: center;
                        gap: 10px;
                        padding: 8px;
                        border-bottom: 1px solid rgba(255,255,255,0.1);
                    }

                    .item-action {
                        color: #ffd700;
                        cursor: pointer;
                        font-size: 12px;
                        padding: 2px 6px;
                        border-radius: 3px;
                        background: rgba(255,215,0,0.1);
                    }

                    .item-action.delete {
                        color: #ff6b6b;
                        background: rgba(255,107,107,0.1);
                    }

                    .item-action:hover {
                        background: rgba(255,215,0,0.2);
                    }

                    .item-action.delete:hover {
                        background: rgba(255,107,107,0.2);
                    }

                    .add-item-form {
                        display: grid;
                        grid-template-columns: 80px 1fr auto;
                        gap: 10px;
                        margin-bottom: 15px;
                    }

                    .add-item-form input {
                        background: rgba(0,0,0,0.2);
                        border: 1px solid rgba(255,215,0,0.3);
                        color: #fff;
                        padding: 8px;
                        border-radius: 4px;
                    }

                    .search {
                        margin-top: 10px;
                    }
                </style>

                <script>
                    function add() {
                        showContent('/profile/<?= $profile['id'] ?>/1?weshEdit=a&ids=' + $(".id_dress").val());
                    }
                    
                    function add2(a) {
                        showContent('/profile/<?= $profile['id'] ?>/1?weshEdit=a&ids=' + a);
                    }

                    function searchdress(etext) {
                        var arr;
                        $.ajax({
                            type: "POST",
                            url: "/admin/shop/search.php?etext=" + etext,
                            dataType: "text",
                            success: function (data) {
                                $(".search").html("");
                                if (data != "") {
                                    arr = JSON.parse(data);
                                    for (var i = 0; i < arr.length; i++) {
                                        addDressSearched(arr[i].name, arr[i].level, arr[i].id);
                                    }
                                }
                            },
                            error: function () {
                                $(".search").html("error");
                            }
                        });
                    }

                    function addDressSearched(name, level, id) {
                        $(".search").append(
                            '<div class="search-result">' +
                            '<span>' + name + ' [' + level + '] id: ' + id + '</span>' +
                            '<button onclick="add2(' + id + ');" class="admin-btn">Добавить</button>' +
                            '</div>'
                        );
                    }
                </script>
            <?php } ?>
        <?php
        }
    }
}
if ($id == 0) {
    require_once ('HeroClass.php');
//объект класса воевода
    $class = new HeroClass;
    ?>
    <script>
        var MaxCanvas = $("mobitva:eq(-1)").find("#MaxCanvas")[0];
        var ctxMaxCanvas = MaxCanvas.getContext("2d");
        var buffMaxCanvas = document.createElement("canvas");
        var ctxbuffMaxCanvas = buffMaxCanvas.getContext("2d");
        var requestAnimationFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
        var cancelAnimationFrame = window.cancelAnimationFrame || window.mozCancelAnimationFrame;
        var myReq;
        var weaponData = [];
        var imageweapon;
        var spriteData = [];
        var spriteImage = [];
        var PshieldNC = 0;
        var Pshield = 0;
        var Panimation = 0;
        var Pweapon = 0;
        var Panimationcount = 0;
        var Pico = <?= $class->img; ?>;
        var PposX = -170;
        var PposY = -130;
        MaxCanvas.width = buffMaxCanvas.width = 280;
        MaxCanvas.height = buffMaxCanvas.height = 150;
        if (Pico === 9) {
            PposX = -170;
            PposY = -75;
            MaxCanvas.width = buffMaxCanvas.width = 280;
            MaxCanvas.height = buffMaxCanvas.height = 200;
        }
        $.ajax({
            url: "./json/weapon/weapon_new.json?139.1114",
            dataType: "json",
            success: function (a) {
                weaponData = JSON.parse(JSON.stringify(a));
                imageweapon = new Image;
                imageweapon.src = weaponData.img;
            }
        });
        $.ajax({
            url: "./json/Mob/animation.json?136.3233",
            dataType: "json",
            success: function (a) {
                spriteData = JSON.parse(JSON.stringify(a));
                var newJson = {};
                for (a = 1; a <= spriteData.AnimCount; a++) {
                    newJson[a] = spriteData[spriteData.keyToAnim[a]];
                }
                newJson.img = spriteData.img;
                spriteData = newJson;
                for (a = 1; a < spriteData.img.length + 1; a++)
                    spriteImage[a] = new Image, spriteImage[a].src = spriteData.img[a - 1];
            }
        });
        function render() {
            MaxCanvas.width = MaxCanvas.width;
            try {
                //ctxMaxCanvas.fillStyle = "#ff0000";
                //ctxMaxCanvas.fillRect(0,0,MaxCanvas.width,MaxCanvas.height);
                ctxMaxCanvas.drawImage(buffMaxCanvas,
                        0,
                        0,
                        MaxCanvas.width,
                        MaxCanvas.height,
                        0,
                        0,
                        buffMaxCanvas.width,
                        buffMaxCanvas.height
                        );
            } catch (e) {
            }
            MyLib.setTimeid[100] = setTimeout(function () {
                myReq = requestAnimationFrame(render);
            }, 1000 / 10);
        }
        myReq = requestAnimationFrame(render);
        MyLib.intervaltimer[1] = setInterval(function () {
            buffMaxCanvas.width = buffMaxCanvas.width;
            try {
                if (Panimationcount >= spriteData[Pico][Panimation].length) {
                    Panimationcount = 0;
                }
                for (var a = 0; a < spriteData[Pico][Panimation][Panimationcount].length; a++) {
                    if (spriteData[Pico][Panimation][Panimationcount][a][9] !== -1) {
                        ctxbuffMaxCanvas.save();
                        ctxbuffMaxCanvas.translate(Math.round(buffMaxCanvas.width / 2 + spriteData[Pico][Panimation][Panimationcount][a][4] + spriteData[Pico][Panimation][Panimationcount][a][6] / 2) + PposX, Math.round(spriteData[Pico][Panimation][Panimationcount][a][5] + spriteData[Pico][Panimation][Panimationcount][a][7] / 2) + PposY);
                        ctxbuffMaxCanvas.rotate(spriteData[Pico][Panimation][Panimationcount][a][8] * Math.PI / 180);
                        ctxbuffMaxCanvas.drawImage(spriteImage[Pico], spriteData[Pico][Panimation][Panimationcount][a][0], spriteData[Pico][Panimation][Panimationcount][a][1], spriteData[Pico][Panimation][Panimationcount][a][2], spriteData[Pico][Panimation][Panimationcount][a][3], Math.round(-spriteData[Pico][Panimation][Panimationcount][a][6] / 2), Math.round(-spriteData[Pico][Panimation][Panimationcount][a][7] / 2), spriteData[Pico][Panimation][Panimationcount][a][6], spriteData[Pico][Panimation][Panimationcount][a][7]);
                        ctxbuffMaxCanvas.restore();
                    } else {
                        ctxbuffMaxCanvas.save();
                        ctxbuffMaxCanvas.translate(Math.round(buffMaxCanvas.width / 2 + spriteData[Pico][Panimation][Panimationcount][a][4] + spriteData[Pico][Panimation][Panimationcount][a][6] / 2) + PposX, Math.round(spriteData[Pico][Panimation][Panimationcount][a][5] + spriteData[Pico][Panimation][Panimationcount][a][7] / 2) + PposY);
                        ctxbuffMaxCanvas.rotate(spriteData[Pico][Panimation][Panimationcount][a][8] * Math.PI / 180);
                        ctxbuffMaxCanvas.drawImage(imageweapon, weaponData.imgC[Pweapon][0], weaponData.imgC[Pweapon][1], weaponData.imgC[Pweapon][2], weaponData.imgC[Pweapon][3], Math.round(-weaponData.imgC[Pweapon][2] / 2), Math.round(-weaponData.imgC[Pweapon][3] / 2), weaponData.imgC[Pweapon][2], weaponData.imgC[Pweapon][3]);
                        ctxbuffMaxCanvas.restore();
                    }
                }
            } catch (e) {

            }

            Panimationcount++;
        }, 200);</script>
    <center>
        <canvas id='MaxCanvas'></canvas>
        <br>
        <?= $profile['name']; ?>
    </center>
    <hr>
    <?php
    echo $class->text;
    //айди монстра воевода
    $amob = 98;
    //переменная
    $mobidarr = [];
    //создаем список
    $mobidarr[] = "" . $amob;
    //запишем список в бд
    $mc->query("UPDATE `users` SET `huntList` = '" . json_encode($mobidarr) . "' WHERE `users`.`id` = '" . $user['id'] . "'");
    echo "<br><br>Команды для чата:<br>Воевода<br>Воевода,привет<br>Воевода,как дела?";
    echo "<hr>";
    //HuntMobBattleOne(0);
    ?>
    <a onclick="HuntMobBattleOne(0);">Атаковать</a>
    <?php
} if (isset($_GET['bonus'])) {
    $footval = "bonus";
    ?>
    <center>-Бонусы-</center>
    <table style="margin: auto;border-spacing: 0;width: 100%;">
        <tr>
            <td class="ptb_1l"></td>
            <td class="ptb_1c"></td>
            <td class="ptb_1r"></td>
        </tr>
        <tr>
            <td class="ptb_2l"></td>
            <td class="ptb_2c">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 70%">Здоровье:</td>
                        <td style="width: 6%;text-align:right;">
                            <img src='/images/icons/hp.png' width='18'>
                        <td style="width: 12%;text-align:right;"><?= $arr['health']; ?></td>
                        <td style="width: 12%;text-align:right;"><?= $arr['health']; ?></td>
                    </tr>
                    <tr>
                        <td>Урон:</td>
                        <td style="text-align:right; ">
                            <img src='/images/icons/power.jpg' width='18' >
                        </td>
                        <td style="text-align:right;"><?= $arr['strength']; ?></td>
                        <td style="text-align:right;"><?= $arr['strength']; ?></td>
                    </tr>
                    <tr>
                        <td>Точность:</td>
                        <td style="text-align:right; ">
                            <img src='/images/icons/toch.png' width='18' >
                        </td>
                        <td style="text-align:right;"><?= $arr['toch']; ?></td>
                        <td style="text-align:right;"><?= $arr['toch']; ?></td>
                    </tr>
                    <tr>
                        <td>Броня:</td>
                        <td style="text-align:right; ">
                            <img src='/images/icons/bron.png' width='18' >
                        </td>
                        <td style="text-align:right;"><?= $arr['bron']; ?></td>
                        <td style="width: 12%;text-align:right;"><?= $arr['bron']; ?></td>
                    </tr>
                    <tr>
                        <td>Уворот:</td>
                        <td style="text-align:right; ">
                            <img src='/images/icons/img235.png' width='18'>
                        </td>
                        <td style="text-align:right;"><?= $arr['lov']; ?></td>
                        <td style="text-align:right;"><?= $arr['lov']; ?></td>
                    </tr>
                    <tr>
                        <td>Оглушение:</td>
                        <td style="text-align:right; ">
                            <img src='/images/icons/kd.png' width='18' >
                        </td>
                        <td style="text-align:right;"><?= $arr['kd']; ?></td>
                        <td style="text-align:right;"><?= $arr['kd']; ?></td>
                    </tr>
                    <tr>
                        <td>Блок:</td>
                        <td style="text-align:right; ">
                            <img src='/images/icons/shit.png' width='18' >
                        </td>
                        <td style="text-align:right;"><?= $arr['block']; ?></td>
                        <td style="text-align:right;"><?= $arr['block']; ?></td>
                    </tr>

                </table>
            </td>
            <td class="ptb_2r"></td>
        </tr>
        <tr><td class="ptb_3l"></td>
            <td class="ptb_3c"></td>
            <td class="ptb_3r"></td>
        </tr>

    </table><?php
}

// Добавляем ссылку на реферальную программу, если не просматриваем бонусы
if (!isset($_GET['bonus'])) {
?>
<div class="referral-link-container">
    <a class="referral-link" onclick="showContent('/ref.php')">
        <div class="referral-link-inner">
            <span class="referral-icon">🔗</span>
            <span class="referral-text">Реферальная программа</span>
        </div>
    </a>
</div>

<style>
    .referral-link-container {
        max-width: 600px;
        margin: 20px auto;
        text-align: center;
    }
    
    .referral-link {
        text-decoration: none;
        cursor: pointer;
    }
    
    .referral-link-inner {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 20px;
        background: linear-gradient(to bottom, #ffd700, #ffa500);
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
        color: #663300;
        font-weight: 600;
    }
    
    .referral-link-inner:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
    }
    
    .referral-icon {
        font-size: 20px;
    }
    
    .referral-text {
        font-size: 16px;
    }
</style>
<?php
}

require_once ('system/foot/foot.php');
?>
