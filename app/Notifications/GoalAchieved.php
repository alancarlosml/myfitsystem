<?php

namespace App\Notifications;

use App\Models\StudentGoal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GoalAchieved extends Notification implements ShouldQueue
{
    use Queueable;

    protected $goal;

    /**
     * Create a new notification instance.
     */
    public function __construct(StudentGoal $goal)
    {
        $this->goal = $goal;
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
            ->subject('Meta Alcançada! 🎉')
            ->greeting("Parabéns, {$notifiable->name}!")
            ->line("Você alcançou a meta: {$this->goal->goal_name}")
            ->line("Progresso: {$this->goal->current_value} de {$this->goal->target_value} {$this->goal->unit}")
            ->action('Ver Dashboard', route('student.dashboard'))
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
            'title' => 'Meta alcançada!',
            'message' => "Parabéns! Você alcançou a meta: {$this->goal->goal_name}",
            'goal_id' => $this->goal->id,
            'goal_name' => $this->goal->goal_name,
            'url' => route('student.dashboard'),
            'type' => 'success'
        ];
    }
}

