<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('这是一封密码重置邮件，如果是您本人操作，请点击一下按钮继续：')
                    ->action('重置密码',url(route('password.reset',$this->token,false)))
                    ->line('如果这不是您的操作，请忽略此邮件。');
    }

}
