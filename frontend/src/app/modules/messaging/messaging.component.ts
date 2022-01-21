import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { MessagingService } from 'src/app/core/_services/messaging.service';
import { Messages } from 'src/app/core/_objects/messages';
import { HostPath } from 'src/app/core/shared/host_path';
import { AuthenticationService } from 'src/app/core/_services';
import { User } from 'src/app/core/_objects';

declare var $: any;

declare global {
  interface Window { last_click: any; }
}

@Component({
  selector: 'app-messaging',
  templateUrl: './messaging.component.html',
  styleUrls: ['./messaging.component.css'],
  encapsulation: ViewEncapsulation.None
})
export class MessagingComponent implements OnInit {

  GroupMessage: Messages;
  GroupMessages: Messages[];
  private path: HostPath = new HostPath;
  timer: any;
  user: User = new User();
  constructor(
    private messagesServ: MessagingService,
    private authServ: AuthenticationService,
  ) { }

  
  get_group_messages(): void {
    this.messagesServ.read_group_messages()
    .subscribe(GroupMessage => this.GroupMessages = GroupMessage);
  }
  // read_group_messages
  ngOnInit(): void {
    var user = this.authServ.getUser();
    // Get Grouped Messages Per Conversion At The Left Sidebar
    this.get_group_messages();
    
    var run = false;
    $(document).ready(() => {
      // User Global Path Variable
      var path = this.path.host_path;

        // On Click Function to Grouped Messages
        $(document).on("click", ".message", function() {

        var unread = $(this).find('span.from');
        if ($(unread).hasClass('unread')) {
          $(unread).removeClass('unread');
        }
        // Keep the Last Clicked Conversion to Refresh the messages
        window.last_click = $(this);
        refresh();
        // Get Receiver id From data Attribute
        var receiver = $(this).data('receiver');
        // Get Product id From data Attribute
        var productid = $(this).data('productid');
        // Get Sender id From data Attribute
        var sender = $(this).data('sender');
        // Get Login in User Id From data Attribute
        var luserid = $(this).data('luserid');
        // Get Message Ttiel From data Attribute
        var title = $(this).find('.from').text();
        // Get All Messages Ids
        var messagesIds = $(this).data('mesagesids');

        // Clear Message Ids cause we need to change status one time
        $(this).data('mesagesids', '');
        $(this).attr('data-mesagesids', '');
        
        // Keep Button to Variable and passed The attribute data for the send button
        var btn = $('.send-btn');
        
        // Add Receiver Attribute ID to btn
        $(btn).data('receiver', receiver);
        $(btn).attr('data-receiver', receiver);
        // Add Product Attribute ID to btn
        $(btn).data('productid', productid);
        $(btn).attr('data-productid', productid);
        // Add Sendear Attribute ID to btn
        $(btn).data('sender', sender);
        $(btn).attr('data-sender', sender);
        // Add Login User Attribute ID to btn
        $(btn).data('luserid', luserid);
        $(btn).attr('data-luserid', luserid);
        // Add Title to btn
        $(btn).data('title', title);
        $(btn).attr('data-title', title);
        // Add Messages Ids to btn
        $(btn).data('mesagesids', messagesIds);
        $(btn).attr('data-mesagesids', messagesIds);

        // Call Function readMessages and passed variables to function
        readMessages(receiver,productid,sender, luserid, messagesIds);

      });
      // On Click Function to Remove Message
      $(document).on("click", ".remove", function() {
        // Get Message Attribute id
        var id = $(this).data('id');
          $.ajax({
            type: 'POST',  // Method Post
            dataType: 'json', // Ajax datatype Json
            url: path + 'backend/api/messages/delete.php', // Add The API Url
            data: JSON.stringify({"message_id" : id}), //Stringify The Data
            success: function(response) {
              // Refresh The Messages Window
              $(window.last_click).click();
            }
          });
      })

      $(document).on("click", ".send-btn", function() {
        // Get Receiver id From data Attribute
        var receiver = $(this).data('receiver');
        // Get Product id From data Attribute
        var productid = $(this).data('productid');
        // Get Sender id From data Attribute
        var sender = $(this).data('sender');
        // Get Login in user id From data Attribute
        var luserid = $(this).data('luserid');
        // Get Message Text
        var message = $('.message-text').val();
        // Get Title
        var title = $(this).data('title');
        // Get All Messages Ids
        var messagesIds = '';
        // Create An array to send it to API
        var array = {'receiver_user_id' : sender, 'product_id' : productid, 'sender_user_id' : receiver, 'message_text' : message, 'message_title' : title};
          $.ajax({
            type: 'POST', // Method Post
            dataType: 'json', // Ajax datatype Json
            url: path + 'backend/api/messages/create.php', // Add The API Url
            data: JSON.stringify(array), //Stringify The Data
            success: function(response) {
            }
          });
          // Clear Message Text On Send
          $('.message-text').val('');
          var html = '';
          
          html = '<div class="message-holder">'+
          '<label class="uname blue"><i class="fa fa-comments" aria-hidden="true"></i> ' + user.user_name + ': </label>'+
          '<span> ' + message + '</span>'+
          '<p><span data-id="" class="remove" onclick="return confirm(\'Are you sure?\')"><i aria-hidden="true" class="fa fa-trash"></i></span></p>'+
        '</div>';
          $('.content-area .user-messages').prepend(html);
      });


      function readMessages(receiver,productid,sender, luserid, messagesIds) {
        // Create An array to send it to API 
        var array = {'receiver_user_id' : receiver, 'product_id' : productid, 'sender_user_id' : sender};
        // Update All Messages from conversation
        if (messagesIds != '') { // Check if ids is empty
          $.ajax({
            type: 'POST', // Method Post
            dataType: 'json', // Ajax datatype Json
            url: path + 'backend/api/messages/update_messages_status.php', // Add The API Url
            data: JSON.stringify({"messages_ids" : messagesIds}), //Stringify The Data
            success: function(response) {
              console.log(response);
              }
            });
          }
          // Use JQuery Ajax Call to get data from the api
          $.ajax({
            type: 'POST', // Method Post
            dataType: 'json', // Ajax datatype Json
            url: path + 'backend/api/messages/get_conversation_messages.php', // Add The API Url
            data: JSON.stringify(array), //Stringify The Data
            success: function(response) {
                // Read The Repsonse From API
                var string = JSON.stringify(response);
                // Parse The Data
                var obj = JSON.parse(string);
                var html = '' //Clean Local Variables
                // Each Data As Object 
                $(obj).each(function(i){
                  // Check If is not undefined 
                  if (obj[i] != undefined) {
                    // Append the Variable Html check from the message object to sender id if is the same with login user id to add blue color and light blue
                    //  Add Sender Name
                    // Add Message Text
                    // Check if messages is on login user so we can add the delete Btn
                    html += '<div class="message-holder">'+
                          '<label class="uname ' + (luserid ==  obj[i].sender_user_id ? "blue" : "lblue") + '"><i class="fa fa-comments" aria-hidden="true"></i> ' + obj[i].sender_name + ': </label>'+
                          '<span> ' + obj[i].message_text + '</span>'+
                          '<p>'+(luserid ==  obj[i].sender_user_id ? '<span data-id="'+obj[i].message_id+'" class="remove" onclick="return confirm(\'Are you sure?\')"><i aria-hidden="true" class="fa fa-trash"></i></span>' : '')+'</p>'+
                        '</div>';
                  }
                  i++;
                });
                // Clear Previous Messages
                $('.content-area .user-messages').html('');
                // Append The New Messages From HTML Variable inside for each
                $('.content-area .user-messages').html(html);
            }
          });	
      }
    });

    function refresh() {
      setTimeout(function(){$(window.last_click).click()}, 5000);
    }
    // Bind Enter (Keyboard BTN) to send messages
  	$('.message-text').bind('keypress', function(e) {
      if ($(this).val() == '') {
        return;
      }
      if(e.keyCode==13){
        $('.send-btn').click();
      }
    });
    
  }

}