/**
 * Equipment Module JavaScript
 * Adds interactivity and modern features to the equipment module
 */

document.addEventListener('DOMContentLoaded', function() {
    // Инициализация модуля экипировки
    initEquipmentModule();
});

/**
 * Инициализация функций для модуля экипировки
 */
function initEquipmentModule() {
    // Добавление обработчиков для карточек предметов
    initItemCards();
    
    // Обработчики для быстрых действий
    initQuickActions();
    
    // Анимации и эффекты
    initAnimations();
    
    // Обработка жестов на мобильных устройствах
    initMobileGestures();
}

/**
 * Инициализация интерактивности карточек предметов
 */
function initItemCards() {
    // Добавляем обработчики для предметов
    const itemCards = document.querySelectorAll('.equipment-item');
    
    itemCards.forEach(card => {
        // Добавляем эффект при наведении
        card.addEventListener('mouseenter', function() {
            this.classList.add('item-hover-effect');
        });
        
        card.addEventListener('mouseleave', function() {
            this.classList.remove('item-hover-effect');
        });
        
        // Двойной тап для быстрого использования предмета (на мобильных)
        let tapCount = 0;
        let tapTimer;
        
        card.addEventListener('touchstart', function() {
            tapCount++;
            
            if (tapCount === 1) {
                tapTimer = setTimeout(function() {
                    tapCount = 0;
                }, 300);
            } else {
                clearTimeout(tapTimer);
                // Эмулируем клик по кнопке "Надеть" или "Снять"
                const actionBtn = this.querySelector('.action-button');
                if (actionBtn) {
                    actionBtn.click();
                }
                tapCount = 0;
            }
        });
    });
}

/**
 * Инициализация быстрых действий
 */
function initQuickActions() {
    // Кнопка "Снять все" в разделе экипировки
    const removeAllButton = document.getElementById('remove-all-equipment');
    if (removeAllButton) {
        removeAllButton.addEventListener('click', function() {
            if (confirm('Вы уверены, что хотите снять всё снаряжение?')) {
                // Получаем все ID экипированных предметов
                const equippedItems = document.querySelectorAll('.equipped');
                const itemIds = Array.from(equippedItems).map(item => {
                    return item.dataset.itemId;
                });
                
                // Последовательно снимаем все предметы
                removeEquipmentBatch(itemIds);
            }
        });
    }
}

/**
 * Функция для массового снятия предметов
 */
function removeEquipmentBatch(itemIds) {
    if (itemIds.length === 0) return;
    
    const id = itemIds[0];
    const remaining = itemIds.slice(1);
    
    // Создаем запрос для снятия предмета
    fetch(`/equip.php?ids=${id}&dress=1&ajax=1`)
        .then(response => response.json())
        .then(data => {
            // Если успешно, обновляем UI
            if (data.success) {
                const itemElement = document.querySelector(`[data-item-id="${id}"]`);
                if (itemElement) {
                    itemElement.classList.remove('equipped');
                }
            }
            
            // Переходим к следующему предмету
            if (remaining.length > 0) {
                setTimeout(() => {
                    removeEquipmentBatch(remaining);
                }, 300);
            } else {
                // Если все предметы сняты, обновляем страницу
                showContent('/equip.php');
            }
        })
        .catch(error => {
            console.error('Ошибка при снятии предмета:', error);
            // Продолжаем со следующим предметом
            if (remaining.length > 0) {
                setTimeout(() => {
                    removeEquipmentBatch(remaining);
                }, 300);
            }
        });
}

/**
 * Инициализация анимаций
 */
function initAnimations() {
    // Анимация прогресс-бара износа
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(bar => {
        const value = parseFloat(bar.getAttribute('aria-valuenow'));
        const max = parseFloat(bar.getAttribute('aria-valuemax'));
        const percent = (value / max) * 100;
        
        // Устанавливаем начальную ширину 0 и затем анимируем
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = percent + '%';
        }, 100);
        
        // Устанавливаем цвет в зависимости от значения
        if (percent < 30) {
            bar.style.backgroundColor = '#dc3545'; // Красный
        } else if (percent < 70) {
            bar.style.backgroundColor = '#ffc107'; // Желтый
        } else {
            bar.style.backgroundColor = '#28a745'; // Зеленый
        }
    });
}

