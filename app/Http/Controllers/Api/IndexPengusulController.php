<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\PengusulController;
use App\Http\Resources\PostResource;

class IndexPengusulController extends PengusulController
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
}
