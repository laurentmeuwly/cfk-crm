<?php

namespace App\Models;

use App\Events\ContactformCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contactform extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'prefered_language',
        'newsletter',
        'agreement',
        'message',
        'title_id',
        'source_id'
    ];


    protected $dates = [
        'created_at',
        'updated_at',

    ];

    protected $dispatchesEvents = [
        'created' => ContactformCreated::class,
    ];

    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/contactforms/'.$this->getKey());
    }


    /* ************************ RELATIONS ************************* */

    public function title()
    {
        return $this->belongsTo(Title::class);
    }

    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
