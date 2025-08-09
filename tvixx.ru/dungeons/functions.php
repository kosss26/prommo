<?php
/**
 * Вспомогательные функции для раздела подземелий
 */

/**
 * Генерирует новую комнату в подземелье
 * @param int $dungeon_id ID подземелья
 * @param int $room_number Номер комнаты
 * @return array Данные комнаты
 */
function generateRoom($dungeon_id, $room_number) {
    global $mc;
    
    // Получаем данные подземелья
    $dungeon = $mc->query("SELECT * FROM `dungeons` WHERE `id` = '$dungeon_id'")->fetch_array(MYSQLI_ASSOC);
    if (!$dungeon) {
        return null;
    }
    
    // Определяем тип комнаты
    $room_types = [
        'normal' => 60,
        'puzzle' => 20,
        'trap' => 15,
        'boss' => 5
    ];
    
    // Если это последняя комната, то это комната с боссом
    $difficulty = isset($dungeon['difficulty']) ? $dungeon['difficulty'] : 1;
    $max_rooms = 10 + ($difficulty * 2); // Примерное количество комнат в подземелье
    
    if ($room_number >= $max_rooms) {
        $room_type = 'boss';
    } else {
        $total = array_sum($room_types);
        $rand = rand(1, $total);
        $current = 0;
        
        foreach ($room_types as $type => $weight) {
            $current += $weight;
            if ($rand <= $current) {
                $room_type = $type;
                break;
            }
        }
    }
    
    // Генерируем описание комнаты
    $descriptions = [
        'normal' => [
            'Заброшенная библиотека с пыльными книгами',
            'Темный коридор с потухшими факелами',
            'Древний склеп с каменными саркофагами',
            'Загадочная комната с древними фресками',
            'Заброшенная кузница с потухшим горном'
        ],
        'puzzle' => [
            'Комната с древними механизмами',
            'Загадочная комната с символами',
            'Комната с древними статуями',
            'Комната с магическими кристаллами',
            'Комната с древними свитками'
        ],
        'trap' => [
            'Комната с ядовитыми шипами',
            'Комната с падающими камнями',
            'Комната с огненными ловушками',
            'Комната с ядовитым газом',
            'Комната с электрическими ловушками'
        ],
        'boss' => [
            'Тронный зал древнего короля',
            'Логово дракона',
            'Чертог лича',
            'Темница демона',
            'Святилище древнего бога'
        ]
    ];
    
    $description = $descriptions[$room_type][array_rand($descriptions[$room_type])];
    
    // Генерируем фон комнаты
    $backgrounds = [
        'normal' => [
            'library.jpg',
            'corridor.jpg',
            'crypt.jpg',
            'fresco.jpg',
            'forge.jpg'
        ],
        'puzzle' => [
            'mechanism.jpg',
            'symbols.jpg',
            'statues.jpg',
            'crystals.jpg',
            'scrolls.jpg'
        ],
        'trap' => [
            'spikes.jpg',
            'rocks.jpg',
            'fire.jpg',
            'gas.jpg',
            'electric.jpg'
        ],
        'boss' => [
            'throne.jpg',
            'dragon.jpg',
            'lich.jpg',
            'demon.jpg',
            'temple.jpg'
        ]
    ];
    
    $background = $backgrounds[$room_type][array_rand($backgrounds[$room_type])];
    
    // Генерируем объекты в комнате
    $objects = [];
    switch ($room_type) {
        case 'normal':
            $objects = generateNormalRoomObjects($difficulty);
            break;
        case 'puzzle':
            $objects = generatePuzzleRoomObjects($difficulty);
            break;
        case 'trap':
            $objects = generateTrapRoomObjects($difficulty);
            break;
        case 'boss':
            $objects = generateBossRoomObjects($difficulty);
            break;
    }
    
    // Генерируем выходы из комнаты
    $exits = generateRoomExits($room_number);
    
    return [
        'type' => $room_type,
        'level' => $room_number,  // Добавляем уровень комнаты
        'description' => $description,
        'background' => $background,
        'objects' => $objects,
        'exits' => $exits,
        'cleared' => false
    ];
}

/**
 * Генерирует объекты для обычной комнаты
 * @param int $difficulty Сложность подземелья
 * @return array Массив объектов
 */
function generateNormalRoomObjects($difficulty) {
    $objects = [];
    
    // Шанс появления сундука
    if (rand(1, 100) <= 30) {
        $objects[] = [
            'type' => 'chest',
            'description' => 'Древний сундук',
            'interacted' => false,
            'loot' => generateDungeonItem($difficulty)
        ];
    }
    
    // Шанс появления фонтана
    if (rand(1, 100) <= 20) {
        $objects[] = [
            'type' => 'fountain',
            'description' => 'Фонтан с целебной водой',
            'interacted' => false,
            'heal_amount' => rand(10, 30)
        ];
    }
    
    // Шанс появления монстра
    if (rand(1, 100) <= 50) {
        $objects[] = [
            'type' => 'monster',
            'description' => 'Враждебное существо',
            'interacted' => false,
            'monster_id' => generateMonster($difficulty)
        ];
    }
    
    return $objects;
}

