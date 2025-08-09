// Кристаллическая шахта — Match-3 с ограниченным числом ходов
// Автор: AI

/* 
Бонусы за особые комбинации:
- 4 камня в ряд: +50 бонусных очков и золотое свечение
- 5 камней в ряд: +100 бонусных очков, огненное свечение и удаление всех камней того же типа
*/

const BOARD_SIZE = 6;
const GEM_TYPES = 6;
const SCORE_PER_GEM = 10;
const MAX_MOVES = 25; // Максимальное количество ходов вместо таймера

// DOM элементы
const boardEl = document.getElementById('mine-board');
const scoreEl = document.getElementById('mine-score');
const timerEl = document.getElementById('mine-timer');
const restartBtn = document.getElementById('mine-restart');
const missionCountEl = document.getElementById('mine-mission-count');
const missionIconEl = document.getElementById('mine-mission-icon');
const missionBarEl = document.getElementById('mine-mission-bar');
const missionDisplayEl = document.getElementById('mine-mission-display');
const movesTextEl = document.getElementById('mine-moves-text');

// Элементы модального окна результатов
const resultModal = document.getElementById('result-modal');
const resultScore = document.getElementById('result-score');
const resultMissionStatus = document.getElementById('result-mission-status');
const resultMissionBar = document.getElementById('result-mission-bar');
const resultMissionProgress = document.getElementById('result-mission-progress');
const resultRewards = document.getElementById('result-rewards');
const resultRestartBtn = document.getElementById('result-restart');
const resultExitBtn = document.getElementById('result-exit');
const resultCloseBtn = document.querySelector('.mine-modal-close');

// Кнопки меню
const btnPlay = document.getElementById('btn-play');
const btnLeaderboard = document.getElementById('btn-leaderboard');
const btnReturn = document.getElementById('btn-return');
const btnMenu = document.getElementById('btn-menu');
const btnBackFromLeaderboard = document.getElementById('btn-back-from-leaderboard');

// Экраны
const menuScreen = document.getElementById('mine-menu');
const gameScreen = document.getElementById('mine-game');
const leaderboardScreen = document.getElementById('mine-leaderboard');

// Состояние игры
let board = [];
let selected = null;
let score = 0;
let movesLeft = MAX_MOVES;
let isAnimating = false;
let gameActive = false;
let currentMission = null;
let missionProgress = 0;

// Возможные задания
const missions = [
  { type: 0, count: 30, name: 'Урон' },
  { type: 1, count: 35, name: 'Броню' },
  { type: 2, count: 40, name: 'Уворот' },
  { type: 3, count: 30, name: 'Капли' },
  { type: 4, count: 25, name: 'Оглушение' },
  { type: 5, count: 30, name: 'Здоровье' }
];

// Создаем и загружаем звуки (с проверкой их существования)
let audioSwap, audioMatch, audioMissionComplete;
try {
  audioSwap = new Audio('/sounds/mine/swap.mp3');
  audioMatch = new Audio('/sounds/mine/match.mp3');
  audioMissionComplete = new Audio('/sounds/mine/complete.mp3');
  
  audioSwap.volume = 0.5;
  audioMatch.volume = 0.6;
  audioMissionComplete.volume = 0.7;
  
  // Проверяем загрузку звуков
  audioSwap.addEventListener('error', () => {
    console.warn('Звук swap.mp3 не загружен');
    audioSwap = null;
  });
  
  audioMatch.addEventListener('error', () => {
    console.warn('Звук match.mp3 не загружен');
    audioMatch = null;
  });
  
  audioMissionComplete.addEventListener('error', () => {
    console.warn('Звук complete.mp3 не загружен');
    audioMissionComplete = null;
  });
} catch(e) {
  console.warn('Аудио не поддерживается');
}

function play(audio) {
  if (audio && audio.play) {
    try {
      audio.currentTime = 0;
      audio.play();
    } catch(e) {}
  }
}

// Предзагрузка изображений (опциональное улучшение производительности)
function preloadImages() {
  for (let i = 0; i < GEM_TYPES; i++) {
    const img = new Image();
    img.src = `/images/mine/gems/gem${i}.png`;
    // Retina версия
    const img2x = new Image();
    img2x.src = `/images/mine/gems/gem${i}@2x.png`;
  }
}

