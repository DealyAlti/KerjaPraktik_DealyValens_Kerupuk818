<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Toko;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function data()
    {
        $users = User::with('toko')->orderBy('id', 'desc')->get();

        return datatables()
            ->of($users)
            ->addIndexColumn()
            ->addColumn('toko', function($user) {
                return $user->toko ? $user->toko->nama_toko : '-';
            })
            ->addColumn('level', function($user) {
                switch ($user->level) {
                    case 0: return 'Owner';
                    case 1: return 'Kepala Gudang';
                    case 2: return 'Kasir';
                    default: return 'Unknown';
                }
            })
            ->addColumn('aksi', function ($user) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('user.show', $user->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('user.destroy', $user->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $toko = Toko::all();
        return view('user.create', compact('toko'));
    }


    public function store(Request $request)
    {
        // Validasi
        $rules = [
            'email' => 'unique:users,email',
            'password' => 'min:6|confirmed',
        ];
        if ($request->level == 2) {
            // Jika level Kasir, id_toko wajib
            $rules['id_toko'] = 'required|exists:toko,id_toko';
        }
        $validated = $request->validate($rules);
        // Buat user baru
        $user = new User;
        $user->name = $request->name;
        $user->email = $validated['email'];
        $user->password = bcrypt($validated['password']);
        $user->level = $request->level;
        if ($user->level == 2) {
            $user->id_toko = $validated['id_toko'];
        } else {
            $user->id_toko = null;
        }
        $user->save();

        return redirect()->route('user.index')->with('success', 'Pengguna berhasil ditambahkan');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
// UserController.php
    public function show($id)
    {
        $user = User::with('toko')->findOrFail($id);
        return response()->json($user);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required','email', Rule::unique('users')->ignore($user->id)],
            'level' => ['required', Rule::in([0,1,2])],
        ];

        // Password hanya validasi jika diisi
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:6|confirmed';
        }

        // Jika level Kasir wajib pilih toko
        if ($request->level == 2) {
            $rules['id_toko'] = 'required|exists:toko,id_toko';
        } else {
            // Jika bukan kasir, kosongkan toko
            $request->merge(['id_toko' => null]);
        }

        $validated = $request->validate($rules);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->level = $validated['level'];

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->id_toko = $validated['id_toko'] ?? null;

        $user->save();

        return response()->json('Data berhasil diupdate', 200);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json('Data berhasil dihapus', 204);
    }

    public function profil()
    {
        $user = auth()->user()->load('toko'); // supaya toko bisa ikut ditampilkan kalau kasir
        return view('user.profil', compact('user'));
    }


}
