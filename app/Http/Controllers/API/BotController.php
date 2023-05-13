<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use JsonException;
use NotificationChannels\Telegram\TelegramMessage;
use NotificationChannels\Telegram\TelegramUpdates;

class BotController extends Controller
{
    /**
     * @throws JsonException
     * @throws Exception
     */
    public function handle(): \Illuminate\Http\JsonResponse
    {
        $updates = TelegramUpdates::create()->latest()->get();
        if ($updates['ok']) {
            $tg_user = [
                'name' => $updates['result'][0]['message']['chat']['first_name'],
                'chat_id' => $updates['result'][0]['message']['chat']['id'],
            ];
            $user = UserController::UserManipulation($tg_user);

            if (!$this->sendMessage($user)) {
                throw new Exception('Сообщение не было отправлено');
            } else {
                return response()->json('Сообщение отправлено', 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                    JSON_UNESCAPED_UNICODE);
            }
        } else {
            throw new Exception('Обновления получены с ошибкой');
        }
    }

    private function sendMessage($user): array|\Psr\Http\Message\ResponseInterface
    {
        return TelegramMessage::create()->to($user->chat_id)
            ->content("Welcome to the club buddy")
            ->line(' id  ' . $user->id)
            ->line(' name  ' . $user->name)
            ->line(' chatId ' . $user->chat_id)
            ->line(' createdAt  ' . $user->created_at)
            ->send();
    }

}
