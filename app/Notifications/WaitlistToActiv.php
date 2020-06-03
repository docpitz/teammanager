<?php

namespace App\Notifications;

use App\Event;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class WaitlistToActiv extends Notification
{
    use Queueable;

    protected $user;
    protected $event;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, Event $event)
    {
        $this->user = $user;
        $this->event = $event;
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
        $mailMessage = (new MailMessage)
            ->subject('Du darfst teilnehmen bei '. $this->event->name)
            ->greeting('Hallo!')
            ->line(new HtmlString('du erhältst diese E-Mail, weil du, liebe(r) <strong>'.$this->user->firstname.'</strong> von der Warteliste zu den Teilnehmer gerutscht bist.'))
            ->line(new HtmlString('<center>Die Veranstaltung</center>'))
            ->line(new HtmlString('<p><center><strong>'.$this->event->name.'</strong></center></p>'))
            ->line(new HtmlString('<p><center>findet am <strong>'.$this->event->date_event_start->format('d.m.Y').' um '.$this->event->date_event_start->format('H:i').' Uhr</strong> statt.</center></p>'))
            ->action('Alle Infos zur Veranstaltung', url(config('app.url').route('showEvent', [$this->event->id], false)))
            ->line('Wir wünschen dir viel Spaß und freuen uns dich dort zu sehen!')
            ->salutation(new HtmlString("Viele Grüße <br>".config('app.name')));

        if(! empty($this->user->email_optional)) {
            $mailMessage->cc([$this->user->email_optional]);
        }
        return $mailMessage;
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
