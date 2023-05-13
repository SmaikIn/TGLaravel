<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public static function UserManipulation(array $tg_user)
    {
        $user = new User;
        if (!$user->existsUser($tg_user['chat_id'])) {
            $user->fill($tg_user);
            $user->save();
        } else {
            $user = $user->getUserByChatId($tg_user['chat_id']);
        }
        return $user;
    }
    //
}
