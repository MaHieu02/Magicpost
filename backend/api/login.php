<?php

    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');


    include_once('../config/db.php');
    include_once('../model/account.php');
    include_once('../model/admin.php');
    include_once('../model/account_user.php');

    $db = new Db_api();
    $connect = $db->connect();

    $admin = new Admin($connect);
    $account = new Account($connect);
    $user = new Account_user($connect);

    $data = json_decode(file_get_contents("php://input"));
    $admin->Ten_tai_khoan = $data->user_name;
    $account->Ten_tai_khoan = $data->user_name;
    $user->Ten_tai_khoan = $data->user_name;

    $counta = $admin->login_admin();
    $countb = $account->login_staff();
    $countc = $user->login_user();

    while($rowa = $counta->fetch(PDO::FETCH_ASSOC)) {
        extract($rowa);
        if($Mat_khau === $data->password) {
            session_start();
            $_SESSION['userlogin']=$data->user_name;  
            echo json_encode("đăng nhập tài khoản chủ tịch thành công");
            return true;
        }
    }
    while($rowb = $countb->fetch(PDO::FETCH_ASSOC)) {
        extract($rowb);
        if($Mat_khau === $data->password && $Vai_tro === 'Quản lý điểm tập kết') {
            session_start();
            $_SESSION['userlogin']=$data->user_name;   
            echo json_encode("đăng nhập quản lý tập kết thành công");
            return true;
        } 
        else if($Mat_khau === $data->password && $Vai_tro === 'Quản lý điểm giao dịch') {
            session_start();
            $_SESSION['userlogin']=$data->user_name;            
            echo json_encode("đăng nhập quản lý giao dịch thành công");
            return true;
        }
        else if($Mat_khau === $data->password && $Vai_tro === 'Giao dịch viên') {
            session_start();
            $_SESSION['userlogin']=$data->user_name;   
            echo json_encode("đăng nhập nhân viên giao dịch thành công");
            return true;
        } 
        else if($Mat_khau === $data->password && $Vai_tro === 'Nhân viên điểm tập kết') {
            session_start();
            $_SESSION['userlogin']=$data->user_name;   
            echo json_encode("đăng nhập nhân viên tập kết thành công");
            return true;
        } 
    }
    while($rowc = $countc->fetch(PDO::FETCH_ASSOC)) {
        extract($rowc);
        if($Mat_khau === $data->password) {
            echo json_encode(array("message","đăng nhập tài khoản người dùng thành công"));
            return true;
        }
    }
    echo json_encode(array("message","Tên tài khoản hoặc mật khẩu không chính xác"));
    return false;

?>
