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
			'author' => Input::get('author'),
			'text' => Input::get('text'),
			'article_id' => Input::get('article_id')
		];

		if(in_array('', $new_comment))
		{
			throw new \PDOException('400, Invalid Data Format for Comment');
		}

		return Comment::create($new_comment);;
	}

	// Update comment.
	public function update($id)
    {
        $comment = Comment::find($id);
        $comment->text = Input::get('text');
        $comment->save();

        return ['success' => true];
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
			throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException("Delete was failed. Comment doesn't exist.");
		}

		Comment::destroy($id);
		return $comment->article;
	}


	public function show($slug)
    {    
    	if (!$article = Article::where('slug', $slug)->first()) 
		{
			throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('404, Comments not found');
		}

    	$comment = $article->comments();
        return $comment->get();	    
    }
}
