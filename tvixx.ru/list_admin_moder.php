<?php
require_once ('system/dbc.php');
require_once ('system/func.php');
require_once ('system/header.php');
auth();

$moder = $mc->query("SELECT * FROM `users` WHERE `access` = 1")->fetch_all(MYSQLI_ASSOC);
$admin = $mc->query("SELECT * FROM `users` WHERE `access` > 1")->fetch_all(MYSQLI_ASSOC);
if(isset($_GET['send']) && isset($_GET['id']) && isset($_GET['id2'])){
	$users2 = $mc->query("SELECT * FROM `users` WHERE `id` = '".$_GET['id2']."'")->fetch_array(MYSQLI_ASSOC);
	if($users2['vk_id'] > 0){
	  message("Модератор-Администратор успешно вызван");
	  file_get_contents("http://62.32.66.249/index.php?send&name=".urlencode($users2['name'])."&name2=".urlencode($user['name'])."&id=".urlencode($users2['vk_id']));
	}
}
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

:root{
  --bg-grad-start:#111;
  --bg-grad-end:#1a1a1a;
  --accent:#f5c15d;
  --accent-2:#ff8452;
  --card-bg:rgba(255,255,255,0.05);
  --glass-bg:rgba(255,255,255,0.08);
  --glass-border:rgba(255,255,255,0.12);
  --text:#fff;
  --muted:#c2c2c2;
  --radius:16px;
  --secondary-bg:rgba(255,255,255,0.03);
  --item-hover:rgba(255,255,255,0.15);
  --panel-shadow:0 8px 24px rgba(0,0,0,0.55);
}

*,*::before,*::after{box-sizing:border-box;}
a{color:inherit;text-decoration:none;}

html,body{
  margin:0;
  padding:0;
  width:100%;
  min-height:100%;
  color:var(--text);
  font-family:'Inter', Arial, sans-serif;
  background:linear-gradient(135deg,var(--bg-grad-start),var(--bg-grad-end));
}

.main-wrapper{
  width:100%;
  max-width:600px;
  margin:auto;
  padding:clamp(8px,2vw,18px);
}

.content-container{
  background:var(--card-bg);
  border:1px solid var(--glass-border);
  border-radius:var(--radius);
  padding:12px;
  backdrop-filter:blur(10px);
  margin-bottom:18px;
  box-shadow:var(--panel-shadow);
}

h2 {
  text-align:center;
  color:var(--accent);
  margin-bottom:20px;
  font-weight:700;
  font-size:24px;
}

.divider{
  height:1px;
  width:100%;
  border:none;
  background:linear-gradient(to right, transparent, var(--glass-border), transparent);
  margin:14px 0;
}

.admin-item{
  display:flex;
  align-items:center;
  gap:10px;
  padding:10px;
  background:var(--glass-bg);
  border:1px solid var(--glass-border);
  border-radius:12px;
  margin-bottom:10px;
  transition:all .3s ease;
}

.admin-item:hover{
  background:rgba(255,255,255,0.15);
  transform:translateY(-2px);
}

.admin-icon{
  display:flex;
  justify-content:center;
  align-items:center;
  width:30px;
}

.admin-name{
  flex:1;
  font-weight:500;
  font-size:16px;
}

.admin-level{
  background:rgba(0,0,0,0.2);
  padding:2px 8px;
  border-radius:8px;
  font-weight:600;
  font-size:14px;
  text-align:center;
  margin:0 5px;
}

.admin-call{
  margin-left:auto;
}

.online{
  color:#2ecc71;
}

.online::before{
  content:'•';
  margin-right:5px;
  font-size:1.5em;
  line-height:0;
  position:relative;
  top:3px;
}

.offline{
  color:var(--muted);
}

.call-button{
  background:var(--secondary-bg);
  color:var(--text);
  border:1px solid var(--glass-border);
  padding:6px 10px;
  border-radius:var(--radius);
  cursor:pointer;
  font-weight:600;
  font-size:14px;
  transition:all .3s ease;
  display:inline-block;
}

.call-button:hover{
  background:var(--item-hover);
  transform:translateY(-2px);
}

@media (max-width: 768px) {
  .admin-name{
    font-size:14px;
  }
  
  .admin-level{
    padding:2px 6px;
    font-size:12px;
  }
  
  .call-button{
    padding:4px 8px;
    font-size:12px;
  }
}
</style>

<div class="main-wrapper">
    <div class="content-container">
        <h2>Модераторы</h2>
        
        <?php for($i = 0; $i < count($moder); $i++): 
            $online_status = $moder[$i]['online'] > time()-60 ? "online" : "offline";
            $icon = $moder[$i]['side'] == 0 || $moder[$i]['side'] == 1 ? 
                '<img width="19px" height="19px" src="/img/icon/icoevil.png" alt="">' : 
                '<img width="19px" height="19px" src="/img/icon/icogood.png" alt="">';
        ?>
        <div class="admin-item">
            <div class="admin-icon">
                <?= $icon; ?>
            </div>
            <div class="admin-name">
                <a onclick="showContent('/profile/<?= $moder[$i]['id']; ?>')" class="<?= $online_status; ?>">
                    <?= $moder[$i]['name']; ?>
                </a>
            </div>
            <div class="admin-level">
                <?= $moder[$i]['level']; ?>
            </div>
            <div class="admin-call">
                <a onclick="showContent('/list_admin_moder.php?send&id2=<?= $moder[$i]['id']; ?>&id=<?= $user['id']; ?>')" class="call-button">
                    <i class="fas fa-bell"></i> Позвать
                </a>
            </div>
        </div>
        <?php endfor; ?>
        
        <?php if(count($moder) == 0): ?>
            <div class="empty-message">Нет модераторов</div>
        <?php endif; ?>
    </div>
    
    <div class="content-container">
        <h2>Администрация</h2>
        
        <?php for($i = 0; $i < count($admin); $i++): 
            $online_status = $admin[$i]['online'] > time()-60 ? "online" : "offline";
            $icon = $admin[$i]['side'] == 0 || $admin[$i]['side'] == 1 ? 
                '<img width="19px" height="19px" src="/img/icon/icoevil.png" alt="">' : 
                '<img width="19px" height="19px" src="/img/icon/icogood.png" alt="">';
        ?>
        <div class="admin-item">
            <div class="admin-icon">
                <?= $icon; ?>
            </div>
            <div class="admin-name">
                <a onclick="showContent('/profile/<?= $admin[$i]['id']; ?>')" class="<?= $online_status; ?>">
                    <?= $admin[$i]['name']; ?>
                </a>
            </div>
            <div class="admin-level">
                <?= $admin[$i]['level']; ?>
            </div>
            <div class="admin-call">
                <a onclick="showContent('/list_admin_moder.php?send&id2=<?= $admin[$i]['id']; ?>&id=<?= $user['id']; ?>')" class="call-button">
                    <i class="fas fa-bell"></i> Позвать
                </a>
            </div>
        </div>
        <?php endfor; ?>
        
        <?php if(count($admin) == 0): ?>
            <div class="empty-message">Нет администраторов</div>
        <?php endif; ?>
    </div>
</div>

<?php
$footval = 'friends';
include 'system/foot/foot.php';
?>
        