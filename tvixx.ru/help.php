<?php
require_once('system/func.php');
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
        padding: 8px;
    }
    
    .help-container {
        max-width: 800px;
        width: 100%;
        margin: 0 auto;
        box-sizing: border-box;
    }
    
    .help-card {
        background: var(--card-bg);
        border-radius: var(--radius);
        border: 1px solid var(--glass-border);
        overflow: hidden;
        box-shadow: var(--panel-shadow);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        margin-bottom: 20px;
        position: relative;
        width: 100%;
        box-sizing: border-box;
    }
    
    .help-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
        border-radius: var(--radius) var(--radius) 0 0;
    }
    
    .help-header {
        color: var(--accent);
        font-size: 1.2em;
        padding: 15px;
        text-align: center;
        font-weight: 700;
        border-bottom: 1px solid var(--glass-border);
    }
    
    .help-content {
        padding: 15px;
        line-height: 1.6;
        color: var(--text);
    }
    
    .help-links {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 10px 15px;
    }
    
    .help-link {
        text-align: center;
        margin: 5px 0;
    }
    
    .help-link a {
        display: block;
        padding: 12px 15px;
        color: var(--text);
        cursor: pointer;
        border: 1px solid var(--glass-border);
        border-radius: var(--radius);
        background: var(--secondary-bg);
        transition: all 0.3s ease;
        text-decoration: none;
        font-weight: 500;
    }
    
    .help-link a:hover {
        background: var(--item-hover);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .help-text {
        margin-top: 15px;
        line-height: 1.6;
        color: var(--text);
        padding: 5px;
    }
    
    /* Адаптивные стили */
    @media (max-width: 768px) {
        body {
            padding: 0;
        }
        
        .help-container {
            padding: 0;
        }
        
        .help-card {
            border-radius: 0;
            margin-bottom: 0;
        }
    }
</style>

<div class="help-container">
    <div class="help-card">
        <?php
        $myPath = "";

        if (isset($_GET['path'])) {
            $myPath = $_GET['path'];
        }
        $allname = explode("/", $myPath);
        $predname = $allname[count($allname) - 1];
        ?>
        
        <div class="help-header">
            <?php echo $predname == "" ? "Информация" : $predname; ?>
        </div>
        
        <div class="help-content">
            <div class="help-links">
                <?php
                $AddName = array();
                $result = $mc->query("SELECT * FROM `support` WHERE `path` LIKE '$myPath%' AND `name` != '' AND `name` IS NOT NULL");
                while ($help = $result->fetch_array(MYSQLI_ASSOC)) {
                    $pathexpl2 = explode($myPath . "/", $help['path']);
                    $pathexpl2[0] = "";
                    $pathexpl2 = implode("/", $pathexpl2);

                    $pathexpl = explode("/", $pathexpl2);
                    if (count($pathexpl) > 2) {
                        if (!in_array($pathexpl[1], $AddName)) {
                            echo "<div class='help-link'><a onclick=\"showContent('/help.php?path=" 
                                 . $myPath . "/" . $pathexpl[1] . "')\">" . $pathexpl[1] . "</a></div>";
                        }
                        array_push($AddName, $pathexpl[1]);
                    } else {
                        echo "<div class='help-link'><a onclick=\"showContent('/help.php?path=" 
                             . $myPath . "/" . $help['name'] . "&id=" . $help['id'] . "/')\">" 
                             . $help['name'] . "</a></div>";
                        array_push($AddName, $help['name']);
                    }
                }
                ?>
            </div>

            <?php if (isset($_GET['id'])): ?>
                <div class="help-text">
                    <?php 
                    $hel = $mc->query("SELECT * FROM `support` WHERE `id` = '" . $_GET['id'] . "'")
                              ->fetch_array(MYSQLI_ASSOC);
                    echo $hel['text']; 
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$footval = "help";
require_once('system/foot/foot.php');
?>