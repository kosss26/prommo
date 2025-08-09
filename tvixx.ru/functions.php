<?php
function nextCountQuests($quests_counts) {
    global $mc, $user;
    if (empty($quests_counts)) return;

    foreach ($quests_counts as $quest_count) {
        $id_quests = $quest_count['id_quests'];
        $count = $quest_count['count'];

        $stmt = $mc->prepare("SELECT `part_num` FROM `quests` WHERE `id` = ?");
        $stmt->bind_param("i", $id_quests);
        $stmt->execute();
        $base_Quest = $stmt->get_result()->fetch_array(MYSQLI_ASSOC);

        $nextStmt = $mc->prepare("SELECT COUNT(*) as cnt FROM `quests_count` WHERE `id_quests` = ? AND `count` = ?");
        $nextCount = $count + 1;
        $nextStmt->bind_param("ii", $id_quests, $nextCount);
        $nextStmt->execute();
        $next_part_exists = $nextStmt->get_result()->fetch_array(MYSQLI_ASSOC);

        if ($next_part_exists['cnt'] == 0 && $base_Quest['part_num'] <= $count) {
            $updateStmt = $mc->prepare("UPDATE `quests_users` SET `variant` = '4' WHERE `id_user` = ? AND `id_quests` = ?");
            $updateStmt->bind_param("ii", $user['id'], $id_quests);
            $updateStmt->execute();
        }
    }
}

function chekDostypeQuest($quest) {
    global $mc, $user;

    if (empty($quest['if_quest']) || $quest['if_quest'] == 0) return true;

    $stmt = $mc->prepare("SELECT COUNT(*) as cnt FROM `quests_users` WHERE `id_user` = ? AND `id_quests` = ?");
    $stmt->bind_param("ii", $user['id'], $quest['if_quest']);
    $stmt->execute();
    if ($stmt->get_result()->fetch_array(MYSQLI_ASSOC)['cnt'] == 0) return false;

    if ($quest['if_quest_part'] == 0) {
        $stmt = $mc->prepare("SELECT COUNT(*) as cnt FROM `quests_users` WHERE `id_user` = ? AND `id_quests` = ? AND `variant` = '4'");
        $stmt->bind_param("ii", $user['id'], $quest['if_quest']);
        $stmt->execute();
        return $stmt->get_result()->fetch_array(MYSQLI_ASSOC)['cnt'] > 0;
    } else {
        $stmt = $mc->prepare("SELECT COUNT(*) as cnt FROM `quests_users` WHERE `id_user` = ? AND `id_quests` = ? AND `count` >= ?");
        $stmt->bind_param("iii", $user['id'], $quest['if_quest'], $quest['if_quest_part']);
        $stmt->execute();
        return $stmt->get_result()->fetch_array(MYSQLI_ASSOC)['cnt'] > 0;
    }
}