<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Validator;

class UsulanController extends Controller
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
            $stmt = $this->pdo->query("
                SELECT usulan.*, pengusul.namaLengkap AS namaPengusul 
                FROM usulan
                JOIN pengusul ON usulan.id_pengusul = pengusul.id
            ");
            $usulan = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return response()->json(new PostResource(true, 'List Data Usulan', $usulan), 200);
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
            'tanggalPengusulan' => 'required|date',
            'id_pengusul' => 'required|exists:pengusul,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO usulan (judulBuku, isbn, kategori, penulis, penerbit, tahunTerbit, tanggalPengusulan, id_pengusul, created_at, updated_at)
                VALUES (:judulBuku, :isbn, :kategori, :penulis, :penerbit, :tahunTerbit, :tanggalPengusulan, :id_pengusul, NOW(), NOW())
            ");

            $stmt->execute([
                ':judulBuku' => $request->judulBuku,
                ':isbn' => $request->isbn,
                ':kategori' => $request->kategori,
                ':penulis' => $request->penulis,
                ':penerbit' => $request->penerbit,
                ':tahunTerbit' => $request->tahunTerbit,
                ':tanggalPengusulan' => $request->tanggalPengusulan,
                ':id_pengusul' => $request->id_pengusul,
            ]);

            $id = $this->pdo->lastInsertId();
            $usulan = $this->fetchUsulanById($id);

            return response()->json(new PostResource(true, 'Data Usulan Berhasil Ditambahkan!', $usulan), 201);
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
            $usulan = $this->fetchUsulanById($id);

            if (!$usulan) {
                return response()->json(['message' => 'Data Usulan tidak ditemukan!'], 404);
            }

            return response()->json(new PostResource(true, 'Detail Data Usulan!', $usulan), 200);
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
            'tanggalPengusulan' => 'required|date',
            'id_pengusul' => 'required|exists:pengusul,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $stmt = $this->pdo->prepare("
                UPDATE usulan 
                SET judulBuku = :judulBuku,
                    isbn = :isbn,
                    kategori = :kategori,
                    penulis = :penulis,
                    penerbit = :penerbit,
                    tahunTerbit = :tahunTerbit,
                    tanggalPengusulan = :tanggalPengusulan,
                    id_pengusul = :id_pengusul,
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
                ':tanggalPengusulan' => $request->tanggalPengusulan,
                ':id_pengusul' => $request->id_pengusul,
                ':id' => $id,
            ]);

            $usulan = $this->fetchUsulanById($id);

            return response()->json(new PostResource(true, 'Data Usulan Berhasil Diperbarui!', $usulan), 200);
        } catch (\PDOException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Helper function to fetch a Usulan by ID.
     */
    private function fetchUsulanById($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT usulan.*, pengusul.namaLengkap AS namaPengusul
            FROM usulan
            JOIN pengusul ON usulan.id_pengusul = pengusul.id
            WHERE usulan.id = :id
        ");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
