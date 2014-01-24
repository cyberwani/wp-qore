<?php //wp-admin dashboard : begin 

/*
 * Timeout in wpqore_foldersize()
 * Fixed by Dave Jesch
 * January 23, 2014
 * Since 1.8.3
 * https://github.com/icryptic/wp-qore/issues/29
 *
 */

//include server_info functions
require_once("server_info.php");

    if (get_option("wpqorefunc_dashboard_switch")=='checked') { 
        echo get_option("wpqorefunc_custom_dashboard");
    } else {

//convert memory usage
function convert($size){
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2);
}

// number of posts
function wpqore_posts() {
    global $wpqore_count_options;
    $count_posts = wp_count_posts();
    return $wpqore_count_options['count_posts_before'] . $count_posts->publish . $wpqore_count_options['count_posts_after'];
}

// number of pages
function wpqore_pages() {
    global $wpqore_count_options;
    $count_pages = wp_count_posts('page');
    return $wpqore_count_options['count_pages_before'] . $count_pages->publish . $wpqore_count_options['count_pages_after'];
}

// number of drafts
function wpqore_drafts() {
    global $wpqore_count_options;
    $count_drafts = wp_count_posts();
    return $wpqore_count_options['count_drafts_before'] . $count_drafts->draft . $wpqore_count_options['count_drafts_after'];
}

// number of comments (total)
function wpqore_comments() {
    global $wpqore_count_options;
    $count_comments = wp_count_comments();
    return $wpqore_count_options['count_comments_before'] . $count_comments->total_comments . $wpqore_count_options['count_comments_after'];
}

// number of comments (moderated)
function wpqore_moderated() {
    global $wpqore_count_options;
    $count_moderated = wp_count_comments();
    return $wpqore_count_options['count_moderated_before'] . $count_moderated->moderated . $wpqore_count_options['count_moderated_after'];
}

// number of comments (approved)
function wpqore_approved() {
    global $wpqore_count_options;
    $count_approved = wp_count_comments();
    return $wpqore_count_options['count_approved_before'] . $count_approved->approved . $wpqore_count_options['count_approved_after'];
}

// number of users
function wpqore_users() {
    global $wpqore_count_options;
    $count_users = count_users();
    return $wpqore_count_options['count_users_before'] . $count_users['total_users'] . $wpqore_count_options['count_users_after'];
}

// number of categories
function wpqore_cats() {
    global $wpqore_count_options;
    $cats = wp_list_categories('title_li=&style=none&echo=0');
    $cats_parts = explode('<br />', $cats);
    $cats_count = count($cats_parts) - 1;
    return $wpqore_count_options['count_cats_before'] . $cats_count . $wpqore_count_options['count_cats_after'];
}

// number of tags
function wpqore_tags() {
    global $wpqore_count_options;
    return $wpqore_count_options['count_tags_before'] . wp_count_terms('post_tag') . $wpqore_count_options['count_tags_after'];
}

// last updated posts
function wpqore_updated($d = '') {
    global $wpqore_count_options;
    $count_posts = wp_count_posts();
    $published_posts = $count_posts->publish; 
    $recent = new WP_Query("showposts=1&orderby=date&post_status=publish");
    if ($recent->have_posts()) {
        while ($recent->have_posts()) {
            $recent->the_post();
            $last_update = get_the_modified_date($d);
        }
        return $wpqore_count_options['site_updated_before'] . $last_update . $wpqore_count_options['site_updated_after'];
    } else {
        return $wpqore_count_options['site_updated_before'] . 'awhile ago' . $wpqore_count_options['site_updated_after'];
    }
}

// calculate space
function wp_calc_disk_usage() {
    $upload_dir     = wp_upload_dir();
    $upload_space   = wpqore_foldersize( $upload_dir['basedir'], NULL );
    $content_space  = wpqore_foldersize( WP_CONTENT_DIR, $upload_dir['basedir'] ) + $upload_space;
    $wp_space       = wpqore_foldersize( ABSPATH, WP_CONTENT_DIR ) + $content_space;

	return (array(
		'upload' => wpqore_format_size( $upload_space ),
		'content' => wpqore_format_size( $content_space ),
		'wp' => wpqore_format_size( $wp_space )
	));
}

