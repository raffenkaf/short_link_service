<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property string link_code
 * @property string url
 * @property int redirect_limit
 * @property Carbon $expires_at
 * @property bool active
 */
class ShortLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'redirect_limit',
        'expires_at'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->link_code = Str::random(8);
    }

    protected function expiresAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value),
            set: fn (string $value) => Carbon::now()->addSeconds($value)->toDateTimeString()
        );
    }

    public function formShortLinkUrl(): string
    {
        return url('/redirect/' . $this->link_code);
    }
}
