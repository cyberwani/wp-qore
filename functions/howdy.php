<?php

// Customize howdy message
add_filter('gettext', 'change_howdy', 10, 3);

function change_howdy($translated, $text, $domain) {

    if (!is_admin() || 'default' != $domain)
        return $translated;

    if (false !== strpos($translated, 'Howdy'))

        //Want to help fix this? Check the issue trac: 
        //https://github.com/icryptic/wp-qore/issues/38
        //
        //$howdy_text = get_option("wpqorefunc_howdy_text");
        //return str_replace('Howdy', __( $howdy_text, 'wp-qore' ), $translated);
        
        return str_replace('Howdy', get_option("wpqorefunc_howdy_text"), $translated);

    return $translated;
}

?>
