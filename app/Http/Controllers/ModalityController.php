<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreModalityRequest;
use App\Http\Requests\UpdateModalityRequest;
use App\Http\Traits\HasEstablishmentContext;
use App\Models\Establishment;
use App\Models\Modality;
use Illuminate\Http\Request;

class ModalityController extends Controller
{
    use HasEstablishmentContext;

    public function index(Request $request)
    {
        $establishmentId = $this->getEstablishmentId();
        $query = Modality::query();
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('active', $request->status == 'ativo' ? 1 : 0);
        }

        // Date range filter
        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }
        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }

        $modalities = $query->orderBy('name')->get();

        $modalities_admin = Modality::select('modalities.*')
                            ->with('establishment')
                            ->where('active', 1)
                            ->orderBy('modalities.name')->get();
                       
        $establishment = null;
        if ($establishmentId) {
            $establishment = Establishment::with('modalities')->find($establishmentId);
        }
                                      
        return view('admin.modalities.index', [
            'modalities' => $modalities, 
            'modalities_admin' => $modalities_admin, 
            'establishment' => $establishment,
            'filters' => $request->only(['search', 'status', 'created_from', 'created_to'])
        ]);
    }

    public function create()
    {
        return view('admin.modalities.add');
    }

    public function store(StoreModalityRequest $request)
    {
        $validatedData = $request->validated();

        if(isset($validatedData['active'])) {
            $validatedData['active'] = 1;
        } else {
            $validatedData['active'] = 0;
        }

        Modality::create($validatedData);

        return redirect()->route('admin.modalities.index')->with('success', 'Modalidade criada com sucesso!');
    }

    public function edit($modality)
    {
        $modality = Modality::find($modality);

        return view('admin.modalities.edit', ['modality' => $modality]);
    }

    public function update(UpdateModalityRequest $request, $modalityId)
    {
        $modality = Modality::findOrFail($modalityId);

        $validatedData = $request->validated();

        if(isset($validatedData['active'])) {
            $validatedData['active'] = 1;
        } else {
            $validatedData['active'] = 0;
        }

        $modality->update($validatedData);

        return redirect()->route('admin.modalities.index')->with('success', 'Modalidade atualizada com sucesso!');
    }

    public function view($modalityId)
    {
        $modality = Modality::findOrFail($modalityId);
        
        return view('admin.modalities.view', ['modality' => $modality]);
    }

    public function destroy($modalityId)
    {
        $modality = Modality::findOrFail($modalityId);
        $modality->delete();
    }

    public function restore($modalityId)
    {
        $modality = Modality::withTrashed()->findOrFail($modalityId);
        $modality->restore();

        return redirect()->route('admin.modalities.index')->with('success', 'Modalidade restaurada com sucesso.');
    }

    public function attach(Request $request)
    {
        $establishmentId = $this->getEstablishmentId();

        if (!$establishmentId) {
            return redirect()->route('admin.modalities.index')->with('error', 'Estabelecimento não selecionado.');
        }

        $modalities = $request->input('modalities');

        $establishment = Establishment::findOrFail($establishmentId);
        $establishment->modalities()->sync($modalities);

        return redirect()->route('admin.modalities.index')->with('success', 'Modalidades atribuidas com sucesso.');
    }
}
