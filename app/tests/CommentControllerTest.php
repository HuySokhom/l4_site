<?php

use \App\Controllers\Admin\CommentController;
use \App\Controllers\Admin\ArticlesController;
use \App\Models\Comment;

class CommentControllerTest extends TestCase
{	
    private $slug;
	private $comment;
	private $delete_comment_id;

	public function setUp()
    {
    	parent::setUp();

    	$this->slug = 'bla-bla';
    	$this->comment = [
    		'author' => 'bonnak',
    		'text'	=> 'hi there',
    		'article_id' => 4
    	];
    	$this->delete_comment_id = 58;

        $this->fetch = new CommentController;
    }

    public function testIfArticleSlugIsValidWhenLoadingComment()
    {
	    $this->call('GET', '/api/comments/' . $this->slug);    	
    }

    public function testArticleShouldReturnCorrectNumberOfComment()
    {
    	$this->assertNotEquals(            
		    $this->fetch->show($this->slug),
	        Comment::all()
	    );
    }

    
    public function testIncompleteDataShouldBeAnError()
    {
    	$error_expected = [
    		'author' 		=> '',
    		'text'			=> '',
    		'article_id' 	=> null
    	];

    	$this->call('POST', '/api/comments', $error_expected);

    	// $stub = $this->getMock('\App\Controllers\Admin\CommentController');
    	// $stub->method('store')->willReturn($error_expected);
    	// $this->assertEquals($error_expected, $stub->store());
    }

    public function testCompleteDataShouldBeCreated()
    {
    	$response = $this->call('POST', '/api/comments', $this->comment);
    	print $response;
    }

    public function testHtmlShouldBeStrippedWhenCreateComment()
    {
    	$this->assertTrue($this->comment['author'] === htmlentities($this->comment['author']));
    	$this->assertTrue($this->comment['text'] === htmlentities($this->comment['text']));
    	$this->assertTrue($this->comment['article_id'] === htmlentities($this->comment['article_id']));
    }

    public function testIfArticleSlugIsValidWhenDeleteComment()
    {
	    $this->call('DELETE', '/api/comments/' .  $this->delete_comment_id);    	
    }

    public function testAfterDeletedTotalRecordShouldBeLessthanItWas()
    {
    	$before_delete_record = json_decode(Article::where('slug', $this->slug)->first()->comments()->get());

	    $response = $this->call('DELETE', '/api/comments/' .  $before_delete_record[0]->id);

    	$this->assertCount(count($before_delete_record) - 1, json_decode($response));

    }
}