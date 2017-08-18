<div class="col-md-10 content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4>Embed Code</h4>
		</div>
		<div class="panel-body">
				Add this To Head Tag of Your Page
				<button class="btn" data-clipboard-target="#copy1">
					<img src="<?php echo $data['config']['url']."/"; ?>kchat/assets/images/clippy.svg" class="copy" alt="Copy to clipboard">
				</button>
				<div class="code" id="copy1"  >
					<span class="sc1">&lt;script</span>
					<span class="sc8"> </span>
					<span class="sc3">src</span>
					<span class="sc8">=</span>
					<span class="sc6">"<?php echo $data['KChat_conf']['kchat_url']; ?>/assets/js/kchat.js"</span>
					<span class="sc1">&gt;&lt;/script&gt;</span>
				</div>
				Add this To Head Tag of Your Page
				<button class="btn" data-clipboard-target="#copy2">
					<img class="copy" src="<?php echo $data['config']['url']."/"; ?>kchat/assets/images/clippy.svg" alt="Copy to clipboard">
				</button>
				<div class="code" id="copy2" >
					<span class="sc1">&lt;link</span>
					<span class="sc8">  </span>
					<span class="sc3">rel</span>
					<span class="sc8">=</span>
					<span class="sc6">"stylesheet"</span>
					<span class="sc8"> </span>
					<span class="sc3">href</span>
					<span class="sc8">=</span>
					<span class="sc6">"<?php echo $data['KChat_conf']['kchat_url']; ?>/assets/css/kchat.css"</span>
					<span class="sc11">/&gt;</span>
				</div>
				Add This to Any Where in Body Tag
				<button class="btn" data-clipboard-target="#copy3">
					<img class="copy" src="<?php echo $data['config']['url']."/"; ?>kchat/assets/images/clippy.svg" alt="Copy to clipboard">
				</button>
				<div class="code" id="copy3" >
					<span class="sc1">&lt;script&gt;</span><br/>
					<span class="sc46">&nbsp;&nbsp;&nbsp;&nbsp;kchat.init</span>
					<span class="sc50">();</span><br/>
					<span class="sc1">&lt;/script&gt;</span>
				</div>
		</div>
	</div>
</div>
<script>
	var clipboard = new Clipboard('.btn');
	clipboard.on('success', function(e) {
		console.log(e);
	});
	clipboard.on('error', function(e) {
		console.log(e);
	});
</script>