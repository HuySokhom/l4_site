var commentApp = angular.module('commentApp', ['mainCtrl', 'commentService']);



// filters js
commentApp.filter("nl2br", function($sce) {
 return function(data) {
   if (!data) return data;
   return $sce.trustAsHtml(data.replace(/\n/g, '<br />'));
 };
});
