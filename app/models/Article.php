<?php namespace App\Models;
 
class Article extends \Eloquent {
 
    protected $table = 'articles';
    protected $fillable = ['title','slug', 'body', 'user_id', 'created_at', 'updated_at'];
 
    public function author()
    {
        return $this->belongsTo('User', 'user_id');
    }

    public function comments()
    {
    	return $this->hasMany('Comment', 'article_id');
    }
 
}