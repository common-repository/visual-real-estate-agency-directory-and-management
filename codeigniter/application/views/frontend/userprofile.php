<h2><?php echo __('Profile page', 'sw_win'); ?></h2>

<div class="row">

    <div class="col-xs-4">
        <div class="image-box">
            <img src="<?php echo sw_profile_image($user, 200); ?>" alt="" class="image" />
        </div>
    </div>

    <div class="col-xs-8">
        <?php if(!empty($user_meta['description'][0])): ?>
        <p><?php echo _ch($user_meta['description'][0], '-'); ?></p>
        <?php endif; ?>
        <p><?php echo __('Name', 'sw_win').': '._ch($user->display_name, '-'); ?></p>
        <p><?php echo __('Email', 'sw_win').': <a href="mailto:'._ch($user->user_email, '#').'">'._ch($user->user_email, '-').'</a>'; ?></p>
        
        <?php if(!empty($user->user_url)): ?>
        <p><?php echo __('Website', 'sw_win').': <a href="'._ch($user->user_url, '#').'">'._ch($user->user_url, '-').'</a>'; ?></p>
        <?php endif; ?>
    </div>

</div>

<div class="agent-listings-container">
<?php $this->load->view('frontend/userprofilelistings'); ?>
</div>

<script>


jQuery(document).ready(function($) {
    
    reloadElements();

    function reloadElements()
    {
        $('#results-profile .view-type').click(function () { 
          $(this).parent().find('.view-type').removeClass("active");
          $(this).addClass("active");
          return false;
        });
        
        $('#results-profile a.view-type:not(.active)').click(function(){
            search_result(0, false, false, false);
            return false;
        });
        
        $('#results-profile #search_order').change(function(){
            search_result(0, false, false, true);
            return false;
        });
        
        $('#results-profile .pagination a').click(function () { 
            
            var href = $(this).attr('href');
            
            var offset = getParameterByName('offset', href);
            
            search_result(offset, true, false, false);

            return false;
        });
        
    }

    function search_result(results_offset, scroll_enabled, save_only, load_map)
    {
        var selectorResults = '#results_top';
        
        // Order ASC/DESC
        var results_order = $('#results-profile #search_order').val();
        
        if (results_order === undefined || results_order === null) {
            results_order = 'idlisting DESC';
        }
        
        // View List/Grid
        var results_view = $('#results-profile .view-type.active').attr('ref');  
                
        if (results_view === undefined || results_view === null) {
            results_view = 'grid';
        }
        
        //Define default data values for search
        var data = {
            search_order: results_order,
            search_view: results_view,
            offset: results_offset,
            user_id: <?php echo $user->ID; ?>
        };
        
        <?php
            // get results page ID
            $page_link = agent_url($user);
        ?>
        
        var gen_url = generateUrl("<?php echo $page_link; ?>", data)+"#results-profile";
        
        <?php if(sw_is_page(sw_settings('user_profile_page'))): ?>
        
        $.extend( data, {
            "page": 'frontendajax_agentlisting',
            "action": 'ci_action'
        });
        
        $("#ajax-indicator").show();
        $.post('<?php echo admin_url( 'admin-ajax.php' ); ?>', data,
        function(data){

            $(selectorResults).parent().parent().html(data.html);
            reloadElements();
            
            $("#ajax-indicator").hide();
            if( scroll_enabled != false && !$(selectorResults).isInViewport() )
                $(document).scrollTop( $(selectorResults).offset().top );
            
            if ('history' in window && 'pushState' in history)
                history.pushState(null, null, gen_url);
            
        }, "json");
        
        <?php else: ?>

        window.location = gen_url;
        <?php endif; ?>

    }
    
    $.fn.isInViewport = function() {
        var elementTop = $(this).offset().top;
        var elementBottom = elementTop + $(this).outerHeight();
    
        var viewportTop = $(window).scrollTop();
        var viewportBottom = viewportTop + $(window).height();
    
        return elementBottom > viewportTop && elementTop < viewportBottom;
    };


});

function generateUrl(url, params) {
    var i = 0, key;
    for (key in params) {
        if (i === 0 && url.indexOf("?")===-1) {
            url += "?";
        } else {
            url += "&";
        }
        url += key;
        url += '=';
        url += params[key];
        i++;
    }
    return url;
}

function getParameterByName(name, url) {
    if (!url) {
      url = window.location.href;
    }
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function sw_win_isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

</script>

<style>

h1.entry-title
{
    display:none;
}

ul.CATEGORY
{
    margin: 0px;
    padding: 0px;
}

h4.CATEGORY
{
    margin: 0px;
    padding: 15px 0px;
}

ul.CATEGORY li
{
    list-style: none;
    margin: 0px;
    padding: 5px 5px 5px 0px;
    width: 168px;
    float: left;
}

ul.CATEGORY li.embed
{
    width:100%;
}

.results_count.widget
{
    margin-bottom:0px;
}


</style>

