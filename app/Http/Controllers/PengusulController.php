<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengusulController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Mendapatkan koneksi PDO
            $pdo = DB::connection()->getPdo();
    
            // Menjalankan query untuk mendapatkan semua data dari tabel pengusul
            $sql = "SELECT * FROM pengusuls";
            $stmt = $pdo->query($sql);
    
            // Mengambil hasil dalam bentuk array
            $pengusul = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
            // Mengembalikan data dalam format JSON
            return response()->json($pengusul, 200);
        } catch (\PDOException $e) {
            // Menangani error PDO
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
        try {
            // Validasi data
            $validatedData = $request->validate([
                'namaLengkap' => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'nik' => 'required|integer',
                'nomorTelepon' => 'required|integer',
                'jenisKelamin' => 'required|string|max:255',
                'email' => 'required|string|max:255',
                'password' => 'required|string|min:8',
            ]);

            // Mendapatkan koneksi PDO
            $pdo = DB::connection()->getPdo();

            // Menyiapkan query untuk menyimpan data ke database
            $sql = "INSERT INTO pengusul (namaLengkap, username, nik, nomorTelepon, jenisKelamin, email, password) 
                    VALUES (:namaLengkap, :username, :nik, :nomorTelepon, :jenisKelamin, :email, :password)";

            // Membuat statement dan bind parameter
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':namaLengkap', $validatedData['namaLengkap']);
            $stmt->bindParam(':username', $validatedData['username']);
            $stmt->bindParam(':nik', $validatedData['nik']);
            $stmt->bindParam(':nomorTelepon', $validatedData['nomorTelepon']);
            $stmt->bindParam(':jenisKelamin', $validatedData['jenisKelamin']);
            $stmt->bindParam(':email', $validatedData['email']);
            $stmt->bindParam(':password', $validatedData['password']);

            // Eksekusi query
            $stmt->execute();

            // Mengambil ID terakhir yang dimasukkan
            $lastInsertId = $pdo->lastInsertId();

            // Menyusun respons
            return response()->json([
                'message' => 'Pengusul berhasil ditambahkan.',
                'data' => [
                    'id' => $lastInsertId,
                    'namaLengkap' => $validatedData['namaLengkap'],
                    'username' => $validatedData['username'],
                    'nik' => $validatedData['nik'],
                    'nomorTelepon' => $validatedData['nomorTelepon'],
                    'jenisKelamin' => $validatedData['jenisKelamin'],
                    'email' => $validatedData['email'],
                ],
            ], 201);
        } catch (\PDOException $e) {
            // Menangani error PDO
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
