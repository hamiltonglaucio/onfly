<?php

namespace App\TravelService\UserInterface\Notifications;

use App\TravelService\Domain\Entities\TravelRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TravelRequestStatusUpdated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private TravelRequest $travelRequest
    ){}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'travel_request_id' => $this->travelRequest->getId(),
            'status' => $this->travelRequest->getStatus(),
            'destination' => $this->travelRequest->getDestination(),
            'departure_date' => $this->travelRequest->getDepartureDate()->toDateString(),
            'return_date' => $this->travelRequest->getReturnDate()->toDateString(),
            'message' => sprintf(
                'Your travel request to %s has been %s.',
                $this->travelRequest->getDestination(),
                $this->travelRequest->getStatus()
            ),
        ];
    }
}
