                    <table class="table table-bordered footable">
                      <tbody id="script_news_table">
                        <tr>
                        	<td colspan="5"><?php echo __('Loading in progress', 'sw_win');?></td>
                        </tr>      
                      </tbody>
                    </table>
<script>
jQuery(document).ready(function($) {
    
    $.getJSON("https://geniuscript.com/winclassified/last_news.php?f=news.json", function( data ) {
      var content = '';
      
      $.each( data, function( key, val ) {
        content+='<tr><td>'+val.date+'</td><td><a href="'+val.link+'" target="_blank">'+val.title+'</a></td></tr>';
      });
        
      $('#script_news_table').html(content);
    });

});
</script>