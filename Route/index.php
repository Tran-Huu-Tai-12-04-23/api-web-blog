<?php 
include 'UserRouter.php';
class Router{
    protected $userRouter;

    public function __construct()
    {   
        $this->userRouter = new UserRouter();
    }

    public function switchRequest( $url,$action ) {
        switch($url) {
            case 'user':{
                $this->userRouter->handleRequest($action);
                break;
            }
        }
    }
}
?>
