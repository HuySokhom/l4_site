<?php namespace App\Models;
 
class Page extends \Eloquent {
 
    protected $table = 'pages';
    protected $fillable = ['title'];
 
    public function author()
    {
        return $this->belongsTo('User');
    }
 
}