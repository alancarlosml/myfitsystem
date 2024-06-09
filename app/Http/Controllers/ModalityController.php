<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreModalityRequest;
use App\Http\Requests\UpdateModalityRequest;
use Illuminate\Http\Request;

class ModalityController extends Controller
{
    public function index()
    {
        $modalities = \App\Models\Modality::select('modalities.*')
                                      ->leftJoin('establishments', 'modalities.establishment_id', '=', 'establishments.id')
                                      ->orderBy('establishments.name')
                                      ->with('establishment')
                                      ->get();
                                      
        return view('admin.modalities.index', ['modalities' => $modalities]);
    }

    public function create()
    {
        $establishments = \App\Models\Establishment::all();

        return view('admin.modalities.add', ['establishments'=> $establishments]);
    }

    public function store(StoreModalityRequest $request)
    {
        $validatedData = $request->validated();

        if(isset($validatedData['active'])) {
            $validatedData['active'] = 1;
        } else {
            $validatedData['active'] = 0;
        }

        \App\Models\Modality::create($validatedData);

        return redirect()->route('admin.modalities.index')->with('success', 'Modalidade criada com sucesso!');
    }

    public function edit($modality)
    {
        $modality = \App\Models\Modality::find($modality);

        return view('admin.modalities.edit', ['modality' => $modality]);
    }

    public function update(UpdateModalityRequest $request, $modalityId)
    {
        $modality = \App\Models\Modality::findOrFail($modalityId);

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
        $modality = \App\Models\Modality::findOrFail($modalityId);
        
        return view('admin.modalities.view', ['modality' => $modality]);
    }

    public function destroy($modalityId)
    {
        $modality = \App\Models\Modality::findOrFail($modalityId);
        $modality->delete();
    }

    public function restore($modalityId)
    {
        $modality = \App\Models\Modality::withTrashed()->findOrFail($modalityId);
        $modality->restore();

        return redirect()->route('admin.modalities.index')->with('success', 'Modalidade restaurada com sucesso.');
    }
}
