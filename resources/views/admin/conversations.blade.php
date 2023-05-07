@extends('admin.master')

@section('title', 'Conversations\'s')

@section('header')
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/kchat.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet" />
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.bundle.min.js"></script>
	<link rel="stylesheet" href="//cdn.materialdesignicons.com/3.7.95/css/materialdesignicons.min.css">
@endsection

@section('body')
<div class="col-md-10 pt-3">
            <div class="card shadow-sm rounded bg-white mb-3">
                <!-- ---------------------------------------------------------------------------- -->
                 <table class="table"style="width:100%">
                    <thead>
                       <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>
                                <select class="pages mb-3 float-right">
                                    @foreach($pages as $page)
                                    <option value="{{ $page }}" >{{ $page }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <div class="dropdown show">
                                   <a href="#" data-toggle="dropdown" data-display="static">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal align-middle">
                                         <circle cx="12" cy="12" r="1"></circle>
                                         <circle cx="19" cy="12" r="1"></circle>
                                         <circle cx="5" cy="12" r="1"></circle>
                                      </svg>
                                   </a>
                                   <div class="dropdown-menu dropdown-menu-right">
                                      <a class="dropdown-item" onclick="SelectAll()" >Select All</a>
                                      <a class="dropdown-item" onclick="delete_convos()" >Delete</a>
                                   </div>
                                </div>
                            </th>
                       </tr>
                    </thead>
                    <thead>
                       <tr>
                          <th>#</th>
                          <th>Name</th>
                          <th>Members</th>
                          <th>Created at</th>
                          <th>Action</th>
                       </tr>
                    </thead>
                    <tbody id="member_table" >
                    @foreach($conversations as $conversation)
                       <tr class="select member" id="{{ $conversation->id }}" >
                          <td><a href="/messages?chat={{ $conversation->id }}" ><img src="{{ $conversation->photo }}" width="32" height="32" class="rounded-circle my-n1" alt="[Photo]" onerror="this.onerror=null; this.src='/logo/KChat.svg';"/></a></td>
                          <td><a href="/messages?chat={{ $conversation->id }}" >{{ $conversation->name }}</a></td>
                          <td>{{ $conversation->members }}</td>
                          <td class="timestamp" >{{ $conversation->created_at }}</span></td>
                          <td>
                            <div class="dropdown show">
                               <a href="#" data-toggle="dropdown" data-display="static">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal align-middle">
                                     <circle cx="12" cy="12" r="1"></circle>
                                     <circle cx="19" cy="12" r="1"></circle>
                                     <circle cx="5" cy="12" r="1"></circle>
                                  </svg>
                               </a>
                               <div class="dropdown-menu dropdown-menu-right">
                                  <a class="dropdown-item" data-convoid="{{ $conversation->id }}" onclick="delete_convo({{ $conversation->id }});" >Delete</a>
                               </div>
                            </div>
                          </td>
                       </tr>
                    @endforeach
                    </tbody>
                    <thead>
                       <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>
                                <select class="pages mb-3 float-right">
                                    @foreach($pages as $page)
                                    <option value="{{ $page }}" >{{ $page }}</option>
                                    @endforeach
                                </select>
                            </th>
                       </tr>
                    </thead>
                 </table>
            </div>
        </div>
@endsection
				  
@section('script')
@endsection
