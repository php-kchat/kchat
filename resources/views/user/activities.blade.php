@extends('user.master')

@section('title', 'Activitie\'s')

@section('header')
	<link href="/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/notification.css" rel="stylesheet">
	<link href="/css/font-awesome.min.css" rel="stylesheet" />
	<script src="/js/jquery.min.js"></script>
	<script src="/js/bootstrap.bundle.min.js"></script>
	<link rel="stylesheet" href="//cdn.materialdesignicons.com/3.7.95/css/materialdesignicons.min.css">
	<link href="/css/kchat.css" rel="stylesheet">
@endsection

@section('body')
<div class="col-md-10 pt-3">
            <div class="card shadow-sm rounded bg-white mb-3">
                <div class="card-header border-bottom p-3">
                    <h6 class="m-0 float-left">Activitie's</h6>
					<div class="btn-group float-right">
						<button type="button" class="btn btn-sm rounded" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="mdi mdi-dots-vertical"></i>
						</button>
						<div class="dropdown-menu dropdown-menu-right">
							<button class="dropdown-item" type="button" onclick="delete_activity()"></i> Delete</button>
							<button class="dropdown-item" type="button" onclick="SelectAll()"></i> Select All</button>
						</div>
					</div>
					<select class="pages float-right mr-3">
						@foreach($pages as $page)
						<option value="{{ $page }}" >{{ $page }}</option>
						@endforeach
					</select>
                </div>
				@foreach($infos as $info)
                <div class="card-body p-2">
                    <div class="pl-2 pr-2 d-flex align-items-center select border-bottom osahan-post-header" id="{{ $info->id }}" >
						@if(isset($info->photo))
                        <div class="dropdown-list-image mr-3">
                            <img class="rounded-circle" src="{{ $info->photo }}" alt="" />
                        </div>
						@endif
                        <div class="font-weight-bold mr-3">
                            <div class="text-truncate">{{ $info->title }}</div>
                            <div class="small">{{ $info->notification }}</div>
                        </div>
                        <span class="ml-auto mb-auto">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm rounded" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
									<input type="hidden" class="act{{ $info->id }}" id="id" value="{{ $info->id }}" />
                                    <button class="dropdown-item" type="button" ajax_post  data-msg="Are you sure you want to delete activities?" action="/activity/delete" form="act{{ $info->id }}" ><i class="mdi mdi-delete"></i> Delete</button>
                                </div>
                            </div>
                            <br />
                            <div class="text-right text-muted pt-1 timestamp">{{ $info->updated_at }}</div>
                        </span>
                    </div>
                </div>
				@endforeach
            </div>
        </div>
@endsection
				  
@section('script')
<script>
	function delete_activity(){
		kchat_alert("Are you sure you want to delete <strong>Activities</strong>?",(function(){__post('/activity/delete',getSelectedID());}));
	}
</script>
@endsection
