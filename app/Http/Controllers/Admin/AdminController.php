<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Quiz;
use App\Models\QuizResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers     = User::where('role', 'creator')->count();
        $totalQuizzes   = Quiz::count();
        $totalResponses = QuizResponse::count();
        $activeQuizzes  = Quiz::where('is_active', true)->count();
        $recentUsers    = User::where('role', 'creator')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalQuizzes', 'totalResponses', 'activeQuizzes', 'recentUsers'
        ));
    }

    // Users management
    public function users(Request $request)
    {
        $users = User::where('role', 'creator')
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%"))
            ->withCount('quizzes')
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function toggleUser(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', $user->is_active ? 'Pengguna diaktifkan.' : 'Pengguna dinonaktifkan.');
    }

    public function destroyUser(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Tidak bisa menghapus admin.');
        }
        $user->delete();
        return back()->with('success', 'Pengguna dihapus.');
    }

    // Admin accounts
    public function admins()
    {
        $admins = User::where('role', 'admin')->get();
        return view('admin.admins.index', compact('admins'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
        ]);

        return back()->with('success', 'Admin berhasil ditambahkan.');
    }

    public function destroyAdmin(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus diri sendiri.');
        }
        $user->delete();
        return back()->with('success', 'Admin dihapus.');
    }

    // Export users
    public function exportUsers()
    {
        $users = User::where('role', 'creator')->withCount('quizzes')->get();
        $filename = 'users-kuisyuk-' . now()->format('Ymd') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($users) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No', 'Nama', 'Email', 'Jumlah Kuis', 'Status', 'Terdaftar']);
            foreach ($users as $i => $user) {
                fputcsv($handle, [
                    $i + 1,
                    $user->name,
                    $user->email,
                    $user->quizzes_count,
                    $user->is_active ? 'Aktif' : 'Nonaktif',
                    $user->created_at->format('d/m/Y'),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
