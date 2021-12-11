<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'email_id',
        'original_name',
        'name',
        'image_path'
    ];

    protected $appends = ['downloadable'];

    /**
     * Get the email.
     */
    public function email()
    {
        return $this->belongsTo(Email::class);
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getDownloadableAttribute()
    {
        return route('download',$this->id);
    }
}
