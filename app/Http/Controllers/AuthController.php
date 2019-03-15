<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use JWTAuthException;

use App\User;

class AuthController extends Controller
{

    public function __construct(){
        $this->middleware('jwt.auth', ['except' => ['store', 'signin', 'allUser']]);
    }

    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:5'
        ]);

        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $token = str_random(40);

        $user = new User([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'remember_token' => $token
        ]);

        $credentials = [
            'email' => $email,
            'password' => $password
        ];

        if($user->save()){

            $token = null;
            try{
                if(!$token = JWTAuth::attempt($credentials)){
                    return response()->json([
                        'msg' => 'Email or Password are incorrect'
                    ], 404);
                }
            }catch (JWTAuthException $e){
                    return response()->json([
                        'msg' => 'Fail to create token'
                    ], 404);
            }

            $user->signin = [
                'href' => 'api/v1/user/signin',
                'method' => 'POST',
                'params' => 'email, password'
            ];
            $response = [
                'msg' => 'User created',
                'user' => $user,
                'token' => $token
            ];
            return response()->json($response, 201);
        }
        $response = [
            'msg' => 'An error occurred'
        ];
        return response()->json($response, 404);
    }

    public function signin(Request $request){
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:5'
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        if($user = User::where('email', $email)->first()){

        $credentials = [
            'email' => $email,
            'password' => $password
        ];

            $token = null;
            try{
                if(!$token = JWTAuth::attempt($credentials)){
                    return response()->json([
                        'msg' => 'Email or Password are incorrect'
                    ], 404);
                }
            }catch (JWTAuthException $e){
                    return response()->json([
                        'msg' => 'Fail to create token'
                    ], 404);
            }
            $result = [
                'id' => $user->id, 'name' => $user->name, 'date_created' => $user->created_at
            ];

            $response = [
                'msg' => 'User Signin',
                'user_details' => $result,
                'token' => $token
            ];
            return response()->json($response, 201);
        
        $response = [
            'msg' => 'An error occurred'
        ];
        return response()->json($response, 404);
      }
    }

    
    public function allUser(){
        $users = User::all();
        foreach($users as $user){
            $users_rec = [
                'href' => 'api/v1/users/',
                'method' => 'GET'
            ];
        }
        $response = [
            'msg' => 'List of all Users',
            'users' => $users,
            'view_users' => $users_rec
        ];
        return response()->json($response, 200);
    }

    public function allUserId($id){
        $users = User::find($id);
        if(!$users){
            return response()->json([
                'status' => 'failed',
                'error' => 'Record not exist in our System'
            ], 404);
        }
        $users->url_link = [
            'href' => 'api/v1/user/'. $id,
            'method' => 'GET'
        ];
        $response = [
            'msg' => 'View Users Details',
            'users' => $users,
            // 'view_users' => $users_rec            
        ];
        return response()->json($response, 200);
    }


    public function updateUser(Request $req, $id){
        try{
                $updUser = User::find($id);
                if(!$updUser){
                    return response()->json([
                        'status' => 'failed',
                        'error' => 'Record cannot be update'
                    ], 404);
                }else {
                    $users_rec = [
                        'href' => 'api/v1/users/users_id/'. $updUser->id,
                        'method' => 'PUT'
                    ];                    
                    $updUser->name = $req->input('name');
                    $updUser->email = $req->input('email');
                    // $updUser->password = bcrypt($password);
                    $updUser->password = app('hash')->make($req['password']);
                    if($updUser->save()){                        
                        $response = [
                            'msg' => 'User Update Successfully',
                            'status' => 'success',
                            'users' => $updUser,
                            'view_users' => $users_rec    
                        ];
                        return response()->json($response, 200);
                    }
                }                
            }catch(Exception $e){
                return response()->json([
                    'success' =>false,
                    'message' => 'Record not Exist',
                ], 400);
            }
    }

    public function deleteUser($id){
        $deleteUser = User::where('id', $id)->delete();
        $users_rec = [
            'href' => 'api/v1/users/delete/',
            'method' => 'DELETE'
        ];
        if($deleteUser){                    
            $response = [
                'msg' => 'User details delete Successfully',
                'view_users' => $users_rec    
            ];
            return response()->json($response, 200);
        }else{             
            return response()->json([
                'status' => 'failed',
                'error' => 'Invalid! Failed to delete record details - '. $id
            ], 401);
        }
    }


    // public function send(Request $request){
    //     $title = $request->input('title');
    //     $content = $request->input('content');
    //     //Grab uploaded file
    //     $attach = $request->file('file');

    //     Mail::send('emails.send', ['title' => $title, 'content' => $content], function ($message) use ($attach){
    //         $message->from('me@gmail.com', 'Christian Nwamba');
    //         $message->to('chrisn@scotch.io');
    //         //Attach file
    //         $message->attach($attach);
    //         //Add a subject
    //         $message->subject("Hello from Scotch");
    //     });
    // }

    // $emails = ['tester@blahdomain.com', 'anotheremail@blahdomian.com'];
    //     Mail::send('emails.lead', ['name' => $name, 'email' => $email, 'phone' => $phone], function ($message) use ($request, $emails)
    //     {
    //         $message->from('no-reply@yourdomain.com', 'Joe Smoe');
    //     //  $message->to( $request->input('email') );
    //         $message->to( $emails);
    //         //Add a subject
    //         $message->subject("New Email From Your site");
    //     });

}
 