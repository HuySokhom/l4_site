angular.module('commentApp', [])
	
	.controller('mainController', function($scope, $location, Comments, Comment) {
		var arr_location = $location.absUrl().split('/');		
		$scope.comments = new Comments(arr_location[arr_location.length - 1]);
		$scope.comments.load();
 	})

	.factory('Comments', function($http, Comment){

		var Comments = function(slug){
			this.elements = [];
			this.slug = slug;
			this.article_id = null;

			this.new_comment = {
				author 			: '',
				text 			: '',
				article_slug 	: slug
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
			return $http.get('/api/comments/' + self.slug)
						.then(function(response){
							var data = response.data;
							
							// Populate all comments to the collection.
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