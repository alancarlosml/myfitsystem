<?php

namespace App\Services;

use App\Models\Establishment;
use App\Models\Student;
use App\Models\StudentContracts;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AccessControlService
{
    /**
     * Determine if the establishment has an active (paid) contract with the platform.
     */
    public function establishmentHasActiveSystemContract(Establishment $establishment): bool
    {
        $latestContract = $establishment->contracts()
            ->where('active', true)
            ->orderByDesc('end_date')
            ->first();

        if (!$latestContract) {
            return false;
        }

        if ($latestContract->status !== 'pago') {
            return false;
        }

        if (!$latestContract->end_date) {
            return false;
        }

        return !Carbon::parse($latestContract->end_date)->endOfDay()->isPast();
    }

    /**
     * Return establishments a given user (non-superuser) can access.
     */
    public function getAccessibleEstablishmentsForUser(User $user): Collection
    {
        // Superuser can access everything
        if ($this->userIsSuperuser($user)) {
            return $user->establishments()->get();
        }

        return $user->getEstablishmentsActive()->get()->filter(function (Establishment $establishment) {
            return $this->establishmentHasActiveSystemContract($establishment);
        })->values();
    }

    /**
     * Return establishments a student can access.
     */
    public function getAccessibleEstablishmentsForStudent(Student $student): Collection
    {
        return $student->establishments()->get()->filter(function (Establishment $establishment) use ($student) {
            return $this->studentHasValidAccessContract($student, $establishment);
        })->values();
    }

    /**
     * Determine if a student can access an establishment (paid and within validity).
     */
    public function studentHasValidAccessContract(Student $student, Establishment $establishment): bool
    {
        if (!$this->establishmentHasActiveSystemContract($establishment)) {
            return false;
        }

        $contract = $this->getLatestStudentContractForEstablishment($student, $establishment);

        if (!$contract) {
            return false;
        }

        if (!$contract->active || $contract->status !== 'pago') {
            return false;
        }

        if (!$contract->end_date) {
            return false;
        }

        return !Carbon::parse($contract->end_date)->endOfDay()->isPast();
    }

    /**
     * Determine if the authenticated user holds the superuser role.
     */
    public function userIsSuperuser(User $user): bool
    {
        return $user->roles()->where('name', 'superuser')->exists();
    }

    protected function getLatestStudentContractForEstablishment(Student $student, Establishment $establishment): ?StudentContracts
    {
        return $student->contracts()
            ->where('establishment_id', $establishment->id)
            ->orderByDesc('end_date')
            ->first();
    }
}

