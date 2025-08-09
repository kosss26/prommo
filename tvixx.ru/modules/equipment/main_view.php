<?php
/**
 * Главная страница экипировки
 * Отображает все категории снаряжения и текущий стиль персонажа
 */

$idcolc = 0;
$idPOYA = 0;

// Названия категорий с форматированием
$nameor = '<span class="category-name">Оружие</span>';
$namezah = '<span class="category-name">Защита</span>';
$nameshl = '<span class="category-name">Шлем</span>';
$nameper = '<span class="category-name">Перчатки</span>';
$namedos = '<span class="category-name">Доспехи</span>';
$nameobu = '<span class="category-name">Обувь</span>';
$nameamu = '<span class="category-name">Амулет</span>';
$namekolca = ['<span class="category-name">Кольца</span>'];
$namepoya = ['<span class="category-name">Пояс</span>'];

// Определение стиля снаряжения персонажа
$stmt = $mc->prepare("SELECT `stil` FROM `userbag` WHERE `id_user` = ? AND `id_punct` < 10 AND `dress` = 1 GROUP BY `stil` ORDER BY `stil` ASC");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$arr = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if (count($arr) == 2) {
    $stil = $arr[1]['stil'];
} elseif (count($arr) == 1 && $arr[0]['stil'] != 0) {
    $stil = $arr[0]['stil'];
} elseif (count($arr) < 2) {
    $stil = 0;
} else {
    $stil = 5; // Нарушенный стиль
}

// Получение количества предметов в каждой категории
$numor = $mc->query("SELECT `id_user` FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_punct` = '1'")->num_rows;
$numzah = $mc->query("SELECT `id_user` FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_punct` = '2'")->num_rows;
$numshl = $mc->query("SELECT `id_user` FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_punct` = '3'")->num_rows;
$numper = $mc->query("SELECT `id_user` FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_punct` = '4'")->num_rows;
$numdos = $mc->query("SELECT `id_user` FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_punct` = '5'")->num_rows;
$numobu = $mc->query("SELECT `id_user` FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_punct` = '6'")->num_rows;
$numamu = $mc->query("SELECT `id_user` FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_punct` = '7'")->num_rows;
$numkolc = $mc->query("SELECT `id_user` FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_punct` = '8'")->num_rows;
$numpoya = $mc->query("SELECT `id_user` FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_punct` = '9'")->num_rows;
$numkv = $mc->query("SELECT `id_user` FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_punct` = '10'")->num_rows;
$numbon = $mc->query("SELECT `id_user` FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_punct` = '11'")->num_rows;

// Получение одетых предметов и их названий для отображения
$equip1 = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $user['id'] . "' && `id_punct` < '11' && `dress`='1'");
while ($equip = $equip1->fetch_array(MYSQLI_ASSOC)) {
    $namevesh = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $equip['id_shop'] . "'")->fetch_array(MYSQLI_ASSOC);
    
    // Форматирование названия в соответствии со стилем
    if ($namevesh['stil'] > 0) {
        $namevesh['name'] = '<span style="color:' . $colorStyle[$namevesh['stil']] . ';font-weight:bold;">' . $namevesh['name'] . '</span>';
    }
    
    // Заполнение названий одетых предметов
    switch ($equip['id_punct']) {
        case 1: $nameor = $namevesh['name']; break;
        case 2: $namezah = $namevesh['name']; break;
        case 3: $nameshl = $namevesh['name']; break;
        case 4: $nameper = $namevesh['name']; break;
        case 5: $namedos = $namevesh['name']; break;
        case 6: $nameobu = $namevesh['name']; break;
        case 7: $nameamu = $namevesh['name']; break;
        case 8: 
            $namekolca[$idcolc] = $namevesh['name'];
            $idcolc++;
            break;
        case 9:
            $namepoya[$idPOYA] = $namevesh['name'];
            $idPOYA++;
            break;
    }
}

// Обработка массивов колец и поясов
$namekolca[0] = isset($namekolca[0]) ? $namekolca[0] : "";
$namekolca[1] = isset($namekolca[1]) ? " , " . $namekolca[1] : "";

