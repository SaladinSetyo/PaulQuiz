<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $fillable = ['module_id', 'title', 'type', 'description', 'body', 'media_url', 'order', 'is_featured', 'quiz_id'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function userProgress()
    {
        return $this->hasMany(UserProgress::class);
    }

    public function getEmbedUrlAttribute()
    {
        if ($this->type !== 'video' || !$this->media_url) {
            return $this->media_url;
        }

        $url = $this->media_url;
        $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
        $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';

        if (preg_match($shortUrlRegex, $url, $matches)) {
            $youtube_id = $matches[1];
        } elseif (preg_match($longUrlRegex, $url, $matches)) {
            $youtube_id = $matches[3];
        } else {
            return $url;
        }

        return 'https://www.youtube.com/embed/' . $youtube_id;
    }
}
