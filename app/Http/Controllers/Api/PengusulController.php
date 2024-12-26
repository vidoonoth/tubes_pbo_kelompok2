<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Validator;

class PengusulController extends Controller
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
            $stmt = $this->pdo->query("SELECT * FROM pengusul");
            $pengusul = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return response()->json(new PostResource(true, 'List Data Pengusul', $pengusul), 200);
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
            'namaLengkap' => 'required',
            'username' => 'required',
            'nik' => 'required',
            'nomorTelepon' => 'required',
            'jenisKelamin' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO pengusul (namaLengkap, username, nik, nomorTelepon, jenisKelamin, email, password)
                VALUES (:namaLengkap, :username, :nik, :nomorTelepon, :jenisKelamin, :email, :password)
            ");

            $stmt->execute([
                ':namaLengkap' => $request->namaLengkap,
                ':username' => $request->username,
                ':nik' => $request->nik,
                ':nomorTelepon' => $request->nomorTelepon,
                ':jenisKelamin' => $request->jenisKelamin,
                ':email' => $request->email,
                ':password' => bcrypt($request->password),
            ]);

            $id = $this->pdo->lastInsertId();
            $pengusul = $this->fetchPengusulById($id);

            return response()->json(new PostResource(true, 'Data Pengusul Berhasil Ditambahkan!', $pengusul), 201);
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
            $pengusul = $this->fetchPengusulById($id);

            if (!$pengusul) {
                return response()->json(['message' => 'Data Pengusul tidak ditemukan!'], 404);
            }

            return response()->json(new PostResource(true, 'Detail Data Pengusul!', $pengusul), 200);
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
            'namaLengkap' => 'required',
            'username' => 'required',
            'nik' => 'required',
            'nomorTelepon' => 'required',
            'jenisKelamin' => 'required',
            'email' => 'required|email',
            'password' => 'nullable|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $stmt = $this->pdo->prepare("
                UPDATE pengusul 
                SET namaLengkap = :namaLengkap,
                    username = :username,
                    nik = :nik,
                    nomorTelepon = :nomorTelepon,
                    jenisKelamin = :jenisKelamin,
                    email = :email,
                    password = IF(:password IS NOT NULL, :password, password)
                WHERE id = :id
            ");

            $stmt->execute([
                ':namaLengkap' => $request->namaLengkap,
                ':username' => $request->username,
                ':nik' => $request->nik,
                ':nomorTelepon' => $request->nomorTelepon,
                ':jenisKelamin' => $request->jenisKelamin,
                ':email' => $request->email,
                ':password' => $request->password ? bcrypt($request->password) : null,
                ':id' => $id,
            ]);

            $pengusul = $this->fetchPengusulById($id);

            return response()->json(new PostResource(true, 'Data Pengusul Berhasil Diperbarui!', $pengusul), 200);
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
            $stmt = $this->pdo->prepare("DELETE FROM pengusul WHERE id = :id");
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();

            return response()->json(new PostResource(true, 'Data Pengusul Berhasil Dihapus!', null), 200);
        } catch (\PDOException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Helper function to fetch a Pengusul by ID.
     */
    private function fetchPengusulById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM pengusul WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
