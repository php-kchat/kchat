@extends('layouts.master')

@section('title', 'Setting\'s')

@section('header')
	<link href="/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/kchat.css" rel="stylesheet">
	<link href="/css/font-awesome.min.css" rel="stylesheet" />
	<script src="/js/jquery.min.js"></script>
	<script src="/js/bootstrap.bundle.min.js"></script>
	<link rel="stylesheet" href="//cdn.materialdesignicons.com/3.7.95/css/materialdesignicons.min.css">
@endsection

@section('body')
<div class="col-xl-10 pt-3">
<div class="card mb-4">
  <div class="card-header">Department's</div>
  <div class="card-body">
	<p>Add and Delete Department</p>
	<div class="mt-3"> <label class="small mb-1" for="twoFactorSMS">Add Department</label>
		<input class="form-control savedpt" id="adddepartment" placeholder="Department" >
		<button class="btn btn-primary mt-3" type="button" ajax_post action="/setting/savedpt" form="savedpt" >Add Department</button>
	</div>
	<div class="mt-3"> <label class="small mb-1" for="twoFactorSMS">Delete Department</label>
		<select class="form-control deletedpt" type="text" id="deletedepartment" >
			@foreach($departments as $department)
				<option value="{{ $department->department }}">{{ $department->department }}</option>
			@endforeach
		</select>
		<button class="btn btn-danger mt-3" type="button" ajax_post action="/setting/deletedpt" form="deletedpt" >Delete Department</button>
	</div>
  </div>
</div>
<div class="card mb-4">
  <div class="card-header">Timezone</div>
  <div class="card-body">
	<div class="mt-3"> <label class="small mb-1" for="twoFactorSMS">Timezone</label>
		<select class="form-control timezone" type="text" id="timezone" >
			@foreach($TimeZone as $tz)
				<option value="{{ $tz }}">{{ $tz }}</option>
			@endforeach
		</select>
		<button class="btn btn-primary mt-3" type="button" ajax_post action="/setting/timezone" form="timezone" >Update Timezone</button>
	</div>
  </div>
</div>
<!--div class="card mb-4">
   <div class="card-header">Security Preferences</div>
   <div class="card-body">
      <h5 class="mb-1">Account Privacy</h5>
      <p class="small text-muted">By setting your account to private, your profile information and posts will not be visible to users outside of your user groups.</p>
      <form>
         <div class="form-check"> <input class="form-check-input" id="radioPrivacy1" type="radio" name="radioPrivacy" checked=""> <label class="form-check-label" for="radioPrivacy1">Public (posts are available to all users)</label></div>
         <div class="form-check"> <input class="form-check-input" id="radioPrivacy2" type="radio" name="radioPrivacy"> <label class="form-check-label" for="radioPrivacy2">Private (posts are available to only users in your groups)</label></div>
      </form>
      <hr class="my-4">
      <h5 class="mb-1">Data Sharing</h5>
      <p class="small text-muted">Sharing usage data can help us to improve our products and better serve our users as they navigation through our application. When you agree to share usage data with us, crash reports and usage analytics will be automatically sent to our development team for investigation.</p>
      <form>
         <div class="form-check"> <input class="form-check-input" id="radioUsage1" type="radio" name="radioUsage" checked=""> <label class="form-check-label" for="radioUsage1">Yes, share data and crash reports with app developers</label></div>
         <div class="form-check"> <input class="form-check-input" id="radioUsage2" type="radio" name="radioUsage"> <label class="form-check-label" for="radioUsage2">No, limit my data sharing with app developers</label></div>
      </form>
   </div>
</div>
<div class="card mb-4">
  <div class="card-header">Two-Factor Authentication</div>
  <div class="card-body">
	 <p>Add another level of security to your account by enabling two-factor authentication. We will send you a text message to verify your login attempts on unrecognized devices and browsers.</p>
	 <form>
		<div class="form-check"> <input class="form-check-input" id="twoFactorOn" type="radio" name="twoFactor" checked=""> <label class="form-check-label" for="twoFactorOn">On</label></div>
		<div class="form-check"> <input class="form-check-input" id="twoFactorOff" type="radio" name="twoFactor"> <label class="form-check-label" for="twoFactorOff">Off</label></div>
		<div class="mt-3"> <label class="small mb-1" for="twoFactorSMS">SMS Number</label> <input class="form-control" id="twoFactorSMS" type="tel" placeholder="Enter a phone number" value="555-123-4567"></div>
	 </form>
  </div>
</div>
<div class="card mb-4">
  <div class="card-header">Delete Account</div>
  <div class="card-body">
	 <p>Deleting your account is a permanent action and cannot be undone. If you are sure you want to delete your account, select the button below.</p>
	 <button class="btn btn-danger-soft text-danger" type="button">I understand, delete my account</button>
  </div>
</div>
</div-->

@endsection
				  
@section('script')

@endsection