/**
 * Инициализация жестов для мобильных устройств
 */
function initMobileGestures() {
    if (typeof Hammer !== 'undefined') {
        // Обработка свайпов для предметов в инвентаре
        const equipmentItems = document.querySelectorAll('.equipment-item');
        
        equipmentItems.forEach(item => {
            const hammer = new Hammer(item);
            
            // Свайп влево - снять предмет (если надет)
            hammer.on('swipeleft', function() {
                if (item.classList.contains('equipped')) {
                    const removeLink = item.querySelector('[data-action="remove"]');
                    if (removeLink) {
                        removeLink.click();
                    }
                }
            });
            
            // Свайп вправо - надеть предмет (если не надет)
            hammer.on('swiperight', function() {
                if (!item.classList.contains('equipped')) {
                    const equipLink = item.querySelector('[data-action="equip"]');
                    if (equipLink) {
                        equipLink.click();
                    }
                }
            });
        });
        
        // Добавление жеста "pull-to-refresh" для обновления списка
        const equipContainer = document.querySelector('.equip_container');
        if (equipContainer) {
            let startY = 0;
            let pullDistance = 0;
            const refreshThreshold = 80; // Пикселей для триггера обновления
            let isPulling = false;
            
            // Создаем индикатор обновления
            const refreshIndicator = document.createElement('div');
            refreshIndicator.className = 'refresh-indicator';
            refreshIndicator.innerHTML = '<div class="refresh-spinner"></div><div>Потяните для обновления</div>';
            refreshIndicator.style.cssText = `
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 60px;
                display: flex;
                justify-content: center;
                align-items: center;
                transform: translateY(-100%);
                transition: transform 0.3s ease;
                background-color: rgba(255, 255, 255, 0.9);
                z-index: 100;
                font-size: 14px;
            `;
            
            equipContainer.style.position = 'relative';
            equipContainer.insertBefore(refreshIndicator, equipContainer.firstChild);
            
            // Обработчики событий
            equipContainer.addEventListener('touchstart', function(e) {
                if (window.pageYOffset === 0) {
                    startY = e.touches[0].clientY;
                    isPulling = true;
                }
            }, { passive: true });
            
            equipContainer.addEventListener('touchmove', function(e) {
                if (!isPulling) return;
                
                pullDistance = e.touches[0].clientY - startY;
                
                if (pullDistance > 0) {
                    e.preventDefault();
                    refreshIndicator.style.transform = `translateY(-${100 - Math.min(pullDistance / 2, 100)}%)`;
                    
                    if (pullDistance > refreshThreshold) {
                        refreshIndicator.querySelector('div:last-child').textContent = 'Отпустите для обновления';
                    } else {
                        refreshIndicator.querySelector('div:last-child').textContent = 'Потяните для обновления';
                    }
                }
            }, { passive: false });
            
            equipContainer.addEventListener('touchend', function() {
                if (!isPulling) return;
                
                if (pullDistance > refreshThreshold) {
                    refreshIndicator.style.transform = 'translateY(0)';
                    refreshIndicator.querySelector('div:last-child').textContent = 'Обновление...';
                    
                    // Запрос на обновление
                    setTimeout(function() {
                        showContent(window.location.pathname + window.location.search);
                    }, 1000);
                } else {
                    refreshIndicator.style.transform = 'translateY(-100%)';
                }
                
                isPulling = false;
                pullDistance = 0;
            }, { passive: true });
        }
    }
}

/**
 * Добавляет функцию быстрого поиска по инвентарю
 */
