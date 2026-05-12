<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeDirector();

        $query = User::where('role', 'employee');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $employees = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Daftar Karyawan',
            'data' => $employees->items(),
            'pagination' => [
                'total' => $employees->total(),
                'per_page' => $employees->perPage(),
                'current_page' => $employees->currentPage(),
                'last_page' => $employees->lastPage(),
            ]
        ], 200);
    }

    public function store(Request $request)
    {
        try {
            $this->authorizeDirector();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Akses hanya untuk Direktur: ' . $e->getMessage(),
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi karyawan gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $employee = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'employee'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Karyawan berhasil ditambahkan',
            'data' => $employee
        ], 201);
    }

    public function show($id)
    {
        $this->authorizeDirector();

        $employee = User::where('role', 'employee')->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Detail Karyawan',
            'data' => $employee
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeDirector();

        $employee = User::where('role', 'employee')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $employee->id,
            'password' => 'nullable|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi update karyawan gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $employee->fill($request->only(['name', 'email']));

        if ($request->filled('password')) {
            $employee->password = Hash::make($request->password);
        }

        $employee->save();

        return response()->json([
            'success' => true,
            'message' => 'Data karyawan berhasil diperbarui',
            'data' => $employee
        ], 200);
    }

    public function destroy($id)
    {
        $this->authorizeDirector();

        $employee = User::where('role', 'employee')->findOrFail($id);
        $employee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Karyawan berhasil dihapus'
        ], 200);
    }

    private function authorizeDirector()
    {
        $user = auth()->user();
        
        if (!$user) {
            throw new \Exception('User tidak terautentikasi');
        }
        
        if ($user->role !== 'direktur') {
            throw new \Exception('User role: ' . $user->role . ', hanya direktur yang diizinkan');
        }
    }
}
