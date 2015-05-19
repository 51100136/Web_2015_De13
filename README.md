# [SkyBox_API] (https://goo.gl/2Glazo)
Đề 13: Ứng dụng upload và quản lý file trên Box.net và Sky Drive

## Cài đặt để chạy thử source code
* Cài đặt đường dẫn đến thư mục chứa source code: http://localhost/Assignment/SkyBox_PHP/
* Thêm vào file host: 127.0.0.1 mytestingdomain.com
* Xóa 2 file token.box và token.sky khi chạy lần đầu tiên

## Các chức năng
### BOX.NET
* Đăng nhập sử dụng REST API kết hợp giao thức xác thực OAuth2.
* Lấy thông tin người dùng:                                   $box->get_user()
* Tương tác với Folders:
      * Lấy thông tin folder:                                 $box->get_folder_details()
      * Lấy danh sách các folder và file trong folder cha:    $box->get_folder_items()
      * Chỉ lấy danh sách các folder trong folder cha:        $box->get_folders()
      * Chỉ lấy danh sách các file trong folder cha:          $box->get_files()
      * Tạo một folder mới:                                   $box->create_folder()
      * Xóa một folder:                                       $box->delete_folder()
      * Chia sẻ một folder:                                   $box->share_folder()
* Tương tác với Files:
      * Lấy thông tin file:                                   $box->get_file_details()
      * Chia sẻ một file:                                     $box->share_file()
      * Upload một file:                                      $box->upload_file()
      * Download một file:                                    $box->download_file()
      * Xóa một file:                                         $box->delete_file()
* Tương tác với Token lấy được:
      * Đọc dữ liệu json từ file token.box:                   $box->read_token()
      * Nếu đoạn dữ liệu json vừa đọc có token thì lưu lại:   $box->load_token()
      * Lấy dữ liệu từ giao thức OAuth2 và lưu vào token.box: $box->write_token()
      * Xóa token và file token.box khi log out:              $box->delete_token()
* Một số hàm hỗ trợ cho việc tương tác
      * Gởi dữ liệu bằng GET:                                 $box->get()
      * Gởi dữ liệu bằng GET kèm Headers:                     $box->get_head()
      * Gởi dữ liệu dạng POST:                                $box->post()
      * Gởi dữ liệu dạng PUT:                                 $box->put()
      * Gởi dữ liệu dạng DELETE:                              $box->delete()
* Và một số hàm phụ trợ khác
