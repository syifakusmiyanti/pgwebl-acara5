<?php

namespace App\Http\Controllers;

use App\Models\polygonsModel;
use Illuminate\Http\Request;

class PolygonsController extends Controller
{
    protected $polygons;
    public function __construct()
    {
        $this->polygons = new polygonsModel;
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
                'geometry_polygon' => 'required',
                'name' => 'required|string|max:255',
                 'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ],
            [
                'geometry_polygon.required' => 'Geometry polygon harus diisi.',
                'name.required' => 'Name harus diisi.',
                'name.string' => 'Name harus berupa string.',
                'name.max' => 'Name tidak boleh melebihi 255 karakter.',
                'description.required' => 'Description harus diisi.',
                'description.string' => 'Description harus berupa string.',
                'image.image' => 'File harus berupa gambar.',
                'image.mimes' => 'Gambar harus berformat jpeg, png, jpg, gif, atau svg.',
                'image.max' => 'Ukuran gambar tidak boleh melebihi 2048 KB.',
            ]
        );

        //create directory if not exist
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        //get the upload image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polygon." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            $name_image = null;
        }


        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'geom' => $request->geometry_polygon,
            'image' => $name_image,
        ];
        // simpan data ke database
        if (!$this->polygons->create($data)) {
            return redirect()->route('peta')->with('error', 'Gagal menyimpan data polygon.');
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
