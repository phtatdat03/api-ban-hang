<?php

    // Hàm này fix lỗi SqlInjection
    // Hàm này thực hiện bỏ qua các kí tự đặc biệt (vd: ' và \)
    // trong chuỗi để giảm nguy cơ SQL Injection
    function fixSqlInject($sql) {
        $sql = str_replace('\\', '\\\\',$sql);// thay thế dấu '\' thành '\\'
        $sql = str_replace('\'', '\\\'', $sql);// thay thế dấu ' thành \'
        return $sql;
    }

    function getGet($key) {
        // Lấy giá trị của tham số từ query string(lấy dữ liệu trực tiếp từ URL)
        // không an toàn cho dữ liệu nhạy cảm của người dùng như (email, mật khẩu)
        // chỉ thích hợp cho các truy vấn tìm kiếm
        // VD: facebook.com?name=John, gọi getGet('name') sẽ trả về John.
        $value = '';
        if(isset($_GET[$key])) { // kiểm tra tham số key có tồn tại trong $_GET không
            // nếu có lấy giá trị và áp dụng hàm fixSqlInject ở trên để xử lý dữ liệu và trả về giá trị đã được xử lý
            $value = $_GET[$key];
            $value = fixSqlInject($value);
        }
        return trim($value);
    }


    function getPost($key) {
        //dữ liệu k hiển thị trên URL, thường lấy từ FORM, tạo/sửa/xóa dữ liệu
        //an toàn cho các dữ liệu nhạy cảm
        //thích hợp cho việc xử lý đăng nhập, gửi form
        $value = '';
        if(isset($_POST[$key])) {
            $value = $_POST[$key];
            $value = fixSqlInject($value);
        }
        return trim($value);
    }

    function getCookie($key) {
        $value = '';
        if(isset($_COOKIE[$key])) {
            $value = $_COOKIE[$key];
            $value = fixSqlInject($value);
        }
        return trim($value);
    }

    function getSecurityMD5($pwd) { // mã hóa 2 lần
        // mã hóa lần 1: mật khẩu người dùng
        // mã hóa lần 2: mk đã mã hóa + chuỗi từ private_key
        return md5(md5($pwd).PRIVATE_KEY);
    }

    function getUserToken(){
        //lưu phiên đăng nhập
        //nếu đã đăng nhập trc đó r hệ thống sẽ tự động nhảy vào trang admin
        if(isset($_SESSION['user'])) { //ktra user có tồn tại chưa
            return $_SESSION['user']; // nếu tồn tại trả về thông tin 
        }
        $token = getCookie('token');//lấy token từ cookie và xác thực trong db
        $sql = "select * from tokens where token ='$token'";
        $item = executeResult($sql,true); // nếu token tồn tại kết quả sẽ được gán vào biến $item
        if ($item!=NULL){ 
            $userId = $item['user_id'];
            $sql = "select * from user where id ='$userId' and deleted = 0"; // kiểm tra nếu tài khoản bị xóa ( trường deleted = 1 thì không cho vào) 
            $item = executeResult($sql,true);
            if($item!=NULL){
                $_SESSION['user'] = $item;
                return $item;
            }
        }
        return NULL;
    }
    function sendResponse($status, $data = null, $message = '') {
        $response = ['status' => $status];
        if ($data !== null) {
            $response['data'] = $data;
        }
        if ($message !== '') {
            $response['message'] = $message;
        }
        echo json_encode($response);
        exit;
    }
?>