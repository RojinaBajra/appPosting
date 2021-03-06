@extends('web.layouts.front')

<style type="text/css">

    .comment{
        background-color: grey

    }
    .commentbox{
        width:900px;
        background-color: #9EB9D4;
        text-align:  left;
        margin-left: 100px;
    }
</style>


<div class="container" ng-controller="PostController" ng-init="loadData();">
        <h1>Create a Post</h1>
        <input type="text" ng-model="threadTopic" class="form-control" placeholder="Topic">
        <textarea ng-model="threadDescription" cols="30" rows="5" class="form-control" placeholder="Write Description"></textarea>
        <button class="btn btn-primary" ng-click="createThread()">Create a post</button>

        View Comments
        <div class="comment" ng-hide="loading" ng-repeat="(key, value) in posts track by $index">
      
            <h3>POST @{{ value.id}} </h3>
             <H3> @{{ value.topic }}</h3>
             <p>@{{ value.description }}</p>
             <div ng-repeat="(keyy, valuee) in value.comment track by $index">
                  <!-- @{{valuee.reply}}   -->
                   
                  <div class="commentbox"> @{{valuee.reply}} </div> 
                <button style="margin-left:850px" ng-click="deleteComment($index ,valuee.id, value.id, keyy, key);">Delete Comment</button>
                <!-- <button ng-model="new_reply" ng-click="editComment(valuee.id,value.id, keyy, key);">Edit Comment</button> -->
             </div>

      

        <div class="container" ng-controller="replyController" >
        <div class="field">
       
        <div ng-show="showPopup" ng-hide='saveField'  class="popup" ng-mouseleave='showPopup = true'>
            <textarea ng-model="reply" style="margin-left:100" cols="40" placeholder="Write comment"></textarea> <button ng-click="showPopup = false;addComments(value.id, key);">Add Comments</button>
        </div>
       </div>
    </div>
         </div>
          <div ng-repeat = "reply in replies">
          <h3> @{{reply.reply}}</h3>
            
          </div>

</div>

