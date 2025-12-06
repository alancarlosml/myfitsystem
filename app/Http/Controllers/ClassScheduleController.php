<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClassScheduleRequest;
use App\Http\Requests\UpdateClassScheduleRequest;
use App\Http\Traits\HasEstablishmentContext;
use App\Models\ClassSchedule;
use App\Models\Establishment;
use App\Models\Modality;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ClassScheduleController extends Controller
{
    use HasEstablishmentContext;

    protected $guard;

    public function __construct()
    {
        $this->guard = Auth::guard('user')->check() ? 'user' : 'student';
    }

    public function index()
    {
        $query = ClassSchedule::select('class_schedules.*')
                                        ->leftJoin('establishments', 'class_schedules.establishment_id', '=', 'establishments.id')
                                        ->leftJoin('modalities', 'class_schedules.modality_id', '=', 'modalities.id')
                                        ->orderBy('class_schedules.class_date','desc')
                                        ->orderBy('class_schedules.start_time','desc')
                                        ->with(['modality', 'establishment']);

        // For students or users non-superuser, always filter by current establishment
        $establishmentId = $this->getEstablishmentId();
        if ($this->guard === 'student') {
            if ($establishmentId) {
                $query->where('establishments.id', $establishmentId);
            } else {
                $query->whereRaw('1 = 0'); // Return empty if no establishment
            }
        } elseif (!$this->hasAnyRole(['superuser'])) {
            if ($establishmentId) {
                $query->where('establishments.id', $establishmentId);
            } else {
                $query->whereRaw('1 = 0'); // Return empty if no establishment
            }
        }

        // For students, add booking information
        if ($this->guard === 'student') {
            $studentId = Auth::guard('student')->id();
            $query->leftJoin('class_bookings', function($join) use ($studentId) {
                $join->on('class_schedules.id', '=', 'class_bookings.class_schedule_id')
                     ->where('class_bookings.student_id', '=', $studentId);
            })->addSelect('class_bookings.id as booking_id');
        }

        $class_schedules = $query->get();

        // Different view for students vs admins
        $view = $this->guard === 'student' ? 'student.class_schedules.index' : 'admin.class_schedules.index';
        return view($view, ['class_schedules' => $class_schedules]);
    }

    public function create()
    {
        $establishments = null;
        $modalities = null;

        if ($this->hasAnyRole(['superuser'])) {
            $establishments = Establishment::all();
            $modalities = Modality::all();
        } else {
            $establishmentId = $this->getEstablishmentId();
            $establishment = $establishmentId ? Establishment::where('id', $establishmentId)->first() : null;
            if ($establishment) {
                $modalities = $establishment->modalities;
            }
        }

        return view('admin.class_schedules.add', ['modalities' => $modalities, 'establishments' => $establishments]);
    }

    public function store(StoreClassScheduleRequest $request)
    {
        $validatedData = $request->validated();

        if (!$this->hasAnyRole(['superuser'])){
            $validatedData['establishment_id'] = $this->getEstablishmentId();
        }

        // Adiciona o user_id do usuário autenticado
        $validatedData['user_id'] = Auth::guard('user')->id();

        ClassSchedule::create($validatedData);
        return redirect()->route('admin.class_schedules.index')->with('success', 'Agendamento de aula criado com sucesso!');
    }

    public function edit($id)
    {
        $class_schedule = ClassSchedule::findOrFail($id);

        $establishments = null;
        $modalities = null;

        if ($this->hasAnyRole(['superuser'])) {
            $establishments = Establishment::all();
            $modalities = Modality::all();
        } else {
            $establishmentId = $this->getEstablishmentId();
            $establishment = $establishmentId ? Establishment::where('id', $establishmentId)->first() : null;
            if ($establishment) {
                $modalities = $establishment->modalities;
            }
        }

        return view('admin.class_schedules.edit', [
            'class_schedule' => $class_schedule,
            'modalities' => $modalities,
            'establishments' => $establishments
        ]);
    }

    public function update(UpdateClassScheduleRequest $request, $id)
    {
        $class_schedule = ClassSchedule::findOrFail($id);
        $validatedData = $request->validated();

        if (!$this->hasAnyRole(['superuser'])){
            $validatedData['establishment_id'] = $this->getEstablishmentId();
        }

        // Mantém o user_id existente ou atualiza com o usuário autenticado se não houver
        if (!isset($validatedData['user_id']) && Auth::guard('user')->check()) {
            $validatedData['user_id'] = Auth::guard('user')->id();
        }

        $class_schedule->update($validatedData);
        return redirect()->route('admin.class_schedules.index')->with('success', 'Agendamento de aula atualizado com sucesso!');
    }

    public function view($id)
    {
        $class_schedule = ClassSchedule::with(['modality', 'establishment', 'class_bookings.student'])->findOrFail($id);
        return view('admin.class_schedules.view', ['class_schedule' => $class_schedule]);
    }

    public function destroy($id)
    {
        $class_schedule = ClassSchedule::findOrFail($id);
        $class_schedule->delete();
        return redirect()->route('admin.class_schedules.index')->with('success', 'Agendamento de aula excluído com sucesso!');
    }

    public function restore($id)
    {
        $class_schedule = ClassSchedule::withTrashed()->findOrFail($id);
        $class_schedule->restore();
        return redirect()->route('admin.class_schedules.index')->with('success', 'Agendamento de aula restaurado com sucesso!');
    }
}
