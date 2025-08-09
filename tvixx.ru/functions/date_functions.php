<?php

/**
 * Форматирует дату следующего захвата земли в понятный текст
 * 
 * @param int $nextZahvatTimestamp Временная метка следующего захвата
 * @return string Текст "сегодня", "завтра", "через 2 дня" или "неизвестно"
 */
function formatNextBattleDate($nextZahvatTimestamp) {
    // Если дата не установлена
    if (empty($nextZahvatTimestamp)) {
        return "неизвестно";
    }
    
    // Определяем метки времени для сравнения
    $shestp = mktime(17, 50, 0, date("m"), date("d"), date("Y")); // 17:50 сегодня
    $vosemp = mktime(19, 50, 0, date("m"), date("d"), date("Y")); // 19:50 сегодня
    $tomorrow = mktime(17, 50, 0, date("m"), date("d")+1, date("Y")); // 17:50 завтра
    $dayAfterTomorrow = mktime(17, 50, 0, date("m"), date("d")+2, date("Y")); // 17:50 послезавтра
    
    // Определяем, когда будет следующий бой
    if ($nextZahvatTimestamp == $shestp || $nextZahvatTimestamp == $vosemp) {
        return "сегодня";
    } else if ($nextZahvatTimestamp == $tomorrow) {
        return "завтра";
    } else if ($nextZahvatTimestamp == $dayAfterTomorrow) {
        return "через 2 дня";
    } else {
        // Если не подходит ни один из вариантов, возвращаем дату в формате DD.MM.YYYY
        return date("d.m.Y", $nextZahvatTimestamp);
    }
} 