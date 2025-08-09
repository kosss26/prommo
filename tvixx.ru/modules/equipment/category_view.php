<?php
/**
 * Страница категории экипировки
 * Отображает список предметов определенной категории
 */

// Определение названий категорий
$categoryNames = [
    1 => 'Оружие',
    2 => 'Защита', 
    3 => 'Шлемы',
    4 => 'Перчатки',
    5 => 'Доспехи',
    6 => 'Обувь',
    7 => 'Амулеты',
    8 => 'Кольца',
    9 => 'Пояса',
    10 => 'Для заданий',
    11 => 'Бонусы'
];

$categoryName = isset($categoryNames[$_GET['equip']]) ? $categoryNames[$_GET['equip']] : 'Снаряжение';
$categoryId = (int)$_GET['equip'];
?>

<div class="scroll_container">
    <div class="equip_container">
        <!-- Заголовок категории -->
        <div class="equip_header p-3 mb-3" style="background-color: rgba(211, 198, 163, 0.9); border-radius: 8px;">
            <div class="d-flex justify-content-between align-items-center">
                <button class="btn btn-sm btn-outline-secondary" onclick="showContent('/equip.php')">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <h4 class="mb-0"><?php echo $categoryName; ?></h4>
                <div style="width:40px;"><!-- место для выравнивания после удаления сортировки --></div>
            </div>
        </div>
        
        <div class="equipment-list">
            <?php
            // Сначала выводим одетые предметы
            $equippedItems = $mc->query("SELECT userbag.*, shop.name, shop.id_image, shop.stil, shop.level, shop.koll as max_koll, shop.iznos as max_iznos 
                FROM `userbag` 
                JOIN `shop` ON userbag.id_shop = shop.id
                WHERE userbag.id_user = '" . $user['id'] . "' 
                AND userbag.id_punct = '" . $categoryId . "' 
                AND userbag.dress = '1' 
                ORDER BY shop.level DESC, shop.name ASC");
            
            if ($equippedItems->num_rows > 0) {
                echo '<h5 class="mb-3 text-center">Экипировано</h5>';
                
                while ($item = $equippedItems->fetch_assoc()) {
                    ?>
                    <div class="card mb-3 equipment-item equipped" data-item-id="<?php echo $item['id']; ?>" data-level="<?php echo $item['level']; ?>" data-style="<?php echo $item['stil']; ?>">
                        <div class="card-body p-2">
                            <a onclick="MyLib.save = 1;showContent('/equip.php?id=<?php echo $item['id']; ?>&equip=<?php echo $categoryId; ?>')" class="text-decoration-none">
                                <div class="d-flex align-items-center">
                                    <div class="equipment-icon me-3">
                                        <div class="shopicobg shopico<?php echo $item['id_image']; ?>"></div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="equipment-name fw-bold"><?php echo $item['name']; ?></div>
                                        <div class="d-flex justify-content-between align-items-center flex-wrap mt-1">
                                            <?php if ($item['stil'] > 0): ?>
                                                <div class="equipment-style small">
                                                    <span class="badge" style="background-color: <?php echo $colorStyle[$item['stil']]; ?>">
                                                        <?php echo $textStyle[$item['stil']]; ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <span class="badge bg-secondary me-1">Ур. <?php echo $item['level']; ?></span>
                                            
                                            <?php if ($item['max_iznos'] > 0): ?>
                                                <div class="durability-indicator" style="width: 60px; height: 8px; background: rgba(0,0,0,0.1); border-radius: 4px; overflow: hidden;">
                                                    <div style="width: <?php echo ($item['iznos'] / $item['max_iznos']) * 100; ?>%; height: 100%; background: <?php echo $item['iznos'] < $item['max_iznos'] * 0.3 ? '#dc3545' : ($item['iznos'] < $item['max_iznos'] * 0.7 ? '#ffc107' : '#28a745'); ?>"></div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="ms-auto">
                                        <span class="btn btn-sm btn-outline-danger" data-action="remove" onclick="event.stopPropagation(); showContent('/equip.php?ids=<?php echo $item['id']; ?>&dress=1&equip=<?php echo $categoryId; ?>')">
                                            <i class="fas fa-times"></i>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <?php
                }
                
                echo '<div class="equipment-divider mb-3 mt-3"></div>';
            }
            
            // Затем выводим неодетые предметы
            $unequippedItems = $mc->query("SELECT 
                userbag.id, userbag.id_shop, userbag.iznos, userbag.koll, 
                shop.name, shop.id_image, shop.stil, shop.level, shop.koll as max_koll, shop.iznos as max_iznos,
                COUNT(*) as count
                FROM `userbag` 
                JOIN `shop` ON userbag.id_shop = shop.id
                WHERE userbag.id_user = '" . $user['id'] . "' 
                AND userbag.id_punct = '" . $categoryId . "' 
                AND userbag.dress = '0' 
                GROUP BY userbag.id_shop, userbag.iznos, userbag.koll
                ORDER BY shop.level DESC, shop.name ASC");
            
            if ($unequippedItems->num_rows > 0) {
                echo '<h5 class="mb-3 text-center">Доступные предметы</h5>';
                
                while ($item = $unequippedItems->fetch_assoc()) {
                    $canEquip = true;
                    $disabledReason = '';
                    
                    // Проверка на возможность экипировки (уровень, износ)
                    if ($item['level'] > $user['level']) {
                        $canEquip = false;
                        $disabledReason = 'Требуется уровень ' . $item['level'];
                    } elseif ($item['max_iznos'] > 0 && $item['iznos'] <= 0) {
                        $canEquip = false;
                        $disabledReason = 'Требуется ремонт';
                    }
                    ?>
                    <div class="card mb-3 equipment-item<?php echo !$canEquip ? ' faded' : ''; ?>" data-item-id="<?php echo $item['id']; ?>" data-level="<?php echo $item['level']; ?>" data-style="<?php echo $item['stil']; ?>">
                        <div class="card-body p-2">
                            <a onclick="MyLib.save = 1;showContent('/equip.php?id=<?php echo $item['id']; ?>&equip=<?php echo $categoryId; ?>')" class="text-decoration-none">
                                <div class="d-flex align-items-center">
                                    <div class="equipment-icon me-3">
                                        <div class="shopicobg shopico<?php echo $item['id_image']; ?>"></div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <div class="equipment-name fw-bold"><?php echo $item['name']; ?></div>
                                            <?php if ($item['count'] > 1): ?>
                                                <span class="badge bg-secondary ms-1"><?php echo $item['count']; ?></span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center flex-wrap mt-1">
                                            <?php if ($item['stil'] > 0): ?>
                                                <div class="equipment-style small me-2">
                                                    <span class="badge" style="background-color: <?php echo $colorStyle[$item['stil']]; ?>">
                                                        <?php echo $textStyle[$item['stil']]; ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <span class="badge bg-secondary me-1">Ур. <?php echo $item['level']; ?></span>
                                            
                                            <?php if (!$canEquip): ?>
                                                <span class="badge bg-warning text-dark"><?php echo $disabledReason; ?></span>
                                            <?php endif; ?>
                                            
                                            <div class="equipment-stats small mt-1">
                                                <?php if ($item['max_koll'] > -1): ?>
                                                    <div class="text-muted small">
                                                        Количество: <?php echo $item['koll'] . '/' . $item['max_koll']; ?>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <?php if ($item['max_iznos'] > -1): ?>
                                                    <div class="d-flex align-items-center text-muted small">
                                                        <span class="me-1">Износ:</span>
                                                        <div class="durability-indicator" style="width: 60px; height: 8px; background: rgba(0,0,0,0.1); border-radius: 4px; overflow: hidden;">
                                                            <div style="width: <?php echo ($item['iznos'] / $item['max_iznos']) * 100; ?>%; height: 100%; background: <?php echo $item['iznos'] < $item['max_iznos'] * 0.3 ? '#dc3545' : ($item['iznos'] < $item['max_iznos'] * 0.7 ? '#ffc107' : '#28a745'); ?>"></div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($canEquip): ?>
                                    <div class="ms-auto">
                                        <span class="btn btn-sm btn-outline-success" data-action="equip" onclick="event.stopPropagation(); showContent('/equip.php?ids=<?php echo $item['id']; ?>&dress=2&equip=<?php echo $categoryId; ?>')">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </div>
                    </div>
                    <?php
                }
            }
            
            // Если нет предметов в категории
            if ($equippedItems->num_rows == 0 && $unequippedItems->num_rows == 0) {
                echo '<div class="alert alert-info text-center">У вас нет предметов в этой категории</div>';
            }
            ?>
        </div>
    </div>
</div>

<!-- Скрипт для поиска инициализации удалён --> 