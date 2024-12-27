<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Validator;

class BukuController extends Controller
{
    private $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new \PDO(
                'mysql:host=' . env('DB_HOST') . ';dbname=' . env('DB_DATABASE'),
                env('DB_USERNAME'),
                env('DB_PASSWORD')
            );
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            abort(500, 'Database connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM buku");
            $buku = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return response()->json(new PostResource(true, 'List Data Buku', $buku), 200);
        } catch (\PDOException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judulBuku' => 'required',
            'isbn' => 'nullable',
            'kategori' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahunTerbit' => 'required',
            'halaman' => 'required',
            'sinopsis' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO buku (judulBuku, isbn, kategori, penulis, penerbit, tahunTerbit, halaman, sinopsis, created_at, updated_at)
                VALUES (:judulBuku, :isbn, :kategori, :penulis, :penerbit, :tahunTerbit, :halaman, :sinopsis, NOW(), NOW())
            ");

            $stmt->execute([
                ':judulBuku' => $request->judulBuku,
                ':isbn' => $request->isbn,
                ':kategori' => $request->kategori,
                ':penulis' => $request->penulis,
                ':penerbit' => $request->penerbit,
                ':tahunTerbit' => $request->tahunTerbit,
                ':halaman' => $request->halaman,
                ':sinopsis' => $request->sinopsis,
            ]);

            $id = $this->pdo->lastInsertId();
            $buku = $this->fetchBukuById($id);

            return response()->json(new PostResource(true, 'Data Buku Berhasil Ditambahkan!', $buku), 201);
        } catch (\PDOException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $buku = $this->fetchBukuById($id);

            if (!$buku) {
                return response()->json(['message' => 'Data Buku tidak ditemukan!'], 404);
            }

            return response()->json(new PostResource(true, 'Detail Data Buku!', $buku), 200);
        } catch (\PDOException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'judulBuku' => 'required',
            'isbn' => 'nullable',
            'kategori' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahunTerbit' => 'required',
            'halaman' => 'required',
            'sinopsis' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $stmt = $this->pdo->prepare("
                UPDATE buku 
                SET judulBuku = :judulBuku,
                    isbn = :isbn,
                    kategori = :kategori,
                    penulis = :penulis,
                    penerbit = :penerbit,
                    tahunTerbit = :tahunTerbit,
                    halaman = :halaman,
                    sinopsis = :sinopsis,
                    updated_at = NOW()
                WHERE id = :id
            ");

            $stmt->execute([
                ':judulBuku' => $request->judulBuku,
                ':isbn' => $request->isbn,
                ':kategori' => $request->kategori,
                ':penulis' => $request->penulis,
                ':penerbit' => $request->penerbit,
                ':tahunTerbit' => $request->tahunTerbit,
                ':halaman' => $request->halaman,
                ':sinopsis' => $request->sinopsis,
                ':id' => $id,
            ]);

            $buku = $this->fetchBukuById($id);

            return response()->json(new PostResource(true, 'Data Buku Berhasil Diperbarui!', $buku), 200);
        } catch (\PDOException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM buku WHERE id = :id");
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();

            return response()->json(new PostResource(true, 'Data Buku Berhasil Dihapus!', null), 200);
        } catch (\PDOException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Helper function to fetch a Buku by ID.
     */
    private function fetchBukuById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM buku WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
