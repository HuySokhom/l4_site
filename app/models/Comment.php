<?php namespace App\Models;

class Comment extends \Eloquent {
    
    protected $table = 'comments';
	protected $fillable = ['author', 'text', 'article_id']; 

	public function article()
    {
        return $this->belongsTo('Article', 'article_id');
    }
}

	
	