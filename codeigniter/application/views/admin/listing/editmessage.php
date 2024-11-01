
<?php if(isset($_GET['id'])): ?>
<h1><?php echo __('Read message','sw_win'); ?> </h1>
<?php else: ?>
<?php exit('Add message is not supported'); ?>
<?php endif; ?>

<div class="bootstrap-wrapper">

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo __('Message data','sw_win'); ?></h3>
  </div>
  <div class="panel-body">
  
    <?php _form_messages(); ?>
  
    <form action="" class="form-horizontal" method="post">
      
      <div class="form-group <?php _has_error('listing_id'); ?>">
        <label for="input_listing_id" class="col-sm-2 control-label"><?php echo __('Listing','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="listing_id" value="<?php echo _fv('form_object', 'listing_id'); ?>" type="text" id="input_listing_id" class="form-control" readonly="" placeholder="<?php echo __('Listing','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('date_sent'); ?>">
        <label for="input_date_sent" class="col-sm-2 control-label"><?php echo __('Date sent','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="date_sent" value="<?php echo _fv('form_object', 'date_sent'); ?>" type="text" id="input_date_sent" class="form-control" readonly="" placeholder="<?php echo __('Date sent','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('email_sender'); ?>">
        <label for="input_email_sender" class="col-sm-2 control-label"><?php echo __('Email sender','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="email_sender" value="<?php echo _fv('form_object', 'email_sender'); ?>" type="text" id="input_email_sender" class="form-control" readonly="" placeholder="<?php echo __('Email sender','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('email_receiver'); ?>">
        <label for="input_email_receiver" class="col-sm-2 control-label"><?php echo __('Email receiver','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="email_receiver" value="<?php echo _fv('form_object', 'email_receiver'); ?>" type="text" id="input_email_receiver" class="form-control" readonly="" placeholder="<?php echo __('Email receiver','sw_win'); ?>"/>
        </div>
      </div>
        
      <div class="form-group <?php _has_error('phone'); ?>">
        <label for="input_phone" class="col-sm-2 control-label"><?php echo __('Phone','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="phone" value="<?php echo _fv('form_object', 'phone'); ?>" type="text" id="input_phone" class="form-control" readonly="" placeholder="<?php echo __('Phone','sw_win'); ?>"/>
        </div>
      </div>
        
      <div class="form-group <?php _has_error('message'); ?>">
        <label for="input_message" class="col-sm-2 control-label"><?php echo __('Message','sw_win'); ?></label>
        <div class="col-sm-10">
            <textarea name="message" id="input_message" class="form-control" readonly="" placeholder="<?php echo __('Message','sw_win'); ?>"><?php echo _fv('form_object', 'message'); ?></textarea>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('is_readed'); ?>">
        <label for="input_is_readed" class="col-sm-2 control-label"><?php echo __('Read by receiver','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="is_readed" value="1" type="checkbox" <?php echo _fv('form_object', 'is_readed', 'CHECKBOX'); ?>/>
        </div>
      </div>

      <hr />
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-primary"><?php echo __('Save', 'sw_win'); ?></button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo __('Live messages','sw_win'); ?></h3>
  </div>
  <div class="panel-body">
    <?php if(!empty($form_object->user_id_sender)):?>
    <div class="messages-section load-box">
        <div class="messages-box" id="messages-box" data-latest_id=''>
            <ul class="messages-box-list">
                <?php if(!empty($messages))foreach ($messages as $value):?>
                <li class="messages-box-item <?php echo ($value->user_id_sender!=$current_user_id) ? 'to':''?>" data-message_id="<?php echo $value->idmessages;?>">
                    <div class="auth-box">
                        <a href="<?php echo $value->profile_url;?>" class="auth-logo">
                            <img src="<?php echo $value->profile_image;?>" alt="">
                        </a>
                        <div class="auth-data"><a href="<?php echo $value->profile_url;?>" class="auth-name"><?php echo $value->display_name;?></a></div>
                    </div>
                    <div class="body">
                        <div class="mask-box">
                            <div class="message"><?php echo $value->message;?></div>
                            <div class="date date_live" title="date"><?php echo $value->date_sent;?></div>
                        </div>
                    </div>
                </li>
                <?php endforeach;?>
            </ul>
        </div>
        <div class="result_preload_indic"></div>
        <div class="send-box">
            <form action="" id="message-form">
                <input type="hidden" id="related_key" name='related_key' readonly value="inquiry_<?php echo $form_object->idinquiry; ?>">
                <input type="hidden" id="user_id_sender" name='user_id_sender' readonly value="<?php echo _fv('form_object', 'user_id_receiver'); ?>">
                <input type="hidden" id="user_id_receiver" name='user_id_receiver' readonly value="<?php echo _fv('form_object', 'user_id_sender'); ?>">
                <input type="hidden" id="email_receiver" name='email_receiver' readonly value="<?php echo _fv('form_object', 'email_sender'); ?>">
                <input type="hidden" id="email_sender" name='email_sender' readonly value="<?php echo _fv('form_object', 'email_receiver'); ?>">
            <div class="form-group form-group from-message">
                <textarea id="message" name="message" class="form-control" rows="3" placeholder="<?php echo esc_html__('Message', 'sw_win');?>"></textarea>
            </div>
            <div class="form-group form-group-submit">
                <button type="submit" class="btn btn-primary btn-sendmessage" value=""> <?php echo esc_html__('Send', 'sw_win');?> </button> <img class="ajax-indicator-masking" src="<?php echo plugins_url( SW_WIN_SLUG.'/assets' );?>/img/ajax-loader.gif" style="display: none;" />
            </div>
            </form>
        </div>
    </div>
    <?php else:?>
    <?php echo esc_html__('User is not registered', 'sw_win');?>
    <?php endif;?>
  </div>
</div>

</div>
    
<script>

/* 
    For custom field type elements, hide/show feature
    
    Example usage:
    css class: NOT-TREE, IS-TREE
    <div class="form-group NOT-TREE">
    <div class="form-group IS-TREE">
*/

jQuery(document).ready(function($) {
    reset_field_visibility();
    
    var field_type = $("select[name=type]").val();
    $(".NOT-"+field_type).hide();
    $(".IS-"+field_type).show();
        
    $("select[name=type]").change(function(){
        reset_field_visibility();
        
        var field_type = $(this).val();
        $(".NOT-"+field_type).hide();
        $(".IS-"+field_type).show();
    });
    
    function reset_field_visibility()
    {
        $("select[name=type] option" ).each(function( index ) {
            var field_type = $( this ).attr('value');
            
            $(".NOT-"+field_type).show();
            $(".IS-"+field_type).hide();
        });
    }

});



/* 
Created by: Kenrick Beckett

Name: Chat Engine
*/

var instanse = false;

function Chat () {
    var $ = jQuery;
    this.update = updateChat;
    this.send = sendChat;
}

//Updates the chat
function updateChat(){
    var $ = jQuery;
    if(!instanse){
       instanse = true;

        //updateChat();
        var data = {};
        data.related_key = "inquiry_<?php echo $form_object->idinquiry; ?>";
        data.last_message_id = $('.messages-section .messages-box > ul .messages-box-item:last-child').attr('data-message_id');
        $.extend( data, {
            "page": 'frontendajax_messagelive',
            "action": 'ci_action',
            "function": 'update'
        });

        $.post("<?php echo esc_url(admin_url( 'admin-ajax.php' ));?>", data, 
            function(data){
                    if(data.success){
                var html='';
                $.each(data.messages, function(index, value){
                var _o = $('.messages-section .messages-box-item[data-message_id="'+ value.idmessages+'"]');   
                if(_o &&  _o.length) {  
                        //_o.find('.date_live').html(value.date_interval)
                    } else {
                        var _cls = '';
                  
                        if(value.user_id_sender != '<?php echo $current_user_id;?>'){
                          _cls='to';
                        }
                        var html = '<li class="messages-box-item '+_cls+'" data-message_id="'+ value.idmessages+'"> \n\
                                        <div class="auth-box"> \n\
                                            <a href="'+ value.profile_url+'" class="auth-logo"> \n\
                                                <img src="'+ value.profile_image+'" alt=""> \n\
                                            </a> \n\
                                            <div class="auth-data"><a href="'+ value.profile_url+'" class="auth-name">'+ value.display_name+'</a></div> \n\
                                        </div> \n\
                                        <div class="body"> \n\
                                            <div class="mask-box"> \n\
                                                <div class="message">'+ value.message+'</div> \n\
                                                <div class="date date_live" title="date">'+ value.date_sent+'</div> \n\
                                            </div> \n\
                                        </div> \n\
                                    </li>';
                                                  
                        $('.messages-section .messages-box > ul').append(html);
                        $('.messages-section .messages-box').scrollTop($('.messages-section .messages-box > ul').height() + 250);
                    }
                })
            }
        });
        instanse = false;
       
    }
    else {
            setTimeout(updateChat, 150);
    }
}

//send the message
function sendChat(data, nickname)
{       
    var $ = jQuery;
    updateChat();
   
    var data = data;
    
    $.extend( data, {
        "page": 'frontendajax_messagelive',
        "action": 'ci_action',
        "function": 'send'
    });
    
    var load_indicator = jQuery('.messages-section #message-form').find('.ajax-indicator-masking');
    load_indicator.css('display', 'inline-block');
    jQuery('.messages-section #message-form').find('.btn-sendmessage').attr('disabled', 'disabled');
    
    $.post("<?php echo esc_url(admin_url( 'admin-ajax.php' ));?>", data, 
    function(data){
        if(data.success) {
            jQuery('.messages-section #message-form').find('#message').val('')
        } else {
            
        }
    }).success(function(){
        updateChat();
        load_indicator.css('display', 'none');
        jQuery('.messages-section #message-form').find('.btn-sendmessage').removeAttr('disabled');
    });
    
}


</script>

<script>
    var chat =  new Chat();
    
    jQuery(function($) {
        $('.messages-section .messages-box').scrollTop($('.messages-section .messages-box > ul').height() + 250);
        setInterval('chat.update()', 8000)
        
        $("#message-form").submit(function(e){
            e.preventDefault();
            var self = $(this);
            var text = self.find('message');

            var data_array= self.serializeArray();
            var data = {};
            $(data_array).each(function(index, obj){
                data[obj.name] = obj.value;
            });
            
            var listing_id = 1;
            chat.send(data, listing_id);
        })
        
        $("#message-form #message").keydown(function(e){
            var key = event.which;  
            if (key == 13) {
                
                if(event.shiftKey){
                } else {

                    e.preventDefault();
                    var self = $("#message-form");
                    var text = self.find('message');

                    var data_array= self.serializeArray();
                    var data = {};
                    $(data_array).each(function(index, obj){
                        data[obj.name] = obj.value;
                    });

                    var listing_id = 1;
                    chat.send(data, listing_id);
                }
                
            }  
        })
    });
</script>

<style>
    
    .messages-section .messages-box-item {
        margin-bottom: 25px;
    }
    
    .messages-section .messages-box-item .auth-box {
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-flex-wrap: wrap;
            -ms-flex-wrap: wrap;
                flex-wrap: wrap;
        -webkit-align-items: center;
        align-items: center;
    }
    
    .messages-section .messages-box-item .auth-box .auth-logo {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        overflow: hidden;
    }
    
    .messages-section .messages-box-item .auth-box .auth-logo img {
        width: 100%;
        height: 100%;
        -webkit-object-fit: cover;
        object-fit: cover;
        -webkit-object-position: center center;
        object-position: center center;
    }
    
    .messages-section .messages-box-item .auth-box .auth-data {
        padding: 5px 15px;
    }
    
    .messages-section .messages-box-item .auth-box .auth-data {
        color: #092c61;
        font-size: 16px;
        font-weight: 700;
        text-transform: capitalize;
    }
    
    .messages-section .messages-box-item .auth-box .auth-data a {
        color: #092c61;
        text-decoration: none;
    }
    
    .messages-section .messages-box-item .auth-box .auth-data a[href="#"] {
        cursor: default;
    }
    
    .messages-section .messages-box-item .body {
        padding-left: 60px;
    }
        
    .messages-section .messages-box-item .body .mask-box {
        display: inline-block;
        background: #eee;
        padding: 15px 20px;
        border-radius: 0 18px 18px 18px;
        position: relative;
    }
    
    .messages-section .messages-box-item .body .mask-box::after {
        content: '';
        position: absolute;
        left: 7px;
        bottom: 100%;
        border: 9px solid transparent;
        border-bottom: 8px solid #eeeeee;
    }
    
    .messages-section .messages-box-item.to .body .mask-box {
        background: #3399CC;
        color: #fff;
    }
    
    .messages-section .messages-box-item.to .body .mask-box:after {
        border-bottom: 8px solid #3399CC;
    }
    
    .messages-section .messages-box-item .body .mask-box .date {
        margin-top: 5px;
        font-size: 11px;
    }
    
    .messages-section .messages-box {
        max-height: 500px;
        overflow-y: scroll;
    }
</style>