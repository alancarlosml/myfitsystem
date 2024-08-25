<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreModalityRequest;
use App\Http\Requests\UpdateModalityRequest;
use App\Models\Establishment;
use App\Models\Modality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ModalityController extends Controller
{
    public function index()
    {
        $modalities = Modality::select('modalities.*')
                           ->orderBy('modalities.name')->get();

        $modalities_admin = Modality::select('modalities.*')
                            ->where('active', 1)
                            ->orderBy('modalities.name')->get();
                       
       $establishment = Establishment::with('modalities')->findOrFail(Session::get('establishment_id'));
                                      
        return view('admin.modalities.index', ['modalities' => $modalities, 'modalities_admin' => $modalities_admin, 'establishment' => $establishment]);
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
        $establishmentId = Session::get('establishment_id');

        $modalities = $request->input('modalities');

        $establishment = Establishment::findOrFail($establishmentId);
        $establishment->modalities()->sync($modalities);

        return redirect()->route('admin.modalities.index')->with('success', 'Modalidades atribuidas com sucesso.');
    }
}