// Управление экранами
function showScreen(screen) {
  document.querySelectorAll('.mine-screen').forEach(s => s.classList.remove('active'));
  screen.classList.add('active');
  
  // Сбрасываем позицию прокрутки экрана
  window.scrollTo(0, 0);
  
  // Принудительно устанавливаем позицию для мобильных устройств
  setTimeout(() => {
    window.scrollTo(0, 0);
    
    if (screen === gameScreen) {
      // Делаем элементы игры кликабельными
      const board = document.getElementById('mine-board');
      if (board) {
        board.style.pointerEvents = 'auto';
        Array.from(board.querySelectorAll('.gem')).forEach(gem => {
          gem.style.pointerEvents = 'auto';
        });
      }
    }
  }, 10);
}

// Выбор случайного задания
function getRandomMission() {
  const missionIndex = Math.floor(Math.random() * missions.length);
  return { ...missions[missionIndex] };
}

// Обновление прогресса задания
function updateMissionProgress(matchedGems) {
  if (!currentMission) return;
  
  // Подсчитываем совпадения драгоценных камней нужного типа
  const previousProgress = missionProgress;
  let gemCount = 0;
  
  matchedGems.forEach(gem => {
    if (gem.type === currentMission.type) {
      gemCount++;
    }
  });
  
  if (gemCount > 0) {
    missionProgress = Math.min(currentMission.count, missionProgress + gemCount);
    
    // Обновляем отображение - показываем оставшееся количество
    const remaining = Math.max(0, currentMission.count - missionProgress);
    missionCountEl.textContent = remaining;
    
    const progressPercent = (missionProgress / currentMission.count) * 100;
    missionBarEl.style.width = `${progressPercent}%`;
    
    // Проверяем выполнение задания
    if (previousProgress < currentMission.count && missionProgress >= currentMission.count) {
      missionDisplayEl.classList.add('mission-complete');
      play(audioMissionComplete);
      
      // Бонус к очкам за выполнение задания
      const bonus = currentMission.count * 5;
      score += bonus;
      scoreEl.textContent = score;
      
      // Показываем бонус
      const combo = document.createElement('div');
      combo.className = 'combo-text';
      combo.textContent = `+${bonus}!`;
      combo.style.color = 'var(--success)';
      boardEl.appendChild(combo);
      setTimeout(() => combo.remove(), 1000);
    }
  }
}

// Отображение текущего задания
function displayMission() {
  if (!currentMission) return;
  
  // Показываем оставшееся количество вместо собранного
  const remaining = Math.max(0, currentMission.count - missionProgress);
  missionCountEl.textContent = remaining;
  
  missionIconEl.src = `/images/mine/gems/gem${currentMission.type}.png`;
  missionBarEl.style.width = '0%';
  missionDisplayEl.classList.remove('mission-complete');
}

// Функция для склонения слова "ход" в зависимости от числа
function declineMoves(number) {
  const lastDigit = number % 10;
  const lastTwoDigits = number % 100;
  
  if (lastTwoDigits >= 11 && lastTwoDigits <= 19) {
    return 'ходов';
  }
  
  if (lastDigit === 1) {
    return 'ход';
  }
  
  if (lastDigit >= 2 && lastDigit <= 4) {
    return 'хода';
  }
  
  return 'ходов';
}

// Обновление отображения оставшихся ходов
function updateMovesDisplay() {
  timerEl.textContent = movesLeft;
  movesTextEl.textContent = declineMoves(movesLeft);
}

function randGem() {
  return Math.floor(Math.random() * GEM_TYPES);
}

function initBoard() {
  board = new Array(BOARD_SIZE).fill(0).map(() => new Array(BOARD_SIZE).fill(0));
  
  // Создаем начальную доску без совпадений
  do {
    for (let x = 0; x < BOARD_SIZE; x++) {
      for (let y = 0; y < BOARD_SIZE; y++) {
        board[x][y] = randGem();
      }
    }
  } while (hasMatches()); // избегаем стартовых совпадений
  
  // Проверяем, есть ли возможные ходы на начальной доске
  if (!hasPossibleMoves()) {
    // Если нет, перегенерируем доску
    console.log("Начальная доска не имеет возможных ходов, перегенерация...");
    initBoard(); // рекурсивно пересоздаем доску
    return;
  }

  renderBoard();
}

