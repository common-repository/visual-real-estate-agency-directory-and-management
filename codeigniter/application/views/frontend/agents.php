<form class="form-inline form-mini pull-right">
    <div class="form-group">
        <input id="search_term" name="search_term" class="form-control" placeholder="<?php echo __('Agent name', 'sw_win'); ?>" value="<?php echo search_value('term', NULL, ''); ?>" type="text">
    </div>
    <button id="search-start-agents" type="submit" class="sw-search-start-agents btn btn-primary btn-inversed btn-block">&nbsp;&nbsp;<?php echo __('Search', 'sw_win'); ?>&nbsp;&nbsp;</button>
    <i class="ajax-indicator" style="display: none;"></i>
</form>

<br style="clear: both;" />

<div class="agents-results-container">

<?php $this->load->view('frontend/agentsresults'); ?>

</div>


<style>

.agents-results-container
{
    padding-top:10px;
}

.agents-results-container .column-sep.col-md-4, .agents-results-container .column-sep.col-sm-6
{
    margin:0px;
    padding:0px 5px 5px 0px;
    height:110px;
    overflow: hidden;
    
}

.agents-results-container .agent-item
{
    background: #F8F8F8;
    display:block;
    min-height:100px;
    margin-bottom:5px;
    color:black;
    width:100%;
    
}

.agents-results-container .agent-item a
{
    /* color:black; */
}

.agents-results-container .agent-item div
{
    padding: 0px;
}

.agents-results-container .agent-item div.sw-smallbox
{
    padding: 3px;
}

.agents-results-container .agent-item div.sw-smallbox div
{
    width:100%;
    overflow:hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.agents-results-container .image-box
{
    height: 100px;
    position: relative;
    overflow: hidden;
    text-align: center;
    
}

.agents-results-container .image-box img.image
{
    vertical-align: middle;
    position:absolute;
    top:0;
    bottom:0;
    left:0;
    right:0;
    margin:auto;
}

.agents-results-container a.property-card-hover {
    background: rgba(0,0,0,0.25);
    position: absolute;
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
    opacity: 0;
    transition: all .2s;
    -webkit-transition: opacity .2s;
}

.agents-results-container .image-box:hover a.property-card-hover {
    opacity: 1;
}

.agents-results-container .property-card-hover .center-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%,-50%);
    -webkit-transform: translate(-50%,-50%);
    width: 35px;
    height: 35px;
}

.agents-results-container div.sw-smallbox-price
{
    font-weight: bold;
    color: #217DBB;
}

.agents-results-container .pagination {
    border-width: 0px;
}


.ci.sw_widget.sw_wrap .form-inline.form-mini .form-control {
    display: inline-block;
    width: auto;
    vertical-align: middle;
}

.form-inline.form-mini .form-group {
    display: inline-block;
    margin-bottom: 0;
    vertical-align: middle;
}

.ci.sw_widget.sw_wrap .form-inline.form-mini .form-group{
    padding-top:0px;
}

#search-start-agents{
    padding:8px;
}

</style>

<script>


jQuery(document).ready(function($) {
    
    $('#search-start-agents').click(function(){
        search_result(0, false, false, true);
        return false;
    });
    
    reloadElements();

    function reloadElements()
    {        
        $('#results-agents .pagination a').click(function () { 
            
            var href = $(this).attr('href');
            
            var offset = getParameterByName('offset', href);
            
            search_result(offset, true, false, false);

            return false;
        });
        
    }

    function search_result(results_offset, scroll_enabled, save_only, load_map)
    {
        var selectorResults = '#results_top';
        
        var search_term = $('#search_term').val(); 
        
        //Define default data values for search
        var data = {
            offset: results_offset,
            search_term: search_term
        };
        
        <?php
            $page_link = '';
            
            // get results page ID
            $agents_page_id = sw_settings('agents_page');
            if(!empty($agents_page_id))
            {
                // get results page link
                $page_link = get_page_link($agents_page_id);
            }
            
        ?>

        var gen_url = generateUrl("<?php echo $page_link; ?>", data)+"#results-agents";
        
        <?php if(is_page(sw_settings('agents_page'))): ?>
        
        $.extend( data, {
            "page": 'frontendajax_agents',
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
