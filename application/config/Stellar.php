<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//https://developers.facebook.com/docs/reference/php/facebook-getLoginUrl/
$config['facebook_login_parameters'] = array(
                                            'scope' => 'publish_stream','read_friendlists','publish_checkins', 'read_stream', 'access_token',
                                            'display' => 'page'
                                            );