function coordsToIndex(x, y) {
  return x * BOARD_SIZE + y;
}

function renderBoard() {
  boardEl.innerHTML = '';
  boardEl.style.position = 'relative';
  boardEl.style.pointerEvents = 'auto';
  
  for (let x = 0; x < BOARD_SIZE; x++) {
    for (let y = 0; y < BOARD_SIZE; y++) {
      const gemType = board[x][y];
      const cell = document.createElement('div');
      cell.className = `gem gem-${gemType}`;
      cell.dataset.x = x;
      cell.dataset.y = y;
      cell.dataset.type = gemType;
      cell.style.pointerEvents = 'auto';
      boardEl.appendChild(cell);
    }
  }
}

function isAdjacent(a, b) {
  return (Math.abs(a.x - b.x) + Math.abs(a.y - b.y)) === 1;
}

function swap(a, b) {
  const temp = board[a.x][a.y];
  board[a.x][a.y] = board[b.x][b.y];
  board[b.x][b.y] = temp;
}

// Проверяет, есть ли на поле возможные ходы
function hasPossibleMoves() {
  // Проверяем все возможные обмены фишек
  for (let x = 0; x < BOARD_SIZE; x++) {
    for (let y = 0; y < BOARD_SIZE; y++) {
      // Проверяем соседа справа
      if (y < BOARD_SIZE - 1) {
        // Временно меняем фишки
        swap({x, y}, {x, y: y + 1});
        // Проверяем, появились ли совпадения
        const hasMatch = hasMatches();
        // Возвращаем фишки на место
        swap({x, y}, {x, y: y + 1});
        if (hasMatch) return true;
      }
      
      // Проверяем соседа снизу
      if (x < BOARD_SIZE - 1) {
        // Временно меняем фишки
        swap({x, y}, {x: x + 1, y});
        // Проверяем, появились ли совпадения
        const hasMatch = hasMatches();
        // Возвращаем фишки на место
        swap({x, y}, {x: x + 1, y});
        if (hasMatch) return true;
      }
    }
  }
  
  // Если дошли сюда, то возможных ходов нет
  return false;
}

// Перемешивает фишки на поле до появления возможных ходов
function shuffleBoard() {
  console.log("Перемешивание фишек, нет доступных ходов...");
  
  // Создаем визуальный эффект перемешивания
  const shuffleText = document.createElement('div');
  shuffleText.className = 'combo-text';
  shuffleText.textContent = 'Перемешивание!';
  shuffleText.style.fontSize = 'clamp(18px, 6vw, 28px)';
  boardEl.appendChild(shuffleText);
  
  // Перемешиваем доску, пока не появятся возможные ходы
  setTimeout(() => {
    shuffleText.remove();
    
    do {
      // Случайно перемешиваем все фишки
      for (let x = 0; x < BOARD_SIZE; x++) {
        for (let y = 0; y < BOARD_SIZE; y++) {
          // Случайная позиция для обмена
          const rx = Math.floor(Math.random() * BOARD_SIZE);
          const ry = Math.floor(Math.random() * BOARD_SIZE);
          // Меняем фишки местами
          const temp = board[x][y];
          board[x][y] = board[rx][ry];
          board[rx][ry] = temp;
        }
      }
      
      // Убеждаемся, что нет начальных совпадений
      while (hasMatches()) {
        for (let x = 0; x < BOARD_SIZE; x++) {
          for (let y = 0; y < BOARD_SIZE; y++) {
            if (board[x][y] === null) {
              board[x][y] = randGem();
            }
          }
        }
      }
    } while (!hasPossibleMoves());
    
    // Обновляем отображение
    renderBoard();
    isAnimating = false;
  }, 800);
}

function hasMatches() {
  return findMatches().matches.length > 0;
}

