
//initialize instance
var enjoyhint_instance = new EnjoyHint({});

if(hint === ''){
	hint = 'main';
}

var enjoyhint_script_steps = [];
//Dashboard
enjoyhint_script_steps.main = [
    {
        "next #chart": 'No of guest started chating from last week.',
    },
    {
        "next #map": "location of online Guests.",
    },
    {
        "next #unread": "no. of unread messages.",
    },
    {
        "next #notification": "click to see notifications.",
    },
    {
        selector: "selector",
		event:'click',
		description:'',
		onBeforeStart:function(){
		  window.location = purl + "/smtp?start";
		}
    }
];
//SMTP Configuration
enjoyhint_script_steps.smtp = [
    {
        "next #smtp_conf": 'feel All required field to configure smtp details and press this button.<br>if you want to send verification link to user via. email.',
    },
    {
        selector: "selector",
		event:'click',
		description:'',
		onBeforeStart:function(){
		  window.location = purl + "/users/cuser?start";
		}
    }
];
//create user
enjoyhint_script_steps.userscuser = [
    {
        "next #createuser": 'Feel all required field to create new user and press this button.<br>copy verification link and send to user for verification.<br>Note. If smtp configured mail will send automatically',
    },
    {
        selector: "selector",
		event:'click',
		description:'',
		onBeforeStart:function(){
		  window.location = purl + "/users/groups?start";
		}
    }
];
//start chat
enjoyhint_script_steps.usersgroups = [
    {
        "next #usersgroups": 'List of all the users you created.<br>start charting by clicking on envelope icon.',
    },
    {
        "next #start_msg1": 'check box of users you want to include in group chat and click this button.',
    },
    {
        selector: "selector",
		event:'click',
		description:'',
		onBeforeStart:function(){
		  window.location = purl + "/users/ulist?start";
		}
    }
];


//start chat
enjoyhint_script_steps.usersulist = [
    {
        "next #usersulist": 'List of all the users you created.<br>you can delete user using garbage button.',
    },
    {
        selector: "selector",
		event:'click',
		description:'',
		onBeforeStart:function(){
		  window.location = purl + "/example?start";
		}
    }
];

//start chat
enjoyhint_script_steps.example = [
    {
        "next #example": 'how to set embedded code to your website ?',
    },
    {
        "next #KChat_logo": 'its a sample page of chat box on your website after embedding code.',
    },
    {
        selector: "selector",
		event:'click',
		description:'',
		onBeforeStart:function(){
		  window.location = purl + "/embed?start";
		}
    }
];

//start chat
enjoyhint_script_steps.embed = [
    {
        "next #copy1": 'Add this To Head Tag of Your Page.',
    },
    {
        "next #copy3": 'Add This befor the end of Body Tag.',
    },
    {
        selector: "selector",
		event:'click',
		description:'',
		onBeforeStart:function(){
		  window.location = purl + "/msgs?start";
		}
    }
];

//start chat
enjoyhint_script_steps.msgs = [
    {
        "next #msgscrl": 'message box.',
    },
    {
        "next .emojionearea": 'type Here.',
    },
    {
        "next #kchatchats": 'list of chats.',
    },
    {
        "next #online": 'list of online users.',
    },
    {
        selector: "selector",
		event:'click',
		description:'',
		onBeforeStart:function(){
		  window.location = purl + "/settings?start";
		}
    }
];

//start chat
enjoyhint_script_steps.settings = [
    {
        "next #adddept": 'add new department.',
    },
    {
        "next .jscolor": 'set colors of chat box on your website.',
    },
    {
        selector: "selector",
		event:'click',
		description:'',
		onBeforeStart:function(){
		  window.location = purl + "/users/glist?start";
		}
    }
];


//guest list
enjoyhint_script_steps.usersglist = [
    {
        "next #usersglist": 'List of all the guest.',
    },
    {
        selector: "selector",
		event:'click',
		description:'Update username and password',
		onBeforeStart:function(){
		  window.location = purl + "/users/profile";
		}
    }
];
//set script config
enjoyhint_instance.set(enjoyhint_script_steps[hint]);

//run Enjoyhint script
enjoyhint_instance.run();