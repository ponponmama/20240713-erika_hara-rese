<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $reservation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Reservation $reservation)
    {
        $this->user = $user;
        $this->reservation = $reservation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.reminder')
                ->with([
                    'user' => $this->user,
                    'reservation' => $this->reservation
                ]);
    }
}
