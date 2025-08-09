<?php
require_once '../../system/func.php';
if ($user['access'] < 3) { // Проверка на админа
    exit();
}

// Активация/деактивация турниров для клана
if (isset($_GET['toggle_clan']) && isset($_GET['clan_id'])) {
    $clan_id = intval($_GET['clan_id']);
    $mc->query("UPDATE `clan` SET `tur_active` = NOT `tur_active` 
                WHERE `id` = '" . $mc->real_escape_string($clan_id) . "'");
    header("Location: /admin/tur/index.php");
    exit();
}

// Редактирование настроек турнира
if (isset($_POST['save_tur'])) {
    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $count_user = intval($_POST['count_user']);
    $min_level = intval($_POST['min_level']);
    $max_level = intval($_POST['max_level']);
    $prize_gold = intval($_POST['prize_gold']);
    $prize_platinum = intval($_POST['prize_platinum']);
    
    $mc->query("UPDATE `tur_list` SET 
                `name` = '" . $mc->real_escape_string($name) . "',
                `count_user` = '" . $count_user . "',
                `min_level` = '" . $min_level . "',
                `max_level` = '" . $max_level . "',
                `prize_gold` = '" . $prize_gold . "',
                `prize_platinum` = '" . $prize_platinum . "'
                WHERE `id` = '" . $id . "'");
                
    header("Location: /admin/tur/index.php");
    exit();
}

?>

<div class="admin_panel">
    <h2>Управление турнирами</h2>
    
    <h3>Настройки турниров</h3>
    <?php
    $tournaments = $mc->query("SELECT * FROM `tur_list`")->fetch_all(MYSQLI_ASSOC);
    foreach ($tournaments as $tur) {
        ?>
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo $tur['id']; ?>">
            <table class="table_block2">
                <tr>
                    <td>Название:</td>
                    <td><input type="text" name="name" value="<?php echo htmlspecialchars($tur['name']); ?>"></td>
                </tr>
                <tr>
                    <td>Количество участников:</td>
                    <td><input type="number" name="count_user" value="<?php echo $tur['count_user']; ?>"></td>
                </tr>
                <tr>
                    <td>Мин. уровень:</td>
                    <td><input type="number" name="min_level" value="<?php echo $tur['min_level']; ?>"></td>
                </tr>
                <tr>
                    <td>Макс. уровень:</td>
                    <td><input type="number" name="max_level" value="<?php echo $tur['max_level']; ?>"></td>
                </tr>
                <tr>
                    <td>Награда (золото):</td>
                    <td><input type="number" name="prize_gold" value="<?php echo $tur['prize_gold']; ?>"></td>
                </tr>
                <tr>
                    <td>Награда (платина):</td>
                    <td><input type="number" name="prize_platinum" value="<?php echo $tur['prize_platinum']; ?>"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="save_tur" value="Сохранить">
                    </td>
                </tr>
            </table>
        </form>
        <?php
    }
    ?>

    <h3>Кланы с турнирами</h3>
    <table class="table_block2">
        <tr>
            <th>Клан</th>
            <th>Статус</th>
            <th>Действие</th>
        </tr>
        <?php
        $clans = $mc->query("SELECT * FROM `clan` ORDER BY `name`")->fetch_all(MYSQLI_ASSOC);
        foreach ($clans as $clan) {
            ?>
            <tr>
                <td><?php echo htmlspecialchars($clan['name']); ?></td>
                <td><?php echo $clan['tur_active'] ? 'Активен' : 'Неактивен'; ?></td>
                <td>
                    <a href="?toggle_clan&clan_id=<?php echo $clan['id']; ?>">
                        <?php echo $clan['tur_active'] ? 'Отключить' : 'Включить'; ?>
                    </a>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
</div> 