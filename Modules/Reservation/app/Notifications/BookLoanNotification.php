<?php

namespace Modules\Reservation\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Reservation\Models\Loan;
use Illuminate\Contracts\Queue\ShouldQueue;

class BookLoanNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public int $tries = 5;

    public function backoff(): array
    {
        return [5, 10, 15, 20];
    }

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Loan $loan)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $this->loan->loadMissing(['bookVersion.book']);
        return (new MailMessage)
            ->line(trans('reservation::message.book_loan_to_user', [
                'book_title' => $this->loan->bookVersion->book->title ?? null,
                'loan_days'  => (int)now()->diffInDays($this->loan->expiration_date),
            ]));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [];
    }
}
