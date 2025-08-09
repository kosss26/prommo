<?php
require_once 'system/func.php';
require_once 'system/dbc.php';
require_once 'system/header.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/bablo.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/wesh_snyat.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/wesh_odet.php';
auth(); // Закроем от неавторизированых

// Подключаем стили и скрипты для модуля экипировки
echo '<link rel="stylesheet" href="/css/equipment.css?v='.filemtime($_SERVER['DOCUMENT_ROOT'].'/css/equipment.css').'">';
echo '<script src="/js/equipment.js?v='.filemtime($_SERVER['DOCUMENT_ROOT'].'/js/equipment.js').'"></script>';

// Определение стилей снаряжения
$colorStyle = array("black", "green", "blue", "red", "yellow");
$textStyle = array("", "Урон", "Уворот", "Броня", "Элита");

// Обработка запросов одевания/снятия снаряжения
if (isset($_GET['dress'])) {
    // Проверка на участие в турнирах
    if ($mc->query("SELECT * FROM `huntb_list` WHERE `user_id`='" . $user['id'] . "'")->num_rows > 0) {
        $mc->query("INSERT INTO `msg` (`id_user`,`message`,`date`,`type`) VALUES ('" . $user['id'] . "','Вам нужно отказаться от дуэлей для продолжения !','" . time() . "','msg')");
        ?><script>/*nextshowcontemt*/showContent("/huntb/index.php");</script><?php
        exit(0);
    }
    
    $infoshop1 = $mc->query("SELECT * FROM `userbag` WHERE `id`='" . $_GET['ids'] . "'")->fetch_array(MYSQLI_ASSOC);
    if ($infoshop1['id_punct'] != "10" && $_GET['ids']) {
        if ($_GET['dress'] == '1') {
            shop_snyat($_GET['ids']);
        }

        if ($_GET['dress'] == '2') {
            wesh_odet($_GET['ids']);
        }
    }
}

// Вспомогательная функция для форматирования времени
function age_times($secs) {
    $bit = array(
        ' year' => floor($secs / 31556926),
        ' day' => $secs / 86400 % 365,
        ' hour' => $secs / 3600 % 24,
        ' minute' => $secs / 60 % 60,
        ' second' => $secs % 60
    );
    
    $years = $days = $hours = 0;
    $ret = array();
    
    foreach ($bit as $k => $v) {
        if ($v <= 0) continue;
        
        $str = (string) $v;
        $str = strlen($str) == 1 ? "0" . $str : $str;
        $lastDigit = (int) $str[strlen($str) - 1];
        
        // Форматирование для годов
        if ($k == ' year') {
            $years = $v;
            if ($lastDigit > 4 || $lastDigit == 0 || ((int) $str[strlen($str) - 2] > 0 && (int) $str[strlen($str) - 2] < 2)) {
                $ret[] = $v . ' лет ';
            } elseif ($lastDigit > 1 && $lastDigit < 5) {
                $ret[] = $v . ' года ';
            } else {
                $ret[] = $v . ' год ';
            }
        }
        
        // Форматирование для дней
        if ($k == ' day') {
            $days = $v;
            if ($lastDigit > 4 || $lastDigit == 0 || ((int) $str[strlen($str) - 2] > 0 && (int) $str[strlen($str) - 2] < 2)) {
                $ret[] = $v . ' дней ';
            } elseif ($lastDigit > 1 && $lastDigit < 5) {
                $ret[] = $v . ' дня ';
            } else {
                $ret[] = $v . ' день ';
            }
        }
        
        // Форматирование для часов
        if ($k == ' hour' && $years == 0) {
            $hours = $v;
            if ($v > 4 && $v < 21) {
                $ret[] = $v . ' часов ';
            } elseif (($v > 1 && $v < 5) || $v > 21) {
                $ret[] = $v . ' часа ';
            } else {
                $ret[] = $v . ' час ';
            }
        }
        
        // Минуты и секунды
        if ($k == ' minute' && $years == 0 && $days == 0) {
            $ret[] = $v . ' мин ';
        }
        
        if ($k == ' second' && $years == 0 && $days == 0 && $hours == 0) {
            $ret[] = $v . ' сек ';
        }
    }
    
    return join(' ', $ret);
}

// Обработка информации о дропе предметов
$item_dropped = isset($item_dropped) ? $item_dropped : false;

if ($item_dropped) {
    // Проверяем активные квесты
    $active_quests = $mc->query("
        SELECT qp.* 
        FROM quest_progress qp 
        WHERE qp.id_user = '{$user['id']}' 
        AND qp.status = 'ACTIVE'
    ");

    if ($active_quests && $active_quests->num_rows > 0) {
        while ($quest = $active_quests->fetch_assoc()) {
            $progress_data = json_decode($quest['progress_data'], true);
            
            // Если есть требования по дропу с монстров
            if (!empty($progress_data['mob_drops'][$monster_id][$item_id])) {
                $progress_data['mob_drops'][$monster_id][$item_id]['current']++;
                
                // Обновляем прогресс
                $mc->query("UPDATE quest_progress SET 
                    progress_data = '" . json_encode($progress_data) . "'
                    WHERE id = '{$quest['id']}'
                ");
            }
        }
    }
}

////Всё снаряжение - Главная страница экипировки
if (!isset($_GET['id']) && !isset($_GET['equip'])) {
    include_once('modules/equipment/main_view.php');
    $footval = "equip";
    require_once('system/foot/foot.php');
}

//Все оружия в этой категории
if (!isset($_GET['id']) && isset($_GET['equip']) && $_GET['equip'] > 0) {
    //если ломится в раздел где бонусные шмотки то в снаряжение отправить
    if ($_GET['equip'] > 10 && $user['access'] < 4) {
        ?><script>showContent("/equip.php");</script><?php
        exit(0);
    }
    
    include_once('modules/equipment/category_view.php');
    $footval = "equip1";
    require_once('system/foot/foot.php');
}

//Конкретная одежда - детальный просмотр предмета
if (isset($_GET['id']) && isset($_GET['equip'])) {
    //если ломится в раздел где бонусные шмотки то в снаряжение отправить
    if ($_GET['equip'] == 11 && $user['access'] < 4) {
        ?><script>showContent("/equip.php");</script><?php
        exit(0);
    }
    
    include_once('modules/equipment/item_view.php');
    $footval = "equip2";
    require_once('system/foot/foot.php');
}
?>