function findMatches() {
  const matches = [];
  const matchSizes = []; // Размеры совпадений

  // горизонтальные
  for (let x = 0; x < BOARD_SIZE; x++) {
    let streak = 1;
    for (let y = 1; y <= BOARD_SIZE; y++) {
      const current = y < BOARD_SIZE ? board[x][y] : null;
      const prev = board[x][y - 1];
      if (current === prev) {
        streak++;
      } else {
        if (streak >= 3) {
          const matchCoords = [];
          for (let k = 0; k < streak; k++) {
            const matchPos = { x, y: y - 1 - k, type: prev };
            matches.push(matchPos);
            matchCoords.push(matchPos);
          }
          // Сохраняем информацию о размере и координатах совпадения
          matchSizes.push({
            size: streak,
            coords: matchCoords,
            direction: 'horizontal'
          });
        }
        streak = 1;
      }
    }
  }

  // вертикальные
  for (let y = 0; y < BOARD_SIZE; y++) {
    let streak = 1;
    for (let x = 1; x <= BOARD_SIZE; x++) {
      const current = x < BOARD_SIZE ? board[x][y] : null;
      const prev = board[x - 1][y];
      if (current === prev) {
        streak++;
      } else {
        if (streak >= 3) {
          const matchCoords = [];
          for (let k = 0; k < streak; k++) {
            const matchPos = { x: x - 1 - k, y, type: prev };
            matches.push(matchPos);
            matchCoords.push(matchPos);
          }
          // Сохраняем информацию о размере и координатах совпадения
          matchSizes.push({
            size: streak,
            coords: matchCoords,
            direction: 'vertical'
          });
        }
        streak = 1;
      }
    }
  }

  return { matches, matchSizes };
}

function removeMatches(matchInfo) {
  const { matches, matchSizes } = matchInfo;
  
  if (matches.length > 0) play(audioMatch);
  
  // Обрабатываем специальные комбинации (4+ в ряд)
  processSpecialMatches(matchSizes);
  
  updateMissionProgress(matches);
  
  // Визуальное улучшение: добавляем прозрачность и эффекты
  matches.forEach(({ x, y }) => {
    board[x][y] = null;
    const index = coordsToIndex(x, y);
    const cell = boardEl.children[index];
    if (cell) {
      cell.classList.remove('selected');
      cell.classList.add('explode');
    }
  });
}

function collapseBoard() {
  for (let y = 0; y < BOARD_SIZE; y++) {
    let pointer = BOARD_SIZE - 1;
    for (let x = BOARD_SIZE - 1; x >= 0; x--) {
      if (board[x][y] !== null) {
        board[pointer][y] = board[x][y];
        if (pointer !== x) board[x][y] = null;
        pointer--;
      }
    }
    for (let x = pointer; x >= 0; x--) {
      board[x][y] = randGem();
    }
  }
}

function updateScore(numGems) {
  score += numGems * SCORE_PER_GEM;
  scoreEl.textContent = score;
}

// Уменьшение счетчика ходов после обработки хода
function decrementMoves() {
  movesLeft--;
  updateMovesDisplay();
  
  if (movesLeft <= 0) {
    // Если ходы закончились, завершаем игру
    endGame();
  }
}

// Обработка касаний для свайпов
let touchStartX, touchStartY, touchEndX, touchEndY;
let touchStartTime;
const MIN_SWIPE_DISTANCE = 30;
const MAX_SWIPE_TIME = 500;

function handleTouchStart(e) {
  if (!e.target.classList.contains('gem') || isAnimating) return;
  e.preventDefault(); // Предотвращаем дефолтное поведение браузера
  
  touchStartX = e.touches[0].clientX;
  touchStartY = e.touches[0].clientY;
  touchStartTime = new Date().getTime();
  
  const cell = e.target;
  const pos = { 
    x: parseInt(cell.dataset.x), 
    y: parseInt(cell.dataset.y) 
  };
  selected = pos;
  cell.classList.add('selected');
}

function handleTouchMove(e) {
  if (!selected) return;
  e.preventDefault(); // Предотвращаем скролл страницы только когда есть выбранный элемент
}

