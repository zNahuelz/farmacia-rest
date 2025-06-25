<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SolicitudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Solicitud::query();

        if ($request->has('deleted') && $request->deleted === 'true') {
            $query->onlyTrashed();
        } elseif ($request->has('deleted') && $request->deleted === 'all') {
            $query->withTrashed();
        }

        $solicitudes = $query->get();

        return response()->json($solicitudes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'creador' => ['required', 'max:30'],
            'contenido' => ['required', 'max:100'],
            'revisado_por' => ['nullable', 'max:30'],
            'respuesta' => ['nullable', 'max:255'],
            'estado' => ['required', 'boolean'],
            'tipo_solicitud' => ['required', 'in:REPOSICION,RETIRO'],
            'productos' => ['required', 'array'],
            'productos.*' => ['exists:productos,id'],
        ]);

        $solicitud = Solicitud::create([
            'creador' => $validated['creador'],
            'contenido' => $validated['contenido'],
            'revisado_por' => $validated['revisado_por'] ?? null,
            'respuesta' => $validated['respuesta'] ?? null,
            'estado' => $validated['estado'],
            'tipo_solicitud' => $validated['tipo_solicitud'],
        ]);

        $solicitud->productos()->sync($validated['productos']);

        return response()->json([
            'mensaje' => "Solicitud creada correctamente con ID: $solicitud->id",
            'solicitud' => $solicitud->load('productos'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $solicitud = Solicitud::with('productos')->find($id);

        if (!$solicitud) {
            return response()->json(['mensaje' => "Solicitud ID: $id no encontrada."], 404);
        }

        return response()->json($solicitud);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'id_solicitud' => ['required', 'exists:solicitudes,id'],
            'revisado_por' => ['nullable', 'string', 'max:30'],
            'respuesta' => ['nullable', 'string', 'max:255'],
            'estado' => ['nullable', 'boolean'],
        ]);

        $solicitud = Solicitud::where('id', $request->id_solicitud)->first();

        $solicitud->update([
            'revisado_por' => $request->revisado_por,
            'respuesta' => $request->respuesta,
            'estado' => $request->estado,
        ]);

        return response()->json([
            'mensaje' => "Solicitud ID: $request->id_solicitud actualizada correctamente.",
            'solicitud' => $solicitud,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $solicitud = Solicitud::find($id);

        if (!$solicitud) {
            return response()->json(['mensaje' => "Solicitud ID: $id no encontrada."], 404);
        }

        $solicitud->delete();

        return response()->json(['mensaje' => "Solicitud ID: $id eliminada correctamente."]);
    }
}
