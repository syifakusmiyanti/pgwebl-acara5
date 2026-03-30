<?php

namespace App\Http\Controllers;

use App\Models\polylinesModel;
use Illuminate\Http\Request;

class PolylinesController extends Controller
{
    public function __construct()
    {
        $this->polylines = new polylinesModel;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validasi input
        $request->validate(
            [
                'geometry_polyline' => 'required',
                'name' => 'required|string|max:255',
            ],
            [
                'geometry_polyline.required' => 'Geometry polylines harus diisi.',
                'name.required' => 'Name harus diisi.',
                'name.string' => 'Name harus berupa string.',
                'name.max' => 'Name tidak boleh melebihi 255 karakter.',
            ]
        );

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'geom' => $request->geometry_polyline,
        ];
        // simpan data ke database
        if (!$this->polylines->create($data)) {
            return redirect()->route('peta')->with('error', 'Gagal menyimpan data polyline.');
        }

        // kembali ke halaman peta
        return redirect()->route('peta')->with('success', 'Data berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