function handleTouchEnd(e) {
  if (!selected || isAnimating) return;
  e.preventDefault(); // Предотвращаем дефолтное поведение браузера
  
  const touchEndTime = new Date().getTime();
  const touchTime = touchEndTime - touchStartTime;
  
  if (touchTime > MAX_SWIPE_TIME) {
    // Слишком медленный свайп, отменяем
    const prevCell = boardEl.children[coordsToIndex(selected.x, selected.y)];
    if (prevCell) prevCell.classList.remove('selected');
    selected = null;
    return;
  }
  
  touchEndX = e.changedTouches[0].clientX;
  touchEndY = e.changedTouches[0].clientY;
  
  const diffX = touchEndX - touchStartX;
  const diffY = touchEndY - touchStartY;
  const absDiffX = Math.abs(diffX);
  const absDiffY = Math.abs(diffY);
  
  // Выбираем направление свайпа с наибольшим смещением
  if (Math.max(absDiffX, absDiffY) < MIN_SWIPE_DISTANCE) {
    // Слишком короткий свайп, считаем как обычный клик
    const prevCell = boardEl.children[coordsToIndex(selected.x, selected.y)];
    if (prevCell) prevCell.classList.remove('selected');
    selected = null;
    return;
  }
  
  let nextPos;
  
  if (absDiffX > absDiffY) {
    // Горизонтальный свайп
    if (diffX > 0) {
      // Вправо
      nextPos = { x: selected.x, y: Math.min(selected.y + 1, BOARD_SIZE - 1) };
    } else {
      // Влево
      nextPos = { x: selected.x, y: Math.max(selected.y - 1, 0) };
    }
  } else {
    // Вертикальный свайп
    if (diffY > 0) {
      // Вниз
      nextPos = { x: Math.min(selected.x + 1, BOARD_SIZE - 1), y: selected.y };
    } else {
      // Вверх
      nextPos = { x: Math.max(selected.x - 1, 0), y: selected.y };
    }
  }
  
  const prevCell = boardEl.children[coordsToIndex(selected.x, selected.y)];
  if (prevCell) prevCell.classList.remove('selected');
  
  if (isAdjacent(selected, nextPos)) {
    makeMove(selected, nextPos);
  }
  
  selected = null;
}

// Общая функция для выполнения хода
function makeMove(pos1, pos2) {
  swap(pos1, pos2);
  play(audioSwap);
  isAnimating = true;
  renderBoard();
  
  setTimeout(() => {
    const matchInfo = findMatches();
    if (matchInfo.matches.length === 0) {
      // откат, если совпадений нет
      swap(pos1, pos2);
      renderBoard();
      isAnimating = false;
    } else {
      // Уменьшаем счетчик ходов ТОЛЬКО если ход валидный
      decrementMoves();
      chainResolve(matchInfo);
    }
  }, 80);
}

function handleClick(e) {
  if (!e.target.classList.contains('gem') || isAnimating) return;
  // Добавляем stopPropagation для предотвращения ложных кликов
  e.stopPropagation();
  
  const cell = e.target;
  const pos = { x: parseInt(cell.dataset.x), y: parseInt(cell.dataset.y) };

  if (!selected) {
    selected = pos;
    cell.classList.add('selected');
  } else {
    const prevCell = boardEl.children[coordsToIndex(selected.x, selected.y)];
    prevCell.classList.remove('selected');

    if (isAdjacent(selected, pos)) {
      makeMove(selected, pos);
    }
    selected = null;
  }
}

function showCombo(chain) {
  if (chain <= 1) return;
  const combo = document.createElement('div');
  combo.className = 'combo-text';
  combo.textContent = `x${chain}!`;
  boardEl.appendChild(combo);
  setTimeout(() => combo.remove(), 1000);
}

