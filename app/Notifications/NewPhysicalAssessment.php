<?php

namespace App\Notifications;

use App\Models\PhysicalAssessment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPhysicalAssessment extends Notification implements ShouldQueue
{
    use Queueable;

    protected $assessment;

    /**
     * Create a new notification instance.
     */
    public function __construct(PhysicalAssessment $assessment)
    {
        $this->assessment = $assessment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nova Avaliação Física Disponível')
            ->greeting("Olá, {$notifiable->name}!")
            ->line('Uma nova avaliação física foi registrada para você.')
            ->line('Data: ' . \Carbon\Carbon::parse($this->assessment->assessment_date)->format('d/m/Y'))
            ->line('Avaliador: ' . ($this->assessment->user->name ?? 'Sistema'))
            ->action('Ver Avaliação', route('student.physical_assessments.show', $this->assessment->id))
            ->line('Continue mantendo seus objetivos!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Nova Avaliação Física',
            'message' => 'Uma nova avaliação física foi registrada para você.',
            'assessment_id' => $this->assessment->id,
            'assessment_date' => $this->assessment->assessment_date->toDateString(),
            'url' => route('student.physical_assessments.show', $this->assessment->id),
            'type' => 'physical_assessment'
        ];
    }
}
