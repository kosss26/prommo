<?php
require_once '../../system/func.php';
require_once '../../system/header.php';
if (!$user OR $user['access'] < 3) {
    ?><script>showContent("/");</script><?php
    exit(0);
}

?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    :root {
        --bg-grad-start: #111;
        --bg-grad-end: #1a1a1a;
        --accent: #f5c15d;
        --accent-2: #ff8452;
        --card-bg: rgba(255,255,255,0.05);
        --glass-bg: rgba(255,255,255,0.08);
        --glass-border: rgba(255,255,255,0.12);
        --text: #fff;
        --muted: #c2c2c2;
        --radius: 16px;
        --secondary-bg: rgba(255,255,255,0.03);
        --item-hover: rgba(255,255,255,0.15);
        --panel-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
        --success-gradient: linear-gradient(135deg, #2ecc71, #27ae60);
        --danger-gradient: linear-gradient(135deg, #e74c3c, #c0392b);
        --primary-gradient: linear-gradient(135deg, var(--accent), var(--accent-2));
    }
    
    body {
        background: linear-gradient(135deg, var(--bg-grad-start), var(--bg-grad-end));
        color: var(--text);
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 15px;
    }
    
    .auction-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .auction-header {
        text-align: center;
        color: var(--accent);
        margin-bottom: 25px;
        font-weight: 700;
        font-size: 28px;
    }
    
    .auction-card {
        background: var(--card-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        overflow: hidden;
        position: relative;
        box-shadow: var(--panel-shadow);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        margin-bottom: 20px;
        padding: 20px;
    }
    
    .auction-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--accent), var(--accent-2));
        border-radius: var(--radius) var(--radius) 0 0;
    }
    
    .section {
        margin-bottom: 25px;
    }
    
    .section-title {
        color: var(--accent);
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--glass-border);
    }
    
    .button {
        background: var(--primary-gradient);
        color: #111;
        border: none;
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 600;
        border-radius: var(--radius);
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        line-height: 1;
        height: 45px;
        box-sizing: border-box;
    }
    
    .button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    
    .button.save {
        background: var(--success-gradient);
    }
    
    .button.delete {
        background: var(--danger-gradient);
    }
    
    .auction-item {
        background: var(--secondary-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        padding: 15px;
        transition: all 0.3s ease;
        margin-bottom: 15px;
    }
    
    .auction-item:hover {
        background: var(--item-hover);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .auction-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .auction-id {
        font-size: 14px;
        color: var(--muted);
    }
    
    .auction-badge {
        display: inline-block;
        padding: 5px 10px;
        background: var(--primary-gradient);
        color: #111;
        border-radius: var(--radius);
        font-size: 12px;
        font-weight: 600;
    }
    
    .auction-form {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .form-label {
        font-size: 14px;
        color: var(--muted);
    }
    
    .form-input {
        padding: 12px 15px;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        color: var(--text);
        border-radius: var(--radius);
        font-size: 14px;
        transition: all 0.3s ease;
        box-sizing: border-box;
        width: 100%;
    }
    
    .form-input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(245, 193, 93, 0.2);
    }
    
    .button-row {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
    
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    
    .modal-content {
        background: var(--card-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        box-shadow: var(--panel-shadow);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        padding: 20px;
        width: 90%;
        max-width: 400px;
        position: relative;
    }
    
    .modal-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--accent), var(--accent-2));
        border-radius: var(--radius) var(--radius) 0 0;
    }
    
    .modal-text {
        color: var(--text);
        font-size: 16px;
        margin-bottom: 20px;
        text-align: center;
        line-height: 1.5;
    }
    
    .modal-buttons {
        display: flex;
        justify-content: center;
        gap: 10px;
    }
    
    .modal-button {
        min-width: 120px;
    }
    
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: var(--card-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        box-shadow: var(--panel-shadow);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        padding: 15px 20px;
        color: var(--text);
        z-index: 1001;
        display: none;
        opacity: 0;
        transition: opacity 0.3s;
    }
    
    @media (max-width: 768px) {
        .auction-form {
            grid-template-columns: 1fr;
        }
        
        .auction-item-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        .button-row {
            flex-direction: column;
            width: 100%;
        }
        
        .button-row .button {
            width: 100%;
        }
    }
</style>

<?php
if (isset($_GET['add_new']) && isset($_GET['auk_lvl_min']) && isset($_GET['auk_lvl_max'])) {
    $mc->query("INSERT INTO `auk_list` (`id`, `lvl_min`, `lvl_max`, `date`) VALUES (NULL, '" . $_GET['auk_lvl_min'] . "', '" . $_GET['auk_lvl_max'] . "', CURRENT_TIMESTAMP)");
    message("Добавлено");
}
?>

<div class="auction-container">
    <h2 class="auction-header">Управление аукционами</h2>

    <!-- Список аукционов -->
    <?php
    $auk_list_Res = $mc->query("SELECT * FROM `auk_list` ORDER BY `lvl_min` ASC");
    if ($auk_list_Res->num_rows > 0) {
        ?>
        <div class="auction-card">
            <div class="section">
                <div class="section-title">Список аукционов</div>
                
                <?php
                $auk_list = $auk_list_Res->fetch_all(MYSQLI_ASSOC);
                foreach ($auk_list as $value) {
                    ?>
                    <div class="auction-item">
                        <div class="auction-item-header">
                            <div class="auction-id">ID: <?= $value['id'] ?> | Создано: <?= $value['date'] ?></div>
                            <div class="auction-badge">Лотов: <?= $value['counts'] ?></div>
                        </div>
                        
                        <div class="auction-form">
                            <div class="form-group">
                                <label class="form-label">Минимальный уровень</label>
                                <input id="lvl_min_id<?= $value['id']; ?>" class="form-input" type="number" value="<?= $value['lvl_min']; ?>" min="0" max="999999">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Максимальный уровень</label>
                                <input id="lvl_max_id<?= $value['id']; ?>" class="form-input" type="number" value="<?= $value['lvl_max']; ?>" min="0" max="999999">
                            </div>
                        </div>
                        
                        <div class="button-row">
                            <button class="button" onclick="showContent('/admin/auk/edit.php?id_auk=<?= $value['id']; ?>');">Изменить лоты</button>
                            <button class="button save" onclick="auk_lvl_save_show_msg(<?= $value['id']; ?>);">Сохранить</button>
                            <button class="button delete" onclick="auk_lvl_delete_show_msg(<?= $value['id']; ?>);">Удалить</button>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
    }
    ?>

    <!-- Форма добавления нового аукциона -->
    <div class="auction-card">
        <div class="section">
            <div class="section-title">Добавить новый аукцион</div>
            
            <div class="auction-form">
                <div class="form-group">
                    <label class="form-label">Минимальный уровень</label>
                    <input id="auk_lvl_min_tmp" class="form-input" type="number" value="0" min="0" max="999999">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Максимальный уровень</label>
                    <input id="auk_lvl_max_tmp" class="form-input" type="number" value="99" min="0" max="999999">
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 20px;">
                <button class="button" onclick="showContent('/admin/auk/index.php?add_new=1&auk_lvl_min=' + $('#auk_lvl_min_tmp').val() + '&auk_lvl_max=' + $('#auk_lvl_max_tmp').val());">Добавить аукцион</button>
            </div>
        </div>
    </div>

    <!-- Модальные окна -->
    <div class="modal" id="aukLvlMsgDel">
        <div class="modal-content">
            <div class="modal-text">
                <b>Внимание!</b><br>
                Данный аукцион будет прекращен и удален вместе со всем списком лотов, а участникам возвращены ставки.
            </div>
            <div class="modal-buttons">
                <button class="button modal-button" onclick="confirmAukDelete();">Подтверждаю</button>
                <button class="button delete modal-button" onclick="$('#aukLvlMsgDel').fadeOut(300);">Отмена</button>
            </div>
        </div>
    </div>

    <div class="modal" id="aukLvlMsg">
        <div class="modal-content">
            <div class="modal-text">
                <b>Внимание!</b><br>
                Данный аукцион будет прекращен и сброшен, а участникам возвращены ставки.
            </div>
            <div class="modal-buttons">
                <button class="button modal-button" onclick="confirmAukSave();">Подтверждаю</button>
                <button class="button delete modal-button" onclick="$('#aukLvlMsg').fadeOut(300);">Отмена</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Обновленные функции для работы с модальными окнами
    function auk_lvl_delete_show_msg(id) {
        MyLib.auk_id = id;
        $('#aukLvlMsgDel').fadeIn(300);
    }

    function confirmAukDelete() {
        $('#aukLvlMsgDel').fadeOut(300);
        
        $.ajax({
            url: "/admin/auk/delete_auk_lvl.php",
            type: 'GET',
            data: {
                auk_id: MyLib.auk_id
            },
            success: function (data) {
                showNotification(data);
                setTimeout(function () {
                    showContent("/admin/auk/index.php");
                }, 1000);
            },
            error: function (e) {
                showNotification('Произошла ошибка');
            }
        });
    }

    function auk_lvl_save_show_msg(id) {
        MyLib.auk_lvl_min = $("#lvl_min_id" + id).val();
        MyLib.auk_lvl_max = $("#lvl_max_id" + id).val();
        MyLib.auk_id = id;
        $('#aukLvlMsg').fadeIn(300);
    }

    function confirmAukSave() {
        $('#aukLvlMsg').fadeOut(300);
        
        $.ajax({
            url: "/admin/auk/save_auk_lvl.php",
            type: 'GET',
            data: {
                auk_lvl_min: MyLib.auk_lvl_min,
                auk_lvl_max: MyLib.auk_lvl_max,
                auk_id: MyLib.auk_id
            },
            success: function (data) {
                showNotification(data);
            },
            error: function (e) {
                showNotification('Произошла ошибка');
            }
        });
    }
    
    function showNotification(message) {
        // Удаляем старые уведомления
        $('.notification').remove();
        
        // Создаем новое уведомление
        var notification = $('<div class="notification">' + message + '</div>');
        $('body').append(notification);
        
        // Показываем уведомление с анимацией
        notification.fadeIn(300).css('opacity', '1');
        
        // Скрываем через некоторое время
        setTimeout(function() {
            notification.fadeOut(300, function() {
                notification.remove();
            });
        }, 3000);
    }
    
    // Обратная совместимость со старым методом msg
    function msg(e) {
        showNotification(e);
    }
</script>

<?php
$footval = 'adminadmin';
require_once '../../system/foot/foot.php';
?>




