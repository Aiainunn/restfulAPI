<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Http\Resources\ProdukResource;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    // =========================
    // BAGIAN API
    // =========================

    // Menampilkan semua produk dalam format JSON
    public function index()
    {
        $produk = Produk::all();

        return ProdukResource::collection($produk);
    }

    // Menyimpan produk melalui API
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga'     => 'required|integer|min:0',
            'stok'      => 'required|integer|min:0',
        ]);

        $produk = Produk::create($validated);

        return (new ProdukResource($produk))
            ->additional([
                'message' => 'Produk berhasil ditambahkan',
            ])
            ->response()
            ->setStatusCode(201);
    }

    // Menampilkan satu produk melalui API
    public function show(Produk $produk)
    {
        return new ProdukResource($produk);
    }

    // Memperbarui produk melalui API
    public function update(Request $request, Produk $produk)
    {
        $validated = $request->validate([
            'nama'      => 'sometimes|required|string|max:255',
            'deskripsi' => 'sometimes|nullable|string',
            'harga'     => 'sometimes|required|integer|min:0',
            'stok'      => 'sometimes|required|integer|min:0',
        ]);

        $produk->update($validated);

        return response()->json([
            'message' => 'Produk berhasil diperbarui',
            'data'    => $produk->fresh(),
        ], 200);
    }

    // Menghapus produk melalui API
    public function destroy(Produk $produk)
    {
        $produk->delete();

        return response()->json([
            'message' => 'Produk berhasil dihapus',
        ], 200);
    }


    // =========================
    // BAGIAN WEB / VIEW BLADE
    // =========================

    // Menampilkan daftar produk
    public function webIndex()
    {
        $produk = Produk::latest()->get();

        return view('produk.index', compact('produk'));
    }

    // Menampilkan form tambah produk
    public function create()
    {
        return view('produk.create');
    }

    // Menyimpan produk dari form web
    public function webStore(Request $request)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga'     => 'required|integer|min:0',
            'stok'      => 'required|integer|min:0',
        ]);

        Produk::create($validated);

        return redirect()
            ->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    // Menampilkan detail produk
    public function webShow(Produk $produk)
    {
        return view('produk.show', compact('produk'));
    }

    // Menampilkan form edit produk
    public function edit(Produk $produk)
    {
        return view('produk.edit', compact('produk'));
    }

    // Memperbarui produk dari form web
    public function webUpdate(Request $request, Produk $produk)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga'     => 'required|integer|min:0',
            'stok'      => 'required|integer|min:0',
        ]);

        $produk->update($validated);

        return redirect()
            ->route('produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    // Menghapus produk dari halaman web
    public function webDestroy(Produk $produk)
    {
        $produk->delete();

        return redirect()
            ->route('produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
