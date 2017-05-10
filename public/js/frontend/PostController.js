'use strict';

var PostController = angular.module('myApp', []);
var siteName = "http://localhost:8000";

PostController.controller('PostController', ['$rootScope', '$scope', '$http', '$window', function ($rootScope, $scope, $http, $window) {
	
	var app = this;
	
	$scope.posts={}
   $scope.replies= {}
   $scope.editMode= true;
   $scope.statusedit = false;

	 $rootScope.userArray = [];

	$scope.loadData = function(){
		$http.get(siteName + '/api/v1/thread')
			.success(function(data){
				$scope.posts = data.data;
		
				
		})};
			


	$scope.createThread = function(){
		$http.post(siteName + '/api/v1/thread', {topic: $scope.threadTopic, description: $scope.threadDescription})
            .success(function (data) {
            	if($scope.posts == null){
            		$scope.posts=[];

            	}

            	
            	$scope.posts.push(data.data)
                if (data.errors)
                    console.log("I am wrong");
                else if (data.success === 0)
                    toastr.error(data.message);
                else {
                    toastr.success("Thread added successfully!");
                    setTimeout(function () {
                    	$scope.posts.push(data.data);
                        $window.location.href = '#';
                    }, 1000);
                }
            });
	};

	$scope.viewReplies = function ($id){
		console.log($id);
		$http.get(siteName + '/api/v1/thread/' + $id + '/viewReplies')
		 .success(function(data){
		 	//console.log(data);
		 	$scope.replies = data.data;
		 //	console.log($scope.replies);
		 });
	}

	$scope.newcomment = {};
    $scope.postCommand = function(key){
      $scope.replies[key].reply.push($scope.newcomment[key]);
      $scope.newcomment = {};
    };

    $scope.addComments= function($id){
    	$http.post(siteName + '/api/v1/thread/' + $id + '/comment', {reply: $scope.reply})
            .success(function (data) {
			   console.log(data);
         });
    	
    };

    $scope.deleteComment = function($i, $comment_id, $post_id, $key, $keyy){
    	console.log($i, $comment_id, $post_id,$key, $keyy);
    	$http.delete(siteName + '/api/v1/thread/' +  $post_id + '/comment/' + $comment_id)
    		.success(function(data){
    			// console.log(data);
    			 console.log($scope.posts[$keyy].comment[$key].reply);
    			 $scope.posts[$keyy].comment.splice($i, 1);
    			// console.log($scope.posts[$keyy].comment.indexOf($comment_id));
    			// var index =$scope.posts[$keyy].comment.indexOf($comment_id);
    		
    		});
    	
    }
    $scope.editComment = function($comment_id,$post_id, $comment_key, $post_key){
    	console.log($scope.new_reply);
    	console.log($comment_id, $post_id, $comment_key);
    	$http.put(siteName + '/api/v1/thread/' + $post_id + '/comment/' + $comment_id, {reply: $scope.new_reply})
    	  .success(function(data){
    	  	console.log(data);
    	  	//$scope.posts[$post_key].comment[$comment_key].reply = data.data.reply;
    	  });

    }
   
}]);

PostController.controller('replyController', ['$rootScope', '$scope', '$http', '$window', function ($rootScope, $scope, $http, $window) {

	$scope.addComments= function($id, $key){
		 console.log($id, $key);
		// console.log($scope.posts[$key]);
    	$http.post(siteName + '/api/v1/thread/' + $id + '/comment', {reply: $scope.reply})
            .success(function (data) {

            	// console.log($scope.posts);
            	$scope.posts[$key].comment.push(data.data);
            	// console.log($scope.posts);
            	// $scope.posts[$key].comment.push(data.data);

            
         });
    	

    };

}]);
