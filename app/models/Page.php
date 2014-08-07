<?php namespace App\Models;
 
class Page extends \Eloquent {
 
    protected $table = 'pages';
    protected $fillable = ['title','slug', 'body', 'user_id'];
 
    public function author()
    {
        return $this->belongsTo('User', 'user_id');
    }
 
}