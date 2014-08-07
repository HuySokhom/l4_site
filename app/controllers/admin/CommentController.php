<?php namespace App\Controllers\Admin;

use App\Models\Comment;
use App\Models\Article;
use Input, Notification, Redirect, Sentry, Str;

class CommentController extends \BaseController {

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
		Comment::create(array(
			'author' => Input::get('author'),
			'text' => Input::get('text'),
			'article_id' => Input::get('article_id')
		));

		return ['success' => true];
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
		Comment::destroy($id);

		return ['success' => true];
	}


	public function show($id)
    {
        return Comment::where('article_id', $id)->get();
    }
}
