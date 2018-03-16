<?php


/*

|--------------------------------------------------------------------------

| Application Routes

|--------------------------------------------------------------------------

|

| Here is where you can register all of the routes for an application.

| It is a breeze. Simply tell Lumen the URIs it should respond to

| and give it the Closure to call when that URI is requested.

|

*/

$app->get('oauth/authorize', 'OauthController@authorized');
$app->post('oauth/token', 'OauthController@token');

$app->post('service/assigncode', 'AssignController@assigngroup');
$app->post('service/package', 'PackageController@package');
$app->post('service/unpackage', 'PackageController@unpackage');

$app->get('service/checkcode', 'CodeController@checkcode');
$app->get('service/codequery', 'CodeController@codequery');
$app->get('service/tracequery', 'CodeController@tracequery');

$app->put('service/upload', 'UploadController@upload');

