<?php

namespace App\Notifications;

use App\Models\Achievement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AchievementUnlocked extends Notification implements ShouldQueue
{
    use Queueable;

    protected $achievement;

    /**
     * Create a new notification instance.
     */
    public function __construct(Achievement $achievement)
    {
        $this->achievement = $achievement;
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
            ->subject('Conquista Desbloqueada! 🏆')
            ->greeting("Parabéns, {$notifiable->name}!")
            ->line("Você desbloqueou uma nova conquista!")
            ->line("Conquista: {$this->achievement->name}")
            ->line($this->achievement->description ?? '')
            ->action('Ver Perfil', route('student.profile'))
            ->line('Continue se dedicando para desbloquear mais conquistas!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Conquista desbloqueada!',
            'message' => "Parabéns! Você desbloqueou a conquista: {$this->achievement->name}",
            'achievement_id' => $this->achievement->id,
            'achievement_name' => $this->achievement->name,
            'url' => route('student.profile'),
            'type' => 'success'
        ];
    }
}

