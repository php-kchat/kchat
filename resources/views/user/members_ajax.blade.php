@foreach($users as $user)
   <tr class="select member" id="{{ $user->id }}" >
      <td><img src="{{ $user->photo }}" width="32" height="32" class="rounded-circle my-n1" alt="[Photo]" onerror="this.onerror=null; this.src='/logo/KChat.svg';"></td>
      <td>{{ $user->first_name }} {{ $user->last_name }}</td>
      <td>{{ $user->department }}</td>
      <td>{{ $user->email }}</td>
      <td><span class="badge bg-{{ $user->status }}">{{ $user->status }}</span></td>
      <td style="position:relative;">
         <div class="dropdown">
            <a href="javascript:void(0)" class="row-action-menu">
               <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
               <a class="dropdown-item" onclick="startChatWith('{{ $user->id }}');">{{ __("lang.create-new-conversation") }}</a>
               <a class="dropdown-item" onclick="block_single_user('{{ $user->id }}');">{{ __("lang.block") }}</a>
               <a class="dropdown-item" onclick="unblock_single_user('{{ $user->id }}');">{{ __("lang.unblock") }}</a>
            </div>
         </div>
      </td>
   </tr>
@endforeach
<script>

    json = @json($jsonusers);
    
    $('.select').on('click', function(e) {
      if ($(e.target).closest('.dropdown').length) return;

      id = localStorage.getItem('selected').split(",");

      id.sort();

      id = $.unique(id);

      id = $.grep(id, function(value) {
        return $.trim(value).length > 0;
      });

      if (control) {
          if($(this).hasClass( "selected" )){
             $(this).removeClass("selected");
             unset(id,$(this).prop('id'));
          }else{
             $(this).addClass("selected");
             id.push($(this).prop('id'));
          }
      } else {
        // ?
      }
      id.sort();
      id = $.unique(id);
      localStorage.setItem('selected', id.join(","));
      setSelectedCount();
      
    });
    
    //Select all ids selected by user using cookie
    $('.select[id]').each(function() {
        selected = localStorage.getItem('selected').split(",");
        selected = $.grep(selected, function(value) {
            return $.trim(value).length > 0;
        });
        if ($.inArray($(this).attr('id'), selected) !== -1) {
            $(this).addClass("selected");
        }

    });
    
</script>