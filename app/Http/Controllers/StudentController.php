<?php
namespace App\Http\Controllers;

use App\Models\Student; 
use App\Models\Gender;
use Illuminate\Http\Request;

class StudentController extends Controller
{
  //menampilkan semua data
  public function index()
  {
      try {
          // Mengambil semua data mahasiswa 
          $students = Student::with('gender')->get();
  
          // Mengembalikan response dengan daftar mahasiswa
          return response()->json([
              'success' => true,
              'message' => 'List Mahasiswa',
              'data' => $students
          ], 200); // Status 200 untuk OK
  
          //error handling
      } catch (\Exception $e) {        
          return response()->json([
              'success' => false,
              'message' => 'Terjadi kesalahan saat mengambil daftar mahasiswa',
              'error' => $e->getMessage() // Menampilkan pesan error untuk debugging
          ], 500); // Status 500 untuk Internal Server Error
      }
  }
  

   //add data
   public function store(Request $request)
{
    try {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'tempat_tinggal' => 'required|string|max:255',
            'gender_id' => 'required|exists:genders,id' // gender yang dipilih berdasarkan ketersedian gender
        ]);

        // Menyimpan data mahasiswa baru ke database
        $student = Student::create([
            'nama' => $request->nama,
            'tempat_tinggal' => $request->tempat_tinggal,
            'gender_id' => $request->gender_id
        ]);

        // Mengembalikan response dengan data mahasiswa yang baru ditambahkan
        return response()->json([
            'success' => true,
            'message' => 'Mahasiswa berhasil ditambahkan',
            'data' => $student
        ], 201); // Status 201 untuk Created

    } catch (Exception $e) {
        // Tangani error jika ada masalah saat menyimpan data
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat menambahkan mahasiswa',
            'error' => $e->getMessage() // Menampilkan pesan error untuk debugging
        ], 500); // Status 500 untuk Internal Server Error
    }
}


     //menampilkan data by id
     public function show($id)
     {
         try {
             // Mencari mahasiswa berdasarkan ID dengan relasi gender
             $student = Student::with('gender')->find($id);
     
             // Jika mahasiswa tidak ditemukan, kembalikan response 404
             if (!$student) {
                 return response()->json([
                     'success' => false,
                     'message' => 'Mahasiswa tidak ditemukan',
                 ], 404); // Status 404 untuk Not Found
             }
     
             // Mengembalikan response dengan detail mahasiswa
             return response()->json([
                 'success' => true,
                 'message' => 'Detail Mahasiswa',
                 'data' => $student
             ], 200); // Status 200 untuk OK
     
             //error handling
         } catch (Exception $e) {
              return response()->json([
                 'success' => false,
                 'message' => 'Terjadi kesalahan saat mengambil detail mahasiswa',
                 'error' => $e->getMessage() // Menampilkan pesan error untuk debugging
             ], 500); // Status 500 untuk Internal Server Error
         }
     }
     

   //update data
   public function update(Request $request, $id)
{
    try {
        // Mencari mahasiswa berdasarkan ID
        $student = Student::find($id);

        // Jika mahasiswa tidak ditemukan, kembalikan response 404
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Mahasiswa tidak ditemukan',
            ], 404); // Status 404 untuk Not Found
        }

        // Validasi input untuk update, 'sometimes' berarti tidak wajib diisi
        $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'tempat_tinggal' => 'sometimes|required|string|max:255',
            'gender_id' => 'sometimes|required|exists:genders,id' // gender_id harus valid jika diberikan
        ]);

        // Mengupdate data mahasiswa
        $student->update([
            'nama' => $request->nama ?? $student->nama, // gunakan data lama jika tidak diisi
            'tempat_tinggal' => $request->tempat_tinggal ?? $student->tempat_tinggal,
            'gender_id' => $request->gender_id ?? $student->gender_id,
        ]);

        // Mengembalikan response dengan data yang sudah diupdate
        return response()->json([
            'success' => true,
            'message' => 'Mahasiswa berhasil diupdate',
            'data' => $student
        ], 200); // Status 200 untuk OK

      //error handling
    } catch (Exception $e) {
         return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat mengupdate mahasiswa',
            'error' => $e->getMessage() // Menampilkan pesan error untuk debugging
        ], 500); // Status 500 untuk Internal Server Error
    }
}


   //delete data
   public function destroy($id)
   {
       try {
           // Mencari mahasiswa berdasarkan ID
           $student = Student::find($id);
   
           // Jika mahasiswa tidak ditemukan, kembalikan response 404
           if (!$student) {
               return response()->json([
                   'success' => false,
                   'message' => 'Mahasiswa tidak ditemukan',
               ], 404); // Status 404 untuk Not Found
           }
   
           // Menghapus data mahasiswa
           $student->delete();
   
           // Mengembalikan response sukses setelah data dihapus
           return response()->json([
               'success' => true,
               'message' => 'Mahasiswa berhasil dihapus'
           ], 200); // Status 200 untuk OK
   
           //error handling
       } catch (\Exception $e) {
           return response()->json([
               'success' => false,
               'message' => 'Terjadi kesalahan saat menghapus mahasiswa',
               'error' => $e->getMessage() // Menampilkan pesan error untuk debugging
           ], 500); // Status 500 untuk Internal Server Error
       }
   }
   
  //search data
  public function search(Request $request)
  {
      try {
          // Membuat query mahasiswa dengan relasi gender
          $students = Student::query()->with('gender');
          
          // Filter pencarian berdasarkan parameter 'search'
          if ($request->has('search')) {
              $students->where('nama', 'like', '%' . $request->search . '%')
                       ->orWhere('tempat_tinggal', 'like', '%' . $request->search . '%');
          }
  
          // Sorting berdasarkan kolom 'nama' atau 'tempat_tinggal'
          if ($request->has('sort')) {
              $column = $request->input('sort.column');
              $direction = $request->input('sort.direction');
  
              // Validasi apakah kolom yang di-sort valid
              if (in_array($column, ['nama', 'tempat_tinggal'])) {
                  $students->orderBy($column, $direction);
              } else {
                  return response()->json([
                      'success' => false,
                      'message' => 'Kolom sorting tidak valid',
                  ], 400); // Status 400 untuk Bad Request
              }
          }
  
          // Mengambil hasil query
          $students = $students->get();
  
          // Mengembalikan response dengan hasil pencarian dan sorting
          return response()->json([
              'success' => true,
              'message' => 'List Mahasiswa',
              'data' => $students
          ], 200); // Status 200 untuk OK
  
          //error handling
      } catch (\Exception $e) {
           return response()->json([
              'success' => false,
              'message' => 'Terjadi kesalahan saat mengambil data mahasiswa',
              'error' => $e->getMessage() // Menampilkan pesan error untuk debugging
          ], 500); // Status 500 untuk Internal Server Error
      }
  }
  
}
