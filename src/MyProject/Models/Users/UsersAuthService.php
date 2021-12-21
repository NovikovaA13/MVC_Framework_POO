<?php
namespace MyProject\Models\Users;

use MyProject\Models\Users\User;

class UsersAuthService
{
    public static function createToken(User $user)
    {
        $token = $user->getId() . ':' . $user->getAuthToken();
        setcookie('token', $token, 0, '/', false, true);
    }
    public static function getUserByToken(): ?User
    {
        $token = $_COOKIE['token'] ?? '';
        if(empty($token)){
            return null;
        }
        [$userId, $authToken] = explode(':', $token, 2);

        $user = User::getOneById($userId);
        if($user === null){
            return null;
        }
        if($user->getAuthToken() != $authToken){
            return null;
        }
        return $user;
    }
}
