<?php

// Класс воеводы
class HeroClass {
    public $text = "Профиль используется только для персональных оповещений<br><br>По любым вопросам пишите в <b>Поддержку.</b> ";
    public $img = 6;
    public $attack = 0;
}

// Класс хода
class Turn {
    public $user;
    public $time;
    public $round;
    public $outcome; // Переименовано с "result" для избежания конфликта

    // Конструктор для инициализации переменных
    public function __construct($user, $time = null, $round = 1) {
        $this->user = $user;
        $this->time = $time ?? time(); // Если не указано, берём текущее время
        $this->round = $round;
    }

    // Метод обработки результата
    public function processResult() {
        if (!is_array($this->user)) {
            message("Ошибка: некорректные данные пользователя.");
            return;
        }

        if (count($this->user) >= 25) {
            battleStart($this->user);
        } else {
            message("Недостаточно игроков.");
        }
    }
}

?>