function chainResolve(initMatchInfo) {
  let { matches, matchSizes } = initMatchInfo;
  let chainCount = 0;
  function step() {
    if (matches.length === 0) {
      isAnimating = false;
      
      // Проверяем наличие возможных ходов после завершения всех каскадных совпадений
      setTimeout(() => {
        if (!hasPossibleMoves() && gameActive) {
          isAnimating = true;
          shuffleBoard();
        }
      }, 500);
      
      return;
    }
    chainCount++;
    showCombo(chainCount);
    updateScore(matches.length * chainCount);
    removeMatches({ matches, matchSizes });
    setTimeout(() => {
      collapseBoard();
      renderBoard();
      const newMatchInfo = findMatches();
      matches = newMatchInfo.matches;
      matchSizes = newMatchInfo.matchSizes;
      step();
    }, 320);
  }
  step();
}

// Показать модальное окно результатов
function showResultModal(results) {
  // Установка счета
  resultScore.textContent = score;
  
  // Статус выполнения миссии
  const isMissionCompleted = missionProgress >= currentMission.count;
  resultMissionStatus.textContent = isMissionCompleted ? 'Задание выполнено!' : 'Задание не выполнено';
  resultMissionStatus.className = 'mine-mission-status ' + (isMissionCompleted ? 'success' : 'fail');
  
  // Прогресс миссии
  const progressPercent = Math.min(100, (missionProgress / currentMission.count) * 100);
  resultMissionBar.style.width = `${progressPercent}%`;
  resultMissionBar.style.background = isMissionCompleted ? 'var(--success)' : 'var(--accent)';
  resultMissionProgress.textContent = `${missionProgress}/${currentMission.count}`;
  
  // Очистка предыдущих наград
  resultRewards.innerHTML = '';
  
  // Отображение наград
  if (results && results.rewards && results.rewards.length > 0) {
    results.rewards.forEach(reward => {
      const rewardItem = document.createElement('div');
      rewardItem.className = 'mine-reward-item';
      
      let icon, title, amount;
      
      if ('gold' in reward) {
        icon = 'mine-reward-gold';
        title = 'Золото';
        amount = reward.gold;
      } else if ('silver' in reward) {
        icon = 'mine-reward-silver';
        title = 'Серебро';
        amount = reward.silver;
      } else if ('item' in reward) {
        icon = 'mine-reward-gem';
        title = 'Самоцвет';
        amount = 'x1';
      }
      
      rewardItem.innerHTML = `
        <div class="mine-reward-icon ${icon}"></div>
        <div class="mine-reward-info">
          <div class="mine-reward-title">${title}</div>
          <div class="mine-reward-amount">${amount}</div>
        </div>
      `;
      
      resultRewards.appendChild(rewardItem);
    });
  } else {
    // Если нет наград
    resultRewards.innerHTML = '<div class="no-rewards">Нет наград</div>';
  }
  
  // Показываем модальное окно
  resultModal.classList.add('active');
}

// Закрыть модальное окно результатов
function closeResultModal() {
  resultModal.classList.remove('active');
}

function endGame() {
  boardEl.removeEventListener('click', handleClick);
  boardEl.removeEventListener('touchstart', handleTouchStart);
  boardEl.removeEventListener('touchmove', handleTouchMove);
  boardEl.removeEventListener('touchend', handleTouchEnd);
  
  gameActive = false;
  isAnimating = true;
  
  // Данные для отправки
  const requestData = { 
    score, 
    token: MINE_TOKEN,
    mission_completed: missionProgress >= currentMission.count,
    mission_type: currentMission.type,
    mission_progress: missionProgress
  };
  
  console.log("Отправляем данные:", JSON.stringify(requestData));
  
  // Отправляем результат
  fetch('/mine/score.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(requestData)
  })
    .then(r => r.json())
    .then(res => {
      console.log("Ответ сервера:", res);
      
      if (res.status === 'error' && res.message && res.message.includes('Токен')) {
        alert('Сессия устарела. Перезагружаем игру...');
        // Обновляем страницу, чтобы получить новый токен
        window.location.reload();
        return;
      }
      
      // Показываем модальное окно вместо alert
      showResultModal(res);
      restartBtn.disabled = false;
    })
    .catch((error) => {
      console.error("Ошибка при отправке результата:", error);
      
      // В случае ошибки тоже показываем модальное окно, но без наград
      showResultModal(null);
      restartBtn.disabled = false;
    });
}

