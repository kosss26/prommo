<?php

require_once '../../system/func.php';
require_once '../../system/dbc.php';
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// --- ДОБАВИТЬ: функция безопасного получения значения ---
function v($arr, $key, $def = '') {
    return isset($arr[$key]) ? $arr[$key] : $def;
}

// --- ДОБАВИТЬ: функция экранирования ---
function esc($str) {
    global $mc;
    if (is_array($str) || is_object($str)) {
        $str = json_encode($str, JSON_UNESCAPED_UNICODE);
    }
    return $mc->real_escape_string($str);
}

if (isset($_POST['strdata']) && isset($user) && isset($user['access']) && $user['access'] > 1) {
    $data = json_decode($_POST['strdata'], true);
    if ($data === null) {
        echo json_encode([
            "otvet" => 0,
            "new_id" => "",
            "error" => "Невалидный JSON в strdata"
        ]);
        exit;
    }

    if (v($data, 'id') === '') {
        $mc->query("INSERT INTO `quests` ("
                . "`id`,"
                . " `level_min`,"
                . " `level_max`,"
                . " `locId`,"
                . " `name`,"
                . " `pred_quest`,"
                . " `quest_not`,"
                . " `predmet`,"
                . " `predmet_none`,"
                . " `time_r`,"
                . " `part_num`,"
                . " `health`,"
                . " `strength`,"
                . " `toch`,"
                . " `bron`,"
                . " `lov`,"
                . " `kd`,"
                . " `block`,"
                . " `level`,"
                . " `exp`,"
                . " `slava`,"
                . " `zvanie`,"
                . " `vinos_t`,"
                . " `vinos_m`,"
                . " `tur_reit`,"
                . " `rep_p`,"
                . " `rep_m`,"
                . " `platinum`,"
                . " `med`,"
                . " `pobedmonser`,"
                . " `pobedigroki`,"
                . " `auto_start`,"
                . " `rasa`,"
                . " `comment`"
                . ") VALUES ("
                . "NULL,"
                . " '" . esc(v($data, 'level_min')) . "',"
                . " '" . esc(v($data, 'level_max')) . "',"
                . " '" . esc(v($data, 'locId')) . "',"
                . " '" . esc(v($data, 'name')) . "',"
                . " '" . esc(v($data, 'pred_quest')) . "',"
                . " '" . esc(v($data, 'quest_not')) . "',"
                . " '" . esc(v($data, 'predmet')) . "',"
                . " '" . esc(v($data, 'predmet_none')) . "',"
                . " '" . esc(v($data, 'time_r')) . "',"
                . " '" . count(v($data, 'elements', [])) . "',"
                . " '" . esc(v($data, 'health')) . "',"
                . " '" . esc(v($data, 'strength')) . "',"
                . " '" . esc(v($data, 'toch')) . "',"
                . " '" . esc(v($data, 'bron')) . "',"
                . " '" . esc(v($data, 'lov')) . "',"
                . " '" . esc(v($data, 'kd')) . "',"
                . " '" . esc(v($data, 'block')) . "',"
                . " '" . esc(v($data, 'level')) . "',"
                . " '" . esc(v($data, 'exp')) . "',"
                . " '" . esc(v($data, 'slava')) . "',"
                . " '" . esc(v($data, 'zvanie')) . "',"
                . " '" . esc(v($data, 'vinos_t')) . "',"
                . " '" . esc(v($data, 'vinos_m')) . "',"
                . " '" . esc(v($data, 'tur_reit')) . "',"
                . " '" . esc(v($data, 'rep_p')) . "',"
                . " '" . esc(v($data, 'rep_m')) . "',"
                . " '" . esc(v($data, 'platinum')) . "',"
                . " '" . esc(v($data, 'med')) . "',"
                . " '" . esc(v($data, 'pobedmonser')) . "',"
                . " '" . esc(v($data, 'pobedigroki')) . "',"
                . " '" . esc(v($data, 'auto_start')) . "',"
                . " '" . esc(v($data, 'rasa')) . "',"
                . " '" . esc(v($data, 'comment')) . "'"
                . ");");
        $id_quest = $mc->insert_id;
        echo json_encode(array(
            "otvet" => 1,
            "new_id" => $id_quest
        ));
        $chatmsg = addslashes("<a onclick=\"showContent('/profile.php?id=" . $user['id'] . "')\"><font color='#0033cc'>" . $user['name'] . "</font></a><font color='#0033cc'> создал квест </font><a onclick=\"showContent('/admin/quest/quest.php?id=" . $mc->insert_id . "')\"><font color='#0033cc'>" . urldecode(esc(v($data, 'name'))) . "</font></a><font color='#0033cc'> !</font>");
        $mc->query("INSERT INTO `chat`(`id`,`name`,`id_user`,`chat_room`,`msg`,`msg2`,`time`, `unix_time`) VALUES (NULL,'АДМИНИСТРИРОВАНИЕ','','5', '" . $chatmsg . "','','','' )");

        for ($i = 0; $i < count(v($data, 'elements', [])); $i++) {
            $mc->query("INSERT INTO `quests_count` "
                    . "(`id_quests`, `count`, `auto_start_c`, `img_id`, `type_c`, `msg_text`, `time_ce`, `type_if`, `autobattle`, `gotolocid`, `new_quest`, "
                    . "`mob_battle`,"
                    . "`banda_battle`,"
                    . "`banda_battle_location`,"
                    . " `delpv`, `delpexp`, `delpslava`, `delpvinos_t`, `delpvinos_m`, `delpplatinum`, `delpmed`, `delppobedmonser`, `delppobedigroki`, `addpv`, `addpexp`, `addpslava`, `addpvinos_t`, `addpvinos_m`, `addpplatinum`, `addpmed`, `addppobedmonser`, `addppobedigroki`,  `mob_idandvesh`, `herowin_c`, `drop_vesh`,`buy_vesh`, `proval_img_id`, `proval_type_c`, `proval_msg_text`, `proval_type_if`, `proval_new_quest`, `proval_delpv`, `proval_delpexp`, `proval_delpslava`, `proval_delpvinos_t`, `proval_delpvinos_m`,`proval_delpplatinum`, `proval_delpmed`, `proval_delppobedmonser`, `proval_delppobedigroki`,  `proval_addpv`,`proval_addpexp`, `proval_addpslava`, `proval_addpvinos_t`, `proval_addpvinos_m`,`proval_addpplatinum`, `proval_addpmed`, `proval_addppobedmonser`, `proval_addppobedigroki`,  `otkaz_img_id`, `otkaz_type_c`, `otkaz_msg_text`, `otkaz_type_if`, `otkaz_new_quest`, `otkaz_delpv`,`otkaz_delpexp`, `otkaz_delpslava`, `otkaz_delpvinos_t`, `otkaz_delpvinos_m`,`otkaz_delpplatinum`, `otkaz_delpmed`, `otkaz_delppobedmonser`, `otkaz_delppobedigroki`,  `otkaz_addpv`,`otkaz_addpexp`, `otkaz_addpslava`, `otkaz_addpvinos_t`, `otkaz_addpvinos_m`,`otkaz_addpplatinum`, `otkaz_addpmed`, `otkaz_addppobedmonser`,  `otkaz_addppobedigroki`,"
                    . "`addprnv`,"
                    . " `addprv`,"
                    . " `proval_addprnv`,"
                    . " `proval_addprv`,"
                    . " `otkaz_addprnv`,"
                    . " `otkaz_addprv`)"
                    . " VALUES "
                    . "('$id_quest', '" . ($i + 1) . "', '" . esc(v($data['elements'][$i], 'auto_start_c')) . "', '" . esc(v($data['elements'][$i], 'img_id')) . "', '" . esc(v($data['elements'][$i], 'type_c')) . "', '" . esc(v($data['elements'][$i], 'msg_text')) . "', '" . esc(v($data['elements'][$i], 'time_ce')) . "', '" . esc(v($data['elements'][$i], 'type_if')) . "', '" . esc(v($data['elements'][$i], 'autobattle')) . "', '" . esc(v($data['elements'][$i], 'gotolocid')) . "', '" . esc(v($data['elements'][$i], 'new_quest')) . "',"
                    . " '" . esc(v($data['elements'][$i], 'mob_battle')) . "',"
                    . " '" . esc(v($data['elements'][$i], 'banda_battle')) . "',"
                    . " '" . esc(v($data['elements'][$i], 'banda_battle_location')) . "',"
                    . " '" . esc(v($data['elements'][$i], 'delpv')) . "','" . esc(v($data['elements'][$i], 'delpexp')) . "', '" . esc(v($data['elements'][$i], 'delpslava')) . "', '" . esc(v($data['elements'][$i], 'delpvinos_t')) . "', '" . esc(v($data['elements'][$i], 'delpvinos_m')) . "', '" . esc(v($data['elements'][$i], 'delpplatinum')) . "', '" . esc(v($data['elements'][$i], 'delpmed')) . "', '" . esc(v($data['elements'][$i], 'delppobedmonser')) . "', '" . esc(v($data['elements'][$i], 'delppobedigroki')) . "',  '" . esc(v($data['elements'][$i], 'addpv')) . "','" . esc(v($data['elements'][$i], 'addpexp')) . "', '" . esc(v($data['elements'][$i], 'addpslava')) . "', '" . esc(v($data['elements'][$i], 'addpvinos_t')) . "', '" . esc(v($data['elements'][$i], 'addpvinos_m')) . "','" . esc(v($data['elements'][$i], 'addpplatinum')) . "', '" . esc(v($data['elements'][$i], 'addpmed')) . "', '" . esc(v($data['elements'][$i], 'addppobedmonser')) . "', '" . esc(v($data['elements'][$i], 'addppobedigroki')) . "',  '" . esc(v($data['elements'][$i], 'mob_idandvesh')) . "', '" . esc(v($data['elements'][$i], 'herowin_c')) . "', '" . esc(v($data['elements'][$i], 'drop_vesh')) . "', '" . esc(v($data['elements'][$i], 'buy_vesh')) . "', '" . esc(v($data['elements'][$i], 'proval_img_id')) . "', '" . esc(v($data['elements'][$i], 'proval_type_c')) . "', '" . esc(v($data['elements'][$i], 'proval_msg_text')) . "', '" . esc(v($data['elements'][$i], 'proval_type_if')) . "', '" . esc(v($data['elements'][$i], 'proval_new_quest')) . "', '" . esc(v($data['elements'][$i], 'proval_delpv')) . "','" . esc(v($data['elements'][$i], 'proval_delpexp')) . "', '" . esc(v($data['elements'][$i], 'proval_delpslava')) . "', '" . esc(v($data['elements'][$i], 'proval_delpvinos_t')) . "', '" . esc(v($data['elements'][$i], 'proval_delpvinos_m')) . "','" . esc(v($data['elements'][$i], 'proval_delpplatinum')) . "', '" . esc(v($data['elements'][$i], 'proval_delpmed')) . "', '" . esc(v($data['elements'][$i], 'proval_delppobedmonser')) . "', '" . esc(v($data['elements'][$i], 'proval_delppobedigroki')) . "',  '" . esc(v($data['elements'][$i], 'proval_addpv')) . "','" . esc(v($data['elements'][$i], 'proval_addpexp')) . "', '" . esc(v($data['elements'][$i], 'proval_addpslava')) . "', '" . esc(v($data['elements'][$i], 'proval_addpvinos_t')) . "', '" . esc(v($data['elements'][$i], 'proval_addpvinos_m')) . "','" . esc(v($data['elements'][$i], 'proval_addpplatinum')) . "', '" . esc(v($data['elements'][$i], 'proval_addpmed')) . "', '" . esc(v($data['elements'][$i], 'proval_addppobedmonser')) . "', '" . esc(v($data['elements'][$i], 'proval_addppobedigroki')) . "',  '" . esc(v($data['elements'][$i], 'otkaz_img_id')) . "', '" . esc(v($data['elements'][$i], 'otkaz_type_c')) . "', '" . esc(v($data['elements'][$i], 'otkaz_msg_text')) . "', '" . esc(v($data['elements'][$i], 'otkaz_type_if')) . "', '" . esc(v($data['elements'][$i], 'otkaz_new_quest')) . "', '" . esc(v($data['elements'][$i], 'otkaz_delpv')) . "','" . esc(v($data['elements'][$i], 'otkaz_delpexp')) . "', '" . esc(v($data['elements'][$i], 'otkaz_delpslava')) . "', '" . esc(v($data['elements'][$i], 'otkaz_delpvinos_t')) . "', '" . esc(v($data['elements'][$i], 'otkaz_delpvinos_m')) . "','" . esc(v($data['elements'][$i], 'otkaz_delpplatinum')) . "', '" . esc(v($data['elements'][$i], 'otkaz_delpmed')) . "', '" . esc(v($data['elements'][$i], 'otkaz_delppobedmonser')) . "', '" . esc(v($data['elements'][$i], 'otkaz_delppobedigroki')) . "',  '" . esc(v($data['elements'][$i], 'otkaz_addpv')) . "','" . esc(v($data['elements'][$i], 'otkaz_addpexp')) . "', '" . esc(v($data['elements'][$i], 'otkaz_addpslava')) . "', '" . esc(v($data['elements'][$i], 'otkaz_addpvinos_t')) . "', '" . esc(v($data['elements'][$i], 'otkaz_addpvinos_m')) . "', '" . esc(v($data['elements'][$i], 'otkaz_addpplatinum')) . "', '" . esc(v($data['elements'][$i], 'otkaz_addpmed')) . "', '" . esc(v($data['elements'][$i], 'otkaz_addppobedmonser')) . "',  '" . esc(v($data['elements'][$i], 'otkaz_addppobedigroki')) . "',"
                    . "'" . esc(v($data['elements'][$i], 'addprnv')) . "',"
                    . "'" . esc(v($data['elements'][$i], 'addprv')) . "',"
                    . "'" . esc(v($data['elements'][$i], 'proval_addprnv')) . "',"
                    . "'" . esc(v($data['elements'][$i], 'proval_addprv')) . "',"
                    . "'" . esc(v($data['elements'][$i], 'otkaz_addprnv')) . "',"
                    . "'" . esc(v($data['elements'][$i], 'otkaz_addprv')) . "');");
        }
    } else {
        $mc->query("UPDATE `quests` SET "
                . "`level_min`='" . esc(v($data, 'level_min')) . "',"
                . "`level_max`='" . esc(v($data, 'level_max')) . "',"
                . "`locId`='" . esc(v($data, 'locId')) . "',"
                . "`name`='" . esc(v($data, 'name')) . "',"
                . "`pred_quest`='" . esc(v($data, 'pred_quest')) . "',"
                . "`quest_not`='" . esc(v($data, 'quest_not')) . "',"
                . "`predmet`='" . esc(v($data, 'predmet')) . "',"
                . "`predmet_none`='" . esc(v($data, 'predmet_none')) . "',"
                . "`time_r`='" . esc(v($data, 'time_r')) . "',"
                . "`part_num`='" . count(v($data, 'elements', [])) . "',"
                . "`health`='" . esc(v($data, 'health')) . "',"
                . "`strength`='" . esc(v($data, 'strength')) . "',"
                . "`toch`='" . esc(v($data, 'toch')) . "',"
                . "`bron`='" . esc(v($data, 'bron')) . "',"
                . "`lov`='" . esc(v($data, 'lov')) . "',"
                . "`kd`='" . esc(v($data, 'kd')) . "',"
                . "`block`='" . esc(v($data, 'block')) . "',"
                . "`level`='" . esc(v($data, 'level')) . "',"
                . "`exp`='" . esc(v($data, 'exp')) . "',"
                . "`slava`='" . esc(v($data, 'slava')) . "',"
                . "`zvanie`='" . esc(v($data, 'zvanie')) . "',"
                . "`vinos_t`='" . esc(v($data, 'vinos_t')) . "',"
                . "`vinos_m`='" . esc(v($data, 'vinos_m')) . "',"
                . "`tur_reit`='" . esc(v($data, 'tur_reit')) . "',"
                . "`rep_p`='" . esc(v($data, 'rep_p')) . "',"
                . "`rep_m`='" . esc(v($data, 'rep_m')) . "',"
                . "`platinum`='" . esc(v($data, 'platinum')) . "',"
                . "`med`='" . esc(v($data, 'med')) . "',"
                . "`pobedmonser`='" . esc(v($data, 'pobedmonser')) . "',"
                . "`pobedigroki`='" . esc(v($data, 'pobedigroki')) . "',"
                . "`auto_start`='" . esc(v($data, 'auto_start')) . "',"
                . "`rasa`='" . esc(v($data, 'rasa')) . "',"
                . "`comment`='" . esc(v($data, 'comment')) . "'"
                . " WHERE `id`='" . esc(v($data, 'id')) . "'");
        $time_rNew = 0;
        if (esc(v($data, 'time_r')) > 0) {
            $time_rNew = time() + esc(v($data, 'time_r'));
        } else if (esc(v($data, 'time_r')) <= 0) {
            $time_rNew = esc(v($data, 'time_r'));
        }
        $mc->query("UPDATE `quests_notActive` SET `time_end` = '$time_rNew' WHERE `id_quests` = '" . esc(v($data, 'id')) . "'");
        echo json_encode(array(
            "otvet" => 2,
            "new_id" => esc(v($data, 'id'))
        ));
        $chatmsg = addslashes("<a onclick=\"showContent('/profile.php?id=" . $user['id'] . "')\"><font color='#0033cc'>" . $user['name'] . "</font></a><font color='#0033cc'> изменил квест </font><a onclick=\"showContent('/admin/quest/quest.php?id=" . esc(v($data, 'id')) . "')\"><font color='#0033cc'>" . urldecode(esc(v($data, 'name'))) . "</font></a><font color='#0033cc'> !</font>");
        $mc->query("INSERT INTO `chat`(`id`,`name`,`id_user`,`chat_room`,`msg`,`msg2`,`time`, `unix_time`) VALUES (NULL,'АДМИНИСТРИРОВАНИЕ','','5', '" . $chatmsg . "','','','' )");

        $mc->query("DELETE FROM `quests_count` WHERE `id_quests` = '" . esc(v($data, 'id')) . "'");
        for ($i = 0; $i < count(v($data, 'elements', [])); $i++) {
            $mc->query("INSERT INTO `quests_count` "
                    . "(`id_quests`, `count`, `auto_start_c`, `img_id`, `type_c`, `msg_text`, `time_ce`, `type_if`, `autobattle`, `gotolocid`, `new_quest`,"
                    . " `mob_battle`,"
                    . " `banda_battle`,"
                    . " `banda_battle_location`,"
                    . " `delpv`, `delpexp`, `delpslava`, `delpvinos_t`, `delpvinos_m`, `delpplatinum`, `delpmed`, `delppobedmonser`, `delppobedigroki`, `addpv`, `addpexp`, `addpslava`, `addpvinos_t`, `addpvinos_m`, `addpplatinum`, `addpmed`, `addppobedmonser`, `addppobedigroki`,  `mob_idandvesh`, `herowin_c`, `drop_vesh`,`buy_vesh`, `proval_img_id`, `proval_type_c`, `proval_msg_text`, `proval_type_if`, `proval_new_quest`, `proval_delpv`, `proval_delpexp`, `proval_delpslava`, `proval_delpvinos_t`, `proval_delpvinos_m`,`proval_delpplatinum`, `proval_delpmed`, `proval_delppobedmonser`, `proval_delppobedigroki`,  `proval_addpv`,`proval_addpexp`, `proval_addpslava`, `proval_addpvinos_t`, `proval_addpvinos_m`,`proval_addpplatinum`, `proval_addpmed`, `proval_addppobedmonser`, `proval_addppobedigroki`,  `otkaz_img_id`, `otkaz_type_c`, `otkaz_msg_text`, `otkaz_type_if`, `otkaz_new_quest`, `otkaz_delpv`,`otkaz_delpexp`, `otkaz_delpslava`, `otkaz_delpvinos_t`, `otkaz_delpvinos_m`,`otkaz_delpplatinum`, `otkaz_delpmed`, `otkaz_delppobedmonser`, `otkaz_delppobedigroki`,  `otkaz_addpv`,`otkaz_addpexp`, `otkaz_addpslava`, `otkaz_addpvinos_t`, `otkaz_addpvinos_m`,`otkaz_addpplatinum`, `otkaz_addpmed`, `otkaz_addppobedmonser`,  `otkaz_addppobedigroki`,"
                    . "`addprnv`,"
                    . " `addprv`,"
                    . " `proval_addprnv`,"
                    . " `proval_addprv`,"
                    . " `otkaz_addprnv`,"
                    . " `otkaz_addprv`)"
                    . " VALUES "
                    . "('".$data['id']."', '" . ($i + 1) . "', '" . esc(v($data['elements'][$i], 'auto_start_c')) . "', '" . esc(v($data['elements'][$i], 'img_id')) . "', '" . esc(v($data['elements'][$i], 'type_c')) . "', '" . esc(v($data['elements'][$i], 'msg_text')) . "', '" . esc(v($data['elements'][$i], 'time_ce')) . "', '" . esc(v($data['elements'][$i], 'type_if')) . "', '" . esc(v($data['elements'][$i], 'autobattle')) . "', '" . esc(v($data['elements'][$i], 'gotolocid')) . "', '" . esc(v($data['elements'][$i], 'new_quest')) . "',"
                    . " '" . esc(v($data['elements'][$i], 'mob_battle')) . "',"
                    . " '" . esc(v($data['elements'][$i], 'banda_battle')) . "',"
                    . " '" . esc(v($data['elements'][$i], 'banda_battle_location')) . "',"
                    . " '" . esc(v($data['elements'][$i], 'delpv')) . "','" . esc(v($data['elements'][$i], 'delpexp')) . "', '" . esc(v($data['elements'][$i], 'delpslava')) . "', '" . esc(v($data['elements'][$i], 'delpvinos_t')) . "', '" . esc(v($data['elements'][$i], 'delpvinos_m')) . "', '" . esc(v($data['elements'][$i], 'delpplatinum')) . "', '" . esc(v($data['elements'][$i], 'delpmed')) . "', '" . esc(v($data['elements'][$i], 'delppobedmonser')) . "', '" . esc(v($data['elements'][$i], 'delppobedigroki')) . "',  '" . esc(v($data['elements'][$i], 'addpv')) . "','" . esc(v($data['elements'][$i], 'addpexp')) . "', '" . esc(v($data['elements'][$i], 'addpslava')) . "', '" . esc(v($data['elements'][$i], 'addpvinos_t')) . "', '" . esc(v($data['elements'][$i], 'addpvinos_m')) . "','" . esc(v($data['elements'][$i], 'addpplatinum')) . "', '" . esc(v($data['elements'][$i], 'addpmed')) . "', '" . esc(v($data['elements'][$i], 'addppobedmonser')) . "', '" . esc(v($data['elements'][$i], 'addppobedigroki')) . "',  '" . esc(v($data['elements'][$i], 'mob_idandvesh')) . "', '" . esc(v($data['elements'][$i], 'herowin_c')) . "', '" . esc(v($data['elements'][$i], 'drop_vesh')) . "', '" . esc(v($data['elements'][$i], 'buy_vesh')) . "', '" . esc(v($data['elements'][$i], 'proval_img_id')) . "', '" . esc(v($data['elements'][$i], 'proval_type_c')) . "', '" . esc(v($data['elements'][$i], 'proval_msg_text')) . "', '" . esc(v($data['elements'][$i], 'proval_type_if')) . "', '" . esc(v($data['elements'][$i], 'proval_new_quest')) . "', '" . esc(v($data['elements'][$i], 'proval_delpv')) . "','" . esc(v($data['elements'][$i], 'proval_delpexp')) . "', '" . esc(v($data['elements'][$i], 'proval_delpslava')) . "', '" . esc(v($data['elements'][$i], 'proval_delpvinos_t')) . "', '" . esc(v($data['elements'][$i], 'proval_delpvinos_m')) . "','" . esc(v($data['elements'][$i], 'proval_delpplatinum')) . "', '" . esc(v($data['elements'][$i], 'proval_delpmed')) . "', '" . esc(v($data['elements'][$i], 'proval_delppobedmonser')) . "', '" . esc(v($data['elements'][$i], 'proval_delppobedigroki')) . "',  '" . esc(v($data['elements'][$i], 'proval_addpv')) . "','" . esc(v($data['elements'][$i], 'proval_addpexp')) . "', '" . esc(v($data['elements'][$i], 'proval_addpslava')) . "', '" . esc(v($data['elements'][$i], 'proval_addpvinos_t')) . "', '" . esc(v($data['elements'][$i], 'proval_addpvinos_m')) . "', '" . esc(v($data['elements'][$i], 'proval_addpplatinum')) . "', '" . esc(v($data['elements'][$i], 'proval_addpmed')) . "', '" . esc(v($data['elements'][$i], 'proval_addppobedmonser')) . "', '" . esc(v($data['elements'][$i], 'proval_addppobedigroki')) . "',  '" . esc(v($data['elements'][$i], 'otkaz_img_id')) . "', '" . esc(v($data['elements'][$i], 'otkaz_type_c')) . "', '" . esc(v($data['elements'][$i], 'otkaz_msg_text')) . "', '" . esc(v($data['elements'][$i], 'otkaz_type_if')) . "', '" . esc(v($data['elements'][$i], 'otkaz_new_quest')) . "', '" . esc(v($data['elements'][$i], 'otkaz_delpv')) . "','" . esc(v($data['elements'][$i], 'otkaz_delpexp')) . "', '" . esc(v($data['elements'][$i], 'otkaz_delpslava')) . "', '" . esc(v($data['elements'][$i], 'otkaz_delpvinos_t')) . "', '" . esc(v($data['elements'][$i], 'otkaz_delpvinos_m')) . "','" . esc(v($data['elements'][$i], 'otkaz_delpplatinum')) . "', '" . esc(v($data['elements'][$i], 'otkaz_delpmed')) . "', '" . esc(v($data['elements'][$i], 'otkaz_delppobedmonser')) . "', '" . esc(v($data['elements'][$i], 'otkaz_delppobedigroki')) . "',  '" . esc(v($data['elements'][$i], 'otkaz_addpv')) . "','" . esc(v($data['elements'][$i], 'otkaz_addpexp')) . "', '" . esc(v($data['elements'][$i], 'otkaz_addpslava')) . "', '" . esc(v($data['elements'][$i], 'otkaz_addpvinos_t')) . "', '" . esc(v($data['elements'][$i], 'otkaz_addpvinos_m')) . "', '" . esc(v($data['elements'][$i], 'otkaz_addpplatinum')) . "', '" . esc(v($data['elements'][$i], 'otkaz_addpmed')) . "', '" . esc(v($data['elements'][$i], 'otkaz_addppobedmonser')) . "',  '" . esc(v($data['elements'][$i], 'otkaz_addppobedigroki')) . "',"
                    . "'" . esc(v($data['elements'][$i], 'addprnv')) . "',"
                    . "'" . esc(v($data['elements'][$i], 'addprv')) . "',"
                    . "'" . esc(v($data['elements'][$i], 'proval_addprnv')) . "',"
                    . "'" . esc(v($data['elements'][$i], 'proval_addprv')) . "',"
                    . "'" . esc(v($data['elements'][$i], 'otkaz_addprnv')) . "',"
                    . "'" . esc(v($data['elements'][$i], 'otkaz_addprv')) . "');");
        }
    }
} else {
    echo json_encode([
        "otvet" => 0,
        "new_id" => "",
        "error" => "Недостаточно прав или пустой запрос"
    ]);
}