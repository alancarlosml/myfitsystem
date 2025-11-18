<?php

namespace App\Services;

use App\Models\EstablishmentContracts;
use App\Models\StudentContracts;
use Carbon\Carbon;

class PaymentService
{
    /**
     * Mark a contract as paid
     */
    public function markAsPaid($contract, $paidAt = null)
    {
        $paidAt = $paidAt ?? now();
        
        $contract->update([
            'status' => 'pago',
            'paid_at' => $paidAt,
        ]);

        return $contract;
    }

    /**
     * Mark a contract as pending
     */
    public function markAsPending($contract)
    {
        $contract->update([
            'status' => 'pendente',
            'paid_at' => null,
        ]);

        return $contract;
    }

    /**
     * Mark a contract as overdue
     */
    public function markAsOverdue($contract)
    {
        $contract->update([
            'status' => 'vencido',
            'paid_at' => null,
        ]);

        return $contract;
    }

    /**
     * Check and update overdue contracts
     */
    public function checkOverdueContracts()
    {
        $today = now();

        // Check establishment contracts
        EstablishmentContracts::where('status', 'pendente')
            ->where('active', true)
            ->where('payment_date', '<', $today)
            ->get()
            ->each(function ($contract) {
                $this->markAsOverdue($contract);
            });

        // Check student contracts
        StudentContracts::where('status', 'pendente')
            ->where('active', true)
            ->where('payment_date', '<', $today)
            ->get()
            ->each(function ($contract) {
                $this->markAsOverdue($contract);
            });
    }

    /**
     * Get total revenue for a period
     */
    public function getTotalRevenue($startDate = null, $endDate = null, $establishmentId = null)
    {
        $startDate = $startDate ?? now()->startOfMonth();
        $endDate = $endDate ?? now()->endOfMonth();

        $establishmentContracts = EstablishmentContracts::where('status', 'pago')
            ->whereBetween('paid_at', [$startDate, $endDate]);

        $studentContracts = StudentContracts::where('status', 'pago')
            ->whereBetween('paid_at', [$startDate, $endDate]);

        if ($establishmentId) {
            $establishmentContracts->where('establishment_id', $establishmentId);
            $studentContracts->where('establishment_id', $establishmentId);
        }

        $totalEstablishment = $establishmentContracts->sum('amount');
        $totalStudent = $studentContracts->sum('amount');

        return [
            'establishment' => $totalEstablishment,
            'student' => $totalStudent,
            'total' => $totalEstablishment + $totalStudent,
        ];
    }

    /**
     * Get pending payments
     */
    public function getPendingPayments($establishmentId = null)
    {
        $establishmentContracts = EstablishmentContracts::where('status', 'pendente')
            ->where('active', true);

        $studentContracts = StudentContracts::where('status', 'pendente')
            ->where('active', true);

        if ($establishmentId) {
            $establishmentContracts->where('establishment_id', $establishmentId);
            $studentContracts->where('establishment_id', $establishmentId);
        }

        return [
            'establishment' => $establishmentContracts->get(),
            'student' => $studentContracts->get(),
            'total_amount' => $establishmentContracts->sum('amount') + $studentContracts->sum('amount'),
        ];
    }

    /**
     * Get overdue payments
     */
    public function getOverduePayments($establishmentId = null)
    {
        $establishmentContracts = EstablishmentContracts::where('status', 'vencido')
            ->where('active', true);

        $studentContracts = StudentContracts::where('status', 'vencido')
            ->where('active', true);

        if ($establishmentId) {
            $establishmentContracts->where('establishment_id', $establishmentId);
            $studentContracts->where('establishment_id', $establishmentId);
        }

        return [
            'establishment' => $establishmentContracts->get(),
            'student' => $studentContracts->get(),
            'total_amount' => $establishmentContracts->sum('amount') + $studentContracts->sum('amount'),
        ];
    }

    /**
     * Create a new contract with default status
     */
    public function createContract($type, array $data)
    {
        $data['status'] = $data['status'] ?? 'pendente';
        
        if ($type === 'establishment') {
            return EstablishmentContracts::create($data);
        } elseif ($type === 'student') {
            return StudentContracts::create($data);
        }

        throw new \InvalidArgumentException("Contract type must be 'establishment' or 'student'");
    }

    /**
     * Get contracts expiring soon
     */
    public function getContractsExpiringSoon($days = 30, $establishmentId = null)
    {
        $endDate = now()->addDays($days);

        $establishmentContracts = EstablishmentContracts::where('active', true)
            ->where('end_date', '<=', $endDate)
            ->where('end_date', '>', now());

        $studentContracts = StudentContracts::where('active', true)
            ->where('end_date', '<=', $endDate)
            ->where('end_date', '>', now());

        if ($establishmentId) {
            $establishmentContracts->where('establishment_id', $establishmentId);
            $studentContracts->where('establishment_id', $establishmentId);
        }

        return [
            'establishment' => $establishmentContracts->get(),
            'student' => $studentContracts->get(),
        ];
    }
}