// Отдельный обработчик для кнопки назад из рейтинга
function handleBackFromLeaderboard() {
  console.log('Нажата кнопка "Назад" из рейтинга');
  resetScroll();
  showScreen(menuScreen);
}

function setupListeners() {
  // Кнопки управления меню
  btnPlay.addEventListener('click', () => {
    resetScroll();
    showScreen(gameScreen);
    startGame();
  });
  
  btnLeaderboard.addEventListener('click', () => {
    resetScroll();
    showScreen(leaderboardScreen);
  });
  
  btnReturn.addEventListener('click', () => {
    resetScroll();
    window.location.href = '/';
  });
  
  btnMenu.addEventListener('click', () => {
    resetScroll();
    showScreen(menuScreen);
  });
  
  // Добавляем обработчик с явным вызовом и проверкой
  if (btnBackFromLeaderboard) {
    console.log('Настройка обработчика для кнопки "Назад" из рейтинга');
    btnBackFromLeaderboard.addEventListener('click', handleBackFromLeaderboard);
    // Добавляем альтернативный обработчик для мобильных устройств
    btnBackFromLeaderboard.addEventListener('touchend', function(e) {
      e.preventDefault();
      handleBackFromLeaderboard();
    });
  } else {
    console.error('Кнопка "Назад" из рейтинга не найдена!');
  }
  
  // Управление игрой
  restartBtn.addEventListener('click', () => {
    if (!restartBtn.disabled) startGame();
  });
  
  // Обработчики для модального окна результатов
  resultRestartBtn.addEventListener('click', () => {
    closeResultModal();
    startGame();
  });
  
  resultExitBtn.addEventListener('click', () => {
    window.location.href = "/";
  });
  
  resultCloseBtn.addEventListener('click', closeResultModal);
}

function startGame() {
  // Проверяем, что токен существует и не пустой
  if (!MINE_TOKEN || MINE_TOKEN === 'undefined' || MINE_TOKEN.trim() === '') {
    // Если токен недействителен, перезагружаем страницу для получения нового
    console.error('Недействительный токен игры, перезагрузка страницы...');
    window.location.reload();
    return;
  }
  
  // Сбрасываем состояние игры
  score = 0;
  scoreEl.textContent = '0';
  movesLeft = MAX_MOVES;
  updateMovesDisplay();
  
  // Выбираем случайное задание
  currentMission = getRandomMission();
  missionProgress = 0;
  displayMission();
  
  // Убираем выделенный кристалл и активируем игру
  selected = null;
  isAnimating = false;
  gameActive = true;
  
  // Создаем игровое поле
  initBoard();
  
  // Добавляем обработчики событий
  boardEl.addEventListener('click', handleClick);
  boardEl.addEventListener('touchstart', handleTouchStart);
  boardEl.addEventListener('touchmove', handleTouchMove);
  boardEl.addEventListener('touchend', handleTouchEnd);
}

function init() {
  // Настройка игры
  preloadImages();
  setupListeners();
  
  // При загрузке страницы сбрасываем скролл
  window.scrollTo(0, 0);
  
  // Предотвращаем проблемы с 100vh на мобильных устройствах (особенно iOS)
  function setViewportHeight() {
    const vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', `${vh}px`);
  }
  
  // Устанавливаем высоту вьюпорта при загрузке и при изменении размера
  setViewportHeight();
  window.addEventListener('resize', setViewportHeight);
  window.addEventListener('orientationchange', () => {
    setTimeout(setViewportHeight, 100);
  });
  
  // Запускаем игру
  showScreen(menuScreen);
  
  // Обработка ошибок при загрузке игры
  window.addEventListener('error', (e) => {
    console.error('Game error:', e.message);
  });
}

// Запускаем инициализацию игры при загрузке страницы
document.addEventListener('DOMContentLoaded', init);

// Добавим временное исправление для загрузки игры, если DOMContentLoaded уже сработал
if (document.readyState === 'complete' || document.readyState === 'interactive') {
  setTimeout(init, 1);
}

