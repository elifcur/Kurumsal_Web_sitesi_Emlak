<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['type', 'name', 'email', 'phone', 'subject', 'message', 'meta', 'ip', 'user_agent', 'is_read', 'read_at'])]
class ContactMessage extends Model
{
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'is_read' => 'boolean',
            'read_at' => 'datetime',
        ];
    }

    #[Scope]
    protected function unread(Builder $query): void
    {
        $query->where('is_read', false);
    }

    public function markAsRead(): void
    {
        if (! $this->is_read) {
            $this->forceFill(['is_read' => true, 'read_at' => now()])->save();
        }
    }

    public function markAsUnread(): void
    {
        $this->forceFill(['is_read' => false, 'read_at' => null])->save();
    }

    public function typeLabel(): string
    {
        return config('cms.message_types.'.$this->type) ?? (template()->messageTypes()[$this->type] ?? ucfirst($this->type));
    }
}