function wp_qore_logdata($sMsg)
{
	global $fUpload;

	if ($fUpload)
	{
		$sFile = dirname(__FILE__) . '/~log.txt';
		$fh = fopen($sFile, 'a+');

		if (!empty($sPlugin))
			$sMsg = $sPlugin . ': ' . $sMsg;

		if ($fh !== FALSE)
		{
			if ($sMsg === NULL)
				fwrite($fh, date("\r\nY-m-d H:i:s:\r\n"));
			else
				fwrite($fh, date("Y-m-d H:i:s: ") . $sMsg . "\r\n");
			fclose($fh);
		}
	}
}

function wpqore_foldersize( $path, $exclude = NULL ) {
    $total_size = 0;
    $path = untrailingslashit($path);

    $transient = 'wp_qore_foldersize_' . str_replace(ABSPATH, '', $path);

	// return transient value if we've done this recently
    $size = get_transient($transient);
    if ($size !== false)
    	return ($size);

	// set up the timer
	list($usec, $sec) = explode(' ', microtime());
	$starttime = $sec;

	$size = wp_qore_calcfoldersize($path, $exclude, $starttime);

	// save transient value for 4 hours
	set_transient($transient, $size, 60 * 60 * 4);
	return $size;
}

function wp_qore_calcfoldersize($path, $exclude, $starttime)
{
	// check timer
	list($usec, $sec) = explode(' ', microtime());
	if ($sec - $starttime > 9) {
		return 0;			// spending more than 15 seconds, give up
	}

	$total_size = 0;
    $cleanPath = $path . '/';
    $files = scandir( $path );

    foreach( $files as $t ) {
        if ( '.' != $t && '..' != $t ) {
            $currentFile = $cleanPath . $t;

            if ( is_dir( $currentFile ) ) {
				$size = 0;
				if ( $exclude == NULL || ($exclude != NULL && substr($currentFile, 0, strlen($exclude)) != $exclude))
	                $size = wp_qore_calcfoldersize( $currentFile, $exclude, $starttime );
                $total_size += $size;
            } else {
                $size = filesize( $currentFile );
                $total_size += $size;
            }
        }
    }

	return $total_size;
}

function wpqore_format_size($size) {
    $units = explode( ' ', 'B KB MB GB TB PB' );
    $mod = 1024;

    for ( $i = 0; $size > $mod; $i++ )
        $size /= $mod;

    $endIndex = strpos( $size, "." ) + 3;
    return substr( $size, 0, $endIndex ) . ' ' . $units[$i];
}

$wpqoreUrl = plugins_url('', dirname(__FILE__));
?>

<link rel="stylesheet" href="<?php echo $wpqoreUrl; ?>/css/layout.css" type="text/css" media="screen" />
<!--[if lt IE 9]>
<link rel="stylesheet" href="<?php echo $wpqoreUrl; ?>/css/ie.css" type="text/css" media="screen" />
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
    
<script type="text/javascript">
//Memory Chart
google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);

function drawChart() {
    var data = google.visualization.arrayToDataTable([
    ['<?php _e( 'Task', 'wp-qore' ); ?>', '<?php _e( 'PHP Memory', 'wp-qore' ); ?>'],
    ['<?php _e( 'Limit', 'wp-qore' ); ?>', <?php echo convert(ini_get('memory_limit')); ?>],
    ['<?php _e( 'Usage', 'wp-qore' ); ?>', <?php echo convert(memory_get_usage(true)); ?>],
    ]);

    var options = {
    title: '<?php _e( 'PHP Memory', 'wp-qore' ); ?>',
    pieHole: 0.3,
    };

    var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
    chart.draw(data, options);
    }
</script>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=483886975061870";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<section id="secondary_bar">
<div class="breadcrumbs_container">    

