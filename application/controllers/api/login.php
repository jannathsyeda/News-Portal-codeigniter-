<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     

     
class Login extends REST_Controller {
    
	  /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
       parent::__construct();
       $this->load->database();
       $this->load->library('form_validation');
       $this->load->model('user_model', 'UserModel');

    }
       


/**
     * User Register
     * --------------------------
     * 
     * @param: username
    * @param: email
     * @param: password
     * --------------------------
     * @method : POST
     * @link : api/Login/register
     */

    public function register_post()
    {
                header("Access-Control-Allow-Origin: *");

        # XSS Filtering (https://www.codeigniter.com/user_guide/libraries/security.html)
                $_POST = $this->security->xss_clean($_POST);

              # Form Validation
        $this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[users.username]|alpha_numeric|max_length[20]',
            array('is_unique' => 'This %s already exists please enter another username')
        );
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[80]|is_unique[users.email]',
            array('is_unique' => 'This %s already exists please enter another email address')
        );
        $this->form_validation->set_rules('password', 'Password', 'trim|required|max_length[100]');


        if ($this->form_validation->run() == FALSE)
        {
            // Form Validation Errors
            $message = array(
                'status' => false,
                'error' => $this->form_validation->error_array(),
                'message' => validation_errors()
            );
            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
        else
        {
            $insert_data = [
               
                'username' => $this->input->post('username', TRUE),
                'password' => ($this->input->post('password', TRUE)),
                 'email' => $this->input->post('email', TRUE),
            ];

             // Insert User in Database
            $output = $this->UserModel->insert_user($insert_data);
            print_r($output);
            // if ($output > 0 AND !empty($output))
            // {
            //     // Success 200 Code Send
            //     $message = [
            //         'status' => true,
            //         'message' => "User registration successful"
            //     ];
            //     $this->response($message, REST_Controller::HTTP_OK);
            // } else
            // {
            //     // Error
            //     $message = [
            //         'status' => FALSE,
            //         'message' => "Not Register Your Account."
            //     ];
            //     $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            // }




    }



        }
    










/**
     * User Login API
     * --------------------
     * @param: username or email
     * @param: password
     * --------------------------
     * @method : POST
     * @link: api/Login/login
     */

  function login_post()
  {
  	header("Access-Control-Allow-Origin: *");
        # XSS Filtering (https://www.codeigniter.com/user_guide/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);
        
        # Form Validation
        $this->form_validation->set_rules('username', 'username', 'trim|required');
        $this->form_validation->set_rules('password', 'password', 'trim|required|max_length[100]');
        if ($this->form_validation->run() == FALSE)
        {
            // Form Validation Errors
            $message = array(
                'status' => false,
                'error' => $this->form_validation->error_array(),
                'message' => validation_errors()
            );
            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
        else
        {

        	// Load Login Function
            $output = $this->UserModel->user_login($this->input->post('username'), $this->input->post('password'));
        if (!empty($output) AND $output != FALSE)
        {

        	$return_data =[
                 'id'=>$output->id,
                 'username'=> $output->username,
                 'password'=> $output->password
        	];
        	// Login Success
                $message = [
                    'status' => true,
                    'data' => $return_data,
                    'message' => "User login successful"
                ];
                $this->response($message, REST_Controller::HTTP_OK);
        }

        else
        {
        	// Login Error
                $message = [
                    'status' =>false,
                    'message' => "Invalid Username or Password"
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
        }
  }


       
      }
