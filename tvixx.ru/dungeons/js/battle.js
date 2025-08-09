// Обработка боевой системы
document.addEventListener('DOMContentLoaded', function() {
    // Инициализация боевого интерфейса
    initBattleInterface();
    
    // Инициализация обработчиков событий
    initBattleEventHandlers();
    
    // Инициализация анимаций
    initBattleAnimations();
});

// Инициализация боевого интерфейса
function initBattleInterface() {
    // Обновление полос здоровья
    updateHealthBars();
    
    // Обновление статистики
    updateBattleStats();
    
    // Обновление сообщений
    updateBattleMessages();
}

// Инициализация обработчиков событий
function initBattleEventHandlers() {
    // Обработка атаки
    const attackButton = document.querySelector('.btn-attack');
    if (attackButton) {
        attackButton.addEventListener('click', handleAttack);
    }
    
    // Обработка побега
    const fleeButton = document.querySelector('.btn-flee');
    if (fleeButton) {
        fleeButton.addEventListener('click', handleFlee);
    }
    
    // Обработка использования предметов
    const itemButtons = document.querySelectorAll('.battle-item');
    itemButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.dataset.itemId;
            handleBattleItemUse(itemId);
        });
    });
}

// Обработка атаки
function handleAttack() {
    fetch('battle.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=attack'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateBattleState(data);
        } else {
            showBattleMessage(data.message, 'error');
        }
    })
    .catch(error => {
        showBattleMessage('Произошла ошибка при атаке', 'error');
    });
}

// Обработка побега
function handleFlee() {
    fetch('battle.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=flee'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.redirect) {
                window.location.href = data.redirect;
            } else {
                showBattleMessage(data.message, 'success');
                updateBattleState(data);
            }
        } else {
            showBattleMessage(data.message, 'error');
        }
    })
    .catch(error => {
        showBattleMessage('Произошла ошибка при попытке побега', 'error');
    });
}

// Обработка использования предмета в бою
function handleBattleItemUse(itemId) {
    fetch('battle.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=use_item&item_id=${itemId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showBattleMessage(data.message, 'success');
            updateBattleState(data);
        } else {
            showBattleMessage(data.message, 'error');
        }
    })
    .catch(error => {
        showBattleMessage('Произошла ошибка при использовании предмета', 'error');
    });
}

// Обновление состояния боя
function updateBattleState(data) {
    // Обновление полос здоровья
    updateHealthBars(data.health);
    
    // Обновление статистики
    updateBattleStats(data.stats);
    
    // Обновление сообщений
    updateBattleMessages(data.messages);
    
    // Обновление предметов
    updateBattleItems(data.items);
    
    // Проверка окончания боя
    if (data.battle_ended) {
        handleBattleEnd(data);
    }
}

// Обновление полос здоровья
function updateHealthBars(health) {
    if (health) {
        // Обновление здоровья игрока
        const playerHealth = document.querySelector('.player-health');
        if (playerHealth) {
            const playerHealthBar = playerHealth.querySelector('.health-bar');
            const playerHealthText = playerHealth.querySelector('.health-text');
            
            if (playerHealthBar && playerHealthText) {
                const playerHealthPercent = (health.player.current / health.player.max) * 100;
                playerHealthBar.style.width = `${playerHealthPercent}%`;
                playerHealthText.textContent = `${health.player.current}/${health.player.max}`;
            }
        }
        
        // Обновление здоровья врага
        const enemyHealth = document.querySelector('.enemy-health');
        if (enemyHealth) {
            const enemyHealthBar = enemyHealth.querySelector('.health-bar');
            const enemyHealthText = enemyHealth.querySelector('.health-text');
            
            if (enemyHealthBar && enemyHealthText) {
                const enemyHealthPercent = (health.enemy.current / health.enemy.max) * 100;
                enemyHealthBar.style.width = `${enemyHealthPercent}%`;
                enemyHealthText.textContent = `${health.enemy.current}/${health.enemy.max}`;
            }
        }
    }
}

// Обновление статистики боя
function updateBattleStats(stats) {
    if (stats) {
        Object.entries(stats).forEach(([key, value]) => {
            const element = document.querySelector(`[data-battle-stat="${key}"]`);
            if (element) {
                element.textContent = value;
            }
        });
    }
}

// Обновление сообщений боя
function updateBattleMessages(messages) {
    if (messages) {
        const battleLog = document.querySelector('.battle-log');
        if (battleLog) {
            messages.forEach(message => {
                const messageElement = document.createElement('div');
                messageElement.className = `battle-message battle-message-${message.type}`;
                messageElement.textContent = message.text;
                
                battleLog.appendChild(messageElement);
                battleLog.scrollTop = battleLog.scrollHeight;
                
                setTimeout(() => {
                    messageElement.remove();
                }, 5000);
            });
        }
    }
}

// Обновление предметов в бою
function updateBattleItems(items) {
    if (items) {
        const battleItems = document.querySelector('.battle-items');
        if (battleItems) {
            battleItems.innerHTML = '';
            items.forEach(item => {
                battleItems.appendChild(createBattleItem(item));
            });
        }
    }
}

// Создание элемента предмета в бою
function createBattleItem(item) {
    const element = document.createElement('button');
    element.className = 'battle-item';
    element.dataset.itemId = item.id;
    
    element.innerHTML = `
        <div class="item-icon">
            <i class="fas ${getItemIcon(item.type)}"></i>
        </div>
        <div class="item-info">
            <div class="item-name">${item.name}</div>
            <div class="item-description">${item.description}</div>
        </div>
    `;
    
    return element;
}

// Обработка окончания боя
function handleBattleEnd(data) {
    if (data.victory) {
        showBattleMessage('Победа!', 'success');
        setTimeout(() => {
            window.location.href = data.redirect;
        }, 2000);
    } else {
        showBattleMessage('Поражение!', 'error');
        setTimeout(() => {
            window.location.href = data.redirect;
        }, 2000);
    }
}

// Отображение сообщения в бою
function showBattleMessage(message, type) {
    const battleLog = document.querySelector('.battle-log');
    if (battleLog) {
        const messageElement = document.createElement('div');
        messageElement.className = `battle-message battle-message-${type}`;
        messageElement.textContent = message;
        
        battleLog.appendChild(messageElement);
        battleLog.scrollTop = battleLog.scrollHeight;
        
        setTimeout(() => {
            messageElement.remove();
        }, 5000);
    }
}

// Инициализация анимаций боя
function initBattleAnimations() {
    // Анимация появления элементов
    const elements = document.querySelectorAll('.battle-container, .battle-enemy, .battle-actions');
    elements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        setTimeout(() => {
            element.style.transition = 'all 0.3s ease';
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, 100);
    });
    
    // Анимация при наведении на кнопки
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.2)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 2px 5px rgba(0, 0, 0, 0.1)';
        });
    });
    
    // Анимация полос здоровья
    const healthBars = document.querySelectorAll('.health-bar');
    healthBars.forEach(bar => {
        bar.style.transition = 'width 0.3s ease';
    });
} 