<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->when($request->filled('q'), function ($q) use ($request): void {
                $term = $request->string('q')->limit(100);
                $q->where(fn ($w) => $w->where('name', 'like', "%{$term}%")->orWhere('email', 'like', "%{$term}%"));
            })
            ->when($request->filled('role'), fn ($q) => $q->where('role', $request->input('role')))
            ->orderBy('name')
            ->paginate(config('cms.pagination.admin'))
            ->withQueryString();

        return view('admin.users.index', ['users' => $users, 'roles' => UserRole::options()]);
    }

    public function create(): View
    {
        return view('admin.users.form', ['user' => new User(['is_active' => true, 'role' => UserRole::Editor]), 'roles' => $this->roleOptions()]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        User::query()->create($request->payload());

        return redirect()->route('admin.users.index')->with('success', 'Kullanıcı oluşturuldu.');
    }

    public function edit(User $user): View
    {
        $this->ensureCanManage($user);

        return view('admin.users.form', ['user' => $user, 'roles' => $this->roleOptions()]);
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $this->ensureCanManage($user);

        $data = $request->payload();

        if ($user->is($request->user())) {
            // Never let users lock themselves out or demote themselves.
            unset($data['role'], $data['is_active']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Kullanıcı güncellendi.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $this->ensureCanManage($user);

        if ($user->is($request->user())) {
            return back()->with('error', 'Kendi hesabınızı silemezsiniz.');
        }

        if ($user->isSuperAdmin() && User::query()->where('role', UserRole::SuperAdmin)->count() <= 1) {
            return back()->with('error', 'Son süper yönetici silinemez.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Kullanıcı silindi.');
    }

    private function ensureCanManage(User $target): void
    {
        abort_if($target->isSuperAdmin() && ! auth()->user()->isSuperAdmin(), 403, 'Süper yönetici hesaplarını yalnızca süper yöneticiler düzenleyebilir.');
    }

    /**
     * @return array<string, string>
     */
    private function roleOptions(): array
    {
        $options = UserRole::options();

        if (! auth()->user()->isSuperAdmin()) {
            unset($options[UserRole::SuperAdmin->value]);
        }

        return $options;
    }
}
