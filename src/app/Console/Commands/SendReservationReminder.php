<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Mail\ReservationReminder;
use Illuminate\Support\Facades\Mail;


class SendReservationReminder extends Command
{
    protected $signature = 'send:reservation-reminder';
    

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a reminder email to users with reservations for today.';

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
     * @return int
     */
    public function handle()
    {
        $todayReservations = Reservation::whereDate('reservation_datetime', now()->toDateString())->get();
        foreach ($todayReservations as $reservation) {
            $user = $reservation->user;
            Mail::to($user->email)->send(new ReservationReminder($user, $reservation));
        }
    }
}
