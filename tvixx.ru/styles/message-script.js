/* JavaScript для динамического применения стилей к информационным окнам */
$(document).ready(function() {
    // Применение стилей к обычным информационным окнам
    function styleMessages() {
        $('.msg').css('backgroundColor', 'rgba(0,0,0,0.7)');
        $('.msg div[style*="background-color: #FFFFCC"]').css({
            'backgroundColor': 'transparent',
            'background': 'linear-gradient(135deg, #111, #1a1a1a)',
            'border': '1px solid rgba(255,255,255,0.12)',
            'borderRadius': '16px',
            'boxShadow': '0 10px 30px rgba(0, 0, 0, 0.25)',
            'color': '#fff',
            'backdropFilter': 'blur(10px)',
            '-webkit-backdropFilter': 'blur(10px)'
        });
        $('.text_msg').css({
            'color': '#fff',
            'fontFamily': '"Inter", sans-serif'
        });
        $('.msg .button_alt_01').css({
            'background': 'linear-gradient(135deg, #f5c15d, #ff8452)',
            'color': '#111',
            'borderRadius': '16px',
            'marginBottom': '10px',
            'fontWeight': '600',
            'border': 'none'
        });
    }

    // Применение стилей при появлении новых окон
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes && mutation.addedNodes.length > 0) {
                for (let i = 0; i < mutation.addedNodes.length; i++) {
                    const node = mutation.addedNodes[i];
                    if (node.classList && node.classList.contains('msg')) {
                        styleMessages();
                    }
                }
            }
        });
    });

    // Конфигурация observer
    const config = { childList: true, subtree: true };
    
    // Запуск observer
    observer.observe(document.body, config);

    // Первоначальное применение стилей
    styleMessages();
}); 