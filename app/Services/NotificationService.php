<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Student;
use App\Models\User;
use App\Models\StudentContracts;
use App\Models\EstablishmentContracts;
use Carbon\Carbon;

class NotificationService
{
    /**
     * Create a notification for a user
     */
    public function createForUser($userId, $type, $title, $message, $actionUrl = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'student_id' => null,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'action_url' => $actionUrl,
            'read' => false,
        ]);
    }

    /**
     * Create a notification for a student
     */
    public function createForStudent($studentId, $type, $title, $message, $actionUrl = null)
    {
        return Notification::create([
            'user_id' => null,
            'student_id' => $studentId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'action_url' => $actionUrl,
            'read' => false,
        ]);
    }

    /**
     * Get unread notifications for a user
     */
    public function getUnreadForUser($userId, $limit = null)
    {
        $query = Notification::where('user_id', $userId)
            ->unread()
            ->orderBy('created_at', 'desc');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Get unread notifications for a student
     */
    public function getUnreadForStudent($studentId, $limit = null)
    {
        $query = Notification::where('student_id', $studentId)
            ->unread()
            ->orderBy('created_at', 'desc');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Get all notifications for a user
     */
    public function getAllForUser($userId, $limit = 20)
    {
        return Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    /**
     * Get all notifications for a student
     */
    public function getAllForStudent($studentId, $limit = 20)
    {
        return Notification::where('student_id', $studentId)
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId)
    {
        $notification = Notification::findOrFail($notificationId);
        $notification->markAsRead();
        return $notification;
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsReadForUser($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('read', false)
            ->update([
                'read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Mark all notifications as read for a student
     */
    public function markAllAsReadForStudent($studentId)
    {
        return Notification::where('student_id', $studentId)
            ->where('read', false)
            ->update([
                'read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Delete a notification
     */
    public function delete($notificationId)
    {
        return Notification::destroy($notificationId);
    }

    /**
     * Check for contracts expiring soon and create notifications
     */
    public function checkExpiringContracts($days = 7)
    {
        $expiringDate = Carbon::now()->addDays($days);

        // Check student contracts expiring soon
        $expiringStudentContracts = StudentContracts::where('active', true)
            ->where('end_date', '<=', $expiringDate)
            ->where('end_date', '>', Carbon::now())
            ->with('student')
            ->get();

        foreach ($expiringStudentContracts as $contract) {
            $daysLeft = Carbon::now()->diffInDays($contract->end_date);
            
            // Notify student
            $this->createForStudent(
                $contract->student_id,
                'warning',
                'Contrato expirando em breve',
                "Seu contrato {$contract->service_name} expira em {$daysLeft} dia(s). Entre em contato com a academia para renovar.",
                route('students.profile') // Link to profile where they can see contracts
            );

            // Notify establishment admin (if we can find users from the establishment)
            // This would need establishment_id -> users relationship
        }

        // Check establishment contracts expiring soon
        $expiringEstablishmentContracts = EstablishmentContracts::where('active', true)
            ->where('end_date', '<=', $expiringDate)
            ->where('end_date', '>', Carbon::now())
            ->with('establishment')
            ->get();

        foreach ($expiringEstablishmentContracts as $contract) {
            $daysLeft = Carbon::now()->diffInDays($contract->end_date);
            
            // Find users associated with this establishment
            $users = User::whereHas('establishments', function($q) use ($contract) {
                $q->where('establishment_id', $contract->establishment_id);
            })->get();

            foreach ($users as $user) {
                $this->createForUser(
                    $user->id,
                    'warning',
                    'Contrato de estabelecimento expirando',
                    "O contrato do estabelecimento {$contract->establishment->name} expira em {$daysLeft} dia(s). Renove para continuar usando o sistema.",
                    route('admin.establishments.contracts', $contract->establishment_id)
                );
            }
        }

        return [
            'student_contracts' => $expiringStudentContracts->count(),
            'establishment_contracts' => $expiringEstablishmentContracts->count(),
        ];
    }

    /**
     * Create notification for weekly goal achievement
     */
    public function notifyWeeklyGoalAchievement($studentId, $currentValue, $targetValue)
    {
        return $this->createForStudent(
            $studentId,
            'success',
            'Meta semanal alcançada!',
            "Parabéns! Você completou {$currentValue} de {$targetValue} aulas esta semana.",
            route('student.dashboard')
        );
    }

    /**
     * Create notification for payment reminder
     */
    public function notifyPaymentReminder($studentId, $contract)
    {
        return $this->createForStudent(
            $studentId,
            'warning',
            'Lembrete de pagamento',
            "Seu pagamento de R$ " . number_format($contract->amount, 2, ',', '.') . " está próximo do vencimento.",
            route('student.contracts.index')
        );
    }

    /**
     * Create notification for achievement unlocked
     */
    public function notifyAchievementUnlocked($studentId, $achievementName)
    {
        return $this->createForStudent(
            $studentId,
            'success',
            'Conquista desbloqueada!',
            "Parabéns! Você desbloqueou a conquista: {$achievementName}",
            route('student.profile')
        );
    }

    /**
     * Create notification for goal achieved
     */
    public function notifyGoalAchieved($studentId, $goalName, $goalType = null)
    {
        return $this->createForStudent(
            $studentId,
            'success',
            'Meta alcançada!',
            "Parabéns! Você alcançou a meta: {$goalName}",
            route('student.dashboard')
        );
    }

    /**
     * Create notification for goal expiring soon
     */
    public function notifyGoalExpiringSoon($studentId, $goalName, $daysLeft)
    {
        return $this->createForStudent(
            $studentId,
            'warning',
            'Meta próxima do prazo',
            "Sua meta '{$goalName}' expira em {$daysLeft} dia(s). Continue trabalhando para alcançá-la!",
            route('student.dashboard')
        );
    }

    /**
     * Create notification for new workout assigned
     */
    public function notifyNewWorkout($studentId, $workoutName)
    {
        return $this->createForStudent(
            $studentId,
            'info',
            'Novo treino disponível',
            "Um novo treino foi atribuído a você: {$workoutName}",
            route('student.workouts.index')
        );
    }

    /**
     * Create notification for class booking confirmation
     */
    public function notifyClassBookingConfirmed($studentId, $className, $classDate)
    {
        $formattedDate = \Carbon\Carbon::parse($classDate)->format('d/m/Y H:i');
        return $this->createForStudent(
            $studentId,
            'success',
            'Aula confirmada',
            "Sua reserva para a aula '{$className}' foi confirmada para {$formattedDate}.",
            route('student.class_bookings.index')
        );
    }

    /**
     * Create notification for class booking cancelled
     */
    public function notifyClassBookingCancelled($studentId, $className, $classDate, $reason = null)
    {
        $formattedDate = \Carbon\Carbon::parse($classDate)->format('d/m/Y H:i');
        $message = "Sua reserva para a aula '{$className}' em {$formattedDate} foi cancelada.";
        if ($reason) {
            $message .= " Motivo: {$reason}";
        }
        
        return $this->createForStudent(
            $studentId,
            'warning',
            'Aula cancelada',
            $message,
            route('student.class_bookings.index')
        );
    }

    /**
     * Create notification for user (admin/instructor)
     */
    public function notifyNewStudentRegistration($userId, $studentName)
    {
        return $this->createForUser(
            $userId,
            'info',
            'Novo aluno cadastrado',
            "Um novo aluno foi cadastrado: {$studentName}",
            route('admin.students.index')
        );
    }

    /**
     * Get notification count for user
     */
    public function getUnreadCountForUser($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('read', false)
            ->count();
    }

    /**
     * Get notification count for student
     */
    public function getUnreadCountForStudent($studentId)
    {
        return Notification::where('student_id', $studentId)
            ->where('read', false)
            ->count();
    }

    /**
     * Delete old read notifications (cleanup)
     */
    public function deleteOldReadNotifications($daysOld = 30)
    {
        $cutoffDate = Carbon::now()->subDays($daysOld);
        
        return Notification::where('read', true)
            ->where('read_at', '<', $cutoffDate)
            ->delete();
    }

    /**
     * Bulk create notifications for multiple users
     */
    public function createForMultipleUsers(array $userIds, $type, $title, $message, $actionUrl = null)
    {
        $notifications = [];
        
        foreach ($userIds as $userId) {
            $notifications[] = $this->createForUser($userId, $type, $title, $message, $actionUrl);
        }
        
        return $notifications;
    }

    /**
     * Bulk create notifications for multiple students
     */
    public function createForMultipleStudents(array $studentIds, $type, $title, $message, $actionUrl = null)
    {
        $notifications = [];
        
        foreach ($studentIds as $studentId) {
            $notifications[] = $this->createForStudent($studentId, $type, $title, $message, $actionUrl);
        }
        
        return $notifications;
    }
}