/**
 * Генерирует объекты для комнаты с головоломкой
 * @param int $difficulty Сложность подземелья
 * @return array Массив объектов
 */
function generatePuzzleRoomObjects($difficulty) {
    $objects = [];
    
    // Типы головоломок
    $puzzle_types = [
        'riddle' => [
            'description' => 'Древняя загадка',
            'question' => 'Что это такое: у него есть корень, но нет листьев, есть ствол, но нет веток?',
            'answer' => 'число',
            'hint' => 'Это математическое понятие'
        ],
        'mechanism' => [
            'description' => 'Древний механизм',
            'sequence' => [1, 2, 3, 4, 5],
            'hint' => 'Нажмите кнопки в правильном порядке'
        ],
        'symbols' => [
            'description' => 'Древние символы',
            'symbols' => ['☀', '☽', '★', '⚡', '❄'],
            'correct_order' => [1, 3, 4, 2, 5],
            'hint' => 'Расположите символы в правильном порядке'
        ],
        'statues' => [
            'description' => 'Древние статуи',
            'positions' => ['север', 'юг', 'восток', 'запад'],
            'correct_order' => ['север', 'юг', 'восток', 'запад'],
            'hint' => 'Поверните статуи в правильном порядке'
        ]
    ];
    
    $puzzle_type = array_rand($puzzle_types);
    $puzzle = $puzzle_types[$puzzle_type];
    
    $objects[] = [
        'type' => 'puzzle',
        'description' => $puzzle['description'],
        'interacted' => false,
        'puzzle_type' => $puzzle_type,
        'puzzle_data' => $puzzle,
        'reward' => generateDungeonItem($difficulty)
    ];
    
    return $objects;
}

/**
 * Генерирует объекты для комнаты с ловушкой
 * @param int $difficulty Сложность подземелья
 * @return array Массив объектов
 */
function generateTrapRoomObjects($difficulty) {
    $objects = [];
    
    // Типы ловушек
    $trap_types = [
        'spikes' => [
            'description' => 'Ядовитые шипы',
            'damage' => rand(10, 30),
            'avoid_chance' => 0.3
        ],
        'rocks' => [
            'description' => 'Падающие камни',
            'damage' => rand(20, 40),
            'avoid_chance' => 0.4
        ],
        'fire' => [
            'description' => 'Огненные ловушки',
            'damage' => rand(15, 35),
            'avoid_chance' => 0.35
        ],
        'gas' => [
            'description' => 'Ядовитый газ',
            'damage' => rand(5, 15),
            'avoid_chance' => 0.25
        ],
        'electric' => [
            'description' => 'Электрические ловушки',
            'damage' => rand(25, 45),
            'avoid_chance' => 0.45
        ]
    ];
    
    $trap_type = array_rand($trap_types);
    $trap = $trap_types[$trap_type];
    
    $objects[] = [
        'type' => 'trap',
        'description' => $trap['description'],
        'interacted' => false,
        'trap_type' => $trap_type,
        'trap_data' => $trap,
        'reward' => generateDungeonItem($difficulty)
    ];
    
    return $objects;
}

/**
 * Генерирует объекты для комнаты с боссом
 * @param int $difficulty Сложность подземелья
 * @return array Массив объектов
 */
function generateBossRoomObjects($difficulty) {
    $objects = [];
    
    // Генерируем босса
    $boss_id = generateBoss($difficulty);
    
    $objects[] = [
        'type' => 'boss',
        'description' => 'Могущественный враг, охраняющий глубины подземелья',
        'interacted' => false,
        'monster_id' => $boss_id
    ];
    
    // Шанс появления сундука с сокровищами рядом с боссом
    if (rand(1, 100) <= 30) {
        $objects[] = [
            'type' => 'chest',
            'description' => 'Сокровищница босса',
            'interacted' => false,
            'loot' => generateDungeonItem($difficulty + 1) // Более ценный предмет
        ];
    }
    
    return $objects;
}

/**
 * Генерирует предмет подземелья
 * @param int $level Уровень предмета
 * @return array Данные предмета
 */
