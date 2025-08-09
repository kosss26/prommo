<?php
require_once ('../../system/func.php');
require_once ('../../system/dbc.php');
?>
<style>
    .search_container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        text-align: center;
    }
    
    .search_title {
        font-size: 18px;
        font-weight: bold;
        color: #643201;
        margin-bottom: 20px;
    }
    
    .search_progress {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 150px;
    }
    
    .search_animation {
        display: inline-block;
        position: relative;
        width: 80px;
        height: 80px;
    }
    
    .search_animation:after {
        content: " ";
        display: block;
        border-radius: 50%;
        width: 0;
        height: 0;
        margin: 8px;
        box-sizing: border-box;
        border: 32px solid #a56c2e;
        border-color: #a56c2e transparent #a56c2e transparent;
        animation: search_animation 1.2s infinite;
    }
    
    @keyframes search_animation {
        0% {
            transform: rotate(0);
            animation-timing-function: cubic-bezier(0.55, 0.055, 0.675, 0.19);
        }
        50% {
            transform: rotate(900deg);
            animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
        }
        100% {
            transform: rotate(1800deg);
        }
    }
    
    .found_player {
        background: rgba(255, 215, 0, 0.07);
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        border-left: 3px solid rgba(139, 69, 19, 0.3);
    }
    
    .countdown {
        font-size: 60px;
        font-weight: bold;
        color: #a56c2e;
        margin: 20px 0;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        animation: pulse 1s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 0.8; }
        50% { opacity: 1; transform: scale(1.05); }
        100% { opacity: 0.8; }
    }
    
    .battle_ready {
        color: #ff5e3a;
        animation: battle_ready 0.5s infinite;
    }
    
    @keyframes battle_ready {
        0% { opacity: 0.8; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.1); }
        100% { opacity: 0.8; transform: scale(1); }
    }
    
    .not_found {
        color: #ff5e3a;
        font-style: italic;
    }
    
    @media (max-width: 768px) {
        .search_container {
            padding: 15px;
        }
        
        .countdown {
            font-size: 50px;
        }
    }
    
    @media (max-width: 480px) {
        .search_title {
            font-size: 16px;
        }
        
        .countdown {
            font-size: 40px;
        }
    }
</style>

<div class="search_container">
    <div id="title" class="search_progress">
        <div class="search_animation"></div>
    </div>
</div>

<script>
	$(document).ready(function(){
		$.ajax({
			url: '/huntb/grab/search_user.php',
			data: 'newGrab',
			type: 'GET',
			success: function(data){
				setTimeout(function(){
				    $('#title').removeClass('search_progress');
				    $('#title').html(data);
				}, 2500);
			},
			beforeSend: function(){
				$('#title').html('<div class="search_title">Поиск противника...</div><div class="search_animation"></div>');
			}
		});
	});
</script>
<?php
$footval = 'grab_huntb_search';
require_once ('../../system/foot/foot.php');
