<?php
/**
 * Created by PhpStorm.
 * User: E.FARID
 * Date: 28-Sep-19
 * Time: 2:48 AM
 */
namespace App\Controller;
use Cake\Http\Client;
use Cake\Auth\DefaultPasswordHasher;

class UsersController extends AppController {
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
    }
    /**
     * Users Login
     */
    public function login() {
        if ($this->request->is('post')) {
            $this->loadModel('UserAuthentications');
            $postData = $this->request->getData();
            if (!empty($postData['email']) && !empty($postData['password'])) {
                $this->loadComponent('Auth',[
                    'authenticate' => [
                        'fields' => [
                            'username' => 'email',
                            'password' => 'password'
                        ]
                    ]
                ]);
                $user = $this->Auth->identify();
                //-----------------------------
                if ($user) {
                    $authData = [
                        'user_id' => $user['id'],
                        'user_no' => $user['user_no'],
                        'auth_token' => 'TNTJ-URP'.time(),
                        'platform' => $this->request->getData('platform')
                    ];
                    $authEntity = $this->UserAuthentications->newEntity($authData);
                    if ($authSaveData = $this->UserAuthentications->save($authEntity)) {
                        $userData = [
                            'user_no' => $authSaveData->user_no,
                            'auth_token' => $authSaveData->auth_token,
                            'platform' => $authSaveData->platform
                        ];
                        $this->response->statusCode(200);
                        $this->appResponse = [
                            'message' => 'Login successfull',
                            'data' => $authSaveData
                        ];
                    }
                } else {
                    $this->response->statusCode(405);
                    $this->appResponse = [
                        'message' => 'Unauthorized access'
                    ];
                }
            } else {
                $i = 0;
                if (empty($postData['email'])) {
                    $error[$i]['field'] = 'email';
                    $error[$i]['message'] = 'Required fields are missing';
                    $i++;
                }
                if (empty($postData['password'])) {
                    $error[$i]['field'] = 'password';
                    $error[$i]['message'] = 'Required fields are missing';
                }
                $this->response->statusCode()
            }
        }
    }
    /**
     * Signup
     */
    public function addUser() {
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            if (!empty($postData['name']) && !empty($postData['email'])
                && !empty($postData['mobile']) && !empty($postData['password'])) {
                $this->loadModel('Users');
                $userArray = [
                    'user_no' => 'TNTJ-URUP'.time(),
                    'name' => $postData['name'],
                    'email' => $postData['email'],
                    'password' => $postData['password'],
                    'mobile' => $postData['mobile'],
                    'status' => 1
                ];
                $userEntity = $this->Users->newEntity($userArray);
                if ($userSaveData = $this->Users->save($userEntity)) {
                    $this->response->statusCode(201);
                    $this->appResponse = [
                        'message' => 'Signup successfull'
                    ];
                } else {
                    $this->response->statusCode(422);
                    $this->appResponse = [
                        'message' => 'Signup failed'
                    ];
                }
            } else {
                $this->response->statusCode(422);
                $this->appResponse = [
                  'message' => 'Required fields are missing'
                ];
            }
        } else {
            $this->response->statusCode(405);
            $this->appResponse = [
                'message' => 'No route found for this request'
            ];
        }
        $response = json_encode($this->appResponse);
        return $this->response->body($response);
    }
}