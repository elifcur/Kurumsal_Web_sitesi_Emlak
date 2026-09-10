<?php

namespace App\View\Composers;

use App\Models\ContactMessage;
use App\Templates\Template;
use Illuminate\View\View;

class AdminLayoutComposer
{
    public function __construct(private Template $template) {}

    public function compose(View $view): void
    {
        $view->with('unreadMessages', ContactMessage::query()->unread()->count());
        $view->with('templateMenu', collect($this->template->adminMenu())
            ->filter(fn (array $item) => auth()->user()?->can($item['permission'] ?? 'manage-sector'))
            ->values()
            ->all());
    }
}
