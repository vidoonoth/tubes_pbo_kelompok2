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
        //
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
