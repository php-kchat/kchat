<div class="col-md-5 content">
	<div class="panel panel-default">
		<div class="panel-heading">
			Dashboard
		</div>
		<div class="panel-body scroll_panel" id="msgscrl">
			<div id="messages" ></div>
		</div>
		<div class="panel-heading">
			<span id="typing"><i><b>&nbsp;</b></i></span>
			<textarea  class="kchatemoji" autofocus="autofocus" style="width:100%;height:100%" id="kchattextarea" ></textarea>
		</div>
	</div>
</div>
<div class="col-md-3 content">
	<div class="panel panel-default">
		<div class="panel-heading" >
			Messages
		</div>
		<div class="panel-body scroll_panel">
			<div id="kchatchats" ></div>
		</div>
	</div>
</div>
<div class="col-md-2 content">
	<div class="panel panel-default">
		<div class="panel-heading">
			online in Current room
		</div>
		<div class="panel-body scroll_panel">
			<div id="online" ></div>
		</div>
	</div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
	kchat.Ready();
  });
</script>