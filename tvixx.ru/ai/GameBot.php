<?php
require_once 'GPTJHandler.php';

class GameBot {
    private $gptj;
    private $mc; // MySQL connection
    private $context = [];
    
    public function __construct($mc, $apiKey) {
        $this->mc = $mc;
        $this->gptj = new GPTJHandler($apiKey);
    }

    public function handleMessage($userId, $message) {
        // Загружаем контекст игрока
        $this->loadPlayerContext($userId);
        
        // Определяем тип запроса
        $intent = $this->classifyIntent($message);
        
        switch($intent) {
            case 'quest_help':
                return $this->handleQuestHelp($message);
            case 'item_info':
                return $this->handleItemInfo($message);
            case 'location_help':
                return $this->handleLocationHelp($message);
            case 'battle_advice':
                return $this->handleBattleAdvice();
            default:
                return $this->generateGenericResponse($message);
        }
    }

    private function loadPlayerContext($userId) {
        $player = $this->mc->query("SELECT * FROM users WHERE id = '$userId'")->fetch_array(MYSQLI_ASSOC);
        $this->context = [
            'level' => $player['level'],
            'location' => $player['location'],
            'class' => $player['class'],
            'gold' => $player['money'],
            'equipment' => $this->getPlayerEquipment($userId)
        ];
    }

    private function classifyIntent($message) {
        $keywords = [
            'quest_help' => ['квест', 'задание', 'миссия', 'как пройти'],
            'item_info' => ['предмет', 'вещь', 'оружие', 'броня'],
            'location_help' => ['где найти', 'как попасть', 'локация'],
            'battle_advice' => ['бой', 'битва', 'сражение', 'как победить']
        ];

        foreach ($keywords as $intent => $words) {
            foreach ($words as $word) {
                if (stripos($message, $word) !== false) {
                    return $intent;
                }
            }
        }
        return 'general';
    }

    private function handleQuestHelp($message) {
        $prompt = "Помоги игроку {$this->context['level']} уровня с квестом: $message\n";
        $prompt .= "Контекст: Игрок находится в локации {$this->context['location']}\n";
        
        return $this->gptj->generateResponse($prompt);
    }

    private function handleItemInfo($message) {
        // Получаем информацию о предмете из базы
        $itemName = preg_replace('/[^а-яА-Я0-9\s]/u', '', $message);
        $item = $this->mc->query("SELECT * FROM shop WHERE name LIKE '%$itemName%' LIMIT 1")->fetch_array(MYSQLI_ASSOC);

        if ($item) {
            $prompt = "Расскажи о предмете {$item['name']} в игре.\n";
            $prompt .= "Характеристики: Урон {$item['strength']}, Броня {$item['bron']}\n";
            return $this->gptj->generateResponse($prompt);
        }

        return "Извините, я не нашел информации об этом предмете.";
    }

    private function handleLocationHelp($message) {
        $prompt = "Игрок {$this->context['level']} уровня спрашивает о локации: $message\n";
        $prompt .= "Текущая локация: {$this->context['location']}\n";
        
        return $this->gptj->generateResponse($prompt);
    }

    private function handleBattleAdvice() {
        $prompt = "Дай совет по бою для игрока {$this->context['level']} уровня\n";
        $prompt .= "Экипировка: " . json_encode($this->context['equipment']) . "\n";
        
        return $this->gptj->generateResponse($prompt);
    }

    private function generateGenericResponse($message) {
        $prompt = "Ты игровой помощник в MMORPG. Ответь на вопрос игрока: $message\n";
        $prompt .= "Контекст: " . json_encode($this->context) . "\n";
        
        return $this->gptj->generateResponse($prompt);
    }

    private function getPlayerEquipment($userId) {
        $equipment = [];
        $result = $this->mc->query("SELECT * FROM userbag WHERE id_user = '$userId' AND dress = 1");
        while ($item = $result->fetch_array(MYSQLI_ASSOC)) {
            $equipment[] = $item;
        }
        return $equipment;
    }
} 