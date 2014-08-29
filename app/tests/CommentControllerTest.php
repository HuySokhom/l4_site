<?php

use \App\Controllers\Admin\CommentController;
use \App\Controllers\Admin\ArticlesController;
use \App\Models\Comment;
use \App\Models\Article;

class CommentControllerTest extends TestCase
{	
    private $article_slug;

	public function setUp()
    {
    	parent::setUp();
		
		$this->article_slug = 'first-post';

		Artisan::call('app:seed');
    }

    /**
    * @expectedException Symfony\Component\HttpKernel\Exception\NotFoundHttpException
    */
    public function testIfArticleSlugIsInValidWhenLoadingComment()
    {
    	$error_article_slug = 'unavailable-post';
	    $response = $this->call('GET', '/api/comments/' . $error_article_slug);
    }

    public function testIfArticleSlugIsValidWhenLoadingComment()
    {    
        $response = $this->call('GET', '/api/comments/' . $this->article_slug);  
    }

    public function testArticleShouldReturnCorrectNumberOfComment()
    {            	
        // Loading comments.
        $response = $this->call('GET', '/api/comments/' . $this->article_slug);
        $comments = $response->original->toArray(); 

        // Expected comments.
        $article_id = Article::where('slug', $this->article_slug)->first()->id;
        $Expected_comments = Comment::where('article_id', $article_id)->get()->toArray();

        // Assert if both comments are the same.
    	$this->assertSame( 
	        $comments,
            $Expected_comments
	    );
    }
    
    /**
    * @expectedException PDOException
    */
    public function testPostIncompleteDataShouldBeAnError()
    {
        $error_new_comment = [
            'author' => '',
            'text'  => '',
            'article_slug' => 'first-post'
        ];

    	$this->call('POST', '/api/comments', $error_new_comment);
    }

    public function testPostCompleteDataShouldBeCreateComment()
    {
        $new_comment = [
            'author' => 'author',
            'text'  => 'Testing comment',
            'article_slug' => 'first-post'
        ];

        $this->call('POST', '/api/comments', $new_comment);
    }

    public function testHtmlTagIsStrippedWhenCreateComment()
    {   
        $new_comment = [
            'author' => '<b>author</b>',
            'text'  => '<script> Testing comment</script>',
            'article_slug' => $this->article_slug
        ];

        $response = $this->call('POST', '/api/comments', $new_comment);
        $comments = $response->original->toArray();
        
    	$this->assertTrue($comments['author'] === htmlentities($new_comment['author']), 'Author');
    	$this->assertTrue($comments['text'] === htmlentities($new_comment['text']), 'Text');
    }

    public function testIfDeletedCommentIdIsValid()
    {    	
        // Load comments.
        $article_id = Article::where('slug', $this->article_slug)->first()->id;
        $comments = Comment::where('article_id', $article_id)->get()->toArray();

        // Test delete command.
        $response = $this->call(
        		'DELETE', '/api/comments/' .  $comments[count($comments) - 1]['id']
        );
    }

    /**
    * @expectedException Symfony\Component\HttpKernel\Exception\NotFoundHttpException
    */
    public function testIfDeletedCommentIdIsNotValid()
    {
    	$unexpected_comment_id = 1000;
        // Test delete command.
        $response = $this->call(
        		'DELETE', '/api/comments/' . $unexpected_comment_id
        );
    }

    public function testAtferDeleteCommentTotalRecordShouldBeLessthanItWas()
    {    	
         // Load comments before deleted.
        $article_id = Article::where('slug', $this->article_slug)->first()->id;
        $before_deleted_comments = Comment::where('article_id', $article_id)->get()->toArray();

        // Test delete command.
        $response = $this->call(
        		'DELETE', '/api/comments/' .  $before_deleted_comments[count($before_deleted_comments) - 1]['id']
        );
        
        // Load comments after deleted.
        $article_id = Article::where('slug', $this->article_slug)->first()->id;
        $after_delete_comments = Comment::where('article_id', $article_id)->get()->toArray();

        // Assert count should be less than it was.
        $this->assertCount(count($before_deleted_comments) - 1, $after_delete_comments, 'Number of comments should be less than it was');
    }

    public function testIfUpdatedCommentIdIsValid()
    {
        // Load comments.
        $article_id = Article::where('slug', $this->article_slug)->first()->id;
        $comments = Comment::where('article_id', $article_id)->get()->toArray();

        $Expected_update_comment = [
            'id' => $comments[count($comments) - 1]['id'],
            'text' => 'Test updating comment.'
        ];

        // Test update command.
        $response = $this->call('PATCH', '/api/comments/' . $Expected_update_comment['id'] , $Expected_update_comment);
    }

    /**
    * @expectedException Symfony\Component\HttpKernel\Exception\NotFoundHttpException
    */
    public function testIfUpdatedCommentIdIsInValid()
    {
    	$updated_comment_id = 1000;

        $Expected_update_comment = [
            'id' => $updated_comment_id ,
            'text' => 'Test updating comment.'
        ];

        // Test update command.
        $response = $this->call('PATCH', '/api/comments/' . $Expected_update_comment['id'] , $Expected_update_comment);
    }

    public function testDataShouldBeMatchAfterUpdated()
    {
        // Load comments.
        $article_id = Article::where('slug', $this->article_slug)->first()->id;
        $comments = Comment::where('article_id', $article_id)->get()->toArray();

        $Expected_update_comment = [
            'id' => $comments[count($comments) - 1]['id'],
            'text' => 'Test updating comment, data should be match.'
        ];

        // Test update command.
        $response = $this->call(
        		'PATCH', '/api/comments/' . $Expected_update_comment['id'] , 
        		$Expected_update_comment
		);
        
        $updated_comments = $response->original->toArray();         
    	$this->assertSame($updated_comments['text'], $Expected_update_comment['text'] , 'Test if updated comment text is match.');
    }

    public function testHtmlTagIsStrippedWhenUpdateComment()
    {
        // Load comments.
        $article_id = Article::where('slug', $this->article_slug)->first()->id;
        $comments = Comment::where('article_id', $article_id)->get()->toArray();

        $Expected_update_comment = [
            'id' => $comments[count($comments) - 1]['id'],
            'text' => '<script>Test updating comment, data should be match</script>.'
        ];

        // Test update command.
        $response = $this->call(
        		'PATCH', '/api/comments/' . $Expected_update_comment['id'] , 
        		$Expected_update_comment
		);
        
        $updated_comments = $response->original->toArray(); 
    	$this->assertTrue($updated_comments['text'] === htmlentities($Expected_update_comment['text']), 'Html tag is stripped when update comment.');
    }
}