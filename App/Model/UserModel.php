<?php

namespace App\Model;

use System\Core\BaseModel;
use System\Library\MyPDO;
use App\Token;

class UserModel extends BaseModel
{

    public static function emailExists($email)
    {
        return static::findByEmail($email) !== false;
    }

    public static function findByEmail($email)
    {
        $data = MyPDO::run("SELECT * FROM user_account WHERE email = ?",
            [$email])->fetchObject(get_called_class());
        return $data;
    }

    public static function authenticate($email, $password)
    {
        $user = static::findByEmail($email);

        if ($user) {
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }
        return false;
    }

    public static function findByID($id)
    {
        $data = MyPDO::run("SELECT * FROM user_account WHERE id = ?", [$id])->fetchObject(get_called_class());
        return $data;
    }


    public function rememberLogin()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->remember_token = $token->getValue();
        $this->expiry_timestamp = time() + 60 * 60 * 24 * 30;

        $expires_on = date('Y-m-d H:i:s', $this->expiry_timestamp);

        $data = MyPDO::run("INSERT INTO remember_login (token_hash, user_account_id, expires_at) VALUES (?, ?, ?)",
            [$hashed_token, $this->id, $expires_on])->fetchObject();

    }
}
