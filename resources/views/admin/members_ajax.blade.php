@foreach($users as $user)
   <tr class="select member" id="{{ $user->id }}" >
      <td><img src="{{ $user->photo }}" width="32" height="32" class="rounded-circle my-n1" alt="[Photo]" onerror="this.onerror=null; this.src='/logo/KChat.svg';"></td>
      <td>{{ $user->first_name }} {{ $user->last_name }}</td>
      <td>{{ $user->department }}</td>
      <td>{{ $user->email }}</td>
      <td><span class="badge bg-{{ $user->status }}">{{ $user->status }}</span></td>
   </tr>
@endforeach
<script>

    json = @json($jsonusers);
    
    $('.select').on('click', function() {
    //$(document).on('click', '.select', function() {

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