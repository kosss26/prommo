<?php
require_once '../system/func.php';
require_once '../system/header.php';
if (!$user OR $user['access'] < 3) {
    ?><script>showContent("/");</script><?php
    exit;
}
//сброс флага и счетчика
if($user['news'] == 1){
	$mc->query("UPDATE `users` SET `news_all` = '0' WHERE `id` = '".$user['id']."'");
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
    
    .news-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .news-header {
        text-align: center;
        color: var(--accent);
        margin-bottom: 25px;
        font-weight: 700;
        font-size: 28px;
    }
    
    .news-card {
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
    
    .news-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--accent), var(--accent-2));
        border-radius: var(--radius) var(--radius) 0 0;
    }
    
    .news-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .form-label {
        font-weight: 600;
        color: var(--accent);
        font-size: 16px;
    }
    
    .form-input {
        width: 100%;
        padding: 15px;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        color: var(--text);
        border-radius: var(--radius);
        font-size: 15px;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }
    
    .form-input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(245, 193, 93, 0.2);
    }
    
    .form-input.title {
        height: 50px;
    }
    
    .form-input.txt {
        height: 120px;
        resize: vertical;
    }
    
    .button {
        background: var(--primary-gradient);
        color: #111;
        border: none;
        padding: 15px 30px;
        font-size: 16px;
        font-weight: 600;
        border-radius: var(--radius);
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-top: 10px;
        align-self: center;
    }
    
    .button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    
    @media (max-width: 768px) {
        .news-container {
            padding: 0 10px;
        }
    }
    
    @media (max-width: 480px) {
        .form-input.txt {
            height: 150px;
        }
    }
</style>

<div class="news-container">
    <h2 class="news-header">Создание новости</h2>
    
    <div class="news-card">
        <div class="news-form">
            <div class="form-group">
                <label class="form-label">Заголовок</label>
                <input type="text" class="form-input title" name="title">
            </div>
            
            <div class="form-group">
                <label class="form-label">Текст</label>
                <textarea class="form-input txt" name="text"></textarea>
            </div>
            
            <button class="button" onclick="go()">Создать</button>
        </div>
    </div>
</div>

<script>
var scor = 0;
function go(){
	
	var text = $(".txt").val();
	if(scor == 0){
		scor = 1;
		showContent('/admin/new.php?create&title=' + $(".title").val() + '&text=' + $(".txt").val());
	}
}
</script>
<?php
if(isset($_GET['create']) && isset($_GET['title']) && isset($_GET['text'])){
	
	if($mc->query("INSERT INTO `news`("
                    . "`id`,"
                    . " `title`,"
                    . " `text`,"
                    . " `date`"
                    . ")VALUES("
                    . "NULL,"
                    . "'" . $_GET['title'] . "',"
                    . "'" . $_GET['text'] . "',"
                    . "'" . date("d.m.20y") . "'"
                    . ")")){
                    	message("Новость создана");
                    $mc->query("UPDATE `users` SET `news` = '1',`news_all`=`news_all`+1 ");
                    
                    }
	}
 $footval = 'adminindex';
include '../system/foot/foot.php';
?>
