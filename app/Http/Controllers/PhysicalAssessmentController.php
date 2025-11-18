<?php

namespace App\Http\Controllers;

use App\Models\PhysicalAssessment;
use App\Models\Student;
use App\Models\Establishment;
use App\Jobs\CalculateStudentProgress;
use App\Notifications\NewPhysicalAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class PhysicalAssessmentController extends Controller
{
    protected $role;
    protected $guard;

    public function __construct()
    {
        $this->guard = Auth::guard('user')->check() ? 'user' : 'student';
        if ($this->guard === 'user') {
            $this->role = Auth::user()->getRoleForEstablishment(Session::get('establishment_id'));
        } else {
            $this->role = null; // Students don't have roles
        }
    }

    // List all physical assessments
    public function index(Request $request)
    {
        $query = PhysicalAssessment::query()
            ->with(['student', 'establishment', 'user'])
            ->orderBy('assessment_date', 'desc');

        // Filter by establishment for non-superuser
        if ($this->role && !in_array($this->role->name, ['superuser'])) {
            $establishmentId = Session::get('establishment_id');
            $query->where('establishment_id', $establishmentId);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Filter by student if provided
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $perPage = $request->get('per_page', 15);
        $assessments = $query->paginate($perPage);

        // Get students for filter dropdown (optimized with join)
        $establishmentId = Session::get('establishment_id');
        $students = Student::join('student_establishment', 'students.id', '=', 'student_establishment.student_id')
            ->where('student_establishment.establishment_id', $establishmentId)
            ->select('students.*')
            ->distinct()
            ->orderBy('students.name')
            ->get();

        return view('admin.physical_assessments.index', compact('assessments', 'students'));
    }

    // Show form to create new assessment
    public function create()
    {
        $establishmentId = Session::get('establishment_id');

        $students = Student::whereHas('establishments', function($q) use ($establishmentId) {
            $q->where('establishment_id', $establishmentId);
        })->orderBy('name')->get();

        // If a specific student ID is provided (e.g., from student profile)
        $selectedStudent = null;
        if (request('student_id')) {
            $selectedStudent = Student::find(request('student_id'));
        }

        return view('admin.physical_assessments.add', compact('students', 'selectedStudent'));
    }

    // Store new physical assessment
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'assessment_date' => 'required|date',
            'weight' => 'nullable|numeric|min:1|max:999.99',
            'height' => 'nullable|numeric|min:1|max:999.99',
            'body_fat_percentage' => 'nullable|numeric|min:0|max:100',
            'muscle_mass_percentage' => 'nullable|numeric|min:0|max:100',
            'chest_measurement' => 'nullable|numeric|min:1|max:999.99',
            'waist_measurement' => 'nullable|numeric|min:1|max:999.99',
            'hip_measurement' => 'nullable|numeric|min:1|max:999.99',
            'arm_measurement' => 'nullable|numeric|min:1|max:999.99',
            'thigh_measurement' => 'nullable|numeric|min:1|max:999.99',
            'resting_heart_rate' => 'nullable|integer|min:1|max:255',
            'max_heart_rate' => 'nullable|integer|min:1|max:255',
            'blood_pressure_systolic' => 'nullable|numeric|min:1|max:300',
            'blood_pressure_diastolic' => 'nullable|numeric|min:1|max:200',
            'observations' => 'nullable|string|max:2000',
            'goals' => 'nullable|string|max:2000',
            'recommendations' => 'nullable|string|max:2000',
        ]);

        $establishmentId = Session::get('establishment_id');

        $assessment = PhysicalAssessment::create([
            'establishment_id' => $establishmentId,
            'student_id' => $request->student_id,
            'user_id' => Auth::id(),
            'assessment_date' => $request->assessment_date,
            'weight' => $request->weight,
            'height' => $request->height,
            'body_fat_percentage' => $request->body_fat_percentage,
            'muscle_mass_percentage' => $request->muscle_mass_percentage,
            'chest_measurement' => $request->chest_measurement,
            'waist_measurement' => $request->waist_measurement,
            'hip_measurement' => $request->hip_measurement,
            'arm_measurement' => $request->arm_measurement,
            'thigh_measurement' => $request->thigh_measurement,
            'resting_heart_rate' => $request->resting_heart_rate,
            'max_heart_rate' => $request->max_heart_rate,
            'blood_pressure_systolic' => $request->blood_pressure_systolic,
            'blood_pressure_diastolic' => $request->blood_pressure_diastolic,
            'observations' => $request->observations,
            'goals' => $request->goals,
            'recommendations' => $request->recommendations,
        ]);

        // Notify the student about the new assessment
        $student = Student::find($request->student_id);
        if ($student) {
            $student->notify(new NewPhysicalAssessment($assessment));
        }

        // Calculate progress asynchronously
        CalculateStudentProgress::dispatch($request->student_id);

        return redirect()->route('admin.physical_assessments.index')
            ->with('success', 'Avaliação física criada com sucesso!');
    }

    // Show single assessment
    public function show($id)
    {
        $assessment = PhysicalAssessment::with(['student', 'establishment', 'user'])
            ->findOrFail($id);

        // Check if user can access this assessment
        if ($this->role && !in_array($this->role->name, ['superuser']) &&
            $assessment->establishment_id != Session::get('establishment_id')) {
            abort(403, 'Acesso não autorizado.');
        }

        return view('admin.physical_assessments.view', compact('assessment'));
    }

    // Show form to edit assessment
    public function edit($id)
    {
        $assessment = PhysicalAssessment::findOrFail($id);

        // Check if user can edit this assessment
        if ($this->role && !in_array($this->role->name, ['superuser']) &&
            $assessment->establishment_id != Session::get('establishment_id')) {
            abort(403, 'Acesso não autorizado.');
        }

        $establishmentId = Session::get('establishment_id');
        $students = Student::whereHas('establishments', function($q) use ($establishmentId) {
            $q->where('establishment_id', $establishmentId);
        })->orderBy('name')->get();

        return view('admin.physical_assessments.edit', compact('assessment', 'students'));
    }

    // Update assessment
    public function update(Request $request, $id)
    {
        $assessment = PhysicalAssessment::findOrFail($id);

        // Check if user can update this assessment
        if ($this->role && !in_array($this->role->name, ['superuser']) &&
            $assessment->establishment_id != Session::get('establishment_id')) {
            abort(403, 'Acesso não autorizado.');
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'assessment_date' => 'required|date',
            'weight' => 'nullable|numeric|min:1|max:999.99',
            'height' => 'nullable|numeric|min:1|max:999.99',
            'body_fat_percentage' => 'nullable|numeric|min:0|max:100',
            'muscle_mass_percentage' => 'nullable|numeric|min:0|max:100',
            'chest_measurement' => 'nullable|numeric|min:1|max:999.99',
            'waist_measurement' => 'nullable|numeric|min:1|max:999.99',
            'hip_measurement' => 'nullable|numeric|min:1|max:999.99',
            'arm_measurement' => 'nullable|numeric|min:1|max:999.99',
            'thigh_measurement' => 'nullable|numeric|min:1|max:999.99',
            'resting_heart_rate' => 'nullable|integer|min:1|max:255',
            'max_heart_rate' => 'nullable|integer|min:1|max:255',
            'blood_pressure_systolic' => 'nullable|numeric|min:1|max:300',
            'blood_pressure_diastolic' => 'nullable|numeric|min:1|max:200',
            'observations' => 'nullable|string|max:2000',
            'goals' => 'nullable|string|max:2000',
            'recommendations' => 'nullable|string|max:2000',
        ]);

        $assessment->update($request->only([
            'student_id',
            'assessment_date',
            'weight',
            'height',
            'body_fat_percentage',
            'muscle_mass_percentage',
            'chest_measurement',
            'waist_measurement',
            'hip_measurement',
            'arm_measurement',
            'thigh_measurement',
            'resting_heart_rate',
            'max_heart_rate',
            'blood_pressure_systolic',
            'blood_pressure_diastolic',
            'observations',
            'goals',
            'recommendations',
        ]));

        return redirect()->route('admin.physical_assessments.show', $assessment->id)
            ->with('success', 'Avaliação física atualizada com sucesso!');
    }

    // Delete assessment
    public function destroy($id)
    {
        $assessment = PhysicalAssessment::findOrFail($id);

        // Check if user can delete this assessment
        if ($this->role && !in_array($this->role->name, ['superuser']) &&
            $assessment->establishment_id != Session::get('establishment_id')) {
            abort(403, 'Acesso não autorizado.');
        }

        $assessment->delete();

        return redirect()->route('admin.physical_assessments.index')
            ->with('success', 'Avaliação física excluída com sucesso!');
    }

    // Get student's assessment history (for API)
    public function getStudentHistory($studentId)
    {
        // Check if user can access this student
        $student = Student::where('id', $studentId)->whereHas('establishments', function($q) {
            $q->where('establishment_id', Session::get('establishment_id'));
        })->firstOrFail();

        $assessments = PhysicalAssessment::where('student_id', $studentId)
            ->where('establishment_id', Session::get('establishment_id'))
            ->with('user')
            ->orderBy('assessment_date', 'desc')
            ->get();

        return response()->json($assessments);
    }

    // Student index view - list their own assessments
    public function studentIndex()
    {
        $studentId = Auth::guard('student')->id();

        $assessments = PhysicalAssessment::where('student_id', $studentId)
            ->where('establishment_id', Session::get('establishment_id'))
            ->with('user')
            ->orderBy('assessment_date', 'desc')
            ->paginate(10);

        return view('student.physical_assessments.index', compact('assessments'));
    }

    // Student show view - view their own assessments
    public function studentShow($id)
    {
        $studentId = Auth::guard('student')->id();

        $assessment = PhysicalAssessment::where('id', $id)
            ->where('student_id', $studentId)
            ->where('establishment_id', Session::get('establishment_id'))
            ->with(['user', 'establishment'])
            ->firstOrFail();

        return view('student.physical_assessments.view', compact('assessment'));
    }
}
