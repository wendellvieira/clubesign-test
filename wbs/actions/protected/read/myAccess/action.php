<?php
  function myAccess($r){
    $pages = $r['tables']['login']
      ->read('perfil')
      ->where(" cod = '{$r['user']}' ")
      ->toArray() ;

    if( empty($pages['pages']) ) return [];

    $resp = [];
    foreach ($pages['pages'] as $page_id) {
      $page = $r['tables']['pages']
        ->read()
        ->where( " cod = '{$page_id}' ")
        ->toArray();

      if( empty($page) ) continue;
      $page['title'] = utf8_encode($page['title']);
      $resp[] = $page;
    }

    return $resp;
  }