function generateDungeonItem($level) {
    $item_types = [
        'weapon' => [
            'name' => 'Древний меч',
            'type' => 'weapon',
            'damage' => rand(5, 15),
            'durability' => rand(50, 100)
        ],
        'armor' => [
            'name' => 'Древняя броня',
            'type' => 'armor',
            'defense' => rand(3, 10),
            'durability' => rand(50, 100)
        ],
        'potion' => [
            'name' => 'Целебное зелье',
            'type' => 'potion',
            'heal_amount' => rand(20, 50)
        ],
        'scroll' => [
            'name' => 'Древний свиток',
            'type' => 'scroll',
            'effect' => rand(1, 3)
        ]
    ];
    
    $item_type = array_rand($item_types);
    $item = $item_types[$item_type];
    
    // Добавляем бонусы в зависимости от уровня
    $item['level'] = $level;
    $item['value'] = rand(10, 50) * $level;
    
    return $item;
}

/**
 * Генерирует монстра для подземелья
 * @param int $difficulty Сложность подземелья
 * @return int ID монстра
 */
function generateMonster($difficulty) {
    global $mc;
    
    // Определяем подходящий уровень монстра
    $monster_level = max(1, $difficulty);
    
    // Проверяем, есть ли подходящие монстры в таблице dungeon_monsters
    $query = "SELECT * FROM `dungeon_monsters` WHERE 
              `level` <= '$monster_level' AND 
              `is_boss` = 0 AND
              `difficulty` <= '$difficulty'
              ORDER BY RAND() LIMIT 1";
    
    $result = $mc->query($query);
    
    if ($result && $result->num_rows > 0) {
        // Если есть подходящие монстры, возвращаем ID одного из них
        $monster = $result->fetch_array(MYSQLI_ASSOC);
        return $monster['id'];
    } else {
        // Если нет подходящих монстров, создаем нового
        $monster_types = [
            'goblin', 'skeleton', 'zombie', 'spider', 'rat', 'bat', 'slime', 
            'ghost', 'orc', 'troll', 'elemental', 'demon'
        ];
        
        // Выбираем случайный тип монстра
        $monster_type = $monster_types[array_rand($monster_types)];
        
        // Генерируем имя на основе типа и уровня
        $prefixes = [
            'Малый', 'Слабый', 'Раненый', 'Старый', 'Молодой', 'Голодный', 
            'Злой', 'Яростный', 'Дикий', 'Мутировавший', 'Темный', 'Ядовитый'
        ];
        
        $prefix = $prefixes[array_rand($prefixes)];
        
        // Преобразуем типы монстров на русский язык
        $monster_type_ru = [
            'goblin' => 'гоблин',
            'skeleton' => 'скелет',
            'zombie' => 'зомби',
            'spider' => 'паук',
            'rat' => 'крыса',
            'bat' => 'летучая мышь',
            'slime' => 'слизь',
            'ghost' => 'призрак',
            'orc' => 'орк',
            'troll' => 'тролль',
            'elemental' => 'элементаль',
            'demon' => 'демон'
        ];
        
        $monster_name = $prefix . ' ' . ($monster_type_ru[$monster_type] ?? $monster_type);
        
        // Рассчитываем характеристики монстра на основе уровня и сложности
        $health = 50 + ($monster_level * 20) + (rand(0, 10) * $monster_level);
        $damage = 5 + ($monster_level * 2) + (rand(0, 5));
        $accuracy = 40 + ($monster_level * 2) + (rand(0, 10));
        $evasion = 5 + ($monster_level) + (rand(0, 5));
        $armor = 2 + (int)($monster_level / 2) + (rand(0, 3));
        $block = 3 + (int)($monster_level / 3) + (rand(0, 3));
        $stun = rand(0, 5);
        
        // Генерируем награды
        $gold_min = 5 + ($monster_level * 5);
        $gold_max = 15 + ($monster_level * 10);
        $exp_min = 3 + ($monster_level * 2);
        $exp_max = 8 + ($monster_level * 5);
        
        // Определяем изображение монстра
        $image = $monster_type . '.png';
        
        // Вставляем монстра в базу данных
        $query = "INSERT INTO `dungeon_monsters` 
                  (`name`, `type`, `level`, `difficulty`, `health`, `damage`, 
                   `accuracy`, `evasion`, `armor`, `block`, `stun`, 
                   `gold_min`, `gold_max`, `exp_min`, `exp_max`, `image`) 
                  VALUES 
                  ('$monster_name', '$monster_type', '$monster_level', '$difficulty', 
                   '$health', '$damage', '$accuracy', '$evasion', '$armor', '$block', 
                   '$stun', '$gold_min', '$gold_max', '$exp_min', '$exp_max', '$image')";
        
        $mc->query($query);
        return $mc->insert_id;
    }
}

/**
 * Генерирует босса для подземелья
 * @param int $difficulty Сложность подземелья
 * @return int ID босса
 */
