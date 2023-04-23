@extends('user.master')

@section('title', 'Message\'s')

@section('header')
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/kchat.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet" />
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.bundle.min.js"></script>
	<link rel="stylesheet" href="//cdn.materialdesignicons.com/3.7.95/css/materialdesignicons.min.css">
	<script src="js/kchat.msg.js"></script>
	<meta name="conversation" content="{{ $chat }}" />
@endsection

@section('body')
<div class="mail-list-container col-md-3 pt-4 pb-4 border-right bg-white height10">
 <div class="border-bottom pb-3 px-3">
	<div class="form-group">
	   <input class="form-control w-100" type="search" placeholder="Search Conversation" data-toggle="modal" data-target="#search_conversation" id="Mail-rearch">
	</div>
 </div>
 <ul class="friend-list" id="MessageBox">
	<!--li class="active bounceInDown">
	   <a href="#" class="clearfix">
		  <img src="https://bootdey.com/img/Content/user_1.jpg" alt="" class="img-circle">
		  <div class="friend-name">
			 <strong>John Doe<i class="mdi mdi-star-outline"></i></strong>
		  </div>
		  <div class="last-message text-muted">Hello, Are you there?</div>
		  <small class="time text-muted">Just now</small>
		  <small class="chat-alert label label-danger">1</small>
	   </a>
	</li>
	<li class="bounceInDown">
	   <a href="#" class="clearfix">
		  <img src="https://bootdey.com/img/Content/user_2.jpg" alt="" class="img-circle">
		  <div class="friend-name">
			 <strong>Jane Doe<i class="mdi mdi-star favorite"></i></strong>
		  </div>
		  <div class="last-message text-muted">Lorem ipsum dolor sit amet.</div>
		  <small class="time text-muted">5 mins ago</small>
		  <small class="chat-alert text-muted">
		  <i class="fa fa-check"></i>
		  </small>
	   </a>
	</li-->
 </ul>
</div>
<div id="kchat-msg" class="col-md-9 col-lg-7 bg-white chat height10 px-0 py-0">
@if($conversation)
 <div class="chat-header clearfix">
	<div class="row">
	   <div class="col-lg-6">
		  <a href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
            <img src="{{ $conversation->photo }}" alt="avatar">
		  </a>
		  <div class="chat-about">
			 <h6 class="m-b-0">{{ $conversation->conversation_name }}</h6>
			 <small>Last Created: <span class="timestamp" >{{ $conversation->created_at }}</span></small>
		  </div>
	   </div>
	   <div class="col-lg-6 hidden-sm text-right">
		  <!--a href="javascript:void(0);" class="btn btn-outline-secondary"><i class="fa fa-camera"></i></a>
		  <a href="javascript:void(0);" class="btn btn-outline-primary"><i class="fa fa-image"></i></a-->
		  <a href="javascript:void(0);" data-toggle="modal" data-target="#updateconversatoin" class="btn btn-outline-secondary"><i class="fa fa-cogs"></i></a>
		  <!--a href="javascript:void(0);" class="btn btn-outline-warning"><i class="fa fa-question"></i></a-->
	   </div>
	</div>
 </div>
 <div class="chat-history" id="Msgs" style="min-height: 50%;" >
    <image id="loading" src="/assets/loading.gif"></image>
	<ul class="m-b-0" id="Messages"></ul>
    <button type="button" id="gotobottom" class="btn btn-outline-secondary"><i class="fa fa-chevron-down"></i></button>
 </div>
 <div class="chat-message clearfix">
	<div class="input-group mb-0">
	   <div id="post_msg_btn" class="input-group-append" >
		  <span class="input-group-text"><i class="fa fa-send"></i></span>
	   </div>
	   <input type="text" id="post_msg" class="form-control" placeholder="Enter text here...">
	</div>
 </div>
<!-- Modal -->
<div class="modal fade" id="updateconversatoin" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Group</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="input-group mb-3">
            <input type="file" id="photo" accept="image/*" class="btn btn-primary updategroup">
        </div>
        <div class="input-group mb-0">
           <input id="grpname" type="text" class="form-control updategroup" placeholder="Group Title" value="{{ $conversation->conversation_name }}" />
        </div>
        <input type="hidden" class="updategroup" id="group_id" value="{{ $conversation->id }}" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" ajax_post action="/messages/update" form="updategroup" >Update Group</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="view_info" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Photo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="input-group mb-0">
            <img id="photo" src="{{ $conversation->photo }}" />
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@else
	 <div>
	 <div style="width: 50%; margin-left: 25%; margin-top: 10%;">
			<img src="/logo/KChat_Logo.svg"  />
	 </div>
	 </div>
@endif
</div>

<!-- Modal -->
<div class="modal fade" id="search_conversation" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <input type="text" class="form-control" id="convo_like" />
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="search_conversation_results" >
          <table id="ConvoList" class="table">
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection
				  
@section('script')
<script type="text/javascript">
</script>
@endsection
