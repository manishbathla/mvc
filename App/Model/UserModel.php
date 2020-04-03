<?php

namespace App\Model;

use App\Mail;
use System\Core\BaseModel;
use System\Core\View;
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
        return MyPDO::run(
            "SELECT * FROM user_account WHERE email = ?",
            [$email])->fetchObject(get_called_class());
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

        $data = MyPDO::run(
            "INSERT INTO remember_login (token_hash, user_account_id, expires_at) VALUES (?, ?, ?)",
            [$hashed_token, $this->id, $expires_on]
        )->fetchObject();
    }

    public static function sendPasswordReset($email)
    {
        $user = static::findByEmail($email);
        if ($user && $user->startPasswordReset()) {
            $user->sendPasswordResetEmail();
        }
    }

    protected function startPasswordReset()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->password_reset_token = $token->getValue();
        $this->expiry_timestamp = time() + 60 * 60 * 2;
        $expires_on = date('Y-m-d H:i:s', $this->expiry_timestamp);

        return MyPDO::run(
            "UPDATE user_account SET password_reset_key = :token_hash, password_reset_timestamp = :expires_at WHERE id = :id",
            ['token_hash'=>$hashed_token, 'expires_at'=>$expires_on, 'id'=>$this->id]
        );
    }

    protected function sendPasswordResetEmail()
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/password/reset/' . $this->password_reset_token;
        $html = View::renderTemplate('Password/reset_email.html', ['url' => $url]);
        $text = View::renderTemplate('Password/reset_email.txt', ['url' => $url]);
        Mail::send($this->email, 'Password reset', $text, $html);
    }
}
