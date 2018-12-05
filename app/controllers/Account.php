<?php
    class Account extends Controller {
        public function __construct(){
            if(!isLoggedIn()){
                redirect('users/login');
            } 
            $this->userModel = $this->model('User');
        }

        public function index(){
            
            $this->view('account/tools');
        }

        public function user_management(){
            $pendingUsers = $this->userModel->getPendingUsers();
            $users = $this->userModel->getUsers();
            $power = false;
            if($_SESSION['user_email'] == 'admin@admin.com' || $_SESSION['user_email'] == 'admin2@admin.com'){
                $power = true;
            }
            $data = [
                'users' => $users,
                'pendingUsers' => $pendingUsers,
                'power' => $power
            ];

            $this->view('account/user_management', $data);
        }
        public function delete_user($id){
            if($_SESSION['user_email'] != 'admin@admin.com' && $_SESSION['user_email'] != 'admin2@admin.com'){
                flash('redirect_success', 'You can not delete user.','alert alert-danger');
                redirect('account/user_management');
            }
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                if($this->userModel->deleteUser($id)){
                    flash('redirect_success', 'User Deleted.');
                    redirect('account/user_management');
                } else {
                    die('Something went wrong');
                }
            } else {
                redirect('account/user_management');
            }
            
        }
        public function add_pending_user(){
            if($_SESSION['user_email'] != 'admin@admin.com' && $_SESSION['user_email'] != 'admin2@admin.com'){
                flash('redirect_success', 'You can add pending user.','alert alert-danger');
                redirect('account/user_management');
            }
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                //process the form
                //Sanitize POST data
                
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                //init data
                $data = [
                    'email' => trim($_POST['email']),
                    'confirm_email' => trim($_POST['confirm_email']),
                    'email_err' => '',
                    'confirm_email_err' =>''
                ];

                //VALIDATE EMAIL
                if(empty($data['email'])){
                    $data['email_err'] = 'Please enter email';
                } else {
                    //check email
                    if($data['email'] !== $data['confirm_email']){
                        $data['confirm_email_err'] = 'Email do not match';
                    } else {
                        if($this->userModel->findUserByEmail($data['email'])){
                            $data['email_err'] = 'Email is already registered';
                        }
                        if($this->userModel->findPendingUserByEmail($data['email'])){
                            $data['email_err'] = 'Email is already added';
                        }
                    }
                }

                if(empty($data['email_err']) && empty($data['confirm_email_err'])){
                    if($this->userModel->addPendingUser($data['email'])){
                        flash('redirect_success', 'Pending User added, user can register');
                        redirect('account/user_management');
                    } else {
                        die('Something went wrong');
                    }
                } else {
                    // load the view with errors
                    $this->view('account/add_pending_user', $data);
                }

            } else {
                //init data
                $data = [
                    'email' => '',
                    'confirm_email' => '',
                    'email_err' => '',
                    'confirm_email_err' =>''
                ];
                $this->view('account/add_pending_user', $data);
            }
        }

        public function update_password(){

            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                //process the form
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                //init data
                $data = [
                    'old_password' => trim($_POST['old_password']),
                    'new_password' => trim($_POST['new_password']),
                    'confirm_password' => trim($_POST['confirm_password']),
                    'old_password_err' => '',
                    'new_password_err' => '',
                    'confirm_password_err' => ''
                ];

                if(empty($data['old_password'])){
                    $data['old_password_err'] = 'Please enter current password';
                }

                if(empty($data['new_password'])){
                    $data['new_password_err'] = 'Please enter new password';
                } elseif (strlen($data['new_password']) < 6){
                    $data['new_password_err'] = 'Password must be at least 6 characters';
                }

                if(empty($data['confirm_password'])){
                    $data['confirm_password_err'] = 'Please enter confirm the new password';
                } else {
                    if($data['new_password'] != $data['confirm_password']){
                        $data['confirm_password_err'] = 'Password do not match';
                    }
                }

                //check for user email
                if($this->userModel->findUserByEmail($_SESSION['user_email'])){
                    //user found
                } else {
                    flash('redirect_success', 'Please log in','alert alert-danger');
                    redirect('users/login');
                }
                if(empty($data['old_password_err']) && empty($data['new_password_err']) && empty($data['confirm_password_err'])){
                    //check and set logged in user
                    $data['new_password'] = password_hash($data['new_password'], PASSWORD_DEFAULT);
                    $updateUserPassword = $this->userModel->updatePassword($_SESSION['user_email'], $data['old_password'],$data['new_password']);
                    
                    if($updateUserPassword){
                        unset($_SESSION['user_id']);
                        unset($_SESSION['user_email']);
                        unset($_SESSION['user_name']);
                        flash('redirect_success', 'Password Updated,Pleas log in with new Password');
                        redirect('users/login');
                    } else {
                        $data['old_password_err'] = 'Password incorrect';
                        $this->view('account/update_password', $data);
                    }
                } else {
                    // load the view with errors
                    $this->view('account/update_password', $data);
                }


            } else {
                
                //init data
                $data = [
                    'old_password' => '',
                    'new_password' => '',
                    'confirm_password' => '',
                    'old_password_err' => '',
                    'new_password_err' => '',
                    'confirm_password_err' => '',
                ];

                //load the form
                $this->view('account/update_password', $data);
            }
        }  
    }