<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClassScheduleRequest;
use App\Http\Requests\UpdateClassScheduleRequest;
use App\Models\ClassSchedule;
use App\Models\Establishment;
use App\Models\Modality;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ClassScheduleController extends Controller
{
    protected $role;
    public function __construct()
    {
        $this->role = Auth::user()->getRoleForEstablishment(Session::get('establishment_id'));
    }

    public function index()
    {
        $query = ClassSchedule::select('class_schedules.*')
                                        ->leftJoin('establishments', 'class_schedules.establishment_id', '=', 'establishments.id')
                                        ->leftJoin('modalities', 'class_schedules.modality_id', '=', 'modalities.id')
                                        ->orderBy('class_schedules.class_date','desc')
                                        ->orderBy('class_schedules.start_time','desc')
                                        ->with(['modality', 'establishment']);
        
        if ($this->role && !in_array($this->role->name, ['superuser'])){
            $query->where('establishments.id', Session::get('establishment_id'));
        }

        $class_schedules = $query->get();
        
        return view('admin.class_schedules.index', ['class_schedules' => $class_schedules]);
    }

    public function create()
    {
        $establishments = null;
        $modalities = null;

        if ($this->role && !in_array($this->role->name, ['superuser'])) {
            $establishment = Establishment::where('id', Session::get('establishment_id'))->first(); 
            if ($establishment) {
                $modalities = $establishment->modalities;
            }
        } else {
            $establishments = Establishment::all();
            $modalities = Modality::all();
        }

        return view('admin.class_schedules.add', ['modalities' => $modalities, 'establishments' => $establishments]);
    }

    public function store(StoreClassScheduleRequest $request)
    {
        $validatedData = $request->validated();

        if ($this->role && !in_array($this->role->name, ['superuser'])){
            $validatedData['establishment_id'] = Session::get('establishment_id');
        }

        ClassSchedule::create($validatedData);
        return redirect()->route('admin.class_schedules.index')->with('success', 'Agendamento de aula criado com sucesso!');
    }

    public function edit($id)
    {
        $class_schedule = ClassSchedule::findOrFail($id);

        $establishments = null;
        $modalities = null;

        if ($this->role && !in_array($this->role->name, ['superuser'])) {
            $establishment = Establishment::where('id', Session::get('establishment_id'))->first(); 
            if ($establishment) {
                $modalities = $establishment->modalities;
            }
        } else {
            $establishments = Establishment::all();
            $modalities = Modality::all();
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

        if ($this->role && !in_array($this->role->name, ['superuser'])){
            $validatedData['establishment_id'] = Session::get('establishment_id');
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
