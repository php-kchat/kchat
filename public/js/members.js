
function delete_users(){
	__post('/members/delete_users',getSelectedID());
}

function set_inactive_users(){
	__post('/members/set_inactive_users',getSelectedID());
}

function set_active_users(){
	__post('/members/set_active_users',getSelectedID());
}

function block_users(){
	__post('/members/block_users',getSelectedID());
}

function unblock_users(){
	__post('/members/unblock_users',getSelectedID());
}

function NewConversation(){
	__post('/members/newconversation',getSelectedID(),{
        'grpname' : $('#grpname').val()
    });
}

function delete_user(){
	__post('/members/delete_users',[$('#m_user').val()]);
}

function set_inactive_user(){
	__post('/members/set_inactive_users',[$('#m_user').val()]);
}

function set_active_user(){
	__post('/members/set_active_users',[$('#m_user').val()]);
}

function block_user(){
	__post('/members/block_users',[$('#m_user').val()]);
}

function unblock_user(){
	__post('/members/unblock_users',[$('#m_user').val()]);
}

$('.member').on('dblclick', function() {
	$('.m_photo').attr('src',json[$(this).attr('id')].photo);
	$('.m_about').text(json[$(this).attr('id')].about);
	$('.m_name').text(json[$(this).attr('id')].first_name + " " + json[$(this).attr('id')].last_name);
	$('.m_email').text(json[$(this).attr('id')].email);
	$('.m_department').text(json[$(this).attr('id')].department);
	$('.m_phone').text(json[$(this).attr('id')].phone);
	$('.m_status').text(json[$(this).attr('id')].status);
	$('.m_status').removeClass("bg-Active bg-Blocked bg-Inactive");
	$('.m_status').addClass("bg-"+json[$(this).attr('id')].status);
	$('#m_user').val($(this).attr('id'));
	$('.m_created_at').text(getRelativeTime(json[$(this).attr('id')].created_at));
	$('.m_updated_at').text(getRelativeTime(json[$(this).attr('id')].updated_at));
});