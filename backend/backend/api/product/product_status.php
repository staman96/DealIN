<?php
// required headers
include '../../config/core.php';

$product = new Product($dbconn);

$products = $product->getProductsByPassedDate();

echo date("d-m-Y h:i:s");
foreach ($products as $upd) {
    // $product_end_date = new DateTime($upd->$auction_ends);
    // $now = new DateTime();
    $date_compare1 = date("d-m-Y h:i:s", strtotime($upd->auction_ends));
    // date now
    $date_compare2 = date("d-m-Y h:i:s");
   
    // Compare the dates 
    var_dump($date_compare1 < $date_compare2);
    if ($date_compare1 < $date_compare2) {
        $update = new Product($dbconn);
        $update->product_id = $upd->product_id;
        $update->product_status = 2;    
        $check_update = $update->updateProductStatus();
        if ($check_update) {
            // $message = new Messages($dbconn);
            $LastBid = new Bid($dbconn);
            $LastBid->product_id = $upd->product_id;
            $bid = $LastBid->currentBid();

            // Get Sender
            $sender = new User($dbconn);
            $sender->user_id = $bid->user_id;
            $senderData = $sender->read_one();

            // Get Receiver
            $receiver = new User($dbconn);
            $receiver->user_id = $upd->user_id;
            $receiverData = $receiver->read_one();

            // Create Message
            $message = new Messages($dbconn);
            $message->message_title = 'Auction Ends for '.$upd->product_name;
            $message->message_text = 'Hello '.$receiverData->fname.' '.$receiverData->lname . ',<br> the user '.$senderData->fname . ' ' . $senderData->lname. ' won this auction at price $'.$bid->bid_amount.'.<br> Please response to this message so you can arrange the next steps!<br>';
            $message->sender_user_id = $senderData->user_id;
            $message->sender_name = $senderData->user_name;
            $message->receiver_user_id = $receiverData->user_id;
            $message->receiver_name = $receiverData->user_name;
            $message->product_id = $upd->product_id;
            $message->create();
    
        }
    }

}