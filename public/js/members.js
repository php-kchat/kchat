
function delete_users(){
	kchat_alert("Are you sure you want to <strong>delete</strong> users?",(function(){__post('/members/delete_users',{'ids':{'ids':getSelectedID()}});}));
}

function set_inactive_users(){
	kchat_alert("Are you sure you want to set <strong>inactive</strong>?",(function(){__post('/members/set_inactive_users',{'ids':getSelectedID()});}));
}

function set_active_users(){
	kchat_alert("Are you sure you want to set <strong>active</strong>?",(function(){__post('/members/set_active_users',{'ids':getSelectedID()});}));
}

function block_users(){
	kchat_alert("Are you sure you want to <strong>block</strong> users?",(function(){__post('/members/block_users',{'ids':getSelectedID()});}));
}

function unblock_users(){
	kchat_alert("Are you sure you want to <strong>unblock</strong> users?",(function(){__post('/members/unblock_users',{'ids':getSelectedID()});}));
}

function NewConversation(){
	kchat_alert("Are you sure you want to start new <strong>Conversation</strong>?",(function(){
            Data = {};
            var singleUserId = $('#createnewconversatoin').data('chat-user-id');
            if (singleUserId) {
                Data['ids'] = [singleUserId];
                $('#createnewconversatoin').removeData('chat-user-id');
            } else {
                Data['ids'] = getSelectedID();
            }
            Data['grpname'] = $('#grpname').val();
            __post('/members/newconversation',Data);
        }));
}

function revoke_admins(){
	kchat_alert("Are you sure you want to revoke <strong>admin privileges</strong>?",(function(){__post('/members/revokeadmin',{'ids':getSelectedID()});}));
}

function make_admins(){
	kchat_alert("Are you sure you want to grant <strong>admin privileges</strong>?",(function(){__post('/members/makeadmin',{'ids':getSelectedID()});}));
}

// Per-row actions
function startChatWith(id){
	// Store the user id temporarily and show the modal for conversation name
	$('#createnewconversatoin').data('chat-user-id', id);
	$('#createnewconversatoin').modal('show');
}

function delete_single_user(id){
	kchat_alert("Are you sure you want to <strong>delete</strong> this user?",(function(){__post('/members/delete_users',{'ids':{'ids':[id]}});}));
}

function set_inactive_single_user(id){
	kchat_alert("Are you sure you want to set <strong>inactive</strong>?",(function(){__post('/members/set_inactive_users',{'ids':[id]});}));
}

function set_active_single_user(id){
	kchat_alert("Are you sure you want to set <strong>active</strong>?",(function(){__post('/members/set_active_users',{'ids':[id]});}));
}

function block_single_user(id){
	kchat_alert("Are you sure you want to <strong>block</strong> this user?",(function(){__post('/members/block_users',{'ids':[id]});}));
}

function unblock_single_user(id){
	kchat_alert("Are you sure you want to <strong>unblock</strong> this user?",(function(){__post('/members/unblock_users',{'ids':[id]});}));
}

function make_single_admin(id){
	kchat_alert("Are you sure you want to grant <strong>admin privileges</strong>?",(function(){__post('/members/makeadmin',{'ids':[id]});}));
}

function revoke_single_admin(id){
	kchat_alert("Are you sure you want to revoke <strong>admin privileges</strong>?",(function(){__post('/members/revokeadmin',{'ids':[id]});}));
}

function delete_user(){
	kchat_alert("Are you sure you want to <strong>delete</strong> users?",(function(){__post('/members/delete_users',[$('#m_user').val()]);}));
}

function set_inactive_user(){
	kchat_alert("Are you sure you want to set <strong>inactive</strong>?",(function(){__post('/members/set_inactive_users',[$('#m_user').val()]);}));
}

function set_active_user(){
	kchat_alert("Are you sure you want to set <strong>active</strong>?",(function(){__post('/members/set_active_users',[$('#m_user').val()]);}));
}

function block_user(){
	kchat_alert("Are you sure you want to <strong>block</strong> user?",(function(){__post('/members/block_users',[$('#m_user').val()]);}));
}

function unblock_user(){
	kchat_alert("Are you sure you want to <strong>unblock</strong> user?",(function(){__post('/members/unblock_users',[$('#m_user').val()]);}));
}

function make_admin(){
	kchat_alert("Are you sure you want to grant <strong>admin privileges</strong>?",(function(){__post('/members/makeadmin',[$('#m_user').val()]);}));
}

function revoke_admin(){
	kchat_alert("Are you sure you want to revoke <strong>admin privileges</strong>?",(function(){__post('/members/revokeadmin',[$('#m_user').val()]);}));
}

$(document).ready(function(){
    
    // Manual dropdown toggle for row action menus using inline style
    $(document).on('click', '.row-action-menu', function(e) {
        e.stopPropagation();
        e.stopImmediatePropagation();
        e.preventDefault();
        var $menu = $(this).next('.dropdown-menu');
        var isVisible = ($menu.css('display') === 'block');
        // Close all open row menus
        $('.row-action-menu').next('.dropdown-menu').hide();
        // Toggle current
        if (!isVisible) {
            $menu.show();
        }
        return false;
    });
    
    // Close dropdown when clicking anywhere else
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.row-action-menu').length && !$(e.target).closest('.dropdown-menu').length) {
            $('.row-action-menu').next('.dropdown-menu').hide();
        }
    });
    
    // Handle dropdown item click
    $(document).on('click', '.dropdown .dropdown-menu .dropdown-item', function(e) {
        e.stopPropagation();
        e.stopImmediatePropagation();
        $('.row-action-menu').next('.dropdown-menu').hide();
    });
    
    $(document).on('dblclick', '.member', function() {
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

    $(document).on('keyup', '#Member-rearch', function() {

        Data = {};
        
        Data['_token'] = $('meta[name="csrf_token"]').attr('content');
        
        Data['ms'] = $(this).val();
        
        $.ajax({
            type: "POST",
            url: '/ajax_members',
            data: Data,
            success: function(result){
                $("#member_table").html(result);
            },
            error: function(result){
                
            }
        });
    });
});