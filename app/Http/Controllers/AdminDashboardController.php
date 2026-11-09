<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminDashboardController extends Controller
{
    /**
     * Protect every action at the controller level, in addition to the route group.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'permission:view dashboard']);
    }

    public function index(): View
    {
        $stats = [
            [
                'label' => 'Total Pengguna',
                'value' => User::count(),
                'icon' => 'bi bi-people-fill',
                'tone' => 'primary',
            ],
            [
                'label' => 'Total Role',
                'value' => Role::count(),
                'icon' => 'bi bi-shield-lock-fill',
                'tone' => 'success',
            ],
            [
                'label' => 'Total Permission',
                'value' => Permission::count(),
                'icon' => 'bi bi-key-fill',
                'tone' => 'warning',
            ],
        ];

        $roles = Role::withCount(['permissions', 'users'])->orderBy('name')->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'roles' => $roles,
        ]);
    }

    public function users(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $roleFilter = trim((string) $request->query('role', ''));

        $users = User::query()
            ->with('roles')
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $query) use ($search) {
                    $query->whereLike('name', "%{$search}%")
                        ->orWhereLike('email', "%{$search}%");
                });
            })
            ->when($roleFilter !== '', function (Builder $query) use ($roleFilter) {
                $query->whereHas('roles', function (Builder $query) use ($roleFilter) {
                    $query->where('name', $roleFilter);
                });
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users', [
            'users' => $users,
            'search' => $search,
            'roleFilter' => $roleFilter,
            'roles' => Role::orderBy('name')->pluck('name'),
        ]);
    }
}
