<?php
/**
* Plugin Name: Remove Header
* Description: This plugin removes admin header
 */

//Remove header from the front-end of admin page
add_filter("show_admin_bar", "__return_false");

?>