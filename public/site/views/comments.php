<div class="container" ng-app="commentApp" ng-controller="mainController">
	<div class="col-md-8 col-md-offset-2">

		<h4>Post your comments</h4>

		<!-- NEW COMMENT FORM =============================================== -->
		<form ng-submit="submitComment()"> <!-- ng-submit will disable the default form action and use our function -->

			<!-- AUTHOR -->
			<div class="form-group">
				<input type="text" class="form-control input-sm" name="author" ng-model="commentData.author" placeholder="Name">
			</div>

			<!-- COMMENT TEXT -->
			<div class="form-group">
				<input type="text" class="form-control input-lg" name="comment" ng-model="commentData.text" placeholder="Say what you have to say">
			</div>
			
			<!-- SUBMIT BUTTON -->
			<div class="form-group text-right">	
				<button type="submit" class="btn btn-primary btn-lg">Submit</button>
			</div>

			<!-- LOADING ICON =============================================== -->
			<!-- show loading icon if the loading variable is set to true -->
			<p class="text-center" ng-show="loading"><span class="fa fa-meh-o fa-5x fa-spin"></span></p>

			<!-- THE COMMENTS =============================================== -->
			<!-- hide these comments if the loading variable is true -->
			<div class="comment" ng-hide="loading" ng-repeat="comment in comments">
				<h3>Comment #{{ comment.id }} <small>by {{ comment.author }}</h3>
				<p>{{ comment.text }}<br>
				<input type="text" placeholder="Edit comment..."></p>

				<p><a href="#" ng-click="deleteComment(comment.id)" class="text-muted">Delete</a>&nbsp;
				<a href="#" ng-click="EditComment(comment.id, 'i love you')" class="text-muted">Edit</a>
				</p>

			</div>					
		</form>
	</div>
</div>
<style>
		.container 		{ padding-top:30px; }
		form 		{ padding-bottom:20px; }
		.comment 	{ padding-bottom:20px; }
</style>