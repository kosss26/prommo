<?php
/**
 * Детальная страница предмета экипировки
 * Отображает подробную информацию о выбранном предмете и позволяет его надеть/снять
 */

// Получение данных о предмете
$allequip1 = $mc->query("SELECT * FROM `userbag` WHERE `id_user`=" . $user['id'] . " AND `id`=" . $_GET['id'] . "")->fetch_array(MYSQLI_ASSOC);

if ($allequip1['id_shop'] == '') {
    // Если предмет не найден, перенаправление на общую страницу экипировки
    ?><script>showContent("/equip.php");</script><?php
    exit(0);
}

// Получение данных из магазина о предмете
$shopmagazin = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $allequip1['id_shop'] . "'")->fetch_array(MYSQLI_ASSOC);

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

// Расчет процента износа для отображения
$durabilityPercent = $shopmagazin['iznos'] > 0 ? ($allequip1['iznos'] / $shopmagazin['iznos']) * 100 : 100;
$durabilityColor = $durabilityPercent < 30 ? '#dc3545' : ($durabilityPercent < 70 ? '#ffc107' : '#28a745');

// Определение, может ли предмет быть экипирован
$canEquip = $shopmagazin['level'] <= $user['level'] && ($shopmagazin['iznos'] == -1 || $allequip1['iznos'] > 0);
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="item-card">
                <!-- Навигация -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button class="btn btn-sm btn-outline-secondary" onclick="showContent('/equip.php?equip=<?php echo $categoryId; ?>')">
                        <i class="fas fa-arrow-left mr-1"></i> Назад
                    </button>
                    <h4 class="mb-0"><?php echo $categoryName; ?></h4>
                    <div style="width: 40px;"><!-- Пустой div для выравнивания --></div>
                </div>
                
                <!-- Главная информация о предмете -->
                <div class="item-showcase">
                    <div class="item-title">
                        <?php echo $shopmagazin['name']; ?>
                        <div class="level-info">
                            <span class="level-star">⭐</span>
                            <span class="level-number"><?php echo $shopmagazin['level']; ?></span>
                        </div>
                    </div>
                    
                    <?php if ($shopmagazin['stil'] > 0): ?>
                        <div class="text-center mb-2">
                            <span class="item-style-badge" style="background-color: <?php echo $colorStyle[$shopmagazin['stil']]; ?>; color: white;">
                                <?php echo $textStyle[$shopmagazin['stil']]; ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Изображение и быстрая статистика -->
                    <div class="showcase-content">
                        <div class="item-preview">
                            <div class="item-image-wrapper">
                                <div class="item-glow"></div>
                                <div class="shopicobg shopico<?php echo $shopmagazin['id_image']; ?>"></div>
                            </div>
                            
                            <?php if ($shopmagazin['iznos'] > 0): ?>
                                <div class="item-durability">
                                    <div class="quick-stat">
                                        Прочность: <span style="color: <?php echo $durabilityColor; ?>"><?php echo $allequip1['iznos'] . '/' . $shopmagazin['iznos']; ?></span>
                                    </div>
                                </div>
                                <div class="progress mb-3">
                                    <div class="progress-bar" role="progressbar" 
                                        style="width: <?php echo $durabilityPercent; ?>%; background-color: <?php echo $durabilityColor; ?>;" 
                                        aria-valuenow="<?php echo $allequip1['iznos']; ?>" 
                                        aria-valuemin="0" 
                                        aria-valuemax="<?php echo $shopmagazin['iznos']; ?>">
                                        <?php echo round($durabilityPercent); ?>%
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="item-info">
                            <?php if (!empty($shopmagazin['opisanie'])): ?>
                                <div class="item-description">
                                    <?php echo $shopmagazin['opisanie']; ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Характеристики предмета -->
                            <div class="stats-section">
                                <?php if ($shopmagazin['toch'] != 0): ?>
                                    <div class="stat-row <?php echo $shopmagazin['toch'] > 0 ? 'positive' : 'negative'; ?>">
                                        <span class="stat-label">Точность:</span>
                                        <span class="stat-value">
                                            <img src="/images/icons/toch.png" width="16" class="me-1">
                                            <?php echo ($shopmagazin['toch'] > 0 ? '+' : '') . $shopmagazin['toch']; ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($shopmagazin['strength'] != 0): ?>
                                    <div class="stat-row <?php echo $shopmagazin['strength'] > 0 ? 'positive' : 'negative'; ?>">
                                        <span class="stat-label">Урон:</span>
                                        <span class="stat-value">
                                            <img src="/images/icons/power.jpg" width="16" class="me-1">
                                            <?php echo ($shopmagazin['strength'] > 0 ? '+' : '') . $shopmagazin['strength']; ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($shopmagazin['block'] != 0): ?>
                                    <div class="stat-row <?php echo $shopmagazin['block'] > 0 ? 'positive' : 'negative'; ?>">
                                        <span class="stat-label">Блок:</span>
                                        <span class="stat-value">
                                            <img src="/images/icons/shit.png" width="16" class="me-1">
                                            <?php echo ($shopmagazin['block'] > 0 ? '+' : '') . $shopmagazin['block']; ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($shopmagazin['lov'] != 0): ?>
                                    <div class="stat-row <?php echo $shopmagazin['lov'] > 0 ? 'positive' : 'negative'; ?>">
                                        <span class="stat-label">Уворот:</span>
                                        <span class="stat-value">
                                            <img src="/images/icons/img235.png" width="16" class="me-1">
                                            <?php echo ($shopmagazin['lov'] > 0 ? '+' : '') . $shopmagazin['lov']; ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($shopmagazin['bron'] != 0): ?>
                                    <div class="stat-row <?php echo $shopmagazin['bron'] > 0 ? 'positive' : 'negative'; ?>">
                                        <span class="stat-label">Броня:</span>
                                        <span class="stat-value">
                                            <img src="/images/icons/bron.png" width="16" class="me-1">
                                            <?php echo ($shopmagazin['bron'] > 0 ? '+' : '') . $shopmagazin['bron']; ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($shopmagazin['health'] != 0): ?>
                                    <div class="stat-row <?php echo $shopmagazin['health'] > 0 ? 'positive' : 'negative'; ?>">
                                        <span class="stat-label">Здоровье:</span>
                                        <span class="stat-value">
                                            <img src="/images/icons/hp.png" width="16" class="me-1">
                                            <?php echo ($shopmagazin['health'] > 0 ? '+' : '') . $shopmagazin['health']; ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Эффекты предмета -->
                            <?php if (!empty($shopmagazin['nameeffects'])): ?>
                                <div class="mt-3">
                                    <h5 class="mb-2">Эффекты:</h5>
                                    <div class="effects-section">
                                        <?php
                                        $effects = explode("|", $shopmagazin['nameeffects']);
                                        foreach ($effects as $effect) {
                                            if (!empty($effect)) {
                                                echo '<span class="effect-badge">' . $effect . '</span>';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Кнопки действий -->
                    <div class="showcase-actions mt-4">
                        <?php if ($allequip1['dress'] == 1): ?>
                            <?php if ($allequip1['id_punct'] < 10): ?>
                                <button class="action-button remove" onclick="showContent('/equip.php?ids=<?= $_GET['id'] ?>&dress=1&equip=<?= $_GET['equip'] ?>')">
                                    <i class="fas fa-times me-2"></i>Снять
                                </button>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if ($canEquip && $allequip1['id_punct'] < 10): ?>
                                <button class="action-button equip" onclick="showContent('/equip.php?ids=<?= $_GET['id'] ?>&dress=2&equip=<?= $_GET['equip'] ?>')">
                                    <i class="fas fa-check me-2"></i>Надеть
                                </button>
                            <?php elseif ($shopmagazin['level'] > $user['level']): ?>
                                <div class="repair-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Требуется уровень <?php echo $shopmagazin['level']; ?>
                                </div>
                            <?php elseif ($shopmagazin['iznos'] > 0 && $allequip1['iznos'] <= 0): ?>
                                <div class="repair-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Требуется ремонт
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 