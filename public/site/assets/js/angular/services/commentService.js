angular.module('commentService', [])

	.factory('Comment', function($http) {

		return {
			// get all the comments
			get : function() {
				return $http.get('/api/comments/' + $('#article_id').val());
			},

			// save a comment (pass in comment data)
			save : function(commentData) {
				return $http({
					method: 'POST',
					url: '/api/comments',
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
					data: $.param(commentData) + '&article_id=' + $('#article_id').val()
				});
			},

			// destroy a comment
			destroy : function(id) {
				return $http.delete('/api/comments/' + id);
			},

			// Edit comment.
			update : function(id, text){
				return $http({
					method: 'PATCH',
					url: '/api/comments/' + id,
					headers: { 'Content-Type' : 'application/x-www-form-urlencoded' },
					data: 'text=' + text
				});
			}
		}

	});