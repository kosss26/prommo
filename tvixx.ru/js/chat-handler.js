/**
 * ChatSystem - Модульная система чата для Mobitva 2 (AJAX версия)
 * 
 * @version 2.1.0
 * @author Команда разработчиков Mobitva
 * @copyright 2023-2024 Mobitva Game Studio
 */

const ChatSystem = (() => {
    // Приватные переменные модуля
    let config = {};
    let lastMessageId = 0;
    let isLoading = false;
    let cooldownTimer = 0;
    let updateInterval = null;
    let messagesQueue = [];
    let onlineUsersCount = 0;
    let connectionLost = false;
    let connectionFailures = 0;
    
    // DOM элементы
    let elements = {
        chatContainer: null,
        messageInput: null,
        sendButton: null,
        chatMessages: null,
        form: null,
        adminMode: null,
        connectionStatus: null,
        onlineUsers: null,
        messageTemplate: null
    };
    
    /**
     * Инициализация системы чата
     * @param {Object} options - Конфигурация чата
     */
    const init = (options) => {
        // Установка конфигурации
        config = {
            ...options,
            messageLimit: 50,
            cooldownDuration: 5,
            updateInterval: 3000, // Интервал обновления в мс
            maxConnectionFailures: 5
        };
        
        // Находим DOM элементы
        elements = {
            chatContainer: document.querySelector('.chat-page'),
            messageInput: document.getElementById('messageInput'),
            sendButton: document.getElementById('sendButton'),
            chatMessages: document.getElementById('chatMessages'),
            form: document.getElementById('chatForm'),
            adminMode: document.getElementById('adminMode'),
            connectionStatus: document.getElementById('connectionStatus'),
            onlineUsers: document.getElementById('onlineUsers'),
            messageTemplate: document.getElementById('messageTemplate')
        };
        
        if (elements.chatMessages) {
            lastMessageId = parseInt(elements.chatMessages.dataset.lastId || "0", 10);
        }
        
        // Настройка обработчиков событий
        setupEventListeners();
        
        // Начальная загрузка сообщений
        loadInitialMessages();
        
        // Настройка периодического обновления
        updateInterval = setInterval(() => {
            updateMessages();
        }, config.updateInterval);
        
        // Устанавливаем фокус на поле ввода
        if (elements.messageInput && !config.isBanned) {
            elements.messageInput.focus();
        }
        
        // Запуск таймера кулдауна, если есть
        if (cooldownTimer > 0) {
            startCooldown(cooldownTimer);
        }
        
        // Установка обработчика видимости страницы для оптимизации
        document.addEventListener('visibilitychange', handleVisibilityChange);
        
        console.log('ChatSystem initialized (AJAX mode)');
    };
    
    /**
     * Обработка изменения видимости страницы
     */
    const handleVisibilityChange = () => {
        if (document.hidden) {
            // Страница неактивна - увеличиваем интервал до 10 секунд
            clearInterval(updateInterval);
            updateInterval = setInterval(() => {
                updateMessages();
            }, 10000);
        } else {
            // Страница активна - возвращаем обычный интервал
            clearInterval(updateInterval);
            updateInterval = setInterval(() => {
                updateMessages();
            }, config.updateInterval);
            
            // Немедленно обновляем сообщения при возврате на страницу
            updateMessages();
        }
    };
    
    /**
     * Настройка обработчиков событий
     */
    const setupEventListeners = () => {
        // Если пользователь заблокирован, не устанавливаем обработчики отправки
        if (config.isBanned) return;
        
        if (elements.form) {
            elements.form.addEventListener('submit', (e) => {
                e.preventDefault();
                sendMessage();
            });
        }
        
        if (elements.messageInput) {
            elements.messageInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });
        }
        
        // Обработка скролла для подгрузки старых сообщений
        if (elements.chatMessages) {
            elements.chatMessages.addEventListener('scroll', () => {
                // Если скролл в верху контейнера, загружаем более старые сообщения
                if (elements.chatMessages.scrollTop === 0 && !isLoading) {
                    loadOlderMessages();
                }
            });
        }
        
        // Добавляем обработчик для кнопки переподключения
        const reconnectButton = document.getElementById('reconnectButton');
        if (reconnectButton) {
            reconnectButton.addEventListener('click', () => {
                reconnectButton.disabled = true;
                reconnectButton.textContent = 'Переподключение...';
                
                // Пробуем восстановить соединение
                updateMessages(true);
            });
        }
    };
    
    /**
     * Загрузка начальных сообщений
     */
    const loadInitialMessages = () => {
        isLoading = true;
        updateLoaderVisibility(true);
        updateConnectionStatus(true);
        
        fetch(config.apiEndpoints.read, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                nick: config.userLogin,
                pass: config.userPassword,
                lastId: lastMessageId,
                chat: config.currentChat
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            connectionFailures = 0;
            displayMessages(data);
            isLoading = false;
            updateLoaderVisibility(false);
            updateConnectionStatus(true);
            
            // Запрашиваем количество онлайн пользователей
            fetchOnlineUsersCount();
        })
        .catch(error => {
            console.error('Error loading initial messages:', error);
            isLoading = false;
            updateLoaderVisibility(false);
            connectionLost = true;
            updateConnectionStatus(false);
            
            // Показываем сообщение об ошибке
            showErrorMessage('Не удалось загрузить сообщения. Пожалуйста, обновите страницу.');
        });
    };
    
    /**
     * Получение количества онлайн пользователей
     */
    const fetchOnlineUsersCount = () => {
        fetch(config.apiEndpoints.online || '/api/online.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                chat: config.currentChat,
                csrf_token: config.csrf
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data && data.count) {
                updateOnlineUsers(data.count);
            }
        })
        .catch(error => {
            console.error('Error fetching online users count:', error);
        });
    };
    
    /**
     * Загрузка более старых сообщений
     */
    const loadOlderMessages = () => {
        if (isLoading) return;
        
        isLoading = true;
        
        // Добавляем индикатор загрузки в начало чата
        const loadingIndicator = document.createElement('div');
        loadingIndicator.className = 'text-center my-2 loading-older';
        loadingIndicator.innerHTML = '<div class="spinner-border spinner-border-sm text-secondary" role="status"></div> Загрузка...';
        elements.chatMessages.prepend(loadingIndicator);
        
        // Получаем ID самого старого сообщения
        const oldestMessageId = getOldestMessageId();
        
        fetch(config.apiEndpoints.read, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                nick: config.userLogin,
                pass: config.userPassword,
                lastId: oldestMessageId,
                chat: config.currentChat,
                direction: 'older'
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Удаляем индикатор загрузки
            loadingIndicator.remove();
            
            // Сохраняем текущую позицию скролла
            const scrollPos = elements.chatMessages.scrollHeight;
            
            if (data.length > 0) {
                displayOlderMessages(data);
                
                // Восстанавливаем положение скролла относительно новой высоты
                elements.chatMessages.scrollTop = elements.chatMessages.scrollHeight - scrollPos;
            } else {
                // Если больше нет сообщений, показываем уведомление
                const noMoreIndicator = document.createElement('div');
                noMoreIndicator.className = 'text-center my-3 no-more-messages';
                noMoreIndicator.textContent = 'Больше сообщений нет';
                elements.chatMessages.prepend(noMoreIndicator);
                
                // Убираем уведомление через 3 секунды
                setTimeout(() => {
                    noMoreIndicator.remove();
                }, 3000);
            }
            
            isLoading = false;
        })
        .catch(error => {
            console.error('Error loading older messages:', error);
            loadingIndicator.remove();
            isLoading = false;
        });
    };
    
    /**
     * Получение ID самого старого сообщения в чате
     * @returns {number} ID самого старого сообщения
     */
    const getOldestMessageId = () => {
        const messages = elements.chatMessages.querySelectorAll('.chat-message');
        if (messages.length > 0) {
            const oldestMessage = messages[0];
            return parseInt(oldestMessage.dataset.messageId || "0", 10);
        }
        return 0;
    };
    
    /**
     * Отображение сообщений в чате
     * @param {Array} messages - Массив сообщений
     */
    const displayMessages = (messages) => {
        if (!messages || messages.length === 0) return;
        
        // Очищаем контейнер сообщений от индикатора загрузки
        while (elements.chatMessages.firstChild) {
            elements.chatMessages.removeChild(elements.chatMessages.firstChild);
        }
        
        // Отображаем сообщения в обратном порядке (новые внизу)
        for (let i = messages.length - 1; i >= 0; i--) {
            const message = messages[i];
            addMessageToDOM(message);
            
            // Обновляем lastMessageId
            if (parseInt(message[0], 10) > lastMessageId) {
                lastMessageId = parseInt(message[0], 10);
            }
        }
        
        // Прокручиваем до последнего сообщения
        scrollToBottom();
    };
    
    /**
     * Отображение более старых сообщений в начале чата
     * @param {Array} messages - Массив сообщений
     */
    const displayOlderMessages = (messages) => {
        if (!messages || messages.length === 0) return;
        
        // Вставляем в начало чата в прямом порядке (старые выше)
        for (let i = 0; i < messages.length; i++) {
            const message = messages[i];
            addMessageToDOM(message, true); // true = вставить в начало
        }
    };
    
    /**
     * Отправка сообщения
     */
    const sendMessage = () => {
        if (!elements.messageInput || !elements.sendButton) return;
        
        const message = elements.messageInput.value.trim();
        if (!message) return;
        
        if (cooldownTimer > 0) {
            showNotification('Пожалуйста, подождите перед отправкой следующего сообщения', 'warning');
            return;
        }
        
        // Проверка на подключение
        if (connectionLost) {
            showNotification('Нет соединения с сервером. Пожалуйста, восстановите соединение', 'danger');
            return;
        }
        
        // Блокируем кнопку отправки
        elements.sendButton.disabled = true;
        elements.messageInput.disabled = true;
        
        // Админ-режим (если есть)
        const adminMode = elements.adminMode ? elements.adminMode.checked : false;
        
        // Подготавливаем данные для отправки
        const formData = new URLSearchParams({
            nick: config.userLogin,
            pass: config.userPassword,
            msg: message,
            chat: config.currentChat,
            pip: adminMode,
            csrf_token: config.csrf
        });
        
        // Отправляем через AJAX
        fetch(config.apiEndpoints.send, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Очищаем ввод
            elements.messageInput.value = '';
            
            // Проверка на ошибки
            if (data.error) {
                showErrorMessage(data.error);
            } else if (data.success) {
                // Обновляем сообщения сразу после отправки
                updateMessages();
                
                // Если звуковые уведомления включены
                playSound('message-sent');
            }
            
            // Запускаем кулдаун
            startCooldown(config.cooldownDuration);
            
            // Сбрасываем счетчик ошибок соединения
            connectionFailures = 0;
            connectionLost = false;
            updateConnectionStatus(true);
        })
        .catch(error => {
            console.error('Error sending message:', error);
            showErrorMessage('Ошибка при отправке сообщения. Попробуйте еще раз.');
            connectionFailures++;
            
            if (connectionFailures >= config.maxConnectionFailures) {
                connectionLost = true;
                updateConnectionStatus(false);
            }
        })
        .finally(() => {
            // Разблокируем форму
            elements.sendButton.disabled = false;
            elements.messageInput.disabled = false;
            elements.messageInput.focus();
        });
    };
    
    /**
     * Воспроизведение звука уведомления
     * @param {string} soundType - Тип звука
     */
    const playSound = (soundType) => {
        // Проверяем, включены ли звуки в настройках
        if (!config.soundEnabled) return;
        
        try {
            const sound = new Audio();
            
            switch (soundType) {
                case 'message-sent':
                    sound.src = '/sounds/message-sent.mp3';
                    break;
                case 'new-message':
                    sound.src = '/sounds/new-message.mp3';
                    break;
                case 'error':
                    sound.src = '/sounds/error.mp3';
                    break;
                default:
                    return;
            }
            
            sound.volume = 0.5;
            sound.play().catch(e => {
                // Игнорируем ошибки воспроизведения (часто из-за политик браузера)
                console.log('Звук не может быть воспроизведен автоматически');
            });
        } catch (e) {
            console.log('Ошибка воспроизведения звука');
        }
    };
    
    /**
     * Запуск таймера задержки между сообщениями
     * @param {number} seconds - Длительность задержки в секундах
     */
    const startCooldown = (seconds) => {
        cooldownTimer = seconds;
        
        if (elements.sendButton) {
            elements.sendButton.disabled = true;
            elements.sendButton.innerHTML = `<i class="fas fa-clock"></i> ${cooldownTimer}`;
        }
        
        const cooldownInterval = setInterval(() => {
            cooldownTimer--;
            
            if (elements.sendButton) {
                elements.sendButton.innerHTML = `<i class="fas fa-clock"></i> ${cooldownTimer}`;
            }
            
            if (cooldownTimer <= 0) {
                clearInterval(cooldownInterval);
                if (elements.sendButton) {
                    elements.sendButton.disabled = false;
                    elements.sendButton.innerHTML = '<i class="fas fa-paper-plane"></i> Отправить';
                }
            }
        }, 1000);
    };
    
    /**
     * Обновление сообщений через AJAX
     * @param {boolean} forceReconnect - Принудительное обновление для восстановления соединения
     */
    const updateMessages = (forceReconnect = false) => {
        // Пропускаем, если уже идет загрузка
        if (isLoading && !forceReconnect) return;
        
        // Пропускаем, если страница неактивна и не принудительное подключение
        if (document.hidden && !forceReconnect) return;
        
        fetch(config.apiEndpoints.read, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                nick: config.userLogin,
                pass: config.userPassword,
                lastId: lastMessageId,
                chat: config.currentChat
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data && data.length > 0) {
                // Проверяем, есть ли новые сообщения
                if (data[0][0] > lastMessageId) {
                    // Добавляем только новые сообщения
                    const wasAtBottom = isScrolledToBottom();
                    let newMessages = 0;
                    
                    for (let i = data.length - 1; i >= 0; i--) {
                        if (parseInt(data[i][0], 10) > lastMessageId) {
                            addMessageToDOM(data[i]);
                            lastMessageId = parseInt(data[i][0], 10);
                            newMessages++;
                        }
                    }
                    
                    // Прокручиваем вниз только если пользователь был внизу
                    if (wasAtBottom) {
                        scrollToBottom();
                    } else if (newMessages > 0) {
                        // Показываем уведомление о новых сообщениях
                        showNewMessagesNotification(newMessages);
                    }
                    
                    // Если звуковые уведомления включены и пришли новые сообщения
                    if (newMessages > 0) {
                        playSound('new-message');
                    }
                }
            }
            
            // Сбрасываем счетчик ошибок соединения
            connectionFailures = 0;
            
            // Если было разорвано соединение - восстанавливаем
            if (connectionLost || forceReconnect) {
                connectionLost = false;
                updateConnectionStatus(true);
                
                // Сбрасываем состояние кнопки переподключения
                const reconnectButton = document.getElementById('reconnectButton');
                if (reconnectButton) {
                    reconnectButton.disabled = false;
                    reconnectButton.textContent = 'Переподключиться';
                }
                
                showNotification('Соединение восстановлено', 'success');
            }
            
            // Обновляем информацию об онлайн пользователях каждые 5 циклов обновления
            if (Math.random() < 0.2) { // ~20% вероятность каждого запроса
                fetchOnlineUsersCount();
            }
        })
        .catch(error => {
            console.error('Error updating messages:', error);
            connectionFailures++;
            
            // Если превышено количество ошибок подряд - считаем соединение потерянным
            if (connectionFailures >= config.maxConnectionFailures && !connectionLost) {
                connectionLost = true;
                updateConnectionStatus(false);
                showErrorMessage('Соединение с сервером потеряно. Пожалуйста, проверьте подключение к интернету.');
            }
        });
    };
    
    /**
     * Показывает уведомление о новых сообщениях
     * @param {number} count - Количество новых сообщений
     */
    const showNewMessagesNotification = (count) => {
        const notificationEl = document.createElement('div');
        notificationEl.className = 'new-messages-notification';
        notificationEl.innerHTML = `<i class="fas fa-arrow-down"></i> ${count} ${count === 1 ? 'новое сообщение' : 'новых сообщений'}`;
        
        notificationEl.addEventListener('click', () => {
            scrollToBottom();
            notificationEl.remove();
        });
        
        elements.chatMessages.appendChild(notificationEl);
        
        // Автоматически скрываем уведомление через 5 секунд
        setTimeout(() => {
            if (notificationEl.parentNode) {
                notificationEl.classList.add('fade-out');
                setTimeout(() => {
                    if (notificationEl.parentNode) {
                        notificationEl.remove();
                    }
                }, 300);
            }
        }, 5000);
    };
    
    /**
     * Добавление сообщения в DOM
     * @param {Array} messageData - Данные о сообщении
     * @param {boolean} prepend - Добавить в начало списка
     */
    const addMessageToDOM = (messageData, prepend = false) => {
        if (!elements.messageTemplate || !elements.chatMessages) return;
        
        const [id, html, userId] = messageData;
        
        // Создаем элемент сообщения из шаблона
        const messageElement = document.importNode(elements.messageTemplate.content, true).firstElementChild;
        messageElement.dataset.messageId = id;
        messageElement.dataset.userId = userId || 0;
        
        // Проверяем, свое ли это сообщение
        if (parseInt(userId, 10) === config.userId) {
            messageElement.classList.add('own-message');
        }
        
        // Проверяем, сообщение администратора или модератора
        if (html.includes("color='red'") || html.includes("color='#ff0000'")) {
            messageElement.classList.add('admin-message');
        }
        
        // Парсим HTML-содержимое
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        
        // Извлекаем имя автора
        let authorName = '';
        const nameMatch = html.match(/\[(.*?)\]/);
        if (nameMatch && nameMatch[1]) {
            authorName = nameMatch[1];
        }
        
        // Извлекаем время
        let messageTime = '';
        const timeMatch = html.match(/\(\d{2}:\d{2}\)/);
        if (timeMatch) {
            messageTime = timeMatch[0];
        }
        
        // Извлекаем содержимое сообщения
        let messageContent = tempDiv.textContent || '';
        const contentMatch = messageContent.match(/\[.*?\] \(.*?\) (.*)/);
        if (contentMatch && contentMatch[1]) {
            messageContent = contentMatch[1];
        }
        
        // Заполняем шаблон данными
        messageElement.querySelector('.message-author').textContent = authorName;
        messageElement.querySelector('.message-time').textContent = messageTime;
        messageElement.querySelector('.message-content').textContent = messageContent;
        
        // Добавляем кнопки действий для модераторов и администраторов
        if ((config.userAccess >= 1) && userId && userId != config.userId) {
            const actionsContainer = messageElement.querySelector('.message-actions');
            
            const replyButton = document.createElement('span');
            replyButton.className = 'action-btn reply';
            replyButton.innerHTML = '<i class="fas fa-reply"></i>';
            replyButton.title = 'Ответить';
            replyButton.addEventListener('click', () => {
                replyToUser(authorName);
            });
            
            actionsContainer.appendChild(replyButton);
            
            if (config.userAccess >= 3) {
                const banButton = document.createElement('span');
                banButton.className = 'action-btn ban';
                banButton.innerHTML = '<i class="fas fa-ban"></i>';
                banButton.title = 'Заблокировать';
                banButton.addEventListener('click', () => {
                    showBanModal(id, authorName, userId);
                });
                
                actionsContainer.appendChild(banButton);
            }
        }
        
        // Добавляем в контейнер
        if (prepend) {
            elements.chatMessages.prepend(messageElement);
        } else {
            elements.chatMessages.appendChild(messageElement);
        }
    };
    
    /**
     * Ответ пользователю (вставка имени в поле ввода)
     * @param {string} username - Имя пользователя
     */
    const replyToUser = (username) => {
        if (!elements.messageInput) return;
        
        elements.messageInput.value = `${username}, ${elements.messageInput.value}`;
        elements.messageInput.focus();
    };
    
    /**
     * Показ модального окна для блокировки пользователя
     * @param {string} messageId - ID сообщения
     * @param {string} username - Имя пользователя
     * @param {string} userId - ID пользователя
     */
    const showBanModal = (messageId, username, userId) => {
        // URL для модерации
        const url = `${config.apiEndpoints.moderate}?msgid=${messageId}&chat=${config.currentChat}`;
        
        // Перенаправляем на страницу модерации
        window.location.href = url;
    };
    
    /**
     * Переключение на другую вкладку чата
     * @param {number} chatId - ID чата
     */
    const switchTab = (chatId) => {
        window.location.href = `/chat.php?chat=${chatId}`;
    };
    
    /**
     * Проверка, прокручен ли чат до конца
     * @returns {boolean}
     */
    const isScrolledToBottom = () => {
        if (!elements.chatMessages) return true;
        
        const tolerance = 50; // пикселей от дна
        const scrollPosition = elements.chatMessages.scrollTop + elements.chatMessages.clientHeight;
        const scrollMax = elements.chatMessages.scrollHeight;
        
        return scrollMax - scrollPosition <= tolerance;
    };
    
    /**
     * Прокрутка чата до конца
     */
    const scrollToBottom = () => {
        if (!elements.chatMessages) return;
        
        elements.chatMessages.scrollTop = elements.chatMessages.scrollHeight;
    };
    
    /**
     * Показ уведомления об ошибке
     * @param {string} message - Текст ошибки
     */
    const showErrorMessage = (message) => {
        const errorElement = document.createElement('div');
        errorElement.className = 'alert alert-danger my-2';
        errorElement.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
        
        elements.chatMessages.prepend(errorElement);
        
        // Воспроизводим звук ошибки
        playSound('error');
        
        setTimeout(() => {
            errorElement.classList.add('fade-out');
            setTimeout(() => {
                errorElement.remove();
            }, 300);
        }, 5000);
    };
    
    /**
     * Показ уведомления
     * @param {string} message - Текст уведомления
     * @param {string} type - Тип уведомления (success, warning, info)
     */
    const showNotification = (message, type = 'info') => {
        const notificationElement = document.createElement('div');
        notificationElement.className = `alert alert-${type} my-2 notification-popup`;
        notificationElement.innerHTML = message;
        
        document.body.appendChild(notificationElement);
        
        setTimeout(() => {
            notificationElement.classList.add('fade-out');
            setTimeout(() => {
                notificationElement.remove();
            }, 300);
        }, 3000);
    };
    
    /**
     * Обновление статуса подключения
     * @param {boolean} isConnected - Статус подключения
     */
    const updateConnectionStatus = (isConnected) => {
        if (!elements.connectionStatus) return;
        
        if (isConnected) {
            elements.connectionStatus.innerHTML = '<i class="fas fa-circle text-success"></i> Подключено';
            elements.chatContainer.classList.remove('connection-lost');
            
            // Скрываем кнопку переподключения
            const reconnectBtn = document.getElementById('reconnectButton');
            if (reconnectBtn) reconnectBtn.style.display = 'none';
        } else {
            elements.connectionStatus.innerHTML = '<i class="fas fa-circle text-danger"></i> Отключено';
            elements.chatContainer.classList.add('connection-lost');
            
            // Показываем кнопку переподключения
            const statusEl = document.getElementById('connectionStatus');
            if (statusEl && !document.getElementById('reconnectButton')) {
                const reconnectBtn = document.createElement('button');
                reconnectBtn.id = 'reconnectButton';
                reconnectBtn.className = 'btn btn-sm btn-danger ml-2';
                reconnectBtn.textContent = 'Переподключиться';
                reconnectBtn.addEventListener('click', () => {
                    reconnectBtn.disabled = true;
                    reconnectBtn.textContent = 'Переподключение...';
                    updateMessages(true);
                });
                statusEl.appendChild(reconnectBtn);
            } else {
                const reconnectBtn = document.getElementById('reconnectButton');
                if (reconnectBtn) reconnectBtn.style.display = 'inline-block';
            }
        }
    };
    
    /**
     * Обновление счетчика онлайн пользователей
     * @param {number} count - Количество пользователей
     */
    const updateOnlineUsers = (count) => {
        if (!elements.onlineUsers) return;
        
        onlineUsersCount = count;
        elements.onlineUsers.textContent = count;
    };
    
    /**
     * Обновление видимости индикатора загрузки
     * @param {boolean} visible - Отображать ли индикатор
     */
    const updateLoaderVisibility = (visible) => {
        const loader = elements.chatMessages.querySelector('.chat-loading');
        if (!loader) return;
        
        if (visible) {
            loader.style.display = 'flex';
        } else {
            loader.style.display = 'none';
        }
    };
    
    /**
     * Очистка ресурсов перед уничтожением модуля
     */
    const destroy = () => {
        // Остановка всех интервалов
        clearInterval(updateInterval);
        
        // Удаление обработчиков событий
        document.removeEventListener('visibilitychange', handleVisibilityChange);
        
        // Сброс переменных
        config = {};
        lastMessageId = 0;
        isLoading = false;
        cooldownTimer = 0;
        
        console.log('ChatSystem destroyed');
    };
    
    // Публичное API модуля
    return {
        init,
        switchTab,
        sendMessage,
        destroy
    };
})();

// Поддержка для старых скриптов
if (typeof MyLib === 'undefined') {
    window.MyLib = {
        intervaltimer: [],
        setTimeid: []
    };
} 