function generateBoss($difficulty) {
    global $mc;
    
    // Определяем подходящий уровень босса
    $boss_level = max(1, $difficulty);
    
    // Проверяем, есть ли подходящие боссы в таблице dungeon_monsters
    $query = "SELECT * FROM `dungeon_monsters` WHERE 
              `level` <= '$boss_level' AND 
              `is_boss` = 1 AND
              `difficulty` <= '$difficulty'
              ORDER BY RAND() LIMIT 1";
    
    $result = $mc->query($query);
    
    if ($result && $result->num_rows > 0) {
        // Если есть подходящие боссы, возвращаем ID одного из них
        $boss = $result->fetch_array(MYSQLI_ASSOC);
        return $boss['id'];
    } else {
        // Если нет подходящих боссов, создаем нового
        $boss_types = [
            'dragon', 'lich', 'demon_lord', 'giant', 'vampire', 'werewolf', 
            'golem', 'dark_knight', 'necromancer', 'witch', 'shadow'
        ];
        
        // Выбираем случайный тип босса
        $boss_type = $boss_types[array_rand($boss_types)];
        
        // Генерируем имя на основе типа и уровня
        $prefixes = [
            'Древний', 'Жуткий', 'Громадный', 'Ужасающий', 'Легендарный', 
            'Проклятый', 'Злобный', 'Темный', 'Кровавый', 'Беспощадный'
        ];
        
        $boss_names = [
            'dragon' => ['Дракон', 'Виверна', 'Дрейк'],
            'lich' => ['Лич', 'Архилич', 'Некромант'],
            'demon_lord' => ['Демон', 'Повелитель Демонов', 'Архидемон'],
            'giant' => ['Гигант', 'Титан', 'Колосс'],
            'vampire' => ['Вампир', 'Кровопийца', 'Носферату'],
            'werewolf' => ['Оборотень', 'Волкодлак', 'Ликантроп'],
            'golem' => ['Голем', 'Каменный страж', 'Конструкт'],
            'dark_knight' => ['Темный рыцарь', 'Падший паладин', 'Черный страж'],
            'necromancer' => ['Некромант', 'Повелитель смерти', 'Костеплет'],
            'witch' => ['Ведьма', 'Колдунья', 'Чародейка'],
            'shadow' => ['Тень', 'Сущность ночи', 'Кошмар']
        ];
        
        $prefix = $prefixes[array_rand($prefixes)];
        $boss_name_variants = $boss_names[$boss_type] ?? [$boss_type];
        $boss_name_base = $boss_name_variants[array_rand($boss_name_variants)];
        $boss_name = $prefix . ' ' . $boss_name_base;
        
        // Рассчитываем характеристики босса на основе уровня и сложности
        // Боссы должны быть сильнее обычных монстров
        $health = 150 + ($boss_level * 50) + (rand(0, 20) * $boss_level);
        $damage = 15 + ($boss_level * 5) + (rand(0, 10));
        $accuracy = 50 + ($boss_level * 3) + (rand(0, 15));
        $evasion = 10 + ($boss_level * 2) + (rand(0, 10));
        $armor = 5 + ($boss_level) + (rand(0, 5));
        $block = 8 + ($boss_level) + (rand(0, 5));
        $stun = 5 + rand(0, 10);
        
        // Генерируем награды (боссы дают больше наград)
        $gold_min = 20 + ($boss_level * 15);
        $gold_max = 50 + ($boss_level * 30);
        $exp_min = 10 + ($boss_level * 5);
        $exp_max = 25 + ($boss_level * 10);
        
        // Определяем изображение босса
        $image = $boss_type . '.png';
        
        // Вставляем босса в базу данных
        $query = "INSERT INTO `dungeon_monsters` 
                  (`name`, `type`, `level`, `difficulty`, `health`, `damage`, 
                   `accuracy`, `evasion`, `armor`, `block`, `stun`, 
                   `gold_min`, `gold_max`, `exp_min`, `exp_max`, `image`, `is_boss`) 
                  VALUES 
                  ('$boss_name', '$boss_type', '$boss_level', '$difficulty', 
                   '$health', '$damage', '$accuracy', '$evasion', '$armor', '$block', 
                   '$stun', '$gold_min', '$gold_max', '$exp_min', '$exp_max', '$image', 1)";
        
        $mc->query($query);
        return $mc->insert_id;
    }
}

/**
 * Генерирует выходы из комнаты
 * @param int $room_number Номер комнаты
 * @return array Массив выходов
 */
