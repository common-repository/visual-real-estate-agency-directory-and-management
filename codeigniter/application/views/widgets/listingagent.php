<div class="sw-agents-listings">

<?php foreach($agents as $key=>$user): ?>

<?php 

    //$user_info = get_userdata($user->ID);
    //if(sw_is_user_in_role($user_info, 'AGENCY'))continue; 

?>

<div class="row-smallbox">

    <div class="col-xs-4">
        <div class="image-box">
            <img src="<?php echo sw_profile_image($user, 100); ?>" alt="" class="image" />
            <a href="<?php echo agent_url($user); ?>" class="property-card-hover">
                <img src="<?php echo plugins_url( SW_WIN_SLUG.'/assets' );?>/img/plus.png" alt="" class="center-icon" />
            </a>
        </div>
    </div>
    
    <div class="col-xs-8">
    <div class="sw-smallbox">
        <div class="sw-smallbox-title"><a href="<?php echo agent_url($user); ?>"><?php echo $user->display_name; ?></a></div>
        <div class="sw-smallbox-address"><?php echo $user->user_email; ?></div>
        <div class="sw-smallbox-price"><?php echo _ch($user->phone_number, '-'); ?></div>
    </div>
    </div>

</div>
<?php endforeach; ?>

</div>


<style>

.sw-agents-listings .row-smallbox
{
    background: #F8F8F8;
    display:block;
    min-height:100px;
    margin-bottom:5px;
    color:black;
}

.sw-agents-listings .row-smallbox a
{
    /* color:black; */
}

.sw-agents-listings .row-smallbox div
{
    padding: 0px;
}

.sw-agents-listings .row-smallbox div.sw-smallbox
{
    padding: 3px;
}

.sw-agents-listings .image-box
{
    height: 100px;
    position: relative;
    overflow: hidden;
    text-align: center;
    
}

.sw-agents-listings .image-box img.image
{
    vertical-align: middle;
    position:absolute;
    top:0;
    bottom:0;
    left:0;
    right:0;
    margin:auto;
}

.sw-agents-listings a.property-card-hover {
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

.sw-agents-listings .image-box:hover a.property-card-hover {
    opacity: 1;
}

.sw-agents-listings .property-card-hover .center-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%,-50%);
    -webkit-transform: translate(-50%,-50%);
    width: 35px;
    height: 35px;
}

.sw-agents-listings div.sw-smallbox-price
{
    font-weight: bold;
    color: #217DBB;
}


</style>