function initSearchFilter() {
    const searchInput = document.getElementById('equipment-search');
    if (!searchInput) return;
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const items = document.querySelectorAll('.equipment-item');
        
        items.forEach(item => {
            const itemName = item.querySelector('.equipment-name').textContent.toLowerCase();
            
            if (itemName.includes(searchTerm)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
}

/**
 * Функция для сортировки инвентаря
 */
function sortInventory(sortBy) {
    const container = document.querySelector('.equipment-list');
    if (!container) return;
    
    const items = Array.from(container.querySelectorAll('.equipment-item'));
    
    // Сначала сортируем элементы
    items.sort((a, b) => {
        // Одетые элементы всегда вверху
        const aEquipped = a.classList.contains('equipped');
        const bEquipped = b.classList.contains('equipped');
        
        if (aEquipped && !bEquipped) return -1;
        if (!aEquipped && bEquipped) return 1;
        
        // Затем применяем выбранную сортировку
        switch (sortBy) {
            case 'name':
                const aName = a.querySelector('.equipment-name').textContent;
                const bName = b.querySelector('.equipment-name').textContent;
                return aName.localeCompare(bName);
                
            case 'level':
                const aLevel = parseInt(a.dataset.level || '0');
                const bLevel = parseInt(b.dataset.level || '0');
                return bLevel - aLevel; // От высокого к низкому
                
            case 'style':
                const aStyle = parseInt(a.dataset.style || '0');
                const bStyle = parseInt(b.dataset.style || '0');
                return aStyle - bStyle;
                
            default:
                return 0;
        }
    });
    
    // Затем переупорядочиваем DOM
    items.forEach(item => {
        container.appendChild(item);
    });
}

/**
 * Динамическая загрузка дополнительных библиотек при необходимости
 */
function loadExternalLibrary(libraryUrl, callback) {
    const script = document.createElement('script');
    script.src = libraryUrl;
    script.onload = callback;
    document.head.appendChild(script);
}

// Проверяем доступность необходимых библиотек и при необходимости загружаем
if (typeof Hammer === 'undefined') {
    loadExternalLibrary('https://hammerjs.github.io/dist/hammer.min.js', initMobileGestures);
}

// Функция для обновления стиля снаряжения
function updateEquipmentStyle() {
    const equippedItems = document.querySelectorAll('.equipped');
    const styles = {};
    
    // Подсчитываем количество каждого стиля
    equippedItems.forEach(item => {
        const style = item.dataset.style;
        if (style && style !== '0') {
            styles[style] = (styles[style] || 0) + 1;
        }
    });
    
    // Определяем доминирующий стиль
    let dominantStyle = '0';
    let maxCount = 0;
    
    for (const style in styles) {
        if (styles[style] > maxCount) {
            maxCount = styles[style];
            dominantStyle = style;
        }
    }
    
    // Обновляем стиль в DOM
    const styleDisplay = document.querySelector('.equip_style b');
    if (styleDisplay) {
        const styleNames = ['Нет', 'Урон', 'Уворот', 'Броня', 'Элита'];
        const styleColors = ['black', 'green', 'blue', 'red', 'yellow'];
        
        if (dominantStyle === '0' || !styleNames[dominantStyle]) {
            styleDisplay.textContent = 'Нет';
            styleDisplay.style.color = '';
        } else if (dominantStyle === '5') {
            styleDisplay.textContent = 'Нарушен!';
            styleDisplay.style.color = '#FF4500';
        } else {
            styleDisplay.textContent = styleNames[dominantStyle];
            styleDisplay.style.color = styleColors[dominantStyle];
        }
    }
}

// Вспомогательная функция для анимации изменений
function animateChange(element, className) {
    element.classList.add(className);
    setTimeout(() => {
        element.classList.remove(className);
    }, 1000);
}

// Экспорт функций для доступа из глобального контекста
window.EquipmentModule = {
    sortInventory,
    updateEquipmentStyle,
    removeEquipmentBatch
}; 