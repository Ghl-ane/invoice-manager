<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'user_id', 'client_id', 'invoice_number',
        'issue_date', 'due_date', 'status', 'total', 'notes'
    ];

    // An invoice belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // An invoice belongs to a client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // An invoice has many items
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}