function generateRoomExits($room_number) {
    $exits = [];
    
    // Всегда есть выход назад
    if ($room_number > 1) {
        $exits[] = [
            'direction' => 'back',
            'room' => $room_number - 1
        ];
    }
    
    // Шанс появления выхода вперед
    if (rand(1, 100) <= 70) {
        $exits[] = [
            'direction' => 'forward',
            'room' => $room_number + 1
        ];
    }
    
    // Шанс появления выхода влево
    if (rand(1, 100) <= 30) {
        $exits[] = [
            'direction' => 'left',
            'room' => $room_number + 10
        ];
    }
    
    // Шанс появления выхода вправо
    if (rand(1, 100) <= 30) {
        $exits[] = [
            'direction' => 'right',
            'room' => $room_number + 10
        ];
    }
    
    return $exits;
}

/**
 * Обрабатывает взаимодействие с объектом в комнате
 * @param string $target_id ID объекта
 * @param array $current_room Данные текущей комнаты
 * @return array Результат взаимодействия
 */
function handleInteraction($target_id, &$current_room) {
    global $mc, $user;
    
    // Находим нужный объект
    $object_index = null;
    foreach ($current_room['objects'] as $index => $object) {
        if ($index == $target_id) {
            $object_index = $index;
            break;
        }
    }
    
    if ($object_index === null) {
        return [
            'success' => false,
            'message' => 'Объект не найден'
        ];
    }
    
    // Получаем объект
    $object = $current_room['objects'][$object_index];
    
    // Проверяем, не взаимодействовали ли мы уже с этим объектом
    if (isset($object['interacted']) && $object['interacted']) {
        return [
            'success' => false,
            'message' => 'Вы уже взаимодействовали с этим объектом'
        ];
    }
    
    // Обработка разных типов объектов
    switch ($object['type']) {
        case 'chest':
            // Сундук с предметом
            if (isset($object['loot'])) {
                // Добавляем предмет в инвентарь
                $user_id = $user['id'];
                $item_id = $object['loot']['id'];
                
                // Проверяем, есть ли уже такой предмет у пользователя
                $existing_item = $mc->query("SELECT * FROM `dungeon_inventory` WHERE `user_id` = '$user_id' AND `item_id` = '$item_id'")->fetch_array(MYSQLI_ASSOC);
                
                if ($existing_item) {
                    // Увеличиваем количество
                    $mc->query("UPDATE `dungeon_inventory` SET `quantity` = `quantity` + 1 WHERE `id` = '" . $existing_item['id'] . "'");
                } else {
                    // Добавляем новый предмет
                    $mc->query("INSERT INTO `dungeon_inventory` (`user_id`, `item_id`, `quantity`) VALUES ('$user_id', '$item_id', 1)");
                }
                
                // Отмечаем объект как использованный
                $current_room['objects'][$object_index]['interacted'] = true;
                
                return [
                    'success' => true,
                    'message' => 'Вы нашли предмет: ' . $object['loot']['name']
                ];
            }
            break;
            
        case 'monster':
            // Монстр
            if (isset($object['monster_id'])) {
                $monster_id = $object['monster_id'];
                
                // Получаем данные монстра
                $monster = $mc->query("SELECT * FROM `dungeon_monsters` WHERE `id` = '$monster_id'")->fetch_array(MYSQLI_ASSOC);
                
                if (!$monster) {
                    return [
                        'success' => false,
                        'message' => 'Монстр не найден'
                    ];
                }
                
                // Создаем запись в таблице боев
                $user_id = $user['id'];
                $user_hp = $user['health'];
                $enemy_hp = $monster['health'];
                $room_type = 'normal';
                
                $battle_data = [
                    'user_id' => $user_id,
                    'enemy_id' => $monster_id,
                    'user_hp' => $user_hp,
                    'enemy_hp' => $enemy_hp,
                    'user_uron' => 0,
                    'enemy_uron' => 0,
                    'start_time' => time(),
                    'end_time' => 0,
                    'victory' => 0,
                    'fled' => 0,
                    'room_type' => $room_type
                ];
                
                // Преобразуем в SQL запрос
                $fields = implode('`, `', array_keys($battle_data));
                $values = implode("', '", $battle_data);
                
                $mc->query("INSERT INTO `battle` (`$fields`) VALUES ('$values')");
                $battle_id = $mc->insert_id;
                
                // Отмечаем объект как использованный
                $current_room['objects'][$object_index]['interacted'] = true;
                
                return [
                    'success' => true,
                    'message' => 'Начинается бой с ' . $monster['name'],
                    'redirect' => 'battle.php?battle=' . $battle_id
                ];
            }
            break;
            
        case 'boss':
            // Босс
            if (isset($object['monster_id'])) {
                $boss_id = $object['monster_id'];
                
                // Получаем данные босса
                $boss = $mc->query("SELECT * FROM `dungeon_monsters` WHERE `id` = '$boss_id'")->fetch_array(MYSQLI_ASSOC);
                
                if (!$boss) {
                    return [
                        'success' => false,
                        'message' => 'Босс не найден'
                    ];
                }
                
                // Создаем запись в таблице боев
                $user_id = $user['id'];
                $user_hp = $user['health'];
                $enemy_hp = $boss['health'];
                $room_type = 'boss';
                
                $battle_data = [
                    'user_id' => $user_id,
                    'enemy_id' => $boss_id,
                    'user_hp' => $user_hp,
                    'enemy_hp' => $enemy_hp,
                    'user_uron' => 0,
                    'enemy_uron' => 0,
                    'start_time' => time(),
                    'end_time' => 0,
                    'victory' => 0,
                    'fled' => 0,
                    'room_type' => $room_type
                ];
                
                // Преобразуем в SQL запрос
                $fields = implode('`, `', array_keys($battle_data));
                $values = implode("', '", $battle_data);
                
                $mc->query("INSERT INTO `battle` (`$fields`) VALUES ('$values')");
                $battle_id = $mc->insert_id;
                
                // Отмечаем объект как использованный
                $current_room['objects'][$object_index]['interacted'] = true;
                
                return [
                    'success' => true,
                    'message' => 'Начинается бой с ' . $boss['name'],
                    'redirect' => 'battle.php?battle=' . $battle_id
                ];
            }
            break;
            
        case 'fountain':
            // Фонтан лечения
            if (isset($object['heal_amount'])) {
                $heal_amount = $object['heal_amount'];
                
                // Восстанавливаем здоровье персонажа
                $user_id = $user['id'];
                $mc->query("UPDATE `users` SET `health` = LEAST(`max_health`, `health` + $heal_amount) WHERE `id` = '$user_id'");
                
                // Отмечаем объект как использованный
                $current_room['objects'][$object_index]['interacted'] = true;
                
                return [
                    'success' => true,
                    'message' => 'Вы восстановили ' . $heal_amount . ' единиц здоровья'
                ];
            }
            break;
            
        case 'puzzle':
            // Головоломка
            if (isset($object['puzzle_id'])) {
                $puzzle_id = $object['puzzle_id'];
                
                // Получаем данные головоломки
                $puzzle = $mc->query("SELECT * FROM `dungeon_puzzles` WHERE `id` = '$puzzle_id'")->fetch_array(MYSQLI_ASSOC);
                
                if (!$puzzle) {
                    return [
                        'success' => false,
                        'message' => 'Головоломка не найдена'
                    ];
                }
                
                return [
                    'success' => true,
                    'message' => 'Вы обнаружили головоломку: ' . $puzzle['name'],
                    'puzzle' => $puzzle
                ];
            }
            break;
            
        case 'trap':
            // Ловушка
            if (isset($object['damage'])) {
                $damage = $object['damage'];
                
                // Наносим урон персонажу
                $user_id = $user['id'];
                $mc->query("UPDATE `users` SET `health` = GREATEST(1, `health` - $damage) WHERE `id` = '$user_id'");
                
                // Отмечаем объект как использованный
                $current_room['objects'][$object_index]['interacted'] = true;
                
                return [
                    'success' => true,
                    'message' => 'Вы попали в ловушку и получили ' . $damage . ' единиц урона'
                ];
            }
            break;
    }
    
    return [
        'success' => false,
        'message' => 'Неизвестное действие'
    ];
}

