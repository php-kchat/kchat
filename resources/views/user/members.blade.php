
@extends('user.master')

@section('title', 'Member\'s')

@section('javascript')
<script src="js/members.js"></script>
@endsection

@section('header')
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/kchat.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet" />
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.bundle.min.js"></script>
	<link rel="stylesheet" href="//cdn.materialdesignicons.com/3.7.95/css/materialdesignicons.min.css">
	<style>
		.member-table .member { cursor: pointer; transition: background-color 0.15s ease; }
		.member-table .member:hover { background-color: #f0f4f9; }
		.member-table .member.selected { background-color: #e3f2fd; }
		.member-table th { font-size: 0.8rem; text-transform: uppercase; color: #6c757d; font-weight: 600; border-top: none; }
		.member-table td { vertical-align: middle; font-size: 0.875rem; }
		.row-action-menu { color: #6c757d; padding: 4px 8px; border-radius: 4px; display: inline-block; transition: background-color 0.15s ease; }
		.row-action-menu:hover { background-color: #e9ecef; color: #343a40; }
		.member-search { border-radius: 20px; padding: 6px 16px; font-size: 0.85rem; border: 1px solid #dee2e6; transition: border-color 0.15s ease; }
		.member-search:focus { border-color: #464dee; outline: none; box-shadow: 0 0 0 2px rgba(70,77,238,0.1); }
		.toolbar-row { display: flex; align-items: center; justify-content: space-between; padding: 8px 0; margin-bottom: 8px; border-bottom: 1px solid #f0f0f0; }
		.toolbar-left { display: flex; align-items: center; gap: 10px; }
		.badge-selected { background-color: #464dee; color: #fff; font-size: 0.75rem; padding: 4px 10px; border-radius: 12px; }
		.pages-select { border-radius: 4px; padding: 4px 8px; font-size: 0.8rem; border: 1px solid #dee2e6; }
		.profile-card .m_photo { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #f0f4f9; }
		.profile-card .card-header { border-bottom: 2px solid #464dee; }
		.profile-card .table th { font-size: 0.8rem; color: #6c757d; width: 35%; }
		.profile-card .table td { font-size: 0.85rem; }
		.dropdown-menu { box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: 1px solid #e9ecef; border-radius: 8px; padding: 6px 0; }
		.dropdown-item { font-size: 0.85rem; padding: 8px 16px; transition: background-color 0.1s ease; }
		.dropdown-item:hover { background-color: #f0f4f9; }
	</style>
@endsection

@section('body')
<div class="col-md-7 pt-3">
   <div class="card">
      <div class="card-header pb-0">
         <div class="card-actions float-right">
            <div class="dropdown show">
               <a href="#" data-toggle="dropdown" data-display="static">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal align-middle">
                     <circle cx="12" cy="12" r="1"></circle>
                     <circle cx="19" cy="12" r="1"></circle>
                     <circle cx="5" cy="12" r="1"></circle>
                  </svg>
               </a>
               <div class="dropdown-menu dropdown-menu-right">
                  <a class="dropdown-item" onclick="SelectAll()" ><i class="fa fa-check-square-o mr-2"></i>{{ __("lang.select-all") }}</a>
                  <a class="dropdown-item" onclick="block_users()" ><i class="fa fa-ban mr-2"></i>{{ __("lang.block") }}</a>
                  <a class="dropdown-item" onclick="unblock_users()" ><i class="fa fa-check-circle mr-2"></i>{{ __("lang.unblock") }}</a>
                  <a class="dropdown-item" data-toggle="modal" data-target="#createnewconversatoin" ><i class="fa fa-comments mr-2"></i>{{ __("lang.create-new-conversation") }}</a>
               </div>
            </div>
         </div>
         <h5 class="card-title mb-0"><i class="fa fa-users mr-2"></i>{{ __("lang.members") }}</h5>
      </div>
      <div class="card-body">
		<script>
			json = @json($jsonusers);
		</script>
         <div class="toolbar-row">
            <div class="toolbar-left">
               <span class="badge-selected" id="Selected">0 selected</span>
               <select class="pages pages-select">
                  @foreach($pages as $page)
                  <option value="{{ $page }}" >{{ $page }}</option>
                  @endforeach
               </select>
            </div>
            <input class="member-search" type="search" placeholder="{{ __("lang.search-using-mail") }}" id="Member-rearch" value="{{ $ms }}" >
         </div>
         <table class="table member-table" style="width:100%">
            <thead>
               <tr>
                  <th style="width:40px;"></th>
                  <th>{{ __("lang.name") }}</th>
                  <th>{{ __("lang.departement") }}</th>
                  <th>{{ __("lang.email") }}</th>
                  <th>{{ __("lang.status") }}</th>
                  <th style="width:40px;"></th>
               </tr>
            </thead>
            <tbody id="member_table" >
			@foreach($users as $user)
               <tr class="select member" id="{{ $user->id }}" >
                  <td><img src="{{ $user->photo }}" width="32" height="32" class="rounded-circle" alt="[Photo]" onerror="this.onerror=null; this.src='/logo/KChat.svg';"></td>
                  <td><strong>{{ $user->first_name }} {{ $user->last_name }}</strong></td>
                  <td class="text-muted">{{ $user->department }}</td>
                  <td class="text-muted">{{ $user->email }}</td>
                  <td><span class="badge bg-{{ $user->status }}">{{ $user->status }}</span></td>
                  <td style="position:relative;">
                     <div class="dropdown">
                        <a href="javascript:void(0)" class="row-action-menu">
                           <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                           <a class="dropdown-item" onclick="startChatWith('{{ $user->id }}');"><i class="fa fa-comment mr-2"></i>{{ __("lang.create-new-conversation") }}</a>
                           <a class="dropdown-item" onclick="block_single_user('{{ $user->id }}');"><i class="fa fa-ban mr-2"></i>{{ __("lang.block") }}</a>
                           <a class="dropdown-item" onclick="unblock_single_user('{{ $user->id }}');"><i class="fa fa-check-circle mr-2"></i>{{ __("lang.unblock") }}</a>
                        </div>
                     </div>
                  </td>
               </tr>
			@endforeach
            </tbody>
         </table>
		<div class="text-right mt-2">
			<select class="pages pages-select">
				@foreach($pages as $page)
				<option value="{{ $page }}" >{{ $page }}</option>
				@endforeach
			</select>
		</div>
      </div>
   </div>
</div>
<div class="col-md-3 pt-3">
   <div class="card profile-card">
      <div class="card-header">
         <div class="card-actions float-right">
            <div class="dropdown show">
               <a href="#" data-toggle="dropdown" data-display="static">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal align-middle">
                     <circle cx="12" cy="12" r="1"></circle>
                     <circle cx="19" cy="12" r="1"></circle>
                     <circle cx="5" cy="12" r="1"></circle>
                  </svg>
               </a>
               <div class="dropdown-menu dropdown-menu-right">
				  <input type="hidden" value="" id="m_user" />
                  <a class="dropdown-item" onclick="block_user()" ><i class="fa fa-ban mr-2"></i>{{ __("lang.block") }}</a>
                  <a class="dropdown-item" onclick="unblock_user()" ><i class="fa fa-check-circle mr-2"></i>{{ __("lang.unblock") }}</a>
               </div>
            </div>
         </div>
         <h5 class="card-title mb-0 m_name"><i class="fa fa-user mr-2"></i>[name]</h5>
      </div>
      <div class="card-body">
         <div class="text-center mb-3">
            <img class="m_photo" src="/logo/KChat.svg" onerror="this.src='/logo/KChat.svg'" alt="">
         </div>
         <div class="mb-3">
            <strong class="text-muted" style="font-size:0.8rem; text-transform:uppercase;">{{ __("lang.about-me") }}</strong>
            <p class="m_about mt-1" style="font-size:0.9rem;"></p>
         </div>
         <table class="table table-sm mb-0">
            <tbody>
               <tr>
                  <th>{{ __("lang.name") }}</th>
                  <td class="m_name"></td>
               </tr>
               <tr>
                  <th>{{ __("lang.department") }}</th>
                  <td class="m_department"></td>
               </tr>
               <tr>
                  <th>{{ __("lang.email") }}</th>
                  <td class="m_email"></td>
               </tr>
               <tr>
                  <th>Phone</th>
                  <td class="m_phone"></td>
               </tr>
               <tr>
                  <th>{{ __("lang.status") }}</th>
                  <td><span class="badge m_status"></span></td>
               </tr>
               <tr>
                  <th>{{ __("lang.created-at") }}</th>
                  <td><span class="timestamp badge m_created_at"></span></td>
               </tr>
               <tr>
                  <th>{{ __("lang.updated-at") }}</th>
                  <td><span class="timestamp badge m_updated_at"></span></td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>
</div>
<!-- Modal -->
<div class="modal fade" id="createnewconversatoin" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" ><i class="fa fa-comments mr-2"></i>Create Group</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="{{ __("lang.close") }}">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="input-group mb-0">
           <input id="grpname" type="text" class="form-control" placeholder="Enter Group Title"/>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("lang.close") }}</button>
        <button type="button" onclick="NewConversation()" class="btn btn-primary">Create Group</button>
      </div>
    </div>
  </div>
</div>
@endsection
				  
@section('script')

@endsection
