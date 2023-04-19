<?php 
    require_once './ConnectDb/index.php';
    require_once 'index.php';
    class UserController {
        private $db ;
        public function __construct(){
            $database = new DB();
            $this->db = $database->getInstance();
        }
        public function connectDb() {
            $database = new DB();
            $this->db = $database->getInstance();
        }

        public function getDataFrom($query) {
            $result = $this->db->query($query);
            if ($result !== false && $result->num_rows > 0) {
                $row = mysqli_fetch_assoc($result);
                return $row;
            } else {
               return null;
            }
        }

        public function register(){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents("php://input"), true);
                if(isset($data['username']) && $data['password'] && $data['confirm_password'] ) {
                    $username = $data['username'];
                    $password = $data['password'];
                    $confirm_password = $data['confirm_password'];
                    $email = $data['email'] ?? '';
                    $errors = array();
                    if (empty($username)) {
                        $errors[] = "Username is required";
                    }
                    if (empty($password)) {
                        $errors[] = "Password is required";
                    }
                    if ($password != $confirm_password) {
                        $errors[] = "Passwords do not match";
                    }
                    if (empty($errors)) {
                        $password = password_hash($password, PASSWORD_DEFAULT);
                        $query = "SELECT * FROM users where username = '$username'";
                        if( mysqli_num_rows($this->db->query($query)) > 0 ) {
                            echo json_encode(array('status'=>false, 'message'=> 'User already!!'));
                            return;
                        } else {
                            $query = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
                            if ($this->db->query($query) === True) {
                                echo json_encode(array('status'=>true, 'message'=> 'Register successfully!!'));
                                return;
                            } else {
                                echo json_encode(array('status'=>false, 'message'=> 'Register successfully!!'));
                                return;
                            }
                        }
                    } else {
                        echo json_encode(array('status'=>false, 'message'=> 'Registered failed!!'));
                        return ;

                    }
                }else {
                    echo json_encode(array('status'=> false , 'message'=> 'Please enter your username and password !!!'));
                }
            }
            $this->db->close();
        }
        public function login() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents("php://input"), true);
                if(isset($data['username']) && $data['password'] ) {
                    $username = $data['username'];
                    $password = $data['password'];
                    
                    $query = "select * from users where username ='$username'";
                    $user = $this->getDataFrom($query);
                    if( !$user ){
                        echo json_encode(array('status'=>false, 'message'=> 'User not found!! please re-login'));
                        return;
                    }
                    if ($user && password_verify($password, $user['Password'])) {
                        echo json_encode(array('status'=>true, 'message'=> 'Login successfully!!', 'data'=> json_encode(
                            array('username'=>$user['Username'], 'user_id'=>$user['UserID'])
                        )));
                        return;
                    } else {
                        echo json_encode(array('status'=>false, 'message'=> 'Password not match please re-login'));
                        return ;
                    }
                }else {
                    echo json_encode(array('status'=>false, 'message'=> 'Please enter username and password'));
                }
            }else {
                echo json_encode(array('status'=>false, 'message'=> 'Method not allowed'));
            }
        }
        public function logout() {
            echo 'logout';
        }
        // public function post() {
        //     if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //         $user_id = $_POST["user_id"] ?? '';
        //         $title = $_POST["title"] ?? '';
        //         $content = $_POST["content"] ?? '';
        //         $photo = $_POST["photo"] ?? '';
        //         $video = $_POST["video"] ?? '';
        //         // $this->addPost($user_id, $title, $content, $image_url, $video_url);
        //     }
        // }

    //     public function addPost($user_id, $title, $content, $image_url, $video_url) {
    //         $sql = "INSERT INTO Posts (UserID, Title, Content, ImageURL, VideoURL, CreatedAt) VALUES ('$user_id', '$title', '$content', '$image_url', '$video_url', NOW())";
          
    //         if ($this->db->query($sql) === TRUE) {
    //           echo json_encode(array('status'=>true, 'message'=>'Post successfully!!', 'data'=>json_encode(
    //             array('title'=>$title, 'content'=>$content, 'imageUrl'=>$image_url, 'videoUrl'=>$video_url)
    //           )));
    //           return;
    //         } else {
    //           echo json_encode(array('status'=>false, 'message'=>'Post fail!!'));
    //           return;
    //         }
    //         $this->db->close();
    //       }

    

    public function post() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $user_id = $_POST["user_id"] ?? '';
            $title = $_POST["title"] ?? ' ';
            $content = $_POST["content"] ?? '';
            $photo = $_FILES["photo"] ?? null;
            $video = $_FILES["video"] ?? null;

            if (empty($user_id) ) {
                echo json_encode(array('status'=>'false', 'message'=>"Please enter information"));
                return;
            }
    
            $image_url = null;
            $video_url = null;
    
            $query = "INSERT INTO posts (UserID, Title, Content, ImageURL, VideoURL) VALUES ($user_id, '$title', '$content', '$image_url', '$video_url')";
            $res = $this->db->query($query);
            if($res) {
                $post_id = $this->db->insert_id;
                if ($photo && $photo["error"] == UPLOAD_ERR_OK) {
                    $target_dir = "photo_upload/";
                    $ext = pathinfo($photo["name"], PATHINFO_EXTENSION);
                    $image_url = $target_dir . "photo_" . $post_id . "." . $ext;
                    $target_file = $target_dir . basename($image_url);
                    if (!move_uploaded_file($photo["tmp_name"], $target_file)) {
                        echo json_encode(array('status'=>'false', 'message'=>"Load image failed"));
                        return;
                    }
                }
        
                // Lưu tệp video (nếu có)
                if ($video && $video["error"] == UPLOAD_ERR_OK) {
                    $target_dir = "video_upload/";
                    $ext = pathinfo($video["name"], PATHINFO_EXTENSION);
                    $video_url = $target_dir . "video_" . $post_id . "." . $ext;
                    $target_file = $target_dir . basename($video_url);
                    if (!move_uploaded_file($video["tmp_name"], $target_file)) {
                        echo json_encode(array('status'=>'false', 'message'=>"Load video failed"));
                        return;
                    }
                }
        
                $stmt = $this->db->prepare("UPDATE posts SET ImageURL=?, VideoURL=? WHERE PostID=?");
                $stmt->bind_param("ssi", $image_url, $video_url, $post_id);
                $stmt->execute();
        
                $stmt = $this->db->prepare("SELECT * FROM posts WHERE PostID=?");
                $stmt->bind_param("i", $post_id);
                $stmt->execute();
                $result = $stmt->get_result()->fetch_assoc();
        
                $post_data = array(
                    "post_id" => $post_id,
                    "user_id" => $result["UserID"]??'',
                    "title" => $result["Title"]??"",
                    "content" => $result["Content"]??'',
                    "photo_url" => $result["ImageURL"]??'',
                    "video_url" => $result["VideoURL"]??""
                );
                echo json_encode(array('status'=>'true', 'data'=> json_encode($post_data)));
                return;
            }else {
                echo json_encode(array('status'=>'false', 'message'=>"Post new failed"));
                return;
            }
    
            $this->db->close();
            return;
        }    
    }
    



    }

?>
