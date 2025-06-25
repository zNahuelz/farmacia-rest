<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Producto::query();

        if ($request->has('deleted') && $request->deleted == 'true') {
            $query->onlyTrashed();
        } elseif ($request->has('deleted') && $request->deleted == 'all') {
            $query->withTrashed();
        }

        $products = $query->get();

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => ['required','min:5','max:100'],
            'stock' => ['required','integer','min:0'],
            'precio' => ['required','numeric'],
        ]);

        $product = Producto::create([
            'nombre' => trim(strtoupper($request->nombre)),
            'stock' => $request->stock,
            'precio' => $request->precio,
        ]);
        return response()->json([
            'mensaje' => "Producto creado correctamente, asignado ID: $product->id",
            'producto' => $product,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Producto::find($id);
        if (!$product) {
            return response()->json([
                'mensaje' => "Producto de ID: $id no encontrado."
            ], 404);
        }
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'id_producto' => ['required', 'exists:productos,id'],
            'nombre' => ['required','min:5','max:100'],
            'stock' => ['required','integer','min:0'],
            'precio' => ['required','numeric'],
        ]);
        $product = Producto::where('id', $request->id_producto)->first();

        $product->update([
            'nombre' => trim(strtoupper($request->nombre)),
            'stock' => $request->stock,
            'precio' => $request->precio,
        ]);

        return response()->json([
            'mensaje' => "Producto de ID: $request->id_producto actualizado correctamente.",
            'producto' => $product,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Producto::find($id);
        if (!$product) {
            return response()->json([
                'mensaje' => "Producto de ID: $id no encontrado."
            ], 404);
        }
        $product->delete();
        return response()->json(['mensaje' => "Producto de ID: $id eliminado correctamente."]);
    }
}
