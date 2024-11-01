
<div class="wrap">
<?php $this->load->view($subview)?>
</div>

<?php

wp_enqueue_script( 'bootstrap3' );
wp_enqueue_style( 'font-awesome');

?>

<style>
.bootstrap-wrapper
{
    padding: 10px 0px;
}

.tab-content
{
    padding-top:20px;
}

.bootstrap-wrapper .alert {
    padding: 10px;
    margin-bottom: 8px;
}

form.form-horizontal
{
    padding:10px 15px 0px 15px;
}

.bb-alert {
    top: 10%;
    font-size: 1.2em;
    margin-bottom: 0;
    padding: 1em 1.3em;
    position: fixed;
    right: 50%;
}

#map img {
    max-width: none;
}

#map label {
    width: auto; display:inline;
}

.input-error-msg
{
    color:red;
    font-style: italic;
}

/*
#wpfooter {
    position: relative;
    display:none;
}
*/


input[type="radio"]{
    -webkit-appearance: radio;
    -moz-appearance: radio;
    appearance: radio;
}

</style>