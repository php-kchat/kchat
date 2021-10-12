<div class="col-md-10 content">
	<div class="panel panel-default">
		<div class="panel-heading">
			Embed Code
		</div>
		<div class="panel-body">
		
                    <div class="pull-right"><button type="submit" id="example" class="btn btn-success">Example</button></div>
				Add this To Head Tag of Your Page
				<button class="btn" data-clipboard-target="#copy1">
					<img src="<?php echo $this->data['config']['url']."/"; ?>kchat/assets/images/clippy.svg" class="copy" alt="Copy to clipboard">
				</button>
				<div class="code" id="copy1"  >
<?php 

$js_box = 'Javascript URL ie. kchat.js';

$assets_url = 'base url where kchat.js is located';

if(isset($this->data['KChat_conf']['kchat_url'])){
	$js_box = $this->data['KChat_conf']['kchat_url'].'/assets/js/kchat.js';
	$assets_url = $this->data['KChat_conf']['kchat_url'].'/';
}


?>
<pre>
<span class="sc1">&lt;script</span><span class="sc8"> </span><span class="sc3">src</span><span class="sc8">=</span><span class="sc6">"<?php echo $js_box; ?>"</span><span class="sc1">&gt;&lt;/script&gt;</span>
</pre>
				</div>
				Add This befor the end of Body Tag
				<button class="btn" data-clipboard-target="#copy3">
					<img class="copy" src="<?php echo $this->data['config']['url']."/"; ?>kchat/assets/images/clippy.svg" alt="Copy to clipboard">
				</button>
				<div class="code" id="copy3" >
<pre>
<span class="sc1">&lt;script&gt;</span><span class="sc40">
</span><span class="sc41">  </span><span class="sc46">data</span><span class="sc41"> </span><span class="sc50">=</span><span class="sc41"> </span><span class="sc50">{</span><span class="sc41">
    </span><span class="sc49">'url'</span><span class="sc50">:</span><span class="sc41"> </span><span class="sc49">'<?php echo $assets_url; ?>'</span><span class="sc50">,</span><span class="sc41">
    </span><span class="sc49">'key'</span><span class="sc50">:</span><span class="sc41"> </span><span class="sc49">'<?php echo $this->data['config']['key']; ?>'</span><span class="sc41">
  </span><span class="sc50">};</span><span class="sc41">
  </span><span class="sc46">kbox.init</span><span class="sc50">(</span><span class="sc46">data</span><span class="sc50">);</span><span class="sc41">
</span><span class="sc1">&lt;/script&gt;</span></pre>
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