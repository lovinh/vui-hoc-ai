<?php

namespace app\core\helper;

use ErrorException;
use PHPMailer\PHPMailer\PHPMailer;

class MailSend
{

    public static function send(string $name, string $email, string $subject, string $body)
    {
        global $app_config;
        if (empty($app_config['mail'])) {
            throw new ErrorException("Không tìm thấy config server gửi mail!");
        }
        if (empty($app_config['mail']['host'])) {
            throw new ErrorException("Không tìm thấy config server gửi mail 'host'!");
        }
        if (empty($app_config['mail']['secure'])) {
            throw new ErrorException("Không tìm thấy config server gửi mail 'secure'!");
        }
        if (empty($app_config['mail']['port'])) {
            throw new ErrorException("Không tìm thấy config server gửi mail! 'port'");
        }
        if (empty($app_config['mail']['username'])) {
            throw new ErrorException("Không tìm thấy config server gửi mail! 'username'");
        }
        if (empty($app_config['mail']['password'])) {
            throw new ErrorException("Không tìm thấy config server gửi mail! 'password'");
        }
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = $app_config['mail']['host'];
        $mail->SMTPSecure = $app_config['mail']['secure'];
        $mail->Port = $app_config['mail']['port'];
        $mail->Username = $app_config['mail']['username'];
        $mail->Password = $app_config['mail']['password'];
        $mail->setFrom($app_config['mail']['email_from'], $app_config['mail']['name_from']);
        $mail->addAddress($email, $name);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->isHTML(true);

        return $mail->send();
    }
}
