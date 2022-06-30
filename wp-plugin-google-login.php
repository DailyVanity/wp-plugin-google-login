<?php

/**
 * Plugin Name: Daily Vanity Google Workspace Login
 * Description: Enable login via Google Workspace domain
 * Version: 1.0.0
 * Requires at least: 5.4
 * Requires PHP: 7.4
 * Author: Chris Sim
 * Author uri: 
 * License: GNU General Public License v3.0
 * License URI: https://github.com/DailyVanity/wp-plugin-google-login/blob/master/LICENSE
 * Text Domain: google workspace login
 */

if ( ! defined( 'ABSPATH' ) ){
	exit; // don't access directly
};
require(dirname(__FILE__).'/vendor/autoload.php');

add_action('login_head', 'googleScript');
add_action('login_form', 'googleButton');
add_filter( 'authenticate', 'googleWorkspaceAuth' );

function googleScript() {
  echo "<script src=\"//accounts.google.com/gsi/client\" async defer></script>";
  
}

function googleWorkspaceAuth() {
  if(isset($_POST['g_csrf_token'])) {
    if($_COOKIE['g_csrf_token'] === $_POST['g_csrf_token']) {
      $client = new Google_Client(['client_id' => '814432260757-gdjsdrdkfrd6nb4g3rpo4r8tb7sb61jq.apps.googleusercontent.com']);  // Specify the CLIENT_ID of the app that accesses the backend
      $payload = $client->verifyIdToken($_POST['credential']);
      print_r($payload);
      if ($payload) {
        $userid = $payload['sub'];
        // If request specified a G Suite domain:
        //$domain = $payload['hd'];
      } else {
        // Invalid ID token
      }
      print_r($payload);
      die();
    }
  }
}

function googleButton() {
  echo '
  <div>
    <div id="g_id_onload"
      data-client_id="814432260757-gdjsdrdkfrd6nb4g3rpo4r8tb7sb61jq.apps.googleusercontent.com"
      data-login_uri="http://localhost:3500/backdesk-service/"
      data-auto_prompt="false">
    </div>
    <div class="g_id_signin"
      data-type="standard"
      data-size="medium"
      data-theme="filled_blue"
      data-text="signin"
      data-shape="square"
      data-logo_alignment="left">
    </div>
  </div>';
}