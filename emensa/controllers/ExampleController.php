<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/kategorie.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/gericht.php');
class ExampleController
{
    public function m4_7a_queryparameter(RequestData $rd) {
        $name = $rd->query["name"] ?? "unbekannt";

        return view('examples.m4_7a_queryparameter', [
            'name' => $name,
        ]);
    }
    public function m4_7b_kategorie() {
        $categories = db_kategorie_select_all();

        return view('examples.m4_7b_kategorie', [
            'categories' => $categories,
        ]);
    }

    public function m4_7c_gerichte() {
        $gerichte = db_sortiert_gericht_preisintern_hoeher_als_2();
        return view('examples.m4_7c_gerichte', [
            'gerichte' => $gerichte
        ]);
    }
    public function m4_7d_layout(RequestData $requestData) {
        //default landing to page 1
        $page = $requestData->query["no"] ?? 1;
        if(!($page == 1 || $page == 2)) {
            $page = 1;
        }
        return view('examples.pages.m4_7d_page_'.htmlspecialchars($page));
    }
}