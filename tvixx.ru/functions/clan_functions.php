function check_and_delete_empty_clan($clan_id) {
    global $mc;
    
    // Проверяем количество участников в клане
    $members_count = $mc->query("SELECT COUNT(*) as count FROM `users` WHERE `id_clan` = '$clan_id'")->fetch_assoc()['count'];
    
    // Если участников нет - удаляем клан
    if ($members_count == 0) {
        // Удаляем клан из таблицы кланов
        $mc->query("DELETE FROM `clan` WHERE `id` = '$clan_id'");
        
        // Удаляем связанные данные
        $mc->query("DELETE FROM `clan_war` WHERE `clan1` = '$clan_id' OR `clan2` = '$clan_id'");
        $mc->query("DELETE FROM `clan_msg` WHERE `id_clan` = '$clan_id'");
        $mc->query("DELETE FROM `clan_bank` WHERE `id_clan` = '$clan_id'");
        
        return true;
    }
    
    return false;
} 