<article class="breadcrumbs">
<a href="#"><?php _e( 'Dashboard', 'wp-qore' ); ?></a> 
<div class="breadcrumb_divider"></div> 
<a class="current"><?php _e( 'General', 'wp-qore' ); ?></a>
</article>

</div>
</section>
    
<section id="main" class="column">
        
<h4 class="alert_info"><?php _e( 'Welcome to the admin backend', 'wp-qore' ); ?>.</h4>

<!--
<h4 class="alert_warning"><?php _e( 'A Warning Alert', 'wp-qore' ); ?></h4>
<h4 class="alert_error"><?php _e( 'An Error Message', 'wp-qore' ); ?></h4>
<h4 class="alert_success"><?php _e( 'A Success Message', 'wp-qore' ); ?></h4>
-->        

<?php if ( current_user_can('manage_options') ) { 
      if (get_option("wpqorefunc_dash_stats")=='checked') { }else{ ?>
<article class="module width_full" id="module_width_full">
<header><h3><?php _e( 'Stats', 'wp-qore' ); ?></h3></header>
<div class="module_content">
<div class="module_content_stats">

<article class="stats_graph" style="width:450px;">
<div id="donutchart" style="float:left;width:450px;height:245px;margin-left:-20px;margin-top:-10px;margin-bottom:-20px;"></div>
</article>

<article class="stats_overview" id="stats_overview" style="margin-top:10px;margin-bottom:20px;padding-top:5px">
<div class="overview_today">
    <p class="overview_count"><?php echo wpqore_posts(); ?></p>
    <p class="overview_type"><?php _e( 'posts', 'wp-qore' ); ?></p>
    <p class="overview_count"><?php echo wpqore_comments(); ?></p>
    <p class="overview_type"><?php _e( 'comments', 'wp-qore' ); ?></p>
</div>
<div class="overview_previous">
    <p class="overview_count"><?php echo wpqore_pages(); ?></p>
    <p class="overview_type"><?php _e( 'pages', 'wp-qore' ); ?></p>
    <p class="overview_count"><?php $result = count_users(); echo $result['total_users']; ?></p>
    <p class="overview_type"><?php _e( 'users', 'wp-qore' ); ?></p>
</div>
</article>

<?php
	$wp_disk_spaces = @wp_calc_disk_usage();
?>
<article class="stats_overview" id="stats_overview_2" style="margin-right:20px;margin-top:10px;margin-bottom:20px;padding-top:5px">
<div class="overview_today">
    <p class="overview_count"><?php echo $wp_disk_spaces['upload']; ?></p>
    <p class="overview_type"><?php _e( 'uploads', 'wp-qore' ); ?></p>
    <p class="overview_count"><?php echo $wp_disk_spaces['content']; ?></p>
    <p class="overview_type"><?php _e( 'wp-content', 'wp-qore' ); ?></p>
</div>
<div class="overview_previous">
    <p class="overview_count"><?php echo $wp_disk_spaces['wp']; ?></p>
    <p class="overview_type"><?php _e( 'wp total', 'wp-qore' ); ?></p>
    <p class="overview_count"><?php echo $perc;?>%</p>
    <p class="overview_type"><?php _e( 'free hdd', 'wp-qore' ); ?></p>
</div>
</article>

<article class="stats_overview" id="stats_overview_2" style="margin-bottom:10px;padding-top:5px">
<div class="overview_today">
    <p class="overview_count"><?php echo PHP_OS ?></p>
    <p class="overview_type"><?php _e("OS", 'wp-qore'); ?></p>
    <p class="overview_count"><?php echo phpversion() ?></p>
    <p class="overview_type"><?php _e("Php", 'wp-qore'); ?></p>
</div>
<div class="overview_previous">
    <p class="overview_count"><?php echo $wpdb->db_version() ?></p>
    <p class="overview_type"><?php _e("MySQL", 'wp-qore'); ?></p>
    <p class="overview_count"><?php echo $wp_version ?></p>
    <p class="overview_type"><?php _e("WordPress", 'wp-qore'); ?></p>
</div>
</article>

<article class="stats_overview" id="stats_overview_2" style="margin-right:20px;margin-bottom:10px;padding-top:5px">
<div class="overview_today">
    <p class="overview_count"><?php echo PHP_SAPI ?></p>
    <p class="overview_type"><?php _e("SAPI", 'wp-qore'); ?></p>
    <p class="overview_count"><?php echo WPQORE_run_apc() ? 'Yes' : 'No'  ?></p>
    <p class="overview_type"><?php _e("APC Enabled", 'wp-qore'); ?></p>
</div>
<div class="overview_previous">
    <p class="overview_count"><?php echo get_bloginfo('language') ?></p>
    <p class="overview_type"><?php _e("Language", 'wp-qore'); ?></p>
    <p class="overview_count"><?php echo (((strtolower(@ini_get('safe_mode')) == 'on') || (strtolower(@ini_get('safe_mode')) == 'yes') || (strtolower(@ini_get('safe_mode')) == 'true') ||  (ini_get("safe_mode") == 1 ))) ? __('On', 'wp-qore') : __('Off', 'wp-qore'); ?></p>
    <p class="overview_type"><?php _e("Safe Mode", 'wp-qore'); ?></p>
</div>
</article>

<div class="clear"></div>
</div>
</div>
</article>
<?php } } 

