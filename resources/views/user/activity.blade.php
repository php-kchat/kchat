@extends('user.master')

@section('title', 'Member\'s')

@section('header')
	<link href="/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/kchat.css" rel="stylesheet">
	<link href="/css/activity.css" rel="stylesheet">
	<link href="/css/font-awesome.min.css" rel="stylesheet" />
	<script src="/js/jquery.min.js"></script>
	<script src="/js/bootstrap.bundle.min.js"></script>
	<link rel="stylesheet" href="//cdn.materialdesignicons.com/3.7.95/css/materialdesignicons.min.css">
@endsection

@section('body')
<div class="col-xl-10 pt-3">
<div class="card mb-4">
<div class="recent-act"><div class="activity-icon terques"> <i class="fa fa-check"></i></div><div class="activity-desk"><h2>1 Hour Ago</h2><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean porttitor vestibulum imperdiet</p></div><div class="activity-icon terques"> <i class="fa fa-list"></i></div><div class="activity-desk"><h2>2 Hour Ago</h2><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean porttitor vestibulum imperdiet</p></div><div class="activity-icon terques"> <i class="fa fa-envelope"></i></div><div class="activity-desk"><h2>3 Days Ago</h2><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean porttitor vestibulum imperdiet</p></div></div>
</div>
</div>
@endsection
				  
@section('script')

@endsection
