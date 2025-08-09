<?php
class GPTJHandler {
    private $apiEndpoint = 'https://api.gptj.ai/v1/text/generate';  // Замените на актуальный endpoint
    private $apiKey;  // Ваш API ключ

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function generateResponse($prompt) {
        $data = [
            'prompt' => $prompt,
            'max_length' => 100,
            'temperature' => 0.7
        ];

        $response = $this->makeRequest($data);
        return $this->formatResponse($response);
    }

    private function makeRequest($data) {
        $ch = curl_init($this->apiEndpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    private function formatResponse($response) {
        // Форматирование ответа от GPT-J
        if (isset($response['text'])) {
            return trim($response['text']);
        }
        return 'Извините, произошла ошибка при генерации ответа.';
    }
} 