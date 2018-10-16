<?php

namespace App\Http\Controllers;

use App\Models\Prospect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;



class ProspectController extends Controller
{
    public function showAllProspects()
    {
        //Log::debug('An informational message.');
        return response()->json(Prospect::all());
    }

    public function showOneProspect($id)
    {
        return response()->json(Prospect::find($id));
    }

    public function create(Request $request)
    {
        $prospect = Prospect::create($request->all());
        return response()->json($prospect, 201);
    }

    public function update($id, Request $request)
    {
        $prospect = Prospect::findOrFail($id);
        $prospect->update($request->all());
        return response()->json($prospect, 200);
    }

    public function delete($id)
    {
        Prospect::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}

