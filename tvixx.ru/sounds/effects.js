/**
 * Звуковые эффекты для боевой системы
 * Используются как резервные файлы если основные не существуют
 */

// Инициализация звуков при загрузке страницы
window.addEventListener('DOMContentLoaded', function() {
    // Создаем пустые звуки, чтобы избежать ошибок если файлы отсутствуют
    window.sounds = window.sounds || {
        hit: {
            play: function() { console.log('Звук "hit" не найден или не загружен'); }
        },
        slash: {
            play: function() { console.log('Звук "slash" не найден или не загружен'); }
        },
        block: {
            play: function() { console.log('Звук "block" не найден или не загружен'); }
        },
        heal: {
            play: function() { console.log('Звук "heal" не найден или не загружен'); }
        },
        victory: {
            play: function() { console.log('Звук "victory" не найден или не загружен'); }
        },
        defeat: {
            play: function() { console.log('Звук "defeat" не найден или не загружен'); }
        }
    };
    
    // Проверяем доступность библиотеки Howler
    if (typeof Howl !== 'undefined') {
        console.log('Howler.js загружена, инициализируем звуки');
        
        // Список звуков для загрузки
        const soundsList = [
            { name: 'hit', path: 'sounds/hit.mp3', volume: 0.5 },
            { name: 'slash', path: 'sounds/slash.mp3', volume: 0.5 },
            { name: 'block', path: 'sounds/block.mp3', volume: 0.4 },
            { name: 'heal', path: 'sounds/heal.mp3', volume: 0.5 },
            { name: 'victory', path: 'sounds/victory.mp3', volume: 0.5 },
            { name: 'defeat', path: 'sounds/defeat.mp3', volume: 0.5 }
        ];
        
        // Загружаем все звуки
        soundsList.forEach(function(sound) {
            try {
                window.sounds[sound.name] = new Howl({
                    src: [sound.path],
                    volume: sound.volume,
                    onload: function() {
                        console.log(`Звук "${sound.name}" успешно загружен`);
                    },
                    onloaderror: function() {
                        console.error(`Ошибка загрузки звука "${sound.name}"`);
                    }
                });
            } catch (error) {
                console.error(`Ошибка при инициализации звука "${sound.name}":`, error);
            }
        });
    } else {
        console.warn('Библиотека Howler.js не найдена, звуковые эффекты не будут работать');
    }
});

// Функция для воспроизведения звуков с защитой от ошибок
function playSound(name) {
    if (window.sounds && window.sounds[name]) {
        try {
            window.sounds[name].play();
        } catch (error) {
            console.error(`Ошибка при воспроизведении звука "${name}":`, error);
        }
    } else {
        console.warn(`Звук "${name}" не найден`);
    }
}

// Экспортируем функцию воспроизведения
window.playBattleSound = playSound;