if (get_option("wpqorefunc_dash_controls")=='checked') { }else{ ?>
<article class="module width_full">
<header>
<h3><?php _e( 'Control', 'wp-qore' ); ?></h3>
</header>
<div class="module_content">
<div style="display:table;height:100%;padding-left:40px;padding-bottom:30px;">

<?php if (get_option("wpqorefunc_plug-edit")=='checked') {

if ( current_user_can('read') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'All Posts', 'wp-qore' ); ?></div><a href="edit.php" title="View All Posts"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/edit.png'"/></a></div>
<?php }

if ( current_user_can('edit_posts') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Add Posts', 'wp-qore' ); ?></div><a href="post-new.php" title="Add New Post"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/add.png"/></a></div>
<?php }

if ( current_user_can('manage_categories') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Categories', 'wp-qore' ); ?></div><a href="edit-tags.php?taxonomy=category" title="Categories"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/category.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Tags', 'wp-qore' ); ?></div><a href="edit-tags.php?taxonomy=post_tag" title="Tags"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/tags.png"/></a></div>
<?php }

if ( current_user_can('upload_files') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Media Library', 'wp-qore' ); ?></div><a href="upload.php" title="Media Library"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/media.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Add Media', 'wp-qore' ); ?></div><a href="media-new.php" title="Add New Media"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/media_add.png"/></a></div>
<?php }

if ( current_user_can('moderate_comments') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Comments', 'wp-qore' ); ?></div><a href="edit-comments.php" title="Comments"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/comments.png' , __FILE__ ); ?>"/></a></div>
<?php }

if ( current_user_can('publish_pages') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Add Page', 'wp-qore' ); ?></div><a href="edit.php?post_type=page" title="Add New Page"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/add_page.png"/></a></div>
<?php }

if ( current_user_can('switch_themes') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Themes', 'wp-qore' ); ?></div><a href="themes.php" title="Themes"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/themes.png"/></a></div>
<?php }

if ( current_user_can('edit_theme_options') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Widgets', 'wp-qore' ); ?></div><a href="widgets.php" title="Widgets"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/widgets.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Menus', 'wp-qore' ); ?></div><a href="nav-menus.php" title="Menus"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/menus.png"/></a></div>
<?php }

if ( current_user_can('activate_plugins') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Plugins', 'wp-qore' ); ?></div><a href="plugins.php" title="Plugins"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/plugins.png"/></a></div>
<?php }

if ( current_user_can('install_plugins') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Add Plugin', 'wp-qore' ); ?></div><a href="plugin-install.php" title="Add New Plugin"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/add-plugin.png"/></a></div>
<?php }

if ( current_user_can('edit_users') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Users', 'wp-qore' ); ?></div><a href="users.php" title="Users"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/users.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Add User', 'wp-qore' ); ?></div><a href="user-new.php" title="Add New User"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/add-user.png"/></a></div>
<?php }

