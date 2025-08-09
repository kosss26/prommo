<?php
$link_foot = [];
$link[0] = "";
$link[1] = "#";
if (isset($_GET['gifts']) && isset($_GET['id']) && $_GET['id'] == $user['id'] ||
        isset($_GET['gifts']) && isset($_GET['id']) && $user['access'] > 2) {
    $link[0] = "Выкинуть";
    $link[1] = "gifts.php?del&gifts_del=" . $_GET['gifts'] . "&id=" . $_GET['id'];
}


if (empty($_GET['shop'])) {
    $_GET['shop'] = -1;
}
if (empty($chat)) {
    $chat = 0;
}
if (empty($_GET['equip'])) {
    $_GET['equip'] = -1;
}
if (empty($_GET['aplication'])) {
    $_GET['aplication'] = -1;
}
if (empty($_GET['ids'])) {
    $_GET['ids'] = "";
}
if (empty($_GET['id'])) {
    $id = -1;
} else {
    $id = $_GET['id'];
}
if (empty($user['id'])) {
    $user['id'] = -1;
}
if (empty($user['access'])) {
    $user['access'] = -1;
}
if (empty($user['id_clan'])) {
    $user['id_clan'] = 0;
}
$arr = array(
    "grab_huntb_tec" => array(
        "L" => array(
            "Обновить" => "/huntb/grab/tec.php",
        ),
        "R" => array(
            "Назад" => "/huntb/grab/index.php",
        ),
        "Ra" => array(
            "Назад" => "/huntb/grab/index.php",
        ),
    ),
    "grab_huntb_search" => array(
        "L" => array(
            "Назад" => "/huntb/grab/index.php",
        ),
        "R" => array(
            "Назад" => "/huntb/grab/index.php",
        ),
        "Ra" => array(
            "Назад" => "/huntb/grab/index.php",
        ),
    ),
    "slava_huntb" => array(
        "L" => array(
            "Назад" => "/huntb/tur/index.php",
        ),
        "R" => array(
            "Назад" => "/huntb/tur/index.php",
        ),
        "Ra" => array(
            "Назад" => "/huntb/tur/index.php",
        ),
    ),
    "luntur_huntb" => array(
        "L" => array(
            "Назад" => "/huntb/index.php",
        ),
        "R" => array(
            "Назад" => "/huntb/index.php",
        ),
        "Ra" => array(
            "Назад" => "/huntb/index.php",
        ),
    ),
    "clantur_huntb" => array(
        "L" => array(
            "Назад" => "/huntb/index.php",
        ),
        "R" => array(
            "Назад" => "/huntb/index.php",
        ),
        "Ra" => array(
            "Назад" => "/huntb/index.php",
        ),
    ),
    "stenka_huntb_in_registered" => array(
        "L" => array(
            "Обновить" => "/huntb/tur/stenka/in.php",
        ),
        "R" => array(
            "Назад" => "/main.php",
        ),
        "Ra" => array(
            "Назад" => "/main.php",
        ),
    ),
    "stenka_huntb_in" => array(
        "L" => array(
            "Обновить" => "/huntb/tur/stenka/in.php",
        ),
        "R" => array(
            "Назад" => "/huntb/tur/stenka/index.php",
        ),
        "Ra" => array(
            "Назад" => "/huntb/tur/stenka/index.php",
        ),
    ),
    "stenka_huntb_index" => array(
        "L" => array(
            "Обновить" => "/huntb/tur/stenka/index.php",
        ),
        "R" => array(
            "Назад" => "/huntb/tur/index.php",
        ),
        "Ra" => array(
            "Назад" => "/huntb/tur/index.php",
        ),
    ),
    "vjv_huntb_in_registered" => array(
        "L" => array(
            "Обновить" => "/huntb/tur/vjv/in.php",
        ),
        "R" => array(
            "Назад" => "/main.php",
        ),
        "Ra" => array(
            "Назад" => "/main.php",
        ),
    ),
    "vjv_huntb_in" => array(
        "L" => array(
            "Обновить" => "/huntb/tur/vjv/in.php",
        ),
        "R" => array(
            "Назад" => "/huntb/tur/vjv/index.php",
        ),
        "Ra" => array(
            "Назад" => "/huntb/tur/vjv/index.php",
        ),
    ),
    "vjv_huntb_index" => array(
        "L" => array(
            "Обновить" => "/huntb/tur/vjv/index.php",
        ),
        "R" => array(
            "Назад" => "/huntb/tur/index.php",
        ),
        "Ra" => array(
            "Назад" => "/huntb/tur/index.php",
        ),
    ),
    "tec_huntb" => array(
        "L" => array(
            "Обновить" => "/huntb/1x1_tec/tec.php",
        ),
        "R" => array(
            "Назад" => "/huntb/",
        ),
        "Ra" => array(
            "Назад" => "/huntb/",
        ),
    ),
    "tec_hunt" => array(
        "L" => array(
            "Обновить" => "/hunt/tec.php",
        ),
        "R" => array(
            "Назад" => "/hunt/",
        ),
        "Ra" => array(
            "Назад" => "/hunt/",
        ),
    ),
    "bank" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Шахта" => "/mine/index.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/main.php"
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/main.php",
            "Админ" => "/admin/index.php",
        ),
    ),
    "top" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Шахта" => "/mine/index.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/main.php"
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/main.php",
            "Админ" => "/admin/index.php",
        ),
    ),
    "mainLow" => array(
        "L" => array(
            "Меню" => "#",
            "Магазин" => "/shop.php",
            "Шахта" => "/mine/index.php",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Топ" => "/top.php",
            "Онлайн" => "/online.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Выход" => "/index.php?exit_game",
        ),
        "Ra" => array(
            "Доп" => "#",
            "Выход" => "/index.php?exit_game",
            "Админ" => "/admin/index.php",
        ),
    ),
    "main" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Шахта" => "/mine/index.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Выход" => "/index.php?exit_game",
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/main.php",
            "Раздевалка" => "/admin/shkaf.php",
            "Админ" => "/admin/index.php",
            "Выход" => "/index.php?exit_game", // Добавляем кнопку выхода для админа
        ),
    ),
    "requestmoder" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Шахта" => "/mine/index.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Выход" => "/index.php?exit_game",
        ),
        "Ra" => array(
            "Доп" => "#",
            "Выход" => "/index.php?exit_game",
            "Админ" => "/admin/index.php",
        ),
    ),
    "profile" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Шахта" => "/mine/index.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/main.php"
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/main.php",
            "Админ" => "/admin/index.php",
        ),
    ),
    "changeParams" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Шахта" => "/mine/index.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/profile/" . $user['id']
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/profile/" . $user['id'],
            "Админ" => "/admin/index.php",
        ),
    ),
    "changeParamsName" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Шахта" => "/mine/index.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/changeParams.php"
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/changeParams.php",
            "Админ" => "/admin/index.php",
        ),
    ),
    "bonus" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Шахта" => "/mine/index.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/profile/" . $user['id'],
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/profile/" . $user['id'],
            "Админ" => "/admin/index.php",
        ),
    ),
    "hunt_edit" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Шахта" => "/mine/index.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/admin/hunt_equip/index.php",
        ),
        "Ra" => array(
            "Назад" => "/admin/hunt_equip/index.php",
        ),
    ),
    "shop_edit" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Шахта" => "/mine/index.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/admin/shop_equip/index.php",
        ),
        "Ra" => array(
            "Назад" => "/admin/shop_equip/index.php",
        ),
    ),
    "adminindex" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Шахта" => "/mine/index.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/main.php",
        ),
        "Ra" => array(
            "Назад" => "/main.php",
        ),
    ),
    "adminadmin" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Подземелья" => "/dungeons/index.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/admin/index.php"
        ),
        "Ra" => array(
            "Назад" => "/admin/index.php",
        ),
    ),
    "auk_edit" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/admin/auk/index.php"
        ),
        "Ra" => array(
            "Назад" => "/admin/auk/index.php",
        ),
    ),
    "adminhunt" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/admin/index.php",
        ),
        "Ra" => array(
            "Назад" => "/admin/index.php",
        ),
    ),
    "adminmail" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/admin/index.php",
        ),
        "Ra" => array(
            "Назад" => "/admin/index.php",
        ),
    ),
    "adminmoney" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/admin/index.php",
        ),
        "Ra" => array(
            "Назад" => "/admin/index.php",
        ),
    ),
    "adminshop" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/admin/index.php",
        ),
        "Ra" => array(
            "Назад" => "/admin/index.php",
        ),
    ),
    "adminwor" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/admin/index.php",
        ),
        "Ra" => array(
            "Назад" => "/admin/index.php",
        ),
    ),
    "adminlocindex" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/admin/index.php",
        ),
        "Ra" => array(
            "Назад" => "/admin/index.php",
        ),
    ),
    "adminlocedit" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/admin/location/index.php",
        ),
        "Ra" => array(
            "Назад" => "/admin/location/index.php",
        ),
    ),
    "adminbattle" => array(
        "L" => array(
            "Обновить" => (isset($_GET['view_battle']) ? "/admin/battle/index.php?view_battle=" . $_GET['view_battle'] : "/admin/battle/index.php"),
        ),
        "R" => array(
            "Назад" => "/admin/battle/index.php"
        ),
        "Ra" => array(
            "Назад" => "/admin/battle/index.php"
        ),
    ),
    "online" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/main.php"
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/main.php",
            "Админ" => "/admin/index.php",
        ),
    ),
    "shoptomain" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/main.php"
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/main.php",
            "Админ" => "/admin/index.php",
        ),
    ),
    "friends" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/main.php"
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/main.php",
            "Админ" => "/admin/index.php",
        ),
    ),
    "auktoshop" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/auk.php"
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/auk.php",
            "Админ" => "/admin/index.php",
        ),
    ),
    "backtoshop" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/shop.php"
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/shop.php",
            "Админ" => "/admin/index.php",
        ),
    ),
    "backtoshopshop" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/shop.php?shop=" . $_GET['shop'] . "&in",
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/shop.php?shop=" . $_GET['shop'] . "&in",
            "Админ" => "/admin/index.php",
        ),
    ),
    "mailtomain" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/main.php",
            "опции" => "/mail_op.php",
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/main.php",
            " опции" => "/mail_op.php",
            "Админ" => "/admin/index.php",
        ),
    ),
    "tomail" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/mail"
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/mail",
            "Админ" => "/admin/index.php",
        ),
    ),
    "allimages" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/main.php"
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/main.php",
            "Админ" => "/admin/index.php",
        ),
    ),
    "chat_smile" => array(
        "L" => array(
            "Назад" => "/chat.php",
        ),
        "R" => array(
            "Назад" => "/chat.php",
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/chat.php",
            "Админ" => "/admin/index.php",
        ),
    ),
    "chat" => array(
        "L" => array(
            "В таверне" => "/chattav.php?chat=" . $chat,
        ),
        "R" => array(
            "Доп" => "#",
            "Назад" => "/main.php",
            "Модераторы" => "/list_admin_moder.php",
            "Смайлы" => "/smile.php",
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/main.php",
            " Модераторы" => "/list_admin_moder.php",
            "Смайлы" => "/smile.php",
            "Админ" => "/admin/index.php",
        ),
    ),
    "chatclan" => array(
        "L" => array(
            "В чате" => "/chattav.php?chat=" . $chat,
        ),
        "R" => array(
            "Назад" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Админ" => "/admin/index.php",
        ),
    ),
    "chattav" => array(
        "L" => array(
            "" => "#"
        ),
        "R" => array(
            "Назад" => "/chat.php?chat=" . $chat,
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/chat.php?chat=" . $chat,
            "Админ" => "/admin/index.php",
        ),
    ),
    "chatclannone" => array(
        "L" => array(
            "" => "#"
        ),
        "R" => array(
            "Назад" => "/chat.php",
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/chat.php",
            "Админ" => "/admin/index.php",
        ),
    ),
    "ban" => array(
        "L" => array(
            "" => "#"
        ),
        "R" => array(
            "Назад" => "/chat.php?chat=" . $chat,
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/chat.php?chat=" . $chat,
            "Админ" => "/admin/index.php",
        ),
    ),
    "knock" => array(
        "L" => array(
            "" => "#"
        ),
        "R" => array(
            "Назад" => "/chat.php?chat=" . $chat,
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/chat.php?chat=" . $chat,
            "Админ" => "/admin/index.php",
        ),
    ),
    "equip" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/main.php"
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/main.php",
            "Админ" => "/admin/index.php",
        ),
    ),
    "equip1" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/equip.php"
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/equip.php",
            "Админ" => "/admin/index.php",
        ),
    ),
    "equip2" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/equip.php?equip=" . $_GET['equip'],
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/equip.php?equip=" . $_GET['equip'],
            "Админ" => "/admin/index.php",
        ),
    ),
    "error404" => array(
        "L" => array(
            "Назад" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/main.php",
        ),
        "Ra" => array(
            "Назад" => "/main.php",
        ),
    ),
    "huntindex" => array(
        "L" => array(
            "Меню" => "#",
            "Помощь" => "/help.php",
            "Персонаж" => "/profile/" . $user['id'],
            "Снаряжение" => "/equip.php",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Почта" => "/mail",
            "Онлайн" => "/online.php",
            "Банк" => "/bank.php",
            "Магазин" => "/shop.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Топ" => "/top.php",
            "Главная" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/main.php",
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/main.php",
            "Админ" => "/admin/index.php",
        ),
    ),
    "quests" => array(
        "L" => array(
            "Назад" => "/main.php?0000",
        ),
        "R" => array(
            "Назад" => "/main.php?0001",
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/main.php?0002",
            "Админ" => "/admin/index.php",
        ),
    ),
    "registration" => array(
        "L" => array(
            "Назад" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/main.php",
        ),
        "Ra" => array(
            "Назад" => "/main.php",
        ),
    ),
    "robo" => array(
        "L" => array(
            "Назад" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/main.php",
        ),
        "Ra" => array(
            "Назад" => "/main.php",
        ),
    ),
    "gifts" => array(
        "L" => array(
            $link[0] => $link[1],
        ),
        "R" => array(
            "Назад" => "/profile/" . $id,
        ),
        "Ra" => array(
            "Назад" => "/profile/" . $id,
        ),
    ),
    "help" => array(
        "L" => array(
            "В локацию" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/help.php",
        ),
        "Ra" => array(
            "Назад" => "/help.php",
        ),
    ),
    "huntattack" => array(
        "L" => array(
            "Назад" => "/hunt/",
        ),
        "R" => array(
            "Назад" => "/hunt/",
        ),
        "Ra" => array(
            "Назад" => "/hunt/",
        ),
    ),
    "huntbattle" => array(
        "L" => array(
            "Меню" => "#",
            "Чат" => "/chat.php",
            "Друзья" => "/friends.php",
            "Команды" => "/hunt/command.php",
        ),
        "R" => array(
            "" => "#"
        ),
        "Ra" => array(
            "Параметры" => "/admin/battle/index.php?this_battle"
        ),
    ),
    "command" => array(
        "L" => array(
            "Обновить" => "/hunt/command.php",
        ),
        "R" => array(
            "Назад" => "/hunt/battle.php"
        ),
        "Ra" => array(
            "Назад" => "/hunt/battle.php",
        ),
    ),
    "huntresult" => array(
        "L" => array(
            "Далее" => "/main.php",
        ),
        "R" => array(
            "Далее" => "/main.php",
        ),
        "Ra" => array(
            "Далее" => "/main.php",
        ),
    ),
    "clan" => array(
        "L" => array(
            "Меню клана" => "#",
            "Владения" => "/clan/vladenia.php",
            "Тотем" => "/clan/totem.php",
            "Казна" => "/clan/kazna.php",
        ),
        "R" => array(
            "Назад" => "/main.php",
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/main.php",
            "Админ" => "/admin/index.php",
        ),
    ),
    "clannone" => array(
        "L" => array(
            "Назад" => "/main.php",
        ),
        "R" => array(
            "Назад" => "/main.php",
        ),
        "Ra" => array(
            "Назад" => "/main.php",
        ),
    ),
    "totem" => array(
        "L" => array(
            "Меню клана" => "#",
            "Владения" => "/clan/vladenia.php",
            "Казна" => "/clan/kazna.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
        ),
        "R" => array(
            "Назад" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Админ" => "/admin/index.php",
        ),
    ),
    "kazna" => array(
        "L" => array(
            "Меню клана" => "#",
            "Владения" => "/clan/vladenia.php",
            "Тотем" => "/clan/totem.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
        ),
        "R" => array(
            "Назад" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Админ" => "/admin/index.php",
        ),
    ),
    "vladenia" => array(
        "L" => array(
            "Меню клана" => "#",
            "Тотем" => "/clan/totem.php",
            "Казна" => "/clan/kazna.php",
            "Клан" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
        ),
        "R" => array(
            "Назад" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
        ),
        "Ra" => array(
            "Доп" => "#",
            "Назад" => "/clan/clan_all.php?see_clan=" . $user['id_clan'],
            "Админ" => "/admin/index.php",
        ),
    ),
    "huntb1x1" => array(
        "L" => array(
            "Назад" => "/huntb/",
        ),
        "R" => array(
            "Назад" => "/huntb/",
        ),
        "Ra" => array(
            "Назад" => "/huntb/",
        ),
    ),
    "indexnone" => array(
        "L" => array(
            "" => "#"
        ),
        "R" => array(
            "" => "#"
        ),
        "Ra" => array(
            "" => "#"
        ),
    ),
    "ref" => array(
        "L" => array(
            "Назад" => "/profile.php",
        ),
        "R" => array(
            "Назад" => "/profile.php",
        ),
        "Ra" => array(
            "Назад" => "/profile.php",
        ),
    ),
    "ref_viev" => array(
        "L" => array(
            "Назад" => "/ref.php",
        ),
        "R" => array(
            "Назад" => "/ref.php",
        ),
        "Ra" => array(
            "Назад" => "/ref.php",
        ),
    ),
);
$arrNo5Level = ["Почта", "Банк", "Клан", "Друзья", "Топ", "Онлайн", ""];
?>
<link rel="stylesheet" href="/style/foot.css?1536.7214353045348" type="text/css">
<style>
:root{
  --bg-grad-start:#111;
  --bg-grad-end:#1a1a1a;
  --accent:#f5c15d;
  --accent-2:#ff8452;
  --card-bg:rgba(255,255,255,0.05);
  --glass-bg:rgba(255,255,255,0.08);
  --glass-border:rgba(255,255,255,0.12);
  --text:#fff;
  --radius:16px;
}

