/**
 * Мобильные улучшения для страницы снаряжения
 * Этот скрипт добавляет улучшенное взаимодействие на мобильных устройствах
 */

document.addEventListener('DOMContentLoaded', function() {
    // Инициализация мобильных функций
    initMobile();
    
    /**
     * Инициализация всех мобильных функций
     */
    function initMobile() {
        // Определяем, находимся ли мы на мобильном устройстве
        const isMobile = window.innerWidth <= 840;
        
        if (isMobile) {
            // Показываем подсказку при первой загрузке
            if (!localStorage.getItem('swipeHintShown')) {
                showSwipeHint();
                localStorage.setItem('swipeHintShown', 'true');
            }
            
            // Инициализация FAB для детальной страницы предмета
            initMobileFab();
            
            // Добавляем поддержку свайпа для элементов списка
            addSwipeSupport();
            
            // Инициализация мобильных фильтров
            initMobileFilters();
            
            // Исправление ссылок для предотвращения добавления символа "@"
            fixLinks();
        }
        
        // Обработка изменения размера окна
        window.addEventListener('resize', function() {
            const newIsMobile = window.innerWidth <= 840;
            if (newIsMobile !== isMobile) {
                // Обновляем страницу при изменении режима отображения
                location.reload();
            }
        });
    }
    
    /**
     * Исправление ссылок для предотвращения изменения URL в адресной строке
     * и обеспечения правильного перехода на детали предмета
     */
    function fixLinks() {
        // Сохраняем текущий URL
        const originalURL = window.location.href.split('?')[0]; // Берем только базовый URL без параметров
        
        // Перехватываем все клики по ссылкам
        document.addEventListener('click', function(e) {
            // Ищем ссылку в цепочке элементов события
            let target = e.target;
            while (target && target.tagName !== 'A') {
                target = target.parentElement;
                if (!target) break;
            }
            
            // Если клик был не по ссылке, выходим
            if (!target || target.tagName !== 'A') return;
            
            const href = target.getAttribute('href');
            
            // Если ссылка ведет на страницу снаряжения
            if (href && href.includes('equip.php?')) {
                e.preventDefault(); // Предотвращаем стандартное поведение
                
                // Создаем AJAX запрос для загрузки содержимого без изменения URL
                const xhr = new XMLHttpRequest();
                xhr.open('GET', href, true);
                
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // Получаем DOM из ответа
                        const parser = new DOMParser();
                        const responseDoc = parser.parseFromString(xhr.responseText, 'text/html');
                        
                        // Заменяем содержимое страницы, сохраняя заголовок и структуру
                        document.title = responseDoc.title;
                        
                        // Находим основной контейнер с содержимым
                        const mainContent = document.querySelector('.main-content, #main, #content');
                        const newContent = responseDoc.querySelector('.main-content, #main, #content');
                        
                        if (mainContent && newContent) {
                            mainContent.innerHTML = newContent.innerHTML;
                            
                            // Повторно инициализируем функциональность после обновления DOM
                            setTimeout(() => {
                                // Определяем, находимся ли мы на мобильном устройстве
                                const isMobile = window.innerWidth <= 840;
                                
                                if (isMobile) {
                                    // Инициализация FAB для детальной страницы предмета
                                    initMobileFab();
                                    
                                    // Добавляем поддержку свайпа для элементов списка
                                    addSwipeSupport();
                                    
                                    // Инициализация мобильных фильтров
                                    initMobileFilters();
                                    
                                    // Рекурсивно исправляем новые ссылки
                                    fixLinks();
                                }
                            }, 100);
                        } else {
                            // Если не удалось найти основной контейнер, просто перенаправляем
                            window.location.href = href;
                        }
                    } else if (xhr.readyState === 4) {
                        // В случае ошибки, используем обычное перенаправление
                        window.location.href = href;
                    }
                };
                
                xhr.send();
                
                // Восстанавливаем исходный URL в адресной строке (без изменения истории браузера)
                window.history.replaceState({}, document.title, originalURL);
            }
        }, true); // Используем capture phase для перехвата перед обработкой ссылок
    }
    
    /**
     * Показывает подсказку о свайпе для мобильных устройств
     */
    function showSwipeHint() {
        const hintElement = document.getElementById('swipeHint');
        if (hintElement) {
            setTimeout(() => {
                hintElement.classList.add('show');
            }, 1000);
            
            setTimeout(() => {
                hintElement.classList.remove('show');
            }, 5000);
        }
    }
    
    /**
     * Инициализирует мобильную плавающую кнопку действий
     */
    function initMobileFab() {
        const fabButton = document.getElementById('mobileFab');
        const overlay = document.getElementById('mobileOverlay');
        const actionSheet = document.getElementById('mobileItemActions');
        
        if (fabButton && overlay && actionSheet) {
            // Добавляем обработчик на кнопку
            fabButton.addEventListener('click', function() {
                overlay.classList.add('active');
                actionSheet.classList.add('active');
            });
            
            // Закрытие по клику на оверлей
            overlay.addEventListener('click', function() {
                overlay.classList.remove('active');
                actionSheet.classList.remove('active');
            });
            
            // Закрытие по свайпу вниз
            let startY, endY;
            actionSheet.addEventListener('touchstart', function(e) {
                startY = e.touches[0].clientY;
            });
            
            actionSheet.addEventListener('touchmove', function(e) {
                endY = e.touches[0].clientY;
                const diff = endY - startY;
                if (diff > 0) {
                    actionSheet.style.transform = `translateY(${diff}px)`;
                }
            });
            
            actionSheet.addEventListener('touchend', function() {
                if (endY - startY > 70) {
                    overlay.classList.remove('active');
                    actionSheet.classList.remove('active');
                } else {
                    actionSheet.style.transform = '';
                }
            });
        }
    }
    
    /**
     * Добавляет поддержку свайпа для элементов списка снаряжения
     */
    function addSwipeSupport() {
        const items = document.querySelectorAll('.equipment-item');
        
        items.forEach(item => {
            let startX, moveX, diffX;
            let isSwipe = false;
            
            item.addEventListener('touchstart', function(e) {
                startX = e.touches[0].clientX;
                isSwipe = false;
            });
            
            item.addEventListener('touchmove', function(e) {
                moveX = e.touches[0].clientX;
                diffX = moveX - startX;
                
                // Проверяем, является ли это свайпом (если смещение достаточно большое)
                if (Math.abs(diffX) > 10) {
                    isSwipe = true;
                }
                
                // Ограничиваем смещение
                if (diffX < 0 && diffX > -80) {
                    item.style.transform = `translateX(${diffX}px)`;
                }
            });
            
            item.addEventListener('touchend', function(e) {
                // Возвращаем элемент в исходное положение
                item.style.transform = '';
                
                // Для свайпа используем AJAX загрузку без изменения URL
                if (isSwipe && diffX < -50) {
                    // Если свайп достаточно сильный, переходим к подробностям
                    const link = item.querySelector('a');
                    if (link) {
                        e.preventDefault();
                        
                        const href = link.getAttribute('href');
                        // Загружаем содержимое через функцию, которая не изменяет URL
                        loadContent(href);
                    }
                }
            });
        });
    }
    
    /**
     * Загрузка контента через AJAX без изменения URL
     */
    function loadContent(url) {
        // Сохраняем текущий URL
        const originalURL = window.location.href.split('?')[0]; // Берем только базовый URL без параметров
        
        // Создаем AJAX запрос
        const xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Получаем DOM из ответа
                const parser = new DOMParser();
                const responseDoc = parser.parseFromString(xhr.responseText, 'text/html');
                
                // Заменяем содержимое страницы, сохраняя заголовок и структуру
                document.title = responseDoc.title;
                
                // Находим основной контейнер с содержимым
                const mainContent = document.querySelector('.main-content, #main, #content');
                const newContent = responseDoc.querySelector('.main-content, #main, #content');
                
                if (mainContent && newContent) {
                    mainContent.innerHTML = newContent.innerHTML;
                    
                    // Повторно инициализируем функциональность после обновления DOM
                    setTimeout(() => {
                        // Определяем, находимся ли мы на мобильном устройстве
                        const isMobile = window.innerWidth <= 840;
                        
                        if (isMobile) {
                            // Инициализация FAB для детальной страницы предмета
                            initMobileFab();
                            
                            // Добавляем поддержку свайпа для элементов списка
                            addSwipeSupport();
                            
                            // Инициализация мобильных фильтров
                            initMobileFilters();
                        }
                    }, 100);
                } else {
                    // Если не удалось найти основной контейнер, просто перенаправляем
                    window.location.href = url;
                }
            } else if (xhr.readyState === 4) {
                // В случае ошибки, используем обычное перенаправление
                window.location.href = url;
            }
        };
        
        xhr.send();
        
        // Восстанавливаем исходный URL в адресной строке (без изменения истории браузера)
        window.history.replaceState({}, document.title, originalURL);
    }
    
    /**
     * Инициализация мобильных фильтров для списка снаряжения
     */
    function initMobileFilters() {
        // Поиск по названию предмета
        const searchInput = document.getElementById('item-search');
        const filterBadges = document.querySelectorAll('.filter-badge');
        const sortOptions = document.querySelectorAll('.sort-option');
        
        // Мобильные версии
        const mobileSearchInput = document.querySelector('.mobile-filter-input');
        const mobileSortOptions = document.querySelectorAll('.mobile-sort-option');
        const mobileFilterBadges = document.querySelectorAll('.mobile-filter-badge');
        
        if (searchInput) {
            // Синхронизируем стандартный и мобильный поиск
            searchInput.addEventListener('input', function() {
                filterEquipmentItems(this.value);
                if (mobileSearchInput) {
                    mobileSearchInput.value = this.value;
                }
            });
            
            if (mobileSearchInput) {
                mobileSearchInput.addEventListener('input', function() {
                    filterEquipmentItems(this.value);
                    searchInput.value = this.value;
                });
            }
            
            // Обработка стилевых фильтров
            filterBadges.forEach(badge => {
                badge.addEventListener('click', function() {
                    this.classList.toggle('active');
                    applyFilters();
                    
                    // Синхронизируем с мобильными фильтрами
                    if (mobileFilterBadges) {
                        const styleValue = this.getAttribute('data-style');
                        const mobileBadge = Array.from(mobileFilterBadges).find(b => 
                            b.getAttribute('data-style') === styleValue);
                        if (mobileBadge) {
                            mobileBadge.classList.toggle('active', this.classList.contains('active'));
                        }
                    }
                });
            });
            
            // Синхронизация мобильных фильтров
            if (mobileFilterBadges) {
                mobileFilterBadges.forEach(badge => {
                    badge.addEventListener('click', function() {
                        this.classList.toggle('active');
                        applyFilters();
                        
                        const styleValue = this.getAttribute('data-style');
                        const desktopBadge = Array.from(filterBadges).find(b => 
                            b.getAttribute('data-style') === styleValue);
                        if (desktopBadge) {
                            desktopBadge.classList.toggle('active', this.classList.contains('active'));
                        }
                    });
                });
            }
            
            // Обработка опций сортировки
            sortOptions.forEach(option => {
                option.addEventListener('click', function() {
                    sortOptions.forEach(opt => opt.classList.remove('active'));
                    this.classList.add('active');
                    sortEquipmentItems(this.getAttribute('data-sort'));
                    
                    // Синхронизируем с мобильными опциями
                    if (mobileSortOptions) {
                        const sortValue = this.getAttribute('data-sort');
                        mobileSortOptions.forEach(opt => opt.classList.remove('active'));
                        const mobileSortOption = Array.from(mobileSortOptions).find(o => 
                            o.getAttribute('data-sort') === sortValue);
                        if (mobileSortOption) {
                            mobileSortOption.classList.add('active');
                        }
                    }
                });
            });
            
            // Синхронизация мобильных опций сортировки
            if (mobileSortOptions) {
                mobileSortOptions.forEach(option => {
                    option.addEventListener('click', function() {
                        mobileSortOptions.forEach(opt => opt.classList.remove('active'));
                        this.classList.add('active');
                        sortEquipmentItems(this.getAttribute('data-sort'));
                        
                        const sortValue = this.getAttribute('data-sort');
                        sortOptions.forEach(opt => opt.classList.remove('active'));
                        const desktopSortOption = Array.from(sortOptions).find(o => 
                            o.getAttribute('data-sort') === sortValue);
                        if (desktopSortOption) {
                            desktopSortOption.classList.add('active');
                        }
                    });
                });
            }
        }
        
        // Переключение мобильных фильтров
        const filterToggle = document.querySelector('.mobile-filter-toggle');
        const mobileFilterBar = document.querySelector('.mobile-filter-bar');
        
        if (filterToggle && mobileFilterBar) {
            filterToggle.addEventListener('click', function() {
                mobileFilterBar.classList.toggle('show');
            });
        }
    }
    
    /**
     * Фильтрация предметов снаряжения по названию
     */
    function filterEquipmentItems(searchText) {
        const items = document.querySelectorAll('.equipment-item');
        const searchLower = searchText.toLowerCase();
        
        items.forEach(item => {
            const itemName = item.querySelector('.equipment-name').textContent.toLowerCase();
            if (itemName.includes(searchLower) || searchLower === '') {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
        
        // Проверяем, есть ли видимые элементы
        checkNoVisibleItems();
    }
    
    /**
     * Применение всех активных фильтров
     */
    function applyFilters() {
        const items = document.querySelectorAll('.equipment-item');
        const activeStyleFilters = Array.from(document.querySelectorAll('.filter-badge.active, .mobile-filter-badge.active'))
            .map(badge => badge.getAttribute('data-style'));
        
        // Если активных фильтров нет, показываем все
        const hasActiveFilters = activeStyleFilters.length > 0;
        
        items.forEach(item => {
            const itemStyle = item.getAttribute('data-style');
            
            if (!hasActiveFilters || activeStyleFilters.includes(itemStyle)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
        
        // Проверяем, есть ли видимые элементы
        checkNoVisibleItems();
    }
    
    /**
     * Сортировка предметов снаряжения
     */
    function sortEquipmentItems(sortType) {
        const equippedContainer = document.querySelector('.equipped-items');
        const availableContainer = document.querySelector('.available-items');
        
        if (!equippedContainer || !availableContainer) return;
        
        const equippedItems = Array.from(equippedContainer.querySelectorAll('.equipment-item'));
        const availableItems = Array.from(availableContainer.querySelectorAll('.equipment-item'));
        
        // Функция сортировки в зависимости от типа
        function compareItems(a, b, sortType) {
            switch (sortType) {
                case 'level-desc':
                    return parseInt(b.getAttribute('data-level') || 0) - parseInt(a.getAttribute('data-level') || 0);
                case 'level-asc':
                    return parseInt(a.getAttribute('data-level') || 0) - parseInt(b.getAttribute('data-level') || 0);
                case 'style':
                    return (b.getAttribute('data-style') || 0) - (a.getAttribute('data-style') || 0);
                case 'equipped':
                default:
                    return 0; // Оставляем порядок по умолчанию
            }
        }
        
        // Сортируем и добавляем обратно в контейнеры
        equippedItems.sort((a, b) => compareItems(a, b, sortType));
        availableItems.sort((a, b) => compareItems(a, b, sortType));
        
        equippedContainer.innerHTML = '';
        availableContainer.innerHTML = '';
        
        equippedItems.forEach(item => equippedContainer.appendChild(item));
        availableItems.forEach(item => availableContainer.appendChild(item));
    }
    
    /**
     * Проверка на наличие видимых элементов после фильтрации
     */
    function checkNoVisibleItems() {
        const equippedContainer = document.querySelector('.equipped-items');
        const availableContainer = document.querySelector('.available-items');
        
        if (!equippedContainer || !availableContainer) return;
        
        const hasVisibleEquipped = Array.from(equippedContainer.querySelectorAll('.equipment-item'))
            .some(item => item.style.display !== 'none');
        const hasVisibleAvailable = Array.from(availableContainer.querySelectorAll('.equipment-item'))
            .some(item => item.style.display !== 'none');
        
        // Показываем сообщение, если нет видимых элементов
        let noEquippedMessage = equippedContainer.querySelector('.no-items-message');
        let noAvailableMessage = availableContainer.querySelector('.no-items-message');
        
        if (!hasVisibleEquipped) {
            if (!noEquippedMessage) {
                noEquippedMessage = document.createElement('div');
                noEquippedMessage.className = 'no-items-message';
                noEquippedMessage.textContent = 'Нет предметов, соответствующих фильтру';
                equippedContainer.appendChild(noEquippedMessage);
            }
        } else if (noEquippedMessage) {
            noEquippedMessage.remove();
        }
        
        if (!hasVisibleAvailable) {
            if (!noAvailableMessage) {
                noAvailableMessage = document.createElement('div');
                noAvailableMessage.className = 'no-items-message';
                noAvailableMessage.textContent = 'Нет предметов, соответствующих фильтру';
                availableContainer.appendChild(noAvailableMessage);
            }
        } else if (noAvailableMessage) {
            noAvailableMessage.remove();
        }
    }
}); 