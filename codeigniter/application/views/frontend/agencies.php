<form class="form-inline form-mini pull-right">
    <div class="form-group">
        <input id="search_term" name="search_term" class="form-control" placeholder="<?php echo __('Agency name', 'sw_win'); ?>" value="<?php echo search_value('term', NULL, ''); ?>" type="text">
    </div>
    <button id="search-start-agencies" type="submit" class="sw-search-start-agencies btn btn-primary btn-inversed btn-block">&nbsp;&nbsp;<?php echo __('Search', 'sw_win'); ?>&nbsp;&nbsp;</button>
    <i class="ajax-indicator" style="display: none;"></i>
</form>

<br style="clear: both;" />

<div class="agencies-results-container">

<?php $this->load->view('frontend/agenciesresults'); ?>

</div>


<style>

.agencies-results-container
{
    padding-top:10px;
}

.agencies-results-container .column-sep.col-md-4, .agencies-results-container .column-sep.col-sm-6
{
    margin:0px;
    padding:0px 5px 5px 0px;
    height:110px;
    overflow: hidden;
    
}

.agencies-results-container .agent-item
{
    background: #F8F8F8;
    display:block;
    min-height:100px;
    margin-bottom:5px;
    color:black;
    width:100%;
    
}

.agencies-results-container .agent-item a
{
    /* color:black; */
}

.agencies-results-container .agent-item div
{
    padding: 0px;
}

.agencies-results-container .agent-item div.sw-smallbox
{
    padding: 3px;
}

.agencies-results-container .agent-item div.sw-smallbox div
{
    width:100%;
    overflow:hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.agencies-results-container .image-box
{
    height: 100px;
    position: relative;
    overflow: hidden;
    text-align: center;
    
}

.agencies-results-container .image-box img.image
{
    vertical-align: middle;
    position:absolute;
    top:0;
    bottom:0;
    left:0;
    right:0;
    margin:auto;
}

.agencies-results-container a.property-card-hover {
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

.agencies-results-container .image-box:hover a.property-card-hover {
    opacity: 1;
}

.agencies-results-container .property-card-hover .center-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%,-50%);
    -webkit-transform: translate(-50%,-50%);
    width: 35px;
    height: 35px;
}

.agencies-results-container div.sw-smallbox-price
{
    font-weight: bold;
    color: #217DBB;
}

.agencies-results-container .pagination {
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

#search-start-agencies{
    padding:8px;
}

</style>

<script>


jQuery(document).ready(function($) {
    
    $('#search-start-agencies').click(function(){
        search_result(0, false, false, true);
        return false;
    });
    
    reloadElements();

    function reloadElements()
    {        
        $('#results-agencies .pagination a').click(function () { 
            
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
            $agencies_page_id = sw_settings('agencies_page');
            if(!empty($agencies_page_id))
            {
                // get results page link
                $page_link = get_page_link($agencies_page_id);
            }
            
        ?>

        var gen_url = generateUrl("<?php echo $page_link; ?>", data)+"#results-agencies";
        
        <?php if(is_page(sw_settings('agencies_page'))): ?>
        
        $.extend( data, {
            "page": 'frontendajax_agencies',
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
