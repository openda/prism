<?php

/**
 * Created by PhpStorm.
 * User: wangxk1991@gmail.com
 * Date: 2017/5/25
 * Time: 下午2:54
 * Desc:
 */
//class test {
//
//}
try {
    $pdo = new PDO("sqlite:/phpstudy/www/prism/prism/db/prism.db", null, null);
//    $pdo->exec("SET NAMES utf8;");
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT);
    $update = 'UPDATE user SET user_email=:user_email,user_phone=:user_phone,user_pwd=:user_pwd,update_time=:update_time WHERE user_name = :user_name';
    $select = 'SELECT * FROM  user  WHERE user_name = :user_name';
    echo "prepare\n";
    $stmt = $pdo->prepare($select);
//    $stmt = $pdo->prepare($update);
//    var_dump("$stmt");
//    $stmt->bindValue(":userpwd", "defac44447b57f152d14f30cea7a73cb");
//    $stmt->bindValue(":username", "aaaaa");
    echo "execute\n";
//    $result = $stmt->execute([":user_email" => "897994454@qq.com", ":user_phone" => "18611470725", ":user_pwd" => "25dd283aa400af464c76d713c07ad", ":update_time" => "2017-05-25 20:33:58", ":user_name" => "aaaaa"]);
    $result = $stmt->execute([":user_name" => "aaaaa"]);

    var_dump($stmt->fetchAll());
} catch (Exception $e) {
    echo $e->getMessage();
}

echo "start\n";
