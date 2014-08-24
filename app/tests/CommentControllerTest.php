<?php

use \App\Controllers\Admin\CommentController;
use \App\Controllers\Admin\ArticlesController;
use \App\Models\Comment;

class CommentControllerTest extends TestCase
{	
    private $article_slug;

	public function setUp()
    {
    	parent::setUp();

    	$this->article_slug = 'bla-bla';

        $this->fetch = new CommentController;
    }

    /**
    * @expectedException Symfony\Component\HttpKernel\Exception\NotFoundHttpException
    */
    public function testIfArticleSlugIsInValidWhenLoadingComment()
    {
	    $response = $this->call('GET', '/api/comments/' . $this->article_slug);
    }

    public function testIfArticleSlugIsValidWhenLoadingComment()
    {
        $response = $this->call('GET', '/api/comments/' . $this->article_slug);
    }

    public function testArticleShouldReturnCorrectNumberOfComment()
    {            
        // Loading comments.
        $response = $this->call('GET', '/api/comments/' . $this->article_slug);
        $comments = json_decode(json_encode($response), true)['original']; 

        // Expected comments.
        $article_id = json_decode(json_encode(Article::where('slug', $this->article_slug)->first()), true)['id'];
        $Expected_comments = json_decode(json_encode(Comment::where('article_id', $article_id)->get()), true);

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
            'article_id' => null
        ];