// Функция для сброса прокрутки
function resetScroll() {
  // Сбрасываем позицию экрана без блокировки прокрутки
  window.scrollTo(0, 0);
  
  // Повторный сброс через RAF для надежности
  requestAnimationFrame(() => {
    window.scrollTo(0, 0);
  });
}

// Обработка специальных комбинаций (4+ в ряд)
function processSpecialMatches(matchSizes) {
  for (const match of matchSizes) {
    if (match.size >= 4) {
      // Добавляем визуальный эффект для больших комбинаций
      showSpecialMatchEffect(match);
      
      // Бонусные очки за большие комбинации
      const bonusPoints = match.size === 4 ? 50 : 100;
      score += bonusPoints;
      scoreEl.textContent = score;
      
      // Показываем бонусные очки
      const bonusText = document.createElement('div');
      bonusText.className = 'combo-text';
      bonusText.textContent = `+${bonusPoints}`;
      bonusText.style.color = match.size === 4 ? '#FFD700' : '#FF4500';
      bonusText.style.fontWeight = 'bold';
      boardEl.appendChild(bonusText);
      setTimeout(() => bonusText.remove(), 1000);
      
      // Дополнительные эффекты для комбинаций из 5 камней
      if (match.size >= 5) {
        // Удаляем все камни того же типа на доске (для комбинаций из 5+)
        const gemType = match.coords[0].type;
        const extraRemoved = removeAllGemsOfType(gemType);
        
        // Дополнительные очки за удаленные фишки
        if (extraRemoved > 0) {
          score += extraRemoved * 5;
          scoreEl.textContent = score;
          
          // Показываем эффект
          const extraText = document.createElement('div');
          extraText.className = 'combo-text special-combo';
          extraText.textContent = `СУПЕР! +${extraRemoved * 5}`;
          boardEl.appendChild(extraText);
          setTimeout(() => extraText.remove(), 1500);
        }
      }
    }
  }
}

// Удаляет все камни заданного типа
function removeAllGemsOfType(gemType) {
  let count = 0;
  const toRemove = [];
  
  for (let x = 0; x < BOARD_SIZE; x++) {
    for (let y = 0; y < BOARD_SIZE; y++) {
      if (board[x][y] === gemType) {
        toRemove.push({x, y});
        count++;
      }
    }
  }
  
  // Анимируем удаление
  toRemove.forEach(({x, y}) => {
    const index = coordsToIndex(x, y);
    const cell = boardEl.children[index];
    if (cell) {
      cell.classList.add('explode-special');
      setTimeout(() => {
        board[x][y] = null;
      }, 100);
    }
  });
  
  return count;
}

// Визуальный эффект для больших комбинаций
function showSpecialMatchEffect(match) {
  // Создаем элемент для эффекта
  const effect = document.createElement('div');
  effect.className = 'special-match-effect';
  
  // Определяем центр комбинации для отображения эффекта
  const centerIdx = Math.floor(match.coords.length / 2);
  const centerPos = match.coords[centerIdx];
  const centerIdx2D = coordsToIndex(centerPos.x, centerPos.y);
  const centerEl = boardEl.children[centerIdx2D];
  
  if (centerEl) {
    const rect = centerEl.getBoundingClientRect();
    const boardRect = boardEl.getBoundingClientRect();
    
    // Располагаем эффект по центру комбинации
    effect.style.left = `${rect.left - boardRect.left - rect.width/2}px`;
    effect.style.top = `${rect.top - boardRect.top - rect.height/2}px`;
    effect.style.width = `${rect.width * 2}px`;
    effect.style.height = `${rect.height * 2}px`;
    
    // Стиль эффекта зависит от размера комбинации
    effect.style.background = match.size === 4 
      ? 'radial-gradient(circle, rgba(255,215,0,0.6) 0%, rgba(255,215,0,0) 70%)' 
      : 'radial-gradient(circle, rgba(255,69,0,0.6) 0%, rgba(255,69,0,0) 70%)';
    
    // Добавляем и удаляем эффект
    boardEl.appendChild(effect);
    setTimeout(() => {
      effect.style.opacity = '0';
      setTimeout(() => effect.remove(), 300);
    }, 700);
  }
} 