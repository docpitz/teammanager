<?php
namespace App\Console\Commands;

use App\Event;
use App\EventSendedmail;
use App\SendedMails;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;

class SendEmailsToInformResponsibleAboutParticipants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:informResponsibleAboutParticipants';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $events = Event::where('date_event_start','<', Carbon::now()->addHours(2))
            ->where('date_event_start','>', Carbon::now()->subHours(6))
            ->whereDoesntHave('sendedmails')
            ->orWhereHas('sendedmails', function(Builder $query){
                $query->where('kind','!=', $this->signature);
            }
        );

        foreach($events->getModels() as $event) {
            $responsibles = $event->responsibles()->getModels();
            foreach($responsibles as $responsible) {
                $responsible->sendInformationAboutParticipants($event);
            }
            $event->sendedmails()->save(new EventSendedmail(['kind' => $this->signature]));
        }
    }
}
