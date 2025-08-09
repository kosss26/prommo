document.addEventListener('DOMContentLoaded', () => {
    // Асинхронная загрузка контента
    window.loadContent = function(url) {
        fetch(url, { credentials: 'include' })
            .then(response => response.text())
            .then(data => {
                const container = document.querySelector('.content-container');
                container.innerHTML = data;
                container.classList.add('fade-in');
                setTimeout(() => container.classList.remove('fade-in'), 500);
            })
            .catch(error => console.error('Ошибка загрузки:', error));
    };

    // Эффект снега (заглушка, замените на реальную реализацию)
    window.snowAppend = function(element) {
        console.log('Snow effect applied to:', element);
        // Пример: particles.js или canvas-анимация снега
    };

    // Заглушка для NewFuckOff
    window.NewFuckOff = function() {
        alert('Вы были перемещены!');
    };

    // Анимация кнопок при клике
    document.querySelectorAll('.action-button, .notification-item, .menu-item').forEach(item => {
        item.addEventListener('click', () => {
            item.classList.add('clicked');
            setTimeout(() => item.classList.remove('clicked'), 200);
        });
    });
});