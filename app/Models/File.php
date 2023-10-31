<?php

namespace App\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use Sluggable;
    use HasFactory;
    protected $fillable = [
        'title', 
        'href',
        'access_id', 
        'user_id', 
        'pin', 
        'content',
        'original_name',
        'type',
        'file_extension',
        'file_size',
    ];

    /**
    * Return the sluggable configuration array for this model.
    *
    * @return array
    */
    public function sluggable(): array
    {
        return [
            'slug' => [
            'source' => 'title'
            ]
        ];
    }

    public function access (){
        return $this->belongsTo(Access::class);
    }

    public function pins (){
        return $this->hasMany(Pin::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function users(){
        return $this->belongsToMany(User::class)->withTimestamps();
    }
    public function getFileDate (){
        return Carbon::parse($this->created_at)->diffForHumans();
    }
    
}
