<?php
require_once '../../system/func.php';
require_once '../../system/dbc.php';
ob_start();

$page = isset($_GET['list']) ? (int)$_GET['list'] - 1 : 0;

if (isset($user['level']) && $user['level'] < 2) {
    ?>
    <script>showContent("/main.php?msg=" + decodeURI("Не доступно до 2 уровня ."));</script>
    <?php
    exit(0);
}

$page_num = $mc->query("SELECT COUNT(*) FROM `users` WHERE `tur_reit` > '0'")->fetch_row()[0];
$arrall = $mc->query("SELECT * FROM `users` WHERE `tur_reit` > '0' ORDER BY `tur_reit` DESC LIMIT " . ($page * 10) . ", 10")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Стена славы - Mobitva v1.0</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#111">
    <meta name="author" content="Kalashnikov"/>
    <link rel="shortcut icon" href="/favicon.ico"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<style>
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
    --table-header: rgba(255,255,255,0.1);
    --table-row-alt: rgba(255,255,255,0.02);
    --table-row-hover: rgba(255,255,255,0.07);
    --team1-color: #e74c3c;
    --team2-color: #3498db;
}

body {
    margin: 0;
    padding: 0;
    width: 100%;
    min-height: 100%;
    color: var(--text);
    font-family: 'Inter', sans-serif;
    background: linear-gradient(135deg, var(--bg-grad-start), var(--bg-grad-end));
}

.battle-arena {
    max-width: 800px;
    margin: 15px auto;
    padding: 0 15px;
    animation: fadeIn 0.5s ease-out;
}

.battle-header {
    text-align: center;
    padding: 15px;
    margin-bottom: 20px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(8px);
}

.battle-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--accent);
    display: flex;
    align-items: center;
    justify-content: center;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.battle-title i {
    margin-right: 10px;
}

.battle-ranking {
    background: var(--card-bg);
    border-radius: var(--radius);
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    border: 1px solid var(--glass-border);
    margin-bottom: 25px;
    overflow: hidden;
    backdrop-filter: blur(8px);
}

.battle-ranking-table {
    width: 100%;
    border-collapse: collapse;
}

.battle-ranking-table th,
.battle-ranking-table td {
    padding: 12px;
    text-align: center;
    color: var(--muted);
    font-size: 14px;
}

.battle-ranking-table th {
    background: var(--table-header);
    color: var(--text);
    font-weight: 600;
    text-transform: uppercase;
    font-size: 13px;
    letter-spacing: 0.5px;
}

.battle-ranking-table tr:nth-child(even) {
    background: var(--table-row-alt);
}

.battle-ranking-table tr:hover {
    background: var(--table-row-hover);
}

.battle-ranking-table td img {
    vertical-align: middle;
    height: 15px;
    width: 15px;
}

.battle-ranking-table .rank-number {
    font-weight: 600;
    color: var(--accent-2);
}

.battle-ranking-table .player-name {
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    text-align: left;
}

.battle-ranking-table .player-self {
    color: var(--accent);
    font-weight: 600;
}

.battle-ranking-table .player-rating {
    font-weight: 600;
    color: var(--text);
}

.battle-pagination {
    text-align: center;
    padding: 15px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius);
    backdrop-filter: blur(8px);
}

.battle-pagination a {
    margin: 0 7px;
    font-size: 16px;
    color: var(--muted);
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-block;
    padding: 2px 8px;
}

.battle-pagination a:hover {
    color: var(--accent);
    transform: translateY(-2px);
}

.battle-pagination .current {
    font-size: 16px;
    color: var(--accent);
    font-weight: 600;
    display: inline-block;
    padding: 2px 8px;
    border-radius: 4px;
    background: var(--glass-bg);
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 768px) {
    .battle-arena {
        padding: 0 10px;
    }
    
    .battle-header {
        padding: 12px;
    }
    
    .battle-title {
        font-size: 16px;
    }
    
    .battle-ranking {
        padding: 15px;
    }
    
    .battle-ranking-table th,
    .battle-ranking-table td {
        padding: 10px;
        font-size: 13px;
    }
    
    .battle-pagination a {
        font-size: 15px;
        margin: 0 5px;
    }
}

@media (max-width: 480px) {
    .battle-header {
        padding: 10px;
    }
    
    .battle-title {
        font-size: 14px;
    }
    
    .battle-ranking {
        padding: 10px;
    }
    
    .battle-ranking-table th,
    .battle-ranking-table td {
        padding: 8px;
        font-size: 12px;
    }
    
    .battle-ranking-table td:nth-child(3) {
        max-width: 100px;
    }
    
    .battle-pagination a {
        font-size: 14px;
        margin: 0 3px;
        padding: 2px 6px;
    }
}
</style>
</head>
<body>

<div class="battle-arena">
    <div class="battle-header">
        <div class="battle-title"><i class="fas fa-trophy"></i> Стена славы</div>
    </div>

    <div class="battle-ranking">
        <table class="battle-ranking-table">
            <tr>
                <th>#</th>
                <th>Сторона</th>
                <th>Имя</th>
                <th>Рейтинг</th>
            </tr>
            <?php for ($i = $page * 10, $i2 = 0; $i < ($page * 10) + count($arrall); $i++, $i2++): ?>
                <tr>
                    <td class="rank-number"><?php echo $i + 1; ?></td>
                    <td>
                        <?php if($arrall[$i2]['side'] == 0 || $arrall[$i2]['side'] == 1): ?>
                            <i class="fas fa-skull" style="color: var(--team1-color);"></i>
                        <?php else: ?>
                            <i class="fas fa-shield-alt" style="color: var(--team2-color);"></i>
                        <?php endif; ?>
                    </td>
                    <td class="player-name <?php echo $arrall[$i2]['id'] == $user['id'] ? 'player-self' : ''; ?>">
                        <?php echo htmlspecialchars($arrall[$i2]['name']); ?>
                    </td>
                    <td class="player-rating"><?php echo htmlspecialchars($arrall[$i2]['tur_reit']); ?></td>
                </tr>
            <?php endfor; ?>
        </table>
    </div>

    <div class="battle-pagination">
        <?php echo pagination("huntb/tur/slava.php?", $page + 1, ceil($page_num / 10)); ?>
    </div>
</div>

<?php
// Функция пагинации
function pagination($href, $strlist, $maxstr) {
    $output = "";
    if ($strlist > 4) {
        $output .= "<a onclick=\"showContent('/$href&list=1');\">1</a> .. ";
    } elseif ($strlist == 4) {
        $output .= "<a onclick=\"showContent('/$href&list=1');\">1</a>";
    }

    for ($i = -2; $i <= 3; $i++) {
        $strnm = $i + $strlist;
        if ($strnm > 0 && $strnm <= $maxstr) {
            if ($i == 0) {
                $output .= "<span class='current'>$strlist</span>";
            } else {
                $output .= "<a onclick=\"showContent('/$href&list=$strnm');\">$strnm</a>";
            }
        }
    }

    if ($strlist < $maxstr - 3) {
        $output .= " .. <a onclick=\"showContent('/$href&list=$maxstr');\">$maxstr</a>";
    } elseif ($strlist == $maxstr - 3) {
        $output .= "<a onclick=\"showContent('/$href&list=$maxstr');\">$maxstr</a>";
    }

    return $output;
}

$footval = "slava_huntb";
require_once ('../../system/foot/foot.php');
ob_end_flush();
?>
