<?php

$stop = false;
/**
 * pcntl_fork() - данная функция разветвляет текущий процесс
 */
$pid = pcntl_fork();
if ($pid == -1) {
    /**
     * Не получилось сделать форк процесса, о чем сообщим в консоль
     */
    die('Error fork process' . PHP_EOL);
} elseif ($pid) {
    /**
     * В эту ветку зайдет только родительский процесс, который мы убиваем и сообщаем об этом в консоль
     */
    die('Die parent process' . PHP_EOL);
} else {
    while (!$stop) {
        exec("/opt/php/7.4-bx-optimized/bin/php -f /var/www/u2992855/data/www/tvixx.ru/cron/duel1_1.php & >/dev/null");
        sleep(1);
    }
}
/**
 * Установим дочерний процесс основным, это необходимо для создания процессов
 */
posix_setsid();