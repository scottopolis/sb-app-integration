# WordPress App Integration

This is a plugin to integrate WordPress with a mobile app or PWA.

It was meant to go with this tutorial http://scottbolinger.com/ionic-wordpress-app/ but it does not have to be used with Ionic.

## Usage

Download this repository, and then upload it to your WordPress wp-content/plugins directory. You can also zip the file and upload it through Plugins => Add new in your wp-admin.

Activate the plugin.

## WP-API Login

This plugin adds an endpoint to the WP-API to allow a simple verification of a WordPress user. It is meant as a simple login for a mobile app, it is not secure enough to replace oAuth or any other existing protocol.

Once the plugin is activated, you can make a POST request to https://mysite.com/wp-json/app/v1/login

Include the following:

- `$_POST['security']` is the security phrase
- `$_POST['logout']` if set to "true" it will log the user out
- `$_POST['username']` or `$_SERVER['PHP_AUTH_USER']` (not all servers accept authentication headers)
- `$_POST['password']` or `$_SERVER['PHP_AUTH_PW']` (not all servers accept authentication headers)

**Be sure to change the security passphrase in class-wpapi-login.php, preferably a hash of some sort.**

If a valid username, password, and security phrase is sent, a json success message will be returned that you can use in your app:

	$return = array(
		'message' =>  'The log in you have entered is not valid.',
		'signon' => $info['user_login'],
		'line' => __LINE__,
		'success' => false
	);

## WP-API Mods

This plugin also adds featured image urls to the post response to make it easier to display features images in post lists. The field added is featured_image_urls, with all available urls for each image size returned.