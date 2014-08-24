<?php namespace App\Controllers\Admin;

use App\Models\Comment;
use App\Models\Article;
use App\Models\User;
use Input, Notification, Redirect, Sentry, Str;

class CommentController extends \BaseController 
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return Comment::get();
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{	
		$new_comment = [
			'author' => htmlentities(Input::get('author')),
			'text' => htmlentities(Input::get('text')),
			'article_id' => htmlentities(Input::get('article_id'))
		];

		if(in_array('', $new_comment))
		{
			throw new \PDOException('Invalid Data Format for Comment', 400);
		}

		return Comment::create($new_comment);;
	}

	// Update comment.
	public function update($id)
    {
        if(!$comment = Comment::find($id))
        {
        	\App::abort(404, "Fail to update. Comment doesn't exist.");
        }

        $comment->text = htmlentities(Input::get('text'));
        $comment->save();

        return $comment;
    }


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if(!$comment = Comment::find($id))
		{			
			\App::abort(404, "Delete was failed. Comment doesn't exist.");	
		}

		Comment::destroy($id);
		return $comment;
	}


	public function show($slug)
    {    
    	if (!$article = Article::where('slug', $slug)->first()) 
		{
			\App::abort(404, 'Comments not found');			
		}

    	$comment = $article->comments();
        return $comment->get();	    
    }
}
