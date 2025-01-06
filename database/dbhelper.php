<?php
    # File này được dùng để xây dựng các hàm dùng chung cho toàn bộ dự án
    require_once('config.php');

//INSERT -  UPDATE - DELETE
    function execute($sql) {
        # Kết nối tới cơ sở dữ liệu
        $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
        mysqli_set_charset($conn,'utf8');

        // xử lí truy vấn
        mysqli_query($conn, $sql);

        // đóng kết nối
        mysqli_close($conn);
    }

//SELECT: 
    //Hàm này dùng để lấy dữ liệu từ cơ sở dữ liệu và trả về dữ liệu đó, hàm sẽ lấy 1 hoặc nhiều bản ghi.
        //tham số isSingle(mặc định là false) 
        //cho phép người dùng lấy 1 bản ghi duy nhất (nếu $isSingle = true) 
        //hoặc lấy tất cả bản ghi từ kết quả truy vấn (nếu $isSingle = false)    
    function executeResult($sql, $isSingle = false){ 
        $data = null; //chứa dữ liệu đầu ra
        $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE); // kết nối tới db
        mysqli_set_charset($conn,'utf8'); // thiết lập bộ ký tự cho kết nối csdl, hỗ trợ hiển thị tiếng Việt

        // xử lí câu truy vấn
        $resultset = mysqli_query($conn, $sql);

        if($isSingle) { // nếu $isSingle = true
            $data = mysqli_fetch_array($resultset, 1);
            //chỉ lấy duy nhất 1 bản ghi
        }
        else { 
            //$isSingle = false
            //lấy từng bản ghi 1
            $data = [];
            while(($row = mysqli_fetch_array($resultset, 1)) != null) {
                //nếu isSingle false, lấy tất cả bản ghi và thêm mỗi bản ghi này vào mảng $data
                $data[] = $row;
            }
        }
        // đóng kết nối
        mysqli_close($conn);

        return $data;
    }
?>