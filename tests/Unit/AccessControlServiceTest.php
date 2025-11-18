<?php

namespace Tests\Unit;

use App\Models\Establishment;
use App\Models\EstablishmentContracts;
use App\Models\Role;
use App\Models\Student;
use App\Models\StudentContracts;
use App\Models\User;
use App\Services\AccessControlService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessControlServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AccessControlService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(AccessControlService::class);
    }

    public function test_establishment_with_paid_contract_is_active()
    {
        $establishment = Establishment::factory()->create(['active' => true]);

        EstablishmentContracts::factory()->create([
            'establishment_id' => $establishment->id,
            'active' => true,
            'status' => 'pago',
            'end_date' => Carbon::now()->addMonth(),
        ]);

        $this->assertTrue($this->service->establishmentHasActiveSystemContract($establishment));
    }

    public function test_establishment_with_expired_contract_is_not_active()
    {
        $establishment = Establishment::factory()->create(['active' => true]);

        EstablishmentContracts::factory()->create([
            'establishment_id' => $establishment->id,
            'active' => true,
            'status' => 'pago',
            'end_date' => Carbon::now()->subDay(),
        ]);

        $this->assertFalse($this->service->establishmentHasActiveSystemContract($establishment));
    }

    public function test_user_accessible_establishments_are_filtered_by_contract()
    {
        $role = Role::factory()->create(['name' => 'admin']);
        $user = User::factory()->create();

        $validEstablishment = Establishment::factory()->create(['active' => true]);
        $expiredEstablishment = Establishment::factory()->create(['active' => true]);

        EstablishmentContracts::factory()->create([
            'establishment_id' => $validEstablishment->id,
            'active' => true,
            'status' => 'pago',
            'end_date' => Carbon::now()->addMonths(3),
        ]);

        EstablishmentContracts::factory()->create([
            'establishment_id' => $expiredEstablishment->id,
            'active' => true,
            'status' => 'vencido',
            'end_date' => Carbon::now()->subMonth(),
        ]);

        $user->roles()->attach($role->id, ['establishment_id' => $validEstablishment->id, 'active' => true]);
        $user->roles()->attach($role->id, ['establishment_id' => $expiredEstablishment->id, 'active' => true]);

        $accessible = $this->service->getAccessibleEstablishmentsForUser($user);

        $this->assertCount(1, $accessible);
        $this->assertTrue($accessible->pluck('id')->contains($validEstablishment->id));
    }

    public function test_student_only_sees_establishments_with_paid_membership()
    {
        $student = Student::factory()->create();

        $paidEstablishment = Establishment::factory()->create(['active' => true]);
        $unpaidEstablishment = Establishment::factory()->create(['active' => true]);

        EstablishmentContracts::factory()->create([
            'establishment_id' => $paidEstablishment->id,
            'active' => true,
            'status' => 'pago',
            'end_date' => Carbon::now()->addMonths(2),
        ]);

        EstablishmentContracts::factory()->create([
            'establishment_id' => $unpaidEstablishment->id,
            'active' => true,
            'status' => 'pago',
            'end_date' => Carbon::now()->addMonths(2),
        ]);

        $student->establishments()->attach($paidEstablishment->id, ['active' => true]);
        $student->establishments()->attach($unpaidEstablishment->id, ['active' => true]);

        StudentContracts::factory()->create([
            'student_id' => $student->id,
            'establishment_id' => $paidEstablishment->id,
            'active' => true,
            'status' => 'pago',
            'end_date' => Carbon::now()->addWeek(),
        ]);

        StudentContracts::factory()->create([
            'student_id' => $student->id,
            'establishment_id' => $unpaidEstablishment->id,
            'active' => true,
            'status' => 'vencido',
            'end_date' => Carbon::now()->subDay(),
        ]);

        $accessible = $this->service->getAccessibleEstablishmentsForStudent($student);

        $this->assertCount(1, $accessible);
        $this->assertTrue($accessible->pluck('id')->contains($paidEstablishment->id));
    }
}

