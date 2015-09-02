<?php
/*
Template Name: _mail from slack (committeeitdart@googlegroups.com)
これは、itdart.org WordPressテーマ上で動作させることを前提としていて、
専用に１こ固定ページをつくり、このテンプレートを読み込ませ、
その固定ページのURIでslack apiと連携させ動作させる

あと、tokenは slack api outbound webhooksで設定したのをいれる
送信先アドレスで、headersにいれてあるfromのアドレスを許可するように
↑Google groupsなど
*/

if( isset($_POST['token']) && $_POST['token'] == '' ) {
    // mailfromslackならばこっち
    
    $text = $_POST['text'];
    $text_arr = preg_split('/[\s]+/', $text, 4);

    $sendUser = htmlspecialchars($_POST['user_name']);
    $fromCh = htmlspecialchars($_POST['channel_name']);
    
    $addTo = 'committeeitdart@googlegroups.com';
    
    $setSubject = '[slack #'.$fromCh.'] '.$text_arr[1];
    
    $setText = $sendUser.'が http://itdart.slack.com/ #'.$fromCh." から送信 \n\n".$text_arr[2]."\n\n"."-- \n".'slackより自動送信';
    
    $headers[] = 'From: '.$sendUser.' <tpl_mailfromslack.php@itdart.org>';
    $headers[] = 'Cc: sakadon@itdart.org';
    
    $return = wp_mail( $addTo, $setSubject, $setText, $headers );
    
    if($return){
        echo json_encode(array('text' => 'committeeitdart@googlegroups.com に送信が実行！ちゃんと送れてるか確認してね'));
        //echo json_encode(array('text'=>$text_arr));
        //echo json_encode(array('text'=>'this: '.implode(",", $addTo_arr)));
    } else {
        echo json_encode(array('text' => 'committeeitdart@googlegroups.com に送信失敗！'."\n".'メールサーバがおかしいか、XSERVER/itdart.org/public_html/itdartwp/wp-content/themes/itdart_theme/tpl_mailfromslack1.php を確認してね。この失敗は wp_mail() 関数による判断です。'));
        //echo json_encode(array('text'=>'wp_mail('.$addTo.', '.$setSubject.', '.$setText.', '.$headers.' );'));
        //echo json_encode(array('text'=>'this: '.implode(",", $addTo_arr)));
    }
    
    
  
} else {
    // mailfromslackじゃなければ404
    wp_safe_redirect( home_url() );
    exit;

} 