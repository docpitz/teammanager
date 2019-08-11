<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;
use App\User;

class ResetPassword extends Notification
{
    use Queueable;

    protected $token;
    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, User $user)
    {
        $this->token = $token;
        $this->user = $user;
    }
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->cc([$this->user->email_optional])
            ->subject('Zurücksetzen des Passwortes für ' . config('app.name'))
            ->greeting('Hallo!')
            ->line('du erhältst diese E-Mail, weil wir eine Anfrage zum Zurücksetzen des Passworts für dein Konto erhalten haben.')
            ->action('Passwort zurücksetzen', url(config('app.url').route('password.reset', [$this->token, 'email='.$notifiable->email], false)))
            ->line('Dieser Link zum Zurücksetzen des Passworts läuft in 60 Minuten ab.')
            ->line('Wenn du kein Passwort Reset angefordert hast, ist keine weitere Aktion erforderlich.')
            ->salutation(new HtmlString("Viele Grüße <br>".config('app.name')));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
