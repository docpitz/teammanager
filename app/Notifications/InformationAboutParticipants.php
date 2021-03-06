<?php

namespace App\Notifications;

use App\Buisness\Enum\ParticipationStatusEnum;
use App\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Jenssegers\Date\Date;

class InformationAboutParticipants extends Notification
{
    use Queueable;
    protected $event;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Event $event)
    {
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
        $message = (new MailMessage)
            ->subject('Teilnehmer der Veranstaltung: '.$this->event->name)
            ->greeting('Hallo')
            ->line('Heute von '.Date::parse($this->event->date_event_start)->format('D d.m.Y H:i') .
                ' bis '.Date::parse($this->event->date_event_end)->format('D d.m.Y H:i').' findet unsere Veranstaltung '.$this->event->name.' statt.')
            ->line('Folgende '.$this->event->countPromise().' Teilnehmer kommen: ');
        $persons = "";
        foreach ($this->event->getUsersByParticipation(ParticipationStatusEnum::Promised)->getModels() as $user) {
            $userModel = $user->getModel();
            $persons = $persons.$userModel->firstname.' '.$userModel->surname."<br>";
        }
        $message = $message->line(new HtmlString($persons));
        $message = $message->line('Wir wünschen dir viel Spaß!')
            ->salutation(new HtmlString("Viele Grüße <br>".config('app.name')));
        return $message;
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
