@extends('admin.master')

@section('title', 'Message\'s')

@section('header')
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/kchat.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet" />
	<link href="css/emojionearea.min.css" rel="stylesheet" />
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.bundle.min.js"></script>
	<link rel="stylesheet" href="//cdn.materialdesignicons.com/3.7.95/css/materialdesignicons.min.css">
	<script src="js/kchat.msg.js"></script>
	<script src="js/emojionearea.js"></script>
	<meta name="conversation" content="{{ $chat }}" />
@endsection

@section('body')
<div class="mail-list-container col-md-3 pt-4 pb-4 border-right bg-white height10">
 <div class="border-bottom pb-3 px-3">
	<div class="form-group">
	   <input class="form-control w-100" type="search" placeholder="Search Conversation" data-toggle="modal" data-target="#search_conversation" id="Mail-rearch">
	</div>
 </div>
 <ul class="friend-list" id="MessageBox"></ul>
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
    <image id="loading" src="/assets/loading.gif"/>
	<ul class="pl-3 pr-3" id="Messages"></ul>
    <button type="button" id="gotobottom" class="btn btn-outline-secondary"><i class="fa fa-chevron-down"></i></button>
 </div>
 <div class="chat-message clearfix">
	<div class="input-group mb-0">
	   <div id="post_msg_btn" class="input-group-append mb-1 mr-1" >
		  <span class="input-group-text"><i class="fa fa-commenting-o"></i></span>
	   </div>
	   <div class="input-group-append mb-1 mr-1" data-toggle="modal" data-target="#whiteboard" >
		  <span class="input-group-text"><i class="fa fa-pencil"></i></span>
	   </div>
	   <div class="input-group-append mb-1 mr-1" >
          <input id="selectedFile" type="file" name="name" multiple="multiple" style="display: none;">
		  <span class="input-group-text" onclick="document.getElementById('selectedFile').click();" ><i class="fa fa-paperclip"></i></span>
	   </div>
	   <textarea id="post_msg" class="form-control" ></textarea>
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
        <button type="button" class="btn btn-primary" ajax_post  data-msg="Are you sure you want to update group profile?" action="/messages/update" form="updategroup" >Update Group</button>
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
<!-- whiteboard Modal -->
<div class="modal fade" id="whiteboard" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" id="whiteboard-model" >
      <div class="modal-header">WhiteBoard</div>
      <div class="modal-body" id="search_conversation_results" >
          <canvas id="myCanvas" width="600" height="400" style="border:1px solid #d3d3d3;"></canvas>
            <input class="gadgets" type="button" value="Line" onclick="buttons(this)" data="Line" />
            <input class="gadgets" type="button" value="Circle" onclick="buttons(this)" data="Circle" />
            <input class="gadgets" type="button" value="Rectangle" onclick="buttons(this)" data="Rectangle" />
            <input class="gadgets" type="button" value="ClearRect" onclick="buttons(this)" data="clearRect" />
            <input class="gadgets" type="button" value="Ellipse" onclick="buttons(this)" data="ellipse" />
            <input class="gadgets" type="button" value="Pencil" onclick="buttons(this)" data="Pencil" />
            <input id="filled" type="checkbox" onclick="filled(this)" />
            <input type="button" value="Clear" onclick="Clear(this)" data="None" />
            <input id="color" type="color" onchange="Color(this)" value="#000002" />
            <input id="border" type="text" onchange="bordersize(this)" value="1" style="width: 50px;"/>
            <input id="download" type="button" onclick="download()" value="Download" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="WhiteBoardSend" data-dismiss="modal">Send</button>
      </div>
    </div>
  </div>
</div>
@endsection
				  
@section('script')
<script type="text/javascript">

