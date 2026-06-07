<?php

namespace App\Models;

use App\Services\CurrencyService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'client_id', 'invoice_number',
        'issue_date', 'due_date', 'status', 'total', 'currency', 'notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function getSymbolAttribute(): string
    {
        return app(CurrencyService::class)->symbol($this->currency ?? 'USD');
    }

    public static function currencies(): array
    {
        $service = app(CurrencyService::class);
        $result  = [];

        foreach ($service->all() as $code => $data) {
            $result[$code] = $data['symbol'] . ' ' . $data['name'];
        }

        return $result;
    }
}
