<?php namespace App\Models;

class Comment extends \Eloquent {
    
    protected $table = 'comments';
	protected $fillable = ['author', 'text', 'article_id']; 
}

	
	