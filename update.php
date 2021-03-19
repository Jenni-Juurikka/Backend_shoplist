<?php
require_once 'inc/headers.php';
require_once 'inc/functions.php';

$input = json_decode(file_get_contents('php://input'));
$id = filter_var($input->id,FILTER_SANITIZE_NUMBER_INT);
$description = filter_var($input->description,FILTER_SANITIZE_STRING);
$amount = filter_var($input->amount,FILTER_SANITIZE_NUMBER_INT);

try {
    $db = openDb();

    $query = $db->prepare('update item set description=:description where id=:id');
    $query = $db->prepare('update item set amount=:amount where id=:id');
    $query->bindValue(':description',$description,PDO::PARAM_STR);
    $query->bindValue(':amount',$amount,PDO::PARAM_INT);
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();

    header('HTTP/1.1 200 OK');
    $data = array('id' => $db->lastInsertId(),'description' => $description,'amount' => $amount);
    echo json_encode($data);
    
} catch (PDOException $pdoex) {
    returnError($pdoex);
}