<?php 

namespace App\Interfaces;

use Illuminate\Http\Request;

interface CrudOperations {
    public function store(Request $request);
    public function update(Request $request, $id);
}