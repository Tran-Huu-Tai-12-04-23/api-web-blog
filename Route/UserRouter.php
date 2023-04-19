<?php 
    require_once './Controller/UserController.php';
    class UserRouter{
        protected $userController;

        public function __construct()
        {   
            $this->userController = new UserController();
        }

        public function handleRequest($action) {
            switch($action) {
                case 'register':{
                    $this->userController->register();
                    break;
                }
                case 'login': {
                    $this->userController->login();
                    break;
                } 
                case 'logout':{
                    $this->userController->logout();
                    break;
                }
                case 'post': {
                    $this->userController->post();
                    break;
                }
                default : echo "action not found";
            }
        }
    }
?>