/* Отступ внизу для фиксированного меню */
body {
  padding-bottom: 75px; /* Минимальный отступ */
}

/* Общая полоса времени */
.footlinetime{
  width:100%;
  background:var(--glass-bg);
  backdrop-filter:blur(8px);
  -webkit-backdrop-filter:blur(8px);
  border-top:1px solid var(--glass-border);
  color:var(--text);
  font-weight:600;
  font-size:14px;
  text-align:center;
}

/* Левое и правое меню */
.footlmenut, .footrmenut{
  position:fixed;
  bottom:0;
  z-index:9999;
  background:var(--glass-bg);
  border-top:1px solid var(--glass-border);
  backdrop-filter:blur(8px);
  -webkit-backdrop-filter:blur(8px);
  padding:6px 0;
}
.footlmenut{left:0;}
.footrmenut{right:0;}

/* Ячейки меню */
.footlmenub, .footrmenub{
  padding:6px 14px;
  color:var(--text);
  cursor:pointer;
  white-space:nowrap;
  font-weight:500;
  transition:background .25s,color .25s;
  user-select:none;
}
.footlmenub:hover, .footrmenub:hover{
  background:rgba(255,255,255,0.12);
  color:var(--accent);
}

/* Скрыть старую анимацию стрелок при отсутствии */
.footlmenub img, .footrmenub img{display:none;}