if ( current_user_can('read') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'My Profile', 'wp-qore' ); ?></div><a href="profile.php" title="My Profile"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/profile.png"/></a></div>
<?php }

if ( current_user_can('import') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Import', 'wp-qore' ); ?></div><a href="import.php" title="Import Content"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/import.png"/></a></div>
<?php }

if ( current_user_can('export') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Export', 'wp-qore' ); ?></div><a href="export.php" title="Export Content"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/export.png"/></a></div>
<?php }

if ( current_user_can('manage_options') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Settings', 'wp-qore' ); ?></div><a href="options-general.php" title="General Settings"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/general.png"/></a></div>

<?php } }else{ 

if ( current_user_can('read') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'All Posts', 'wp-qore' ); ?></div><a href="edit.php" title="View All Posts"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/edit.png"/></a></div>
<?php }

if ( current_user_can('edit_posts') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Add Posts', 'wp-qore' ); ?></div><a href="post-new.php" title="Add New Post"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/add.png"/></a></div>
<?php }

if ( current_user_can('manage_categories') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Categories', 'wp-qore' ); ?></div><a href="edit-tags.php?taxonomy=category" title="Categories"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/category.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Tags', 'wp-qore' ); ?></div><a href="edit-tags.php?taxonomy=post_tag" title="Tags"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/tags.png"/></a></div>
<?php }

if ( current_user_can('upload_files') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Media Library', 'wp-qore' ); ?></div><a href="upload.php" title="Media Library"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/media.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Add Media', 'wp-qore' ); ?></div><a href="media-new.php" title="Add New Media"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/media_add.png"/></a></div>
<?php }

if ( current_user_can('moderate_comments') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Comments', 'wp-qore' ); ?></div><a href="edit-comments.php" title="Comments"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/comments.png"/></a></div>
<?php }

if ( current_user_can('publish_pages') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Add Page', 'wp-qore' ); ?></div><a href="edit.php?post_type=page" title="Add New Page"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/add_page.png"/></a></div>
<?php }

if ( current_user_can('switch_themes') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Themes', 'wp-qore' ); ?></div><a href="themes.php" title="Themes"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/themes.png"/></a></div>
<?php }

if ( current_user_can('edit_themes') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Theme Editor', 'wp-qore' ); ?></div><a href="theme-editor.php" title="Theme Editor"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/theme_editor.png"/></a></div>
<?php }

if ( current_user_can('edit_theme_options') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Widgets', 'wp-qore' ); ?></div><a href="widgets.php" title="Widgets"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/widgets.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Menus', 'wp-qore' ); ?></div><a href="nav-menus.php" title="Menus"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/menus.png"/></a></div>
<?php }

if ( current_user_can('activate_plugins') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Plugins', 'wp-qore' ); ?></div><a href="plugins.php" title="Plugins"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/plugins.png"/></a></div>
<?php }

if ( current_user_can('install_plugins') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Add Plugin', 'wp-qore' ); ?></div><a href="plugin-install.php" title="Add New Plugin"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/add-plugin.png"/></a></div>
<?php }

if ( current_user_can('edit_plugins') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Plugins Editor', 'wp-qore' ); ?></div><a href="plugin-editor.php" title="Plugin Editor"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/edit-plugin.png"/></a></div>
<?php }

if ( current_user_can('edit_users') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Users', 'wp-qore' ); ?></div><a href="users.php" title="Users"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/users.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Add User', 'wp-qore' ); ?></div><a href="user-new.php" title="Add New User"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/add-user.png"/></a></div>
<?php }

if ( current_user_can('read') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'My Profile', 'wp-qore' ); ?></div><a href="profile.php" title="My Profile"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/profile.png"/></a></div>
<?php }

if ( current_user_can('import') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Import', 'wp-qore' ); ?></div><a href="import.php" title="Import Content"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/import.png"/></a></div>
<?php }

