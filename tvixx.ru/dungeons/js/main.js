// Обработка взаимодействий с объектами
document.addEventListener('DOMContentLoaded', function() {
    // Инициализация модальных окон
    initModals();
    
    // Инициализация обработчиков событий
    initEventHandlers();
    
    // Инициализация анимаций
    initAnimations();
});

// Инициализация модальных окон
function initModals() {
    const modals = document.querySelectorAll('.modal');
    const closeButtons = document.querySelectorAll('.modal-close');
    
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modal = this.closest('.modal');
            closeModal(modal);
        });
    });
    
    // Закрытие модального окна при клике вне его
    modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this);
            }
        });
    });
}

// Открытие модального окна
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
}

// Закрытие модального окна
function closeModal(modal) {
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Инициализация обработчиков событий
function initEventHandlers() {
    // Обработка взаимодействий с объектами
    const objectCards = document.querySelectorAll('.object-card');
    objectCards.forEach(card => {
        card.addEventListener('click', function() {
            const objectId = this.dataset.objectId;
            const objectType = this.dataset.objectType;
            
            handleObjectInteraction(objectId, objectType);
        });
    });
    
    // Обработка использования предметов
    const inventoryItems = document.querySelectorAll('.inventory-item');
    inventoryItems.forEach(item => {
        item.addEventListener('click', function() {
            const itemId = this.dataset.itemId;
            handleItemUse(itemId);
        });
    });
    
    // Обработка перемещения между комнатами
    const exitButtons = document.querySelectorAll('.exit-btn');
    exitButtons.forEach(button => {
        button.addEventListener('click', function() {
            const direction = this.dataset.direction;
            handleRoomChange(direction);
        });
    });
}

// Обработка взаимодействия с объектом
function handleObjectInteraction(objectId, objectType) {
    switch(objectType) {
        case 'chest':
            handleChestInteraction(objectId);
            break;
        case 'fountain':
            handleFountainInteraction(objectId);
            break;
        case 'monster':
            handleMonsterInteraction(objectId);
            break;
        case 'puzzle':
            handlePuzzleInteraction(objectId);
            break;
        case 'trap':
            handleTrapInteraction(objectId);
            break;
        case 'boss':
            handleBossInteraction(objectId);
            break;
    }
}

// Обработка взаимодействия с сундуком
function handleChestInteraction(objectId) {
    fetch('explore.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=interact&target_id=${objectId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(data.message, 'success');
            updateInventory(data.inventory);
            updateObjectCard(objectId, data.object);
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        showMessage('Произошла ошибка при взаимодействии с сундуком', 'error');
    });
}

// Обработка взаимодействия с фонтаном
function handleFountainInteraction(objectId) {
    fetch('explore.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=interact&target_id=${objectId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(data.message, 'success');
            updateStats(data.stats);
            updateObjectCard(objectId, data.object);
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        showMessage('Произошла ошибка при взаимодействии с фонтаном', 'error');
    });
}

// Обработка взаимодействия с монстром
function handleMonsterInteraction(objectId) {
    fetch('explore.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=interact&target_id=${objectId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.redirect) {
                window.location.href = data.redirect;
            } else {
                showMessage(data.message, 'success');
                updateStats(data.stats);
                updateObjectCard(objectId, data.object);
            }
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        showMessage('Произошла ошибка при взаимодействии с монстром', 'error');
    });
}

// Обработка взаимодействия с головоломкой
function handlePuzzleInteraction(objectId) {
    fetch('explore.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=interact&target_id=${objectId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            openModal('puzzle-modal');
            updatePuzzleModal(data.puzzle);
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        showMessage('Произошла ошибка при взаимодействии с головоломкой', 'error');
    });
}

// Обработка взаимодействия с ловушкой
function handleTrapInteraction(objectId) {
    fetch('explore.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=interact&target_id=${objectId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(data.message, 'success');
            updateStats(data.stats);
            updateObjectCard(objectId, data.object);
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        showMessage('Произошла ошибка при взаимодействии с ловушкой', 'error');
    });
}

// Обработка взаимодействия с боссом
function handleBossInteraction(objectId) {
    fetch('explore.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=interact&target_id=${objectId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.redirect) {
                window.location.href = data.redirect;
            } else {
                showMessage(data.message, 'success');
                updateStats(data.stats);
                updateObjectCard(objectId, data.object);
            }
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        showMessage('Произошла ошибка при взаимодействии с боссом', 'error');
    });
}

// Обработка использования предмета
function handleItemUse(itemId) {
    fetch('explore.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=use_item&item_id=${itemId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(data.message, 'success');
            updateInventory(data.inventory);
            updateStats(data.stats);
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        showMessage('Произошла ошибка при использовании предмета', 'error');
    });
}

// Обработка перемещения между комнатами
function handleRoomChange(direction) {
    fetch('explore.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=move&direction=${direction}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateRoomContent(data.room);
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        showMessage('Произошла ошибка при перемещении', 'error');
    });
}

// Обновление содержимого комнаты
function updateRoomContent(roomData) {
    // Обновление описания комнаты
    const roomDescription = document.querySelector('.room-description');
    if (roomDescription) {
        roomDescription.textContent = roomData.description;
    }
    
    // Обновление объектов комнаты
    const roomObjects = document.querySelector('.room-objects');
    if (roomObjects) {
        roomObjects.innerHTML = '';
        roomData.objects.forEach(object => {
            roomObjects.appendChild(createObjectCard(object));
        });
    }
    
    // Обновление выходов
    const roomExits = document.querySelector('.room-exits');
    if (roomExits) {
        roomExits.innerHTML = '';
        roomData.exits.forEach(exit => {
            roomExits.appendChild(createExitButton(exit));
        });
    }
    
    // Обновление статистики
    updateStats(roomData.stats);
}