/* Меньший шрифт на мобилках */
@media(max-width:480px){
  .footlmenub, .footrmenub{padding:5px 10px;font-size:12px;}
  body {padding-bottom: 65px;} /* Меньший отступ для мобильных */
}
</style>
<table class="footlinetime">
    <tr><td class="timefooter footbcs" style="width: 100%;"></td></tr>
</table>
<table class="footlmenut">
    <?php for ($i = count($arr[$footval]['L']) - 1; $i >= 1; $i--) { ?>
        <?php if (isset($user['level']) && $user['level'] > 1 || isset($user['level']) && $user['level'] < 2 && !in_array(array_keys($arr[$footval][array_keys($arr[$footval])[0]])[$i], $arrNo5Level)) { ?>
            <tr class="fblmenu"><td class="footlmenub footbcs <?= array_keys($arr[$footval][array_keys($arr[$footval])[0]])[$i] == "Магазин" ? "arrowShop" : ""; ?>" onclick='footGo();<?= $arr[$footval]['L'][array_keys($arr[$footval]['L'])[$i]] != '#' ? 'showContent("' . $arr[$footval]['L'][array_keys($arr[$footval]['L'])[$i]] . '");' : ''; ?>' ><?= array_keys($arr[$footval][array_keys($arr[$footval])[0]])[$i]; ?></td></tr>
        <?php } ?>
    <?php } ?>
            <tr><td class="footlmenub footbcs" onclick='newfootL();<?= $arr[$footval]['L'][array_keys($arr[$footval]['L'])[0]] != '#' ? 'showContent("' . $arr[$footval]['L'][array_keys($arr[$footval]['L'])[0]] . '");' : ''; ?>'><?php echo array_keys($arr[$footval][array_keys($arr[$footval])[0]])[0]; ?></td></tr>