if ( current_user_can('export') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Export', 'wp-qore' ); ?></div><a href="export.php" title="Export Content"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/export.png"/></a></div>
<?php }

if ( current_user_can('manage_options') ) { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Settings', 'wp-qore' ); ?></div><a href="options-general.php" title="General Settings"><img src="<?php echo $wpqoreUrl; ?>/images/dashboard/general.png"/></a></div>

<?php } } ?>

</div>
</div>
</article>
<?php } 

if ( current_user_can('read') ) { 
if (get_option("wpqorefunc_dash_pages")=='checked') { }else{ ?>
<article class="module width_half">
<header>
<h3><?php _e( 'Recent Pages', 'wp-qore' ); ?></h3>
</header>
<div class="message_list">
<div class="module_content">
<?php

   $args=array(
   'showposts'=>10,
   'post_type' => 'page',
   'ignore_sticky_posts'=>1
   );
$my_query = new WP_Query($args);
if( $my_query->have_posts() ) {
  while ($my_query->have_posts()) : $my_query->the_post(); ?>
<div class="message">
<p><h3><a target="_blank" href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3></p>
</div>
    <?php
  endwhile;
}

?>
</div>
</div>
    <footer>

    </footer>
</article>
<?php } }

if ( current_user_can('read') ) { 
if (get_option("wpqorefunc_dash_posts")=='checked') { }else{ ?>
<article class="module width_half">
<header>
<h3><?php _e( 'Recent Posts', 'wp-qore' ); ?></h3>
</header>
<div class="message_list">
<div class="module_content">
<?php

global $post;
$args = array( 'numberposts' => 10 );
$myposts = get_posts( $args );
foreach( $myposts as $post ) :  setup_postdata($post); ?>
<div class="message">
<p><h3><a target="_blank" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3></p>
</div>
<?php 

endforeach; 

?>
</div>
</div>
    <footer>
        <form target="_blank" class="post_message" id="posts-filter" action="<?php bloginfo('wpurl');?>/" method="get">
            <input type="text" id="post-search-input" name="s" value="<?php _e( 'find content', 'wp-qore' ); ?>" onfocus="if(!this._haschanged){this.value=''};this._haschanged=true;">
        <input type="submit" class="btn_post_message" value=""/>
    </form>
    </footer>
</article>
<?php } } 

if ( current_user_can('manage_options') ) {
if (get_option("wpqorefunc_dash_serverinfo")=='checked') { }else{ ?>
<div class="footer_section">
<article class="module width_half">
<header>
<h3><?php _e( 'System Info', 'wp-qore' ); ?></h3>
</header>
<div class="module_content">
<b><?php _e("Web Server", 'wp-qore'); ?></b>: <?php echo $_SERVER['SERVER_SOFTWARE'] ?><br />
<br />
<b><?php _e("Max Execution Time", 'wp-qore'); ?></b>: <?php echo @ini_get( 'max_execution_time' ); ?><br />
<br />
<b><?php _e('Server Disk Quota', 'wp-qore'); ?></b>: <?php echo WPQORE_bytesize($space_free);?> of <?php echo WPQORE_bytesize($space);?> available.
</div>
</article>
<?php } 

if (get_option("wpqorefunc_dash_about")=='checked') { }else{ ?>
<article class="module width_half">
<header>
<h3><?php _e( 'About WPQore', 'wp-qore' ); ?></h3>
</header>
<div class="module_content">
<div style="float:right" class="fb-like-box" data-href="http://www.facebook.com/wpqore" data-width="200" data-colorscheme="light" data-show-faces="false" data-header="true" data-stream="false" data-show-border="false"></div>
<p align="justify"><a target="_blank" href="http://wpqore.com/">WP Qore</a>, a WordPress plugin that provides additional security, performance functionality, and developer tools that can be turned on or off at any time. WP Qore offers many powerful features such as a malware and antivirus scanner.</p>
</div>
</article>
<?php } ?>
</div>
<?php } ?>

<div class="clear"></div>        

</section>

<?php } //wp-admin dashboard : end 
 ?>