/**
 * Рассчитывает награды за бой
 * @param array $monster Данные монстра
 * @param string $room_type Тип комнаты
 * @return array Награды за бой
 */
function calculateBattleRewards($monster, $room_type) {
    $base_gold = $monster['level'] * 10;
    $base_exp = $monster['level'] * 5;
    
    // Бонус за босса
    if ($room_type === 'boss') {
        $base_gold *= 2;
        $base_exp *= 2;
    }
    
    // Добавляем случайность (±20%)
    $gold_variance = rand(-20, 20) / 100;
    $exp_variance = rand(-20, 20) / 100;
    
    return [
        'gold' => max(1, ceil($base_gold * (1 + $gold_variance))),
        'exp' => max(1, ceil($base_exp * (1 + $exp_variance)))
    ];
}

/**
 * Рассчитывает награды за подземелье
 * @param array $dungeon Данные подземелья
 * @param array $stats Статистика прохождения
 * @return array Награды за подземелье
 */
function calculateDungeonRewards($dungeon, $stats) {
    $base_gold = $dungeon['gold_reward'];
    $base_exp = $dungeon['exp_reward'];
    
    // Бонусы за статистику
    $gold_bonus = $stats['rooms_visited'] * 10 + $stats['monsters_defeated'] * 5 + $stats['items_found'] * 15;
    $exp_bonus = $stats['rooms_visited'] * 5 + $stats['monsters_defeated'] * 10 + $stats['items_found'] * 10;
    
    // Бонус за победу над боссом
    if ($stats['boss_defeated'] > 0) {
        $gold_bonus *= 2;
        $exp_bonus *= 2;
    }
    
    return [
        'gold' => $base_gold + $gold_bonus,
        'exp' => $base_exp + $exp_bonus
    ];
}

