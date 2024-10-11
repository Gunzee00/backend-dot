<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Auth;
use Exception;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Validasi input: jika validasi gagal, kembalikan response 400 dengan pesan kesalahan
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'confirm_password' => 'required|same:password'
            ]);

            if ($validator->fails()) {
                // Jika validasi gagal, tangani kesalahan dengan mengembalikan pesan error
                return response()->json([
                    'success' => false,
                    'message' => 'Ada kesalahan',
                    'data' => $validator->errors()
                ], 400); // Status code 400 untuk Bad Request
            }

            // Proses registrasi
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);

            // Generate token
            $success['token'] = $user->createToken('auth_token')->plainTextToken;
            $success['name'] = $user->name;

            return response()->json([
                'success' => true,
                'message' => 'Sukses register',
                'data' => $success
            ], 201); // Status code 201 untuk Created

        } catch (Exception $e) {
            // Tangani kesalahan tidak terduga, misalnya kesalahan pada database atau proses lain yang gagal
            return response()->json([
                'success' => false,
                'message' => 'Gagal register',
                'data' => $e->getMessage() // Kirim pesan error untuk debugging
            ], 500); // Status code 500 untuk Server Error
        }
    }

    public function login(Request $request)
    {
        try {
            // Validasi input: cek apakah input email dan password sesuai
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                // Jika validasi gagal, kembalikan response dengan status 400
                return response()->json([
                    'success' => false,
                    'message' => 'Ada kesalahan',
                    'data' => $validator->errors()
                ], 400); // Status code 400 untuk Bad Request
            }

            // Proses login: cek apakah email dan password sesuai
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $auth = Auth::user();
                $success['token'] = $auth->createToken('auth_token')->plainTextToken;
                $success['name'] = $auth->name;
                $success['email'] = $auth->email;

                // Kembalikan response sukses login
                return response()->json([
                    'success' => true,
                    'message' => 'Login sukses',
                    'data' => $success
                ], 200); // Status code 200 untuk OK
            } else {
                // Jika login gagal, kembalikan response dengan status 401
                return response()->json([
                    'success' => false,
                    'message' => 'Cek email dan password lagi',
                    'data' => null
                ], 401); // Status code 401 untuk Unauthorized
            }
        } catch (Exception $e) {
            // Tangani kesalahan tidak terduga selama proses login
            return response()->json([
                'success' => false,
                'message' => 'Gagal login',
                'data' => $e->getMessage() // Kirim pesan error untuk debugging
            ], 500); // Status code 500 untuk Server Error
        }
    }

    public function logout(Request $request)
    {
        try {
            // Cek apakah user sudah login atau memiliki token yang valid
            if (!$request->user()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401); // Status code 401 jika tidak ada token atau user tidak ditemukan
            }

            // Mendapatkan token pengguna yang sedang aktif dan menghapusnya
            $token = $request->user()->currentAccessToken();
            $token->delete();

            // Kembalikan response sukses logout
            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil'
            ], 200); // Status code 200 untuk OK
        } catch (Exception $e) {
            // Tangani kesalahan tidak terduga saat logout
            return response()->json([
                'success' => false,
                'message' => 'Gagal logout',
                'data' => $e->getMessage() // Kirim pesan error untuk debugging
            ], 500); // Status code 500 untuk Server Error
        }
    }
}
