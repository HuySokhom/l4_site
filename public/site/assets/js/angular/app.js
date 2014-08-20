angular.module('commentApp', [])
	
	.controller('mainController', function($scope, Comments, Comment) {
		$scope.comments = new Comments(1);

		$scope.comments.load();
 	})

	.factory('Comments', function($http, Comment){

		var Comments = function(article_id){
			this.elements = [];
			this.article_id = article_id;

			this.new_comment = {
				author 		: '',
				text 		: '',
				article_id 	: this.article_id
			};
		};

		Comments.prototype.getElements = function(elements){
			var comments = [];

			this.elements.forEach(function(data){
				if(!data.deleted){
					comments.push(data);
				}
			});

			return comments;
		};

		Comments.prototype.populate = function(element){
			var comment = new Comment(element);	
			this.elements.push(comment);	
		};

		Comments.prototype.load = function() {
			var self = this;
			return $http.get('/api/comments/' + self.article_id)
						.then(function(response){
							var data = response.data;
							data.forEach(function (element) {	
								self.populate(element);
							});		
						});
		};

		Comments.prototype.createElement = function(){
			var self = this;
			return $http({
						method: 'POST',
						url: '/api/comments',
						headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
						data: $.param(this.new_comment)
					}).then(function(response){
						var element = response.data;
						if(element !== null){
							self.populate(element);
						}
					});


			// and then(function({ this.elements.push(commentObj..

			// eg: comment.create().then(function(){this.elements.push(comment..	
		};

		Comments.prototype.deleteElement = function(comment){
			var self = this;
			comment.delete().then(function(){
				comment.deleted = true;
			});
		};

		Comments.prototype.updateElement = function(comment){
			comment.update().then(function(){
				comment.text = comment.draft.text;
				comment.draft.text = '';
			});
		};


		return Comments;
	})

	.factory('Comment', function($http){

		var Comment = function(comment){
			this.id = comment.id;
			this.author = comment.author;
			this.text = comment.text;
			this.article_id = comment.article_id;
			this.tag = '';
			this.deleted = false;

			this.draft = {
				id: this.id,
				author: this.author,
				text: '',
				article_id: this.article_id,
				tag: this.tag
			};
		};

		Comment.prototype.delete = function(){
			return $http.delete('/api/comments/' + this.id);
		};

		Comment.prototype.update = function(){
			return $http({
					method: 'PATCH',
					url: '/api/comments/' + this.id,
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
					data: $.param(this.draft)
				});
		};

		return Comment;
	})