// Создание карточки объекта
function createObjectCard(object) {
    const card = document.createElement('div');
    card.className = 'object-card';
    card.dataset.objectId = object.id;
    card.dataset.objectType = object.type;
    
    card.innerHTML = `
        <div class="object-icon">
            <i class="fas ${getObjectIcon(object.type)}"></i>
        </div>
        <div class="object-name">${object.name}</div>
        <div class="object-description">${object.description}</div>
    `;
    
    return card;
}

// Создание кнопки выхода
function createExitButton(exit) {
    const button = document.createElement('button');
    button.className = 'exit-btn';
    button.dataset.direction = exit.direction;
    
    button.innerHTML = `
        <i class="fas ${getExitIcon(exit.direction)}"></i>
        ${exit.name}
    `;
    
    return button;
}

// Получение иконки объекта
function getObjectIcon(type) {
    const icons = {
        chest: 'fa-treasure-chest',
        fountain: 'fa-fountain',
        monster: 'fa-skull',
        puzzle: 'fa-puzzle-piece',
        trap: 'fa-exclamation-triangle',
        boss: 'fa-crown'
    };
    
    return icons[type] || 'fa-question';
}

// Получение иконки выхода
function getExitIcon(direction) {
    const icons = {
        north: 'fa-arrow-up',
        south: 'fa-arrow-down',
        east: 'fa-arrow-right',
        west: 'fa-arrow-left'
    };
    
    return icons[direction] || 'fa-question';
}

// Обновление инвентаря
function updateInventory(inventory) {
    const inventoryList = document.querySelector('.inventory-list');
    if (inventoryList) {
        inventoryList.innerHTML = '';
        inventory.forEach(item => {
            inventoryList.appendChild(createInventoryItem(item));
        });
    }
}

// Создание элемента инвентаря
function createInventoryItem(item) {
    const element = document.createElement('div');
    element.className = 'inventory-item';
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

// Получение иконки предмета
function getItemIcon(type) {
    const icons = {
        weapon: 'fa-sword',
        armor: 'fa-shield',
        potion: 'fa-flask',
        scroll: 'fa-scroll'
    };
    
    return icons[type] || 'fa-question';
}

// Обновление статистики
function updateStats(stats) {
    Object.entries(stats).forEach(([key, value]) => {
        const element = document.querySelector(`[data-stat="${key}"]`);
        if (element) {
            element.textContent = value;
        }
    });
}

// Обновление карточки объекта
function updateObjectCard(objectId, object) {
    const card = document.querySelector(`[data-object-id="${objectId}"]`);
    if (card) {
        card.innerHTML = `
            <div class="object-icon">
                <i class="fas ${getObjectIcon(object.type)}"></i>
            </div>
            <div class="object-name">${object.name}</div>
            <div class="object-description">${object.description}</div>
        `;
    }
}

// Обновление модального окна головоломки
function updatePuzzleModal(puzzle) {
    const modal = document.getElementById('puzzle-modal');
    if (modal) {
        const title = modal.querySelector('.modal-title');
        const body = modal.querySelector('.modal-body');
        const footer = modal.querySelector('.modal-footer');
        
        title.textContent = puzzle.name;
        body.innerHTML = `
            <div class="puzzle-description">${puzzle.description}</div>
            <div class="puzzle-hint">${puzzle.hint}</div>
            <div class="puzzle-input">
                <input type="text" id="puzzle-answer" placeholder="Введите ответ">
            </div>
        `;
        footer.innerHTML = `
            <button class="btn btn-primary" onclick="submitPuzzleAnswer('${puzzle.id}')">
                <i class="fas fa-check"></i>
                Ответить
            </button>
        `;
    }
}

// Отправка ответа на головоломку
function submitPuzzleAnswer(puzzleId) {
    const answer = document.getElementById('puzzle-answer').value;
    
    fetch('explore.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=solve_puzzle&puzzle_id=${puzzleId}&answer=${encodeURIComponent(answer)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(data.message, 'success');
            closeModal(document.getElementById('puzzle-modal'));
            updateStats(data.stats);
            updateObjectCard(data.object.id, data.object);
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        showMessage('Произошла ошибка при решении головоломки', 'error');
    });
}

// Отображение сообщения
function showMessage(message, type) {
    const messages = document.querySelector('.messages');
    if (messages) {
        const messageElement = document.createElement('div');
        messageElement.className = `message message-${type}`;
        messageElement.textContent = message;
        
        messages.appendChild(messageElement);
        messages.scrollTop = messages.scrollHeight;
        
        setTimeout(() => {
            messageElement.remove();
        }, 5000);
    }
}

// Инициализация анимаций
function initAnimations() {
    // Анимация появления элементов
    const elements = document.querySelectorAll('.battle-container, .exploration-container, .object-card, .inventory-item');
    elements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        setTimeout(() => {
            element.style.transition = 'all 0.3s ease';
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, 100);
    });
    
    // Анимация при наведении на карточки
    const cards = document.querySelectorAll('.object-card, .inventory-item');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.2)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 2px 5px rgba(0, 0, 0, 0.1)';
        });
    });
} 