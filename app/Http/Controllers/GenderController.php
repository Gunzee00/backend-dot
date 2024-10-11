<?php

namespace App\Http\Controllers;

use App\Models\Gender;
use Illuminate\Http\Request;
use Exception;

class GenderController extends Controller
{

    //  Menampilkan daftar semua gender.
    public function index(Request $request)
    {
        try {
            // Mengambil semua data gender dari database
            $genders = Gender::all();
    
            // Mengembalikan response  
            return response()->json([
                'success' => true,
                'message' => 'List Gender',
                'data' => $genders
            ], 200);  //code 200 untuk berhasil

                // error handling
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data',
                'data' => $e->getMessage()
            ], 500); //code 500 jika server error
        }
    }

    //  add data    
    public function store(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'jenis_kelamin' => 'required|string|unique:genders,jenis_kelamin|max:255',
            ]);

            // Menyimpan gender ke database
            $gender = Gender::create([
                'jenis_kelamin' => $request->jenis_kelamin
            ]);

            // Mengembalikan response  
            return response()->json([
                'success' => true,
                'message' => 'Gender berhasil ditambahkan',
                'data' => $gender
            ], 201); // Status 201 untuk Created

            //error handling
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan gender',
                'data' => $e->getMessage()
            ], 500);
        }
    }

//   Menampilkan detail gender berdasarkan ID.
    public function show($id)
    {
        try {
            // Mencari gender berdasarkan ID
            $gender = Gender::find($id);

            // Jika gender tidak ditemukan, kembalikan response 404
            if (!$gender) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gender tidak ditemukan',
                ], 404); // Status Not Found
            }

            // Mengembalikan response dengan detail gender
            return response()->json([
                'success' => true,
                'message' => 'Detail Gender',
                'data' => $gender
            ], 200); // Status berhasil

        } catch (Exception $e) {
            // error handling
            return response()->json([
                'success' => false,
                'message' => 'Gagal menampilkan gender',
                'data' => $e->getMessage()
            ], 500);//code 500 jika server error
        }
    }

    //   Mengupdate data gender berdasarkan ID.
     
    public function update(Request $request, $id)
    {
        try {
            // Mencari gender berdasarkan ID
            $gender = Gender::find($id);

            if (!$gender) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gender tidak ditemukan',
                ], 404);//code data tidak ditemukan
            }

            // Validasi input untuk update
            $request->validate([
                'jenis_kelamin' => 'required|string|unique:genders,jenis_kelamin,' . $gender->id . '|max:255',
            ]);

            // Mengupdate data gender
            $gender->update([
                'jenis_kelamin' => $request->jenis_kelamin
            ]);

            // Mengembalikan response sukses dengan data yang diupdate
            return response()->json([
                'success' => true,
                'message' => 'Gender berhasil diupdate',
                'data' => $gender
            ], 200); // Status 200 berhasil

        } catch (Exception $e) {
            // Error handling
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate gender',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    //hapus data by id
    public function destroy($id)
    {
        try {
            // Mencari gender berdasarkan ID
            $gender = Gender::find($id);

            // Jika gender tidak ditemukan, kembalikan response 404
            if (!$gender) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gender tidak ditemukan',
                ], 404); //code data tidak ditemukan
            }

            // Menghapus data gender dari database
            $gender->delete();

            // Mengembalikan response sukses setelah data dihapus
            return response()->json([
                'success' => true,
                'message' => 'Gender berhasil dihapus',
            ], 200);

            //error handling
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus gender',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    //fitur pencarian
    public function search(Request $request)
    {
        try {
            // Inisialisasi query untuk gender
            $genders = Gender::query();
    
            // Filter berdasarkan parameter 'search'
            if ($request->has('search')) {
                $genders->where('jenis_kelamin', 'like', '%' . $request->search . '%');
            }
    
            // Sorting berdasarkan kolom 'jenis_kelamin'
            if ($request->has('sort')) {
                $column = $request->input('sort.column');
                $direction = $request->input('sort.direction');
    
                // Validasi apakah kolom yang di-sort valid
                if (in_array($column, ['jenis_kelamin'])) {
                    $genders->orderBy($column, $direction);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kolom untuk sorting tidak valid',
                    ], 400); // Status 400 untuk bad request
                }
            }
    
            // Mendapatkan hasil query
            $genders = $genders->get();

            return response()->json([
                'success' => true,
                'message' => 'List Gender',
                'data' => $genders
            ], 200); // Status 200 artinya berhasil
            
            //error handling
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data',
                'error' => $e->getMessage()//pesan error debug
            ], 500); // Status 500 untuk internal server error
        }
    }
    
}
