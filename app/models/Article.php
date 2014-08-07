<?php namespace App\Models;
 
class Article extends \Eloquent {
 
    protected $table = 'articles';
    protected $fillable = ['title','image'];
 
    public function author()
    {
        return $this->belongsTo('User', 'user_id');
    }
 
}