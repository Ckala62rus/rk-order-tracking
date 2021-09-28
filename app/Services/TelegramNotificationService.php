<?php

namespace App\Services;

class TelegramNotificationService
{
    /**
     * Send request by Curl
     * @param string $url
     * @param array $parameters
     * @return bool|string
     */
    private function initNotificationCurl(string $url, array $parameters)
    {
        $curlopt_array = [
            CURLOPT_URL => $url,
            CURLOPT_POSTFIELDS => $parameters,
        ];

        $c = curl_init();
        curl_setopt_array($c,$curlopt_array);
        $result = curl_exec($c);
        curl_close($c);

        return $result;
    }

    /**
     * Send message to client by user_id or chat_id
     * @param string $message
     * @param $id_user
     * @return bool|string
     */
    public function sendMessageToTelegram(string $message, $id_user)
    {
        $parameters = [
            'text' => $message,
            'chat_id' => $id_user
        ];

        $method = 'sendMessage';

        $url = config('telegram.telegram.url_bot')
            .config('telegram.telegram.token')
            . '/'
            . $method;

        return $this->initNotificationCurl($url, $parameters);
    }
}