    	$this->call('POST', '/api/comments', $error_new_comment);
    }

    public function testPostCompleteDataShouldBeCreateComment()
    {
        $new_comment = [
            'author' => 'author',
            'text'  => 'Testing comment',
            'article_id' => 4
        ];

        $this->call('POST', '/api/comments', $new_comment);
    }

    public function testHtmlTagIsStrippedWhenCreateComment()
    {   
        $new_comment = [
            'author' => '<b>author</b>',
            'text'  => '<script> Testing comment</script>',
            'article_id' => 4
        ];

        $response = $this->call('POST', '/api/comments', $new_comment);
        $comments = json_decode(json_encode($response), true)['original'];
        
    	$this->assertTrue($comments['author'] === htmlentities($comments['author']), 'Author');
    	$this->assertTrue($comments['text'] === htmlentities($comments['text']), 'Text');
    	$this->assertTrue($comments['article_id'] === htmlentities($comments['article_id']), 'Article ID');
    }

    public function testHtmlTagIsNotStrippedWhenCreateComment()
    {   
        $new_comment = [
            'author' => '<b>bonnak</b>',
            'text'  => '<script> hi there </script>',
            'article_id' => 4
        ];

        $response = $this->call('POST', '/api/comments', $new_comment);
        $comments = json_decode(json_encode($response), true)['original'];
        
        $this->assertFalse($comments['author'] === htmlentities($comments['author']), 'Author');
        $this->assertFalse($comments['text'] === htmlentities($comments['text']), 'Text');
        $this->assertFalse($comments['article_id'] === htmlentities($comments['article_id']), 'Article ID');
    }

    public function testIfDeletedCommentIdIsValid()
    {
        // Load comments.
        $article_id = json_decode(json_encode(Article::where('slug', $this->article_slug)->first()), true)['id'];
        $comments = json_decode(json_encode(Comment::where('article_id', $article_id)->get()), true);

        // Test delete command.
        $response = $this->call('DELETE', '/api/comments/' .  $comments[count($comments) - 1]['id']);
        $deleted_comments = json_decode(json_encode($response), true)['original'];        
        $this->assertTrue($deleted_comments['id'] === $comments[count($comments) - 1]['id'], 'Test if deleted comment is right.');
    }

    /**
    * @expectedException Symfony\Component\HttpKernel\Exception\NotFoundHttpException
    */
    public function testIfDeletedCommentIdIsNotValid()
    {
        // Load comments.
        $article_id = json_decode(json_encode(Article::where('slug', $this->article_slug)->first()), true)['id'];
        $comments = json_decode(json_encode(Comment::where('article_id', $article_id)->get()), true);

        // Test delete command.
        $response = $this->call('DELETE', '/api/comments/' .  $comments[count($comments) - 1]['id']);
        $deleted_comments = json_decode(json_encode($response), true)['original'];        
        $this->assertFalse($deleted_comments['id'] === $comments[count($comments) - 1]['id'], 'Test if deleted comment is wrong.');
    }

    public function testAterDeleteCommentTotalRecordShouldBeLessthanItWas()
    {
         // Load comments before deleted.
        $article_id = json_decode(json_encode(Article::where('slug', $this->article_slug)->first()), true)['id'];
        $before_deleted_comments = json_decode(json_encode(Comment::where('article_id', $article_id)->get()), true);

        // Test delete command.
        $response = $this->call('DELETE', '/api/comments/' .  $before_deleted_comments[count($before_deleted_comments) - 1]['id']);
        
        // Load comments after deleted.
        $article_id = json_decode(json_encode(Article::where('slug', $this->article_slug)->first()), true)['id'];
        $after_delete_comments = json_decode(json_encode(Comment::where('article_id', $article_id)->get()), true);

        // Assert count should be less than it was.
        $this->assertCount(count($before_deleted_comments) - 1, $after_delete_comments, 'Number of comments should be less than it was');
    }

    public function testIfUpdatedCommentIdIsValid()
    {
        // Load comments.
        $article_id = json_decode(json_encode(Article::where('slug', $this->article_slug)->first()), true)['id'];
        $comments = json_decode(json_encode(Comment::where('article_id', $article_id)->get()), true);

        $Expected_update_comment = [
            'id' => $comments[count($comments) - 1]['id'],
            'text' => 'Test updating comment.'
        ];

        // Test update command.
        $response = $this->call('PATCH', '/api/comments/' . $Expected_update_comment['id'] , $Expected_update_comment);
        $updated_comments = json_decode(json_encode($response), true)['original'];        
        $this->assertSame($updated_comments['text'], $Expected_update_comment['text'], 'Test if updated comment is valid.');
    }

    /**
    * @expectedException Symfony\Component\HttpKernel\Exception\NotFoundHttpException
    */
    public function testIfUpdatedCommentIdIsInValid()
    {
        // Load comments.
        $article_id = json_decode(json_encode(Article::where('slug', $this->article_slug)->first()), true)['id'];
        $comments = json_decode(json_encode(Comment::where('article_id', $article_id)->get()), true);

        $Expected_update_comment = [
            'id' => $comments[count($comments) - 1]['id'],
            'text' => 'Test updating comment.'
        ];

        // Test update command.
        $response = $this->call('PATCH', '/api/comments/' . $Expected_update_comment['id'] , $Expected_update_comment);
        $updated_comments = json_decode(json_encode($response), true)['original'];        
        $this->assertNotSame($updated_comments['text'], $Expected_update_comment['text'], 'Test if updated comment is invalid.');
    }

    public function testDataShouldBeMatchAfterUpdated()
    {
        // Load comments.
        $article_id = json_decode(json_encode(Article::where('slug', $this->article_slug)->first()), true)['id'];
        $comments = json_decode(json_encode(Comment::where('article_id', $article_id)->get()), true);

        $Expected_update_comment = [
            'id' => $comments[count($comments) - 1]['id'],
            'text' => 'Test updating comment, data should be match.'
        ];

        // Test update command.
        $response = $this->call('PATCH', '/api/comments/' . $Expected_update_comment['id'] , $Expected_update_comment);
        $updated_comments = json_decode(json_encode($response), true)['original'];         
    	$this->assertSame($updated_comments['text'], $Expected_update_comment['text'] , 'Test if updated comment text is match.');
    }

    public function testHtmlTagIsStrippedWhenUpdateComment()
    {
        // Load comments.
        $article_id = json_decode(json_encode(Article::where('slug', $this->article_slug)->first()), true)['id'];
        $comments = json_decode(json_encode(Comment::where('article_id', $article_id)->get()), true);

        $Expected_update_comment = [
            'id' => $comments[count($comments) - 1]['id'],
            'text' => '<script>Test updating comment, data should be match</script>.'
        ];

        // Test update command.
        $response = $this->call('PATCH', '/api/comments/' . $Expected_update_comment['id'] , $Expected_update_comment);
        $updated_comments = json_decode(json_encode($response), true)['original']; 
    	$this->assertTrue($updated_comments['text'] === htmlentities($Expected_update_comment['text']), 'Html tag is stripped when update comment.');
    }

    public function testHtmlTagIsNotStrippedWhenUpdateComment()
    {
        // Load comments.
        $article_id = json_decode(json_encode(Article::where('slug', $this->article_slug)->first()), true)['id'];
        $comments = json_decode(json_encode(Comment::where('article_id', $article_id)->get()), true);

        $Expected_update_comment = [
            'id' => $comments[count($comments) - 1]['id'],
            'text' => '<script>Test updating comment, data should be match</script>.'
        ];

        // Test update command.
        $response = $this->call('PATCH', '/api/comments/' . $Expected_update_comment['id'] , $Expected_update_comment);
        $updated_comments = json_decode(json_encode($response), true)['original']; 
        $this->assertFalse($updated_comments['text'] === htmlentities($Expected_update_comment['text']), 'Html tag is not stripped when update comment.');
    }
}