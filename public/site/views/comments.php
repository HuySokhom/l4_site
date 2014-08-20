<div class="container" ng-app="commentApp" ng-controller="mainController">
	<div class="col-md-8 col-md-offset-2">
		<form>
			<div class="form-group">
				<input type="text" class="form-control input-sm" name="author" ng-model="comments.new_comment.author" placeholder="Name">
			</div>
			<div class="form-group">
				<input type="text" class="form-control input-lg" name="comment" ng-model="comments.new_comment.text" placeholder="Say what you have to say">
			</div>
			<div class="form-group text-right">	
				<button type="submit" class="btn btn-primary btn-lg" ng-click="comments.createElement()">Submit</button>
			</div>


			<div class="comment" ng-repeat="comment in comments.getElements()">
				<h3>Comment #{{ comment.id }} <small>by {{ comment.author }}</h3>
				<p>{{ comment.text }}<br>
					<textarea ng-model="comment.draft.text"></textarea>
				</p>

				<p><a href="#" ng-click="comments.deleteElement(comment)" class="text-muted">Delete</a>&nbsp;
				<a href="#" ng-click="comments.updateElement(comment)" class="text-muted">Edit</a>
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