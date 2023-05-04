
if(localStorage.getItem('selected') == null){
    localStorage.setItem('selected', "");
}

//Unset

function unset(arr,value){
	var index = arr.indexOf(value);
	if (index !== -1) {
		arr.splice(index, 1);
	}
	return arr
}

// Hide submenus
$('#body-row .collapse').collapse('hide'); 

// Collapse/Expand icon
$('#collapse-icon').addClass('fa-angle-double-left'); 

// Collapse click
$('[data-toggle=sidebar-colapse]').click(function() {
    SidebarCollapse();
});

function SidebarCollapse () {
    $('.menu-collapsed').toggleClass('d-none');
    $('.sidebar-submenu').toggleClass('d-none');
    $('.submenu-icon').toggleClass('d-none');
    $('#sidebar-container').toggleClass('sidebar-expanded sidebar-collapsed');
    
    // Treating d-flex/d-none on separators with title
    var SeparatorTitle = $('.sidebar-separator-title');
    if ( SeparatorTitle.hasClass('d-flex') ) {
        SeparatorTitle.removeClass('d-flex');
    } else {
        SeparatorTitle.addClass('d-flex');
    }
    
    // Collapse/Expand icon
    $('#collapse-icon').toggleClass('fa-angle-double-left fa-angle-double-right');
}

/*
---------------------------------------------------------------------
Ajax call
---------------------------------------------------------------------
*/

$( "[ajax_post]" ).on( "click", function () {
	
		form = $(this).attr('form');
		
		row = $('.' + form).get();
		
        let Data = new FormData();

		Data.append('_token',$('meta[name="csrf_token"]').attr('content'));
		
		for (let i = 0; i < row.length; i++) {
			if(row[i].type == 'file'){
				Data.append(row[i].id, $('#'+row[i].id)[0].files[0]);
			}else{
				Data.append(row[i].id, row[i].value);
			}
		}
		
		//console.log(Data);
		
		$.ajax({
			type: "POST",
			url: $(this).attr('action'),
			data: Data,
			processData: false,
			contentType: false,
			success: function(result){
				location.reload();
			},
			error: function(result){
				alert_msg = [];
				errors = JSON.parse(result.responseText).errors;
				Object.values(errors).forEach(val => {
					alert_msg.push(val[0]);
				});
				$('#' + form + '-error').html(alert_msg.join("<br>")).css("display", "block");
			}
		});
		
});

/*
---------------------------------------------------------------------
Submit on Enter
---------------------------------------------------------------------
*/

$('input').keypress(function(event){
	var keycode = (event.keyCode ? event.keyCode : event.which);
	if(keycode == '13'){
		row = $(this).attr('class').split(" ");
		for (let i = 0; i < row.length; i++) {
			var val = String(row[i]).trim();
			selector = '[form=' + val + ']';
			if($(selector).length){
				console.log(selector);
				el = $(selector);
				el.click();
			}
		}	
	}
});

/*
---------------------------------------------------------------------
Pagination
---------------------------------------------------------------------
*/

function getUrlVars()
{
    var vars = {}, hash;
	if(window.location.href.indexOf('?') == -1)
	{
		return vars;
	}
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars[hash[0]] = hash[1];
    }
    return vars;
}

page = getUrlVars()["page"];
$('.pages option[value='+page+']').attr('selected','selected');

$(".pages").change(function(){
	$para = getUrlVars();
	$para['page'] = this.value;
    
    if($('#Member-rearch').length){
        $para['ms'] = $('#Member-rearch').val();
    }
    
	$url = window.location.href.split('?');
	//console.log($url[0] + "?" + $.param($para));
	window.location.href = $url[0] + "?" + $.param($para);
});

/*
---------------------------------------------------------------------
To Select
---------------------------------------------------------------------
*/

var control = false;
$(document).on('keyup keydown', function(e) {
  control = e.ctrlKey;
});

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

/*
---------------------------------------------------------------------
Select All
---------------------------------------------------------------------
*/

function SelectAll(){
	
	$('.select').addClass("selected");

	id = localStorage.getItem('selected').split(",");

	id.sort();
  
	id = $.unique(id);
  
	id = $.grep(id, function(value) {
	  return $.trim(value).length > 0;
	});

	$('.selected').each( function () {
		id.push($(this).prop('id'));
	});

	id.sort();

	id = $.unique(id);

	localStorage.setItem('selected', id.join(","));
    
    setSelectedCount();
}


/*
---------------------------------------------------------------------
Get id of selected options
---------------------------------------------------------------------
*/

function getSelectedID(){

	/*
	id = [];

	$('.selected').each( function () {
		id.push($(this).prop('id'));
	});
	*/
	
	arr = localStorage.getItem('selected').split(",");
    localStorage.removeItem('selected')
    return arr;
}

/*
---------------------------------------------------------------------
Get id of selected options
---------------------------------------------------------------------
*/

function __post(url,posts){
	Data = {};
	Data['ids'] = posts;
	Data['_token'] = $('meta[name="csrf_token"]').attr('content');
	$.ajax({
		type: "POST",
		url: url,
		data: Data,
		success: function(result){
			location.reload();
		}
	});
}

/*
---------------------------------------------------------------------
to get relative time
---------------------------------------------------------------------
*/

function getRelativeTime(dateTime) {

		if(dateTime == null){
			return '';
		}
		
	  const inputDate = new Date(dateTime.replace(' ', 'T') + 'Z');
	  const currentDate = new Date();

	  const timeDiffInSeconds = Math.floor((currentDate - inputDate) / 1000);

	  const seconds = timeDiffInSeconds % 60;
	  const minutes = Math.floor(timeDiffInSeconds / 60) % 60;
	  const hours = Math.floor(timeDiffInSeconds / 3600) % 24;
	  const days = Math.floor(timeDiffInSeconds / (3600 * 24));

	  if (days > 0) {
		return `${days} day${days > 1 ? 's' : ''} ago`;
	  } else if (hours > 0) {
		return `${hours} hour${hours > 1 ? 's' : ''} ago`;
	  } else if (minutes > 0) {
		return `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
	  } else {
		return `${seconds} second${seconds > 1 ? 's' : ''} ago`;
	  }
}

const elements = $(".timestamp");

// Loop through each element and update its content
elements.each(function() {
  const currentContent = $(this).html();
  const updatedContent = getRelativeTime(currentContent); // Example update

  // Set the updated content to the same class
  $(this).html(updatedContent);
});

// Remove Blank values from cookie
localStorage.setItem('selected',localStorage.getItem('selected').split(","), function(value) {
    return $.trim(value).length > 0;
});

//Set count of selected
function setSelectedCount(){
    if($.isArray(localStorage.getItem('selected').split(","))){
        $('#Selected').html(localStorage.getItem('selected').split(",").length);
        if(localStorage.getItem('selected').split(",")[0] == ''){
            $('#Selected').html(0);
        }
    } 
}

setSelectedCount();
