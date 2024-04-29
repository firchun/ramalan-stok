<?php

namespace App\Http\Controllers;

use App\Models\StokMitra;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [
            'title' => 'Akun Admin',
            'role' => 'Admin',
        ];
        return view('admin.users.index', $data);
    }
    public function mitra()
    {
        $data = [
            'title' => 'Akun Mitra',
            'role' => 'Mitra',
        ];
        return view('admin.users.mitra', $data);
    }
    public function getUsersDataTable()
    {
        $users = User::select(['id', 'name', 'email', 'created_at', 'updated_at', 'role', 'avatar'])->where('role', 'Admin')->orderByDesc('id');

        return Datatables::of($users)
            ->addColumn('avatar', function ($user) {
                return view('admin.users.components.avatar', compact('user'));
            })
            ->addColumn('action', function ($user) {
                return view('admin.users.components.actions', compact('user'));
            })
            ->addColumn('role', function ($user) {
                return '<span class="badge bg-label-primary">' . $user->role . '</span>';
            })

            ->rawColumns(['action', 'role', 'avatar'])
            ->make(true);
    }
    public function getUsersMitraDataTable()
    {
        $users = User::select(['id', 'name', 'email', 'created_at', 'updated_at', 'role', 'avatar'])->where('role', 'Mitra')->orderByDesc('id');

        return Datatables::of($users)
            ->addColumn('avatar', function ($user) {
                return view('admin.users.components.avatar', compact('user'));
            })
            ->addColumn('action', function ($user) {
                return view('admin.users.components.actions', compact('user'));
            })
            ->addColumn('action_stok', function ($user) {
                return view('admin.stok_mitra.actions', compact('user'));
            })
            ->addColumn('role', function ($user) {
                return '<span class="badge bg-label-primary">' . $user->role . '</span>';
            })
            ->addColumn('produk', function ($user) {
                $produkCount = StokMitra::select('id_produk')
                    ->selectRaw('COUNT(*) as total_produk')
                    ->where('id_user', $user->id)
                    ->groupBy('id_produk')
                    ->get();

                $totalProduk = $produkCount->sum('total_produk');

                return $totalProduk . ' Produk';
            })
            ->addColumn('stok', function ($user) {
                $stok = StokMitra::selectRaw('SUM(CASE WHEN jenis = "Masuk" THEN jumlah ELSE 0 END) - SUM(CASE WHEN jenis = "Keluar" THEN jumlah ELSE 0 END) - SUM(CASE WHEN jenis = "Penjualan" THEN jumlah ELSE 0 END) - SUM(CASE WHEN jenis = "Return" AND konfirmasi = "1" THEN jumlah ELSE 0 END) AS total_jumlah')
                    ->where('id_user', $user->id)
                    ->groupBy('id_user')
                    ->first();


                return $stok ? $stok->total_jumlah . ' Stok' : 0;
            })

            ->rawColumns(['action', 'role', 'avatar', 'action_stok', 'stok', 'produk'])
            ->make(true);
    }
    public function store(Request $request)
    {
        if ($request->filled('id')) {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
            ]);
        } else {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            ]);
        }


        if ($request->filled('id')) {
            $usersData = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'role' => $request->input('role'),
            ];
            $user = User::find($request->input('id'));
            if (!$user) {
                return response()->json(['message' => 'user not found'], 404);
            }

            $user->update($usersData);
            $message = 'user updated successfully';
        } else {
            $usersData = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'role' => $request->input('role'),
                'password' => Hash::make('password'),
            ];

            User::create($usersData);
            $message = 'user created successfully';
        }

        return response()->json(['message' => $message]);
    }
    public function edit($id)
    {
        $User = User::find($id);

        if (!$User) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($User);
    }
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
