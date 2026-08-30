@extends('admin.master')

@section('title', __("lang.chat-widget-setting"))

@section('header')
	<link href="/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/kchat.css" rel="stylesheet">
	<link href="/css/font-awesome.min.css" rel="stylesheet" />
	<script src="/js/jquery.min.js"></script>
	<script src="/js/bootstrap.bundle.min.js"></script>
	<link rel="stylesheet" href="//cdn.materialdesignicons.com/3.7.95/css/materialdesignicons.min.css">
	<style>
		.widget-card { border-left: 4px solid #007bff; transition: transform 0.2s; }
		.widget-card:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
		.embed-code { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; padding: 12px; font-family: 'Courier New', monospace; font-size: 13px; word-break: break-all; position: relative; }
		.embed-code .copy-btn { position: absolute; top: 8px; right: 8px; }
		.icon-preview { width: 40px; height: 40px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: #fff; font-size: 18px; }
		.widget-table td { vertical-align: middle; }
		.color-preview { width: 24px; height: 24px; border-radius: 50%; display: inline-block; border: 2px solid #dee2e6; }
		.token-text { font-family: monospace; font-size: 12px; color: #6c757d; max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: inline-block; }
	</style>
@endsection

@section('body')
<div class="col-md-10 pt-3">

<!-- Create / Edit Widget Card -->
<div class="card mb-4">
	<div class="card-header">
		<i class="fa fa-puzzle-piece mr-1"></i> {{ __("lang.create-widget") }}
	</div>
	<div class="card-body">
		<p class="text-muted small">Configure a chat widget to embed on your website. Visitors will be connected to the least-busy agent in the selected department.</p>
		
		<input type="hidden" class="widgetform" id="widget_id" value="" />
		
		<div class="row">
			<div class="col-md-6">
				<div class="form-group mb-3">
					<label class="small mb-1"><strong>{{ __("lang.widget-title") }}</strong></label>
					<input class="form-control widgetform" id="widget_title" placeholder="e.g. Chat with us" value="Chat with us" />
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group mb-3">
					<label class="small mb-1"><strong>{{ __("lang.widget-department") }}</strong></label>
					<select class="form-control widgetform" id="widget_department">
						<option value="">-- Select Department --</option>
						@foreach($departments as $dept)
							<option value="{{ $dept->department }}">{{ $dept->department }}</option>
						@endforeach
					</select>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-4">
				<div class="form-group mb-3">
					<label class="small mb-1"><strong>{{ __("lang.widget-icon") }}</strong></label>
					<select class="form-control widgetform" id="widget_icon">
						<option value="fa-comments" selected>💬 Comments</option>
						<option value="fa-comment">💭 Comment</option>
						<option value="fa-headphones">🎧 Headphones</option>
						<option value="fa-life-ring">⭕ Life Ring</option>
						<option value="fa-question-circle">❓ Question</option>
						<option value="fa-envelope">✉️ Envelope</option>
						<option value="fa-phone">📞 Phone</option>
						<option value="fa-support">🆘 Support</option>
					</select>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group mb-3">
					<label class="small mb-1"><strong>{{ __("lang.widget-color") }}</strong></label>
					<div class="d-flex align-items-center">
						<input type="color" class="form-control widgetform" id="widget_color" value="#007bff" style="width: 60px; height: 38px; padding: 2px;" />
						<span class="ml-2 small text-muted" id="color_hex">#007bff</span>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group mb-3">
					<label class="small mb-1"><strong>{{ __("lang.widget-position") }}</strong></label>
					<select class="form-control widgetform" id="widget_position">
						<option value="right" selected>Bottom Right</option>
						<option value="left">Bottom Left</option>
					</select>
				</div>
			</div>
		</div>
		
		<button class="btn btn-primary mt-2" type="button" ajax_post data-msg="Are you sure you want to save this widget?" action="/chat-widget/store" form="widgetform">
			<i class="fa fa-save mr-1"></i> {{ __("lang.create-widget") }}
		</button>
	</div>
</div>

<!-- Existing Widgets List -->
<div class="card mb-4">
	<div class="card-header">
		<i class="fa fa-list mr-1"></i> Your Chat Widgets
	</div>
	<div class="card-body">
		@if(count($widgets) == 0)
			<p class="text-muted text-center py-4">No widgets created yet. Create your first widget above.</p>
		@else
			<div class="table-responsive">
				<table class="table widget-table">
					<thead>
						<tr>
							<th>Widget</th>
							<th>Department</th>
							<th>{{ __("lang.embed-code") }}</th>
							<th class="text-center">{{ __("lang.action") }}</th>
						</tr>
					</thead>
					<tbody>
						@foreach($widgets as $widget)
						<tr>
							<td>
								<div class="d-flex align-items-center">
									<span class="icon-preview mr-2" style="background: {{ $widget->color }};">
										<i class="fa {{ $widget->icon }}"></i>
									</span>
									<div>
										<strong>{{ $widget->title }}</strong><br/>
										<span class="token-text" title="{{ $widget->token }}">{{ $widget->token }}</span>
									</div>
								</div>
							</td>
							<td><span class="badge badge-secondary">{{ $widget->department }}</span></td>
							<td>
								<div class="embed-code" id="embed-{{ $widget->id }}">
									&lt;script src="{{ $appUrl }}/widget/embed.js?token={{ $widget->token }}"&gt;&lt;/script&gt;
									<button class="btn btn-sm btn-outline-primary copy-btn" onclick="copyEmbed({{ $widget->id }})">
										<i class="fa fa-copy"></i>
									</button>
								</div>
							</td>
							<td class="text-center">
								<button class="btn btn-sm btn-outline-danger" onclick="deleteWidget({{ $widget->id }})">
									<i class="fa fa-trash"></i>
								</button>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		@endif
	</div>
</div>

</div>
@endsection

@section('script')

@endsection

@section('javascript')
<script type="text/javascript">

$('#widget_color').on('input', function(){
	$('#color_hex').text($(this).val());
});

function copyEmbed(id){
	var el = document.getElementById('embed-' + id);
	var text = el.innerText.replace('Copy', '').trim();
	// Build the actual script tag text
	var tempEl = document.createElement('textarea');
	tempEl.value = text;
	document.body.appendChild(tempEl);
	tempEl.select();
	document.execCommand('copy');
	document.body.removeChild(tempEl);
	
	var btn = el.querySelector('.copy-btn');
	btn.innerHTML = '<i class="fa fa-check"></i>';
	setTimeout(function(){ btn.innerHTML = '<i class="fa fa-copy"></i>'; }, 2000);
}

function deleteWidget(id){
	kchat_alert("Are you sure you want to <strong>delete</strong> this widget?", function(){
		__post('/chat-widget/delete', {'widget_id': id});
	});
}

</script>
@endsection
