<?php
$auth = new WPIC_Authentication();

$access_token = $auth->get_access_token();
?>

<div class="wrap">
	<h2>WP Instagram Comments Settings</h2>

	<header class="wpic-options-header">
		Thanks for using WP Instagram Comments!
	</header>

	<div class="wpic-auth-settings">
		To use the plugin, you'll have to grant WordPress the ability to pull comments from your Instagram account. Please click the
		button below to authorize your account.
		<br>
		<a class="btn btn-primary" href="<?php echo $auth->get_auth_endpoint(); ?>">Authorize your account</a>

		<?php if ( $access_token ) : ?>
			<div class="access-token">You are authenticated with the Instagram API, with access token <span><?php echo $access_token; ?></span></div>
		<?php endif; ?>
	</div>

	<pre>
	<?php
	$user = WPIC_Requests::get_user();


	print_r( $user );

	$media_item = WPIC_Requests::get_media_by_url( 'http://instagram.com/p/xUrYezibnU/' );
	//print_r( $media_item )

	$comments = WPIC_Requests::get_media_comments( '888525824843168212_180655621' );
	print_r( $comments );
	?>
	</pre>



</div>