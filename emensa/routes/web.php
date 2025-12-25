<?php
/**
 * Mapping of paths to controllers.
 * Note, that the path only supports one level of directory depth:
 *     /demo is ok,
 *     /demo/subpage will not work as expected
 */

return array(
    '/'             => "HomeController@index",
    "/demo"         => "DemoController@demo",
    '/dbconnect'    => 'DemoController@dbconnect',
    '/debug'        => 'HomeController@debug',
    '/error'        => 'DemoController@error',
    '/requestdata'   => 'DemoController@requestdata',
    '/gerichte'   => 'HomeController@index',
    '/newsletter' => 'HomeController@index',
    '/wunschgericht' => 'HomeController@wunschgericht',
    '/wunschgerichtmeldung' => 'HomeController@wunschgerechtemeldung',
    '/anmeldung' => 'HomeController@anmeldung',
    '/anmeldung_verifizieren' => 'HomeController@anmeldung_verifizieren',
    '/abmeldung' => 'HomeController@abmeldung',
    '/berichtkategorien' => 'HomeController@berichtkategorien',
    '/bewertung' => 'HomeController@bewertung',
    '/bewertung_action' => 'HomeController@bewertung_action',
    '/meinebewertungen' => 'HomeController@meinebewertungen',
    '/delete_bewertung' => 'HomeController@delete_bewertung',
    '/updateHervorheben' => 'HomeController@updateHervorheben',

    // Erstes Beispiel:
    '/m4_6a_queryparameter' => 'ExampleController@m4_6a_queryparameter',
    '/m4_7a_queryparameter' => 'ExampleController@m4_7a_queryparameter',
    '/m4_7b_kategorie' => 'ExampleController@m4_7b_kategorie',
    '/m4_7c_gerichte' => 'ExampleController@m4_7c_gerichte',
    '/m4_7d_layout' => 'ExampleController@m4_7d_layout',
);