var shape,fill,points=[],x=y=0,c=document.getElementById("myCanvas"),ctx=c.getContext("2d"),color="#000000",border=1;function getMousePos(e,t){var n=e.getBoundingClientRect();x=t.clientX-n.left,y=t.clientY-n.top}function _wb_event(e,t){}function F_Points(){void 0!=shape&&(points[points.length]=[shape,color,border,[[x,y]]])}var tmp=0;function T_Points(){"Pencil"==shape||"bezier"==shape?(tmp=points[points.length-1][3].length,points[points.length-1][1]=color,points[points.length-1][2]=border,points[points.length-1][3][tmp]=[x,y]):void 0!=shape&&(points[points.length-1][1]=color,points[points.length-1][2]=border,points[points.length-1][3][0][2]=x,points[points.length-1][3][0][3]=y,points[points.length-1][4]=fill)}function Color(e){color=e.value}function bordersize(e){border=e.value}function filled(e){fill=e.checked}function draw(e,t){ctx.beginPath(),ctx.lineWidth=e[2],ctx.fillStyle=e[1],ctx.strokeStyle=e[1],"Line"==e[0]&&(ctx.moveTo(e[3][0][0],e[3][0][1]),ctx.lineTo(e[3][0][2],e[3][0][3])),"Circle"==e[0]&&ctx.arc(e[3][0][0],e[3][0][1],Math.sqrt((e[3][0][2]-e[3][0][0])*(e[3][0][2]-e[3][0][0])+(e[3][0][3]-e[3][0][1])*(e[3][0][3]-e[3][0][1])),0,2*Math.PI),"Rectangle"==e[0]&&(e[4]?ctx.fillRect(e[3][0][0],e[3][0][1],e[3][0][2]-e[3][0][0],e[3][0][3]-e[3][0][1]):ctx.strokeRect(e[3][0][0],e[3][0][1],e[3][0][2]-e[3][0][0],e[3][0][3]-e[3][0][1])),"clearRect"==e[0]&&ctx.clearRect(e[3][0][0],e[3][0][1],e[3][0][2]-e[3][0][0],e[3][0][3]-e[3][0][1]),"ellipse"==e[0]&&ctx.ellipse(e[3][0][0],e[3][0][1],Math.abs(e[3][0][2]-e[3][0][0]),Math.abs(e[3][0][3]-e[3][0][1]),0,0,2*Math.PI,!1),"Pencil"==e[0]&&(ctx.moveTo(e[3][0][0],e[3][0][1]),e[3].forEach(e=>{ctx.lineTo(e[0],e[1])})),"bezier"==e[0]&&(ctx.moveTo(e[3][0][0],e[3][0][1]),e[3].forEach(e=>{ctx.bezierCurveTo(e[0],e[1])})),e[4]&&ctx.fill(),ctx.stroke()}var go=!1;function buttons(e){shape=e.getAttribute("data")}function Clear(){points=[],ctx.clearRect(0,0,c.width,c.height)}function download(){let e=document.getElementById("myCanvas"),t=document.createElement("a"),n=e.toDataURL();t.href=n,t.download="KChat-WhiteBoard.png",t.click()}function download_json(){let e=JSON.stringify(points),t=btoa(e),n=document.createElement("a");n.href="data:text/plain;base64,"+t,n.download="KChat-WhiteBoard.json",n.click()}c.addEventListener("drag",function(e){_wb_event(e,"drag")},!1),c.addEventListener("click",function(e){_wb_event(e,"click")},!1),c.addEventListener("drop",function(e){_wb_event(e,"drop")},!1),c.addEventListener("keydown",function(e){_wb_event(e,"keydown")},!1),c.addEventListener("keypress",function(e){_wb_event(e,"keypress")},!1),c.addEventListener("keyup",function(e){_wb_event(e,"keyup")},!1),c.addEventListener("mousedown",function(e){_wb_event(e,"mousedown"),F_Points(),go=!0},!1),c.addEventListener("mouseenter",function(e){_wb_event(e,"mouseenter")},!1),c.addEventListener("mouseleave",function(e){_wb_event(e,"mouseleave")},!1),c.addEventListener("mousemove",function(e){_wb_event(e,"mousemove"),getMousePos(c,e),go&&(ctx.clearRect(0,0,c.width,c.height),points.forEach(draw),T_Points())},!1),c.addEventListener("mouseover",function(e){_wb_event(e,"mouseover")},!1),c.addEventListener("mouseout",function(e){_wb_event(e,"mouseout")},!1),c.addEventListener("mouseup",function(e){_wb_event(e,"mouseup"),T_Points(),go=!1,points.forEach(draw)},!1),c.addEventListener("mousewheel",function(e){_wb_event(e,"mousewheel")},!1),c.addEventListener("offline",function(e){_wb_event(e,"offline")},!1),c.addEventListener("online",function(e){_wb_event(e,"online")},!1);

</script>
@endsection
