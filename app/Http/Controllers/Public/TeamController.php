<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Contracts\View\View;

class TeamController extends Controller
{
    public function index(): View
    {
        return view('template::team.index', [
            'members' => TeamMember::query()->active()->ordered()->get(),
        ]);
    }

    public function show(string $slug): View
    {
        $member = TeamMember::query()->active()->where('slug', $slug)->firstOrFail();

        return view('template::team.show', [
            'member' => $member,
            'others' => TeamMember::query()->active()->ordered()->whereKeyNot($member->id)->limit(4)->get(),
        ]);
    }
}