</table>

<?php if ($user['access'] > 2) { ?>
    <table class="footrmenut">
        <?php for ($i = count($arr[$footval]['Ra']) - 1; $i >= 1; $i--) { ?>
            <tr class="fbrmenu"><td class="footrmenub footbcs" onclick='footGo();<?= $arr[$footval]['Ra'][array_keys($arr[$footval]['Ra'])[$i]] != '#' ? 'showContent("' . $arr[$footval]['Ra'][array_keys($arr[$footval]['Ra'])[$i]] . '");' : ''; ?>' ><?= array_keys($arr[$footval][array_keys($arr[$footval])[2]])[$i]; ?></td></tr>
        <?php } ?>
        <tr><td class="footrmenub footbcs" onclick='newfootR();<?= $arr[$footval]['Ra'][array_keys($arr[$footval]['Ra'])[0]] != '#' ? 'showContent("' . $arr[$footval]['Ra'][array_keys($arr[$footval]['Ra'])[0]] . '");' : ''; ?>'><?php echo array_keys($arr[$footval][array_keys($arr[$footval])[2]])[0]; ?></td></tr>
    </table>
<?php } else { ?>
    <table class="footrmenut">
        <?php for ($i = count($arr[$footval]['R']) - 1; $i >= 1; $i--) { ?>
            <tr class="fbrmenu"><td class="footrmenub footbcs" onclick='footGo();<?= $arr[$footval]['R'][array_keys($arr[$footval]['R'])[$i]] != '#' ? 'showContent("' . $arr[$footval]['R'][array_keys($arr[$footval]['R'])[$i]] . '");' : ''; ?>' ><?= array_keys($arr[$footval][array_keys($arr[$footval])[1]])[$i]; ?></td></tr>
        <?php } ?>
        <tr><td class="footrmenub footbcs" onclick='newfootR();<?= $arr[$footval]['R'][array_keys($arr[$footval]['R'])[0]] != '#' ? 'showContent("' . $arr[$footval]['R'][array_keys($arr[$footval]['R'])[0]] . '");' : ''; ?>'><?php echo array_keys($arr[$footval][array_keys($arr[$footval])[1]])[0]; ?></td></tr>
    </table>
<?php } ?>