/**
 * Рассчитывает очки за подземелье
 * @param array $stats Статистика прохождения
 * @return int Очки за подземелье
 */
function calculateDungeonScore($stats) {
    $score = 0;
    
    // Базовые очки
    $score += $stats['rooms_visited'] * 10;
    $score += $stats['monsters_defeated'] * 20;
    $score += $stats['items_found'] * 15;
    
    // Бонус за босса
    if ($stats['boss_defeated'] > 0) {
        $score += 100;
    }
    
    return $score;
}

/**
 * Проверяет условия достижения
 * @param array $achievement Данные достижения
 * @param array $stats Статистика прохождения
 * @return bool Результат проверки
 */
function checkAchievementConditions($achievement, $stats) {
    switch ($achievement['condition_type']) {
        case 'rooms_visited':
            return $stats['rooms_visited'] >= $achievement['condition_value'];
        case 'monsters_defeated':
            return $stats['monsters_defeated'] >= $achievement['condition_value'];
        case 'items_found':
            return $stats['items_found'] >= $achievement['condition_value'];
        case 'puzzles_solved':
            return $stats['puzzles_solved'] >= $achievement['condition_value'];
        case 'boss_defeated':
            return $stats['boss_defeated'] >= $achievement['condition_value'];
        case 'gold_earned':
            return $stats['gold_earned'] >= $achievement['condition_value'];
        case 'exp_earned':
            return $stats['exp_earned'] >= $achievement['condition_value'];
        default:
            return false;
    }
}

/**
 * Обновляет статистику подземелий
 * @param int $user_id ID пользователя
 * @param array $stats Статистика для обновления
 */
