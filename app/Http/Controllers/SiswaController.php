<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SiswaController extends Controller
{
    public function index()
    {
        $siswa = Siswa::selectRaw("
                    siswas.*,
                    users.name as nama_user
                ")
            ->join('users', 'users.id', 'siswas.created_by')
            ->get();

        return view('siswa.index', [
            'siswa' => $siswa,
        ]);
    }

    public function tambah()
    {
        return view('siswa.tambah');
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'nama' => 'required',
            'nis' => 'required|numeric|unique:siswas,nis', // Menambahkan aturan unique
            'tanggal_lahir' => 'required',
            'alamat' => 'required',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        try {
            DB::beginTransaction();
            Siswa::create([
                'nama' => $request->nama,
                'nis' => $request->nis,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return redirect()->route('siswa.index')->with('success', 'Berhasil menambah data!');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->withErrors([$th->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        // Ambil data siswa berdasarkan ID
        $siswa = Siswa::findOrFail($id);

        // Tampilkan halaman edit dengan membawa data siswa
        return view('siswa.edit', compact('siswa'));
    }

    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'required|exists:siswas,id',
            'nama' => 'required',
            'nis' => 'required|numeric|unique:siswas,nis,' . $request->id,
            'tanggal_lahir' => 'required',
            'alamat' => 'required',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        try {
            DB::beginTransaction();

            $siswa = Siswa::where('id', $request->id)->first();

            $siswa->update([
                'nama' => $request->nama,
                'nis' => $request->nis,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return redirect()->route('siswa.index')->with('success', 'Berhasil mengupdate data!');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->withErrors([$th->getMessage()])->withInput();
        }
    }

    public function delete(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'required|exists:siswas,id',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validation->errors()
            ], 422);
        }

        Siswa::where('id', $request->id)->delete();

        return redirect()->route('siswa.index')->with('success', 'Berhasil menghapus data!');
    }
}