<script>
        MyLib.footName = "<?php echo $footval; ?>";
        MyLib.time = <?php echo time(); ?> + 10800;
        menuOnOff(1);
        menuButtonOnOff(0);
        resizer();
        
        // Динамически установим высоту padding-bottom для body
        document.addEventListener('DOMContentLoaded', function() {
            // Найдем самое высокое из двух меню
            var leftMenuHeight = document.querySelector('.footlmenut') ? 
                document.querySelector('.footlmenut').offsetHeight : 0;
            var rightMenuHeight = document.querySelector('.footrmenut') ? 
                document.querySelector('.footrmenut').offsetHeight : 0;
            var maxHeight = Math.max(leftMenuHeight, rightMenuHeight);
            
            // Добавим небольшой запас и установим padding-bottom
            if (maxHeight > 0) {
                document.body.style.paddingBottom = (maxHeight + 15) + 'px';
            }
            
            // Слушатель для обновления при изменении размера окна
            window.addEventListener('resize', function() {
                var leftMenuHeight = document.querySelector('.footlmenut') ? 
                    document.querySelector('.footlmenut').offsetHeight : 0;
                var rightMenuHeight = document.querySelector('.footrmenut') ? 
                    document.querySelector('.footrmenut').offsetHeight : 0;
                var maxHeight = Math.max(leftMenuHeight, rightMenuHeight);
                
                if (maxHeight > 0) {
                    document.body.style.paddingBottom = (maxHeight + 15) + 'px';
                }
            });
        });