function updateDungeonStats($user_id, $stats) {
    global $mc;
    
    $stats_json = json_encode($stats, JSON_UNESCAPED_UNICODE);
    $mc->query("INSERT INTO `dungeon_stats` (`user_id`, `dungeons_completed`, `rooms_visited`, `monsters_defeated`, `items_found`, `puzzles_solved`, `total_score`, `best_score`) 
                VALUES ('$user_id', " . $stats['dungeons_completed'] . ", " . $stats['rooms_visited'] . ", " . $stats['monsters_defeated'] . ", " . $stats['items_found'] . ", " . $stats['puzzles_solved'] . ", " . $stats['total_score'] . ", " . $stats['best_score'] . ")
                ON DUPLICATE KEY UPDATE 
                `dungeons_completed` = VALUES(`dungeons_completed`),
                `rooms_visited` = VALUES(`rooms_visited`),
                `monsters_defeated` = VALUES(`monsters_defeated`),
                `items_found` = VALUES(`items_found`),
                `puzzles_solved` = VALUES(`puzzles_solved`),
                `total_score` = VALUES(`total_score`),
                `best_score` = GREATEST(`best_score`, VALUES(`best_score`))");
}

/**
 * Проверяет и выдает ежедневные награды
 * @param int $user_id ID пользователя
 * @return array Награды
 */
function checkDailyRewards($user_id) {
    global $mc;
    
    $today = date('Y-m-d');
    $rewards = [];
    
    // Проверяем, получал ли игрок награды сегодня
    $claimed = $mc->query("SELECT * FROM `dungeon_daily_rewards` WHERE `user_id` = '$user_id' AND DATE(`claimed_at`) = '$today'")->fetch_array(MYSQLI_ASSOC);
    
    if (!$claimed) {
        // Генерируем награды
        $rewards = [
            ['type' => 'gold', 'value' => rand(100, 500)],
            ['type' => 'exp', 'value' => rand(50, 250)],
            ['type' => 'item', 'value' => generateDungeonItem(1)]
        ];
        
        // Сохраняем награды
        foreach ($rewards as $reward) {
            $reward_json = json_encode($reward, JSON_UNESCAPED_UNICODE);
            $mc->query("INSERT INTO `dungeon_daily_rewards` (`user_id`, `reward_type`, `reward_value`) 
                        VALUES ('$user_id', '" . $reward['type'] . "', '" . $reward['value'] . "')");
        }
    }
    
    return $rewards;
}

/**
 * Обновляет рейтинг подземелий
 * @param int $user_id ID пользователя
 * @param int $score Очки
 * @param int $dungeons_completed Количество пройденных подземелий
 */
function updateLeaderboard($user_id, $score, $dungeons_completed) {
    global $mc;
    
    $mc->query("INSERT INTO `dungeon_leaderboard` (`user_id`, `score`, `dungeons_completed`) 
                VALUES ('$user_id', $score, $dungeons_completed)
                ON DUPLICATE KEY UPDATE 
                `score` = VALUES(`score`),
                `dungeons_completed` = VALUES(`dungeons_completed`)");
}

/**
 * Вспомогательная функция для построения SQL-запроса
 * @param array $data Данные для запроса
 * @return string SQL-запрос
 */
function buildQuery($data) {
    $query = [];
    foreach ($data as $key => $value) {
        if (is_null($value)) {
            $query[] = "`$key` = NULL";
        } else {
            $query[] = "`$key` = '" . $value . "'";
        }
    }
    return implode(', ', $query);
}

/**
 * Проверяет авторизацию пользователя для доступа к разделу подземелий
 * Возвращает false или перенаправляет на страницу входа
 * @param bool $redirect Перенаправлять ли пользователя на страницу входа
 * @return bool Авторизован ли пользователь
 */
function checkDungeonAuth($redirect = true) {
    // Отладка - запишем информацию в файл
    error_log("=== DEBUG AUTH ===");
    error_log("SESSION: " . print_r($_SESSION, true));
    error_log("COOKIE: " . print_r($_COOKIE, true));
    
    // Сначала проверим куки авторизации и попробуем восстановить сессию
    if ((!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) && isset($_COOKIE['id'])) {
        $_SESSION['user_id'] = $_COOKIE['id'];
        error_log("Восстановили user_id из кук: " . $_COOKIE['id']);
    }
    
    // Если у нас есть куки логина/пароля, но нет куки id, попробуем получить id
    if ((!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) && isset($_COOKIE['login']) && isset($_COOKIE['password'])) {
        global $mc;
        $login = $mc->real_escape_string(urldecode($_COOKIE['login']));
        $password = $mc->real_escape_string($_COOKIE['password']);
        
        $user_data = $mc->query("SELECT `id` FROM `users` WHERE `login` = '$login' AND `password` = '$password' LIMIT 1")->fetch_array(MYSQLI_ASSOC);
        
        if ($user_data && isset($user_data['id'])) {
            $_SESSION['user_id'] = $user_data['id'];
            error_log("Восстановили user_id из логина/пароля: " . $user_data['id']);
        }
    }
    
    // Проверка наличия сессии
    if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
        error_log("Ошибка: Нет user_id в сессии");
        if ($redirect) {
            // Возвращаем на страницу входа
            header('Location: /login.php?from=dungeons');
            exit;
        }
        return false;
    }
    
    // Проверка существования пользователя
    global $mc, $user;
    error_log("User DATA до проверки: " . print_r($user, true));
    
    // Если объект пользователя не определен или не содержит id
    if (!isset($user) || !is_array($user) || empty($user['id'])) {
        error_log("Ошибка: Нет данных пользователя, пробуем восстановить");
        
        // Пробуем восстановить данные пользователя из сессии
        $user_id = intval($_SESSION['user_id']);
        $user_query = $mc->query("SELECT * FROM `users` WHERE `id` = '$user_id' LIMIT 1");
        
        if ($user_query && $user_query->num_rows > 0) {
            $user = $user_query->fetch_array(MYSQLI_ASSOC);
            error_log("Восстановлены данные пользователя: " . print_r($user, true));
            
            // Восстанавливаем куки для совместимости с системой
            if(!isset($_COOKIE['login']) || !isset($_COOKIE['password'])) {
                setcookie("login", urlencode($user['login']), time() + 86400 * 30, "/");
                setcookie("password", $user['password'], time() + 86400 * 30, "/");
                setcookie("id", $user['id'], time() + 86400 * 30, "/");
                error_log("Восстановили куки для пользователя");
            }
        } else {
            error_log("Ошибка: Пользователь не найден в базе данных");
            // Если пользователь не найден в базе данных, то сбрасываем сессию и перенаправляем
            if ($redirect) {
                session_unset();
                session_destroy();
                setcookie("login", "", time() - 3600, "/");
                setcookie("password", "", time() - 3600, "/");
                setcookie("id", "", time() - 3600, "/");
                
                header('Location: /login.php?from=dungeons&error=not_found');
                exit;
            }
            return false;
        }
    }
    
    // Проверяем совпадение ID пользователя в сессии и в объекте $user
    if (intval($_SESSION['user_id']) !== intval($user['id'])) {
        error_log("Ошибка: Несоответствие ID в сессии (" . $_SESSION['user_id'] . ") и в объекте user (" . $user['id'] . ")");
        
        // Обновляем ID в сессии для соответствия с объектом user
        $_SESSION['user_id'] = $user['id'];
        error_log("Обновили ID в сессии на " . $user['id']);
    }
    
    error_log("Авторизация успешна! User ID: " . $user['id']);
    return true;
} 