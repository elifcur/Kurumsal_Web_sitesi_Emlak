<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeamMemberRequest;
use App\Models\TeamMember;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    public function index(Request $request): View
    {
        $members = TeamMember::query()
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->string('q')->limit(100).'%'))
            ->when($request->input('status') === 'active', fn ($q) => $q->where('is_active', true))
            ->when($request->input('status') === 'inactive', fn ($q) => $q->where('is_active', false))
            ->ordered()
            ->paginate(config('cms.pagination.admin'))
            ->withQueryString();

        return view('admin.team.index', ['members' => $members]);
    }

    public function create(): View
    {
        return view('admin.team.form', ['member' => new TeamMember(['is_active' => true, 'sort_order' => (int) TeamMember::query()->max('sort_order') + 1])]);
    }

    public function store(TeamMemberRequest $request): RedirectResponse
    {
        $member = TeamMember::query()->create($request->payload());

        return redirect()->route('admin.team.edit', $member)->with('success', 'Ekip üyesi oluşturuldu.');
    }

    public function edit(TeamMember $member): View
    {
        return view('admin.team.form', ['member' => $member]);
    }

    public function update(TeamMemberRequest $request, TeamMember $member): RedirectResponse
    {
        $member->update($request->payload());

        return redirect()->route('admin.team.edit', $member)->with('success', 'Ekip üyesi güncellendi.');
    }

    public function destroy(TeamMember $member): RedirectResponse
    {
        $member->delete();

        return redirect()->route('admin.team.index')->with('success', 'Ekip üyesi silindi.');
    }

    public function reorder(Request $request): JsonResponse
    {
        $data = $request->validate(['order' => ['required', 'array', 'max:500'], 'order.*' => ['integer']]);

        foreach (array_values($data['order']) as $position => $id) {
            TeamMember::query()->whereKey($id)->update(['sort_order' => $position]);
        }

        return response()->json(['ok' => true]);
    }
}
