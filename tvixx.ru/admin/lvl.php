<?php
require_once ('../system/func.php');
require_once ('../system/header.php');

if (!$user OR $user['access'] < 3) {
    ?><script>showContent("/");</script><?php
    exit;
}
$res_099 = $mc->query("SELECT * FROM exp ORDER BY `exp`.`lvl` ASC");
$arrtablopit_01 = array();
while ($row = $res_099->fetch_array(MYSQLI_ASSOC)) {
    $arrtablopit_01[] = $row;
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
    
    .level-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .level-header {
        text-align: center;
        color: var(--accent);
        margin-bottom: 25px;
        font-weight: 700;
        font-size: 28px;
    }
    
    .level-card {
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
    
    .level-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--accent), var(--accent-2));
        border-radius: var(--radius) var(--radius) 0 0;
    }
    
    .add-level-form {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .level-input {
        padding: 12px 15px;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        color: var(--text);
        border-radius: var(--radius);
        font-size: 14px;
        transition: all 0.3s ease;
        box-sizing: border-box;
        width: 120px;
    }
    
    .level-input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(245, 193, 93, 0.2);
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
        min-width: 100px;
    }
    
    .button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    
    .button.delete {
        background: var(--danger-gradient);
    }
    
    .level-divider {
        height: 1px;
        background: var(--glass-border);
        margin: 15px 0;
    }
    
    .level-items-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .level-item {
        background: var(--secondary-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        padding: 15px;
        transition: all 0.3s ease;
    }
    
    .level-item:hover {
        background: var(--item-hover);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .level-item-form {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .action-buttons {
        display: flex;
        gap: 10px;
    }
    
    @media (max-width: 768px) {
        .level-container {
            padding: 0 10px;
        }
        
        .level-input {
            width: 100%;
        }
        
        .level-item-form {
            flex-direction: column;
            align-items: stretch;
        }
        
        .action-buttons {
            flex-direction: row;
            justify-content: space-between;
        }
    }
</style>

<div class="level-container">
    <h2 class="level-header">Редактор Уровней</h2>
    
    <div class="level-card">
        <form id="form1" class="add-level-form">
            <input type='text' name='lvl' class="level-input" placeholder="Уровень">
            <input type='text' name='exp' class="level-input" placeholder="Опыт">
            <button type="button" class="button butt1">Добавить</button>
        </form>
        
        <script>
            $(".butt1").click(function () {
                showContent("/admin/lvl.php?create=addbd" + "&" + $("#form1").serialize());
            });
        </script>
        
        <div class="level-divider"></div>
        
        <div class="level-items-list">
            <?php
            if (isset($_GET['cteate'])&& $_GET['create'] == "addbd") {
                if (isset($_GET['lvl']) && isset($_GET['exp'])) {
                    $mc->query("INSERT INTO `exp`("
                            . "`id`,"
                            . "`lvl`,"
                            . "`exp`"
                            . ") VALUES ("
                            . "'NULL',"
                            . "'" . $_GET['lvl'] . "',"
                            . "'" . $_GET['exp'] . "'"
                            . ")");
                    ?><script>showContent("/admin/lvl.php?msg="+encodeURIComponent("added"));</script><?php
                } else {
                    ?><script>showContent("/admin/lvl.php?create=add&msg="+encodeURIComponent("error_added"));</script><?php
                }
            }
            if (isset($_GET['create'])&& $_GET['create'] == "save") {
                if (isset($_GET['id']) && isset($_GET['lvl']) && isset($_GET['exp'])) {
                    $mc->query("UPDATE `exp` SET `lvl` = '".$_GET['lvl']."' , `exp`='".$_GET['exp']."' WHERE `id` = '".$_GET['id']."'");
                    ?><script>showContent("/admin/lvl.php?msg="+encodeURIComponent("saved"));</script><?php
                    exit(0);
                } else {
                    ?><script>showContent("/admin/lvl.php?create=add&msg="+encodeURIComponent("error_saved"));</script><?php
                    exit(0);
                }
            }
            if (isset($_GET['create'])&& $_GET['create'] == "del") {
                if (isset($_GET['id']) && isset($_GET['lvl']) && isset($_GET['exp'])) {
                    $mc->query("DELETE FROM `exp` WHERE `exp`.`id` = '".$_GET['id']."'");
                    ?><script>showContent("/admin/lvl.php?msg="+encodeURIComponent("deleted"));</script><?php
                    exit(0);
                } else {
                    ?><script>showContent("/admin/lvl.php?create=add&msg="+encodeURIComponent("error_saved"));</script><?php
                    exit(0);
                }
            }
            for ($i = count($arrtablopit_01) - 1; $i >= 0; $i--) {
               $arrtablopit_01[$i]['exp']
                ?>
                <div class="level-item">
                    <form id="form1<?php echo $arrtablopit_01[$i]['id'];?>" class="level-item-form">
                        <input name='id' value="<?php echo $arrtablopit_01[$i]['id'];?>" hidden>
                        <input name='lvl' class="level-input" placeholder="Уровень" value="<?php echo $arrtablopit_01[$i]['lvl'];?>">
                        <input name='exp' class="level-input" placeholder="Опыт" value="<?php echo $arrtablopit_01[$i]['exp'];?>">
                        <div class="action-buttons">
                            <button type="button" class="button butt1<?php echo $arrtablopit_01[$i]['id'];?>">Сохранить</button>
                            <button type="button" class="button delete butt2<?php echo $arrtablopit_01[$i]['id'];?>">Удалить</button>
                        </div>
                    </form>
                </div>
                <script>
                    $(".butt1<?php echo $arrtablopit_01[$i]['id'];?>").click(function () {
                        showContent("/admin/lvl.php?create=save" + "&" + $("#form1<?php echo $arrtablopit_01[$i]['id'];?>").serialize());
                    });
                    $(".butt2<?php echo $arrtablopit_01[$i]['id'];?>").click(function () {
                        showContent("/admin/lvl.php?create=del" + "&" + $("#form1<?php echo $arrtablopit_01[$i]['id'];?>").serialize());
                    });
                </script>
                <?php
            }
            ?>
        </div>
    </div>
</div>

<?php $footval='adminmoney'; include '../system/foot/foot.php';?>