$namepoya[0] = isset($namepoya[0]) ? $namepoya[0] : "";
for ($i = 1; $i <= 6; $i++) {
    $namepoya[$i] = isset($namepoya[$i]) ? " , " . $namepoya[$i] : "";
}

// Форматирование стиля для отображения
if ($stil > 0 && $stil < 5) {
    $style = '<span style="color:' . $colorStyle[$stil] . ';font-weight: bold;">' . $textStyle[$stil] . '</span>';
    $currentStyle = $stil; // Для data-style
} else if ($stil == 0) {
    $style = 'Нет';
    $currentStyle = 0;
} else {
    $style = '<span style="color:#FF4500;font-weight:bold;">Нарушен!</span>';
    $currentStyle = 5; // Специальный индекс для нарушенного стиля
}

// Получение общего количества предметов экипировки
$eqcount = $mc->query("SELECT * FROM `userbag` WHERE `id_user`='" . $user['id'] . "' AND `id_punct`>'0' AND `id_punct`<'10' ")->num_rows;

// Создаем массив категорий для использования в обеих версиях (мобильной и десктопной)
$categories = [
    1 => ['icon' => 'met.png', 'name' => $nameor, 'count' => $numor],
    2 => ['icon' => 'shits.png', 'name' => $namezah, 'count' => $numzah],
    3 => ['icon' => 'shl.png', 'name' => $nameshl, 'count' => $numshl],
    4 => ['icon' => 'perch.png', 'name' => $nameper, 'count' => $numper],
    5 => ['icon' => 'bronya.png', 'name' => $namedos, 'count' => $numdos],
    6 => ['icon' => 'boti.png', 'name' => $nameobu, 'count' => $numobu],
    7 => ['icon' => 'amul.png', 'name' => $nameamu, 'count' => $numamu],
    8 => ['icon' => 'colc.png', 'name' => $namekolca[0] . $namekolca[1], 'count' => $numkolc],
    9 => ['icon' => 'zel.png', 'name' => implode("", $namepoya), 'count' => $numpoya],
    10 => ['icon' => 'sunduk.png', 'name' => 'Для заданий', 'count' => $numkv]
];

if ($user['access'] > 3) {
    $categories[11] = ['icon' => 'sunduk.png', 'name' => 'Бонусы', 'count' => $numbon];
}
?>

<div class="scroll_container">
    <div class="equip_container">
        <!-- Блок статистики снаряжения -->
        <div class="equip_stats_container">
            <div class="equip_header">
                Снаряжение: <?php echo $eqcount; ?>/<?php echo $user['max_bag_count']; ?>
            </div>
            <div class="equip_style">
                Стиль: <b><?php echo $style; ?></b>
            </div>
            
            <!-- Панель управления (сортировка и 'Снять всё') была удалена в рамках редизайна -->
        </div>

        <!-- Мобильная версия с вертикальным списком -->
        <div class="equip_categories_mobile d-block d-md-none">
            <?php
            foreach ($categories as $id => $category) {
                ?>
                <div onclick="showContent('/equip.php?equip=<?= $id ?>')" class="equip_slot mb-2" data-style="<?= $currentStyle ?>">
                    <div class="d-flex align-items-center w-100">
                        <?php echo ico('icons', $category['icon']); ?>
                        <span class="equip_count ms-2">[<?= $category['count'] ?>]</span>
                        <span class="equip_name ms-2"><?= $category['name'] ?></span>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        
        <!-- Десктопная версия с сеткой -->
        <div class="equip_categories d-none d-md-grid">
            <?php
            foreach ($categories as $id => $category) {
                ?>
                <div onclick="showContent('/equip.php?equip=<?= $id ?>')" class="equip_slot" data-style="<?= $currentStyle ?>">
                    <div class="d-flex align-items-center w-100">
                        <?php echo ico('icons', $category['icon']); ?>
                        <span class="equip_count ms-2">[<?= $category['count'] ?>]</span>
                        <span class="equip_name ms-2"><?= $category['name'] ?></span>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div> 