</script>

<?php
$user_questsRes = $mc->query("SELECT `id_quests`,`count`,`time_ce`,`herowin_c` FROM `quests_users` WHERE `id_user` = '" . $user['id'] . "' ORDER BY `time_view` DESC LIMIT 1");
if ($user_questsRes->num_rows > 0 && isset($user['location']) && isset($user['side']) && isset($user['id']) && isset($user['level'])) {
    $user_quests = $user_questsRes->fetch_array(MYSQLI_ASSOC);
    $user_quests_this = $mc->query("SELECT * FROM `quests_count` WHERE `id_quests` = '" . $user_quests['id_quests'] . "' && `count` = '" . $user_quests['count'] . "'")->fetch_array(MYSQLI_ASSOC);

    //a to b , side , level, duels, выбить, купить
    //определение дуэлей
    $herowin_c = $user_quests_this['herowin_c'] - $user_quests['herowin_c'];
    //список кого бить охота
    $arrArrowDropIdMob = [];
    $arrArrowDrop = [];
    $mob_idandvesh = json_decode(urldecode($user_quests_this['mob_idandvesh'])); //[[2,[[778,100]],[0,0],[0,0]],..]
    $drop_vesh = json_decode(urldecode($user_quests_this['drop_vesh'])); //[[1348,5],..]
    for ($i = 0; $i < count($drop_vesh); $i++) {
        //если не все вещи выбиты данного id
        if ($mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_shop` = '" . $drop_vesh[$i][0] . "'")->num_rows < $drop_vesh[$i][1]) {
            //проверить есть ли они у монстров в дропе 
            for ($i1 = 0; $i1 < count($mob_idandvesh); $i1++) {
                //перебираем дроп каждого монстра
                for ($i2 = 0; $i2 < count($mob_idandvesh[$i1][1]); $i2++) {
                    //сравниваем шмотку
                    if ((int) $drop_vesh[$i][0] == (int) $mob_idandvesh[$i1][1][$i2][0]) {
                        //проверяем нет ли уже айди монстра
                        if (!in_array($mob_idandvesh[$i1][0], $arrArrowDropIdMob)) {
                            //если нет то добавим
                            $arrArrowDropIdMob[] = $mob_idandvesh[$i1][0];
                        }
                        //break;
                    }
                }
            }
        }
    }
    //далее получаем список где монстры доступны
    for ($i = 0; $i < count($arrArrowDropIdMob); $i++) {
        if ($mc->query("SELECT `id_loc` FROM `hunt_equip` WHERE `id_hunt` = '" . $arrArrowDropIdMob[$i] . "' && `id_loc`!='102'&&`id_loc`!='0'&&`id_loc`!='23' LIMIT 1")->num_rows > 0) {
            $arrAllHunts = $mc->query("SELECT `id_loc` FROM `hunt_equip` WHERE `id_hunt` = '" . $arrArrowDropIdMob[$i] . "'  && `id_loc`!='102'&&`id_loc`!='0'&&`id_loc`!='23' ")->fetch_all(MYSQLI_ASSOC);
            $arrArrowDrop[] = [$arrArrowDropIdMob[$i], $arrAllHunts];
        }
    }//[[id monster, [[id_loc: "3"]]]
    $arrArrowBuy = [];
    $buy_vesh = json_decode(urldecode($user_quests_this['buy_vesh'])); //[["id","шт"],...]
    for ($i = 0; $i < count($buy_vesh); $i++) {
        if ($mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_shop` = '" . $buy_vesh[$i][0] . "'")->num_rows < $buy_vesh[$i][1]) {
            if ($mc->query("SELECT `id_location` FROM `shop_equip` WHERE `id_shop` = '" . $buy_vesh[$i][0] . "' && `id_location`!='102'&&`id_location`!='0'&&`id_location`!='23' LIMIT 1")->num_rows > 0) {
                $arrAllShops = $mc->query("SELECT `id_location` FROM `shop_equip` WHERE `id_shop` = '" . $buy_vesh[$i][0] . "'  && `id_location`!='102'&&`id_location`!='0'&&`id_location`!='23' ")->fetch_all(MYSQLI_ASSOC);
                $arrThisThing = $mc->query("SELECT `id_punct_shop` FROM `shop` WHERE `id` = '" . $buy_vesh[$i][0] . "'  LIMIT 1")->fetch_array(MYSQLI_ASSOC);
                $arrArrowBuy[] = [$arrThisThing['id_punct_shop'], $buy_vesh[$i][0], $arrAllShops];
            }
        }
    }//[[id_punct, id, [[id_location: "3"]]]
    ?><script>findNewPath(<?= $user['location']; ?>, <?= $user_quests_this['gotolocid']; ?>,<?= $user['side']; ?>,<?= $user['level']; ?>, <?= $herowin_c; ?>, <?= json_encode($arrArrowDrop); ?>, <?= json_encode($arrArrowBuy); ?>);</script><?php
}