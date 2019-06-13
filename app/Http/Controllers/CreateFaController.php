<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\User;

class CreateFaController extends Controller
{
    //
    /**
     * Create a new CreateFaController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }


    /**
     * This endpoint creates a Financial Advisor(FA) /POST/
     */

    /**
     * Below is the request body
     * {
     *      "first_name":"UserFirstName",
     *      "last_name":"UserLastName",
     *      "email":"UserEmail"
     * }
     */

    public function createFA(Request $request){
        /**
         * This operation can only be performed by a Distribution agent or a system admin
         * Once an FA is created, a system generated password is sent to the user's email for the initial login.
         */
        // Validate if user is admin or distribution agent
        $role = (string)$request->user()->role_id;

        if ($role === "1" || $role === "3" ){
            // Validate the input
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email|unique:users',
            ]);

            if ($validator->fails()){
                return response()->json(['error'=>$validator->errors()], 401);
            }

            $firstName = $request->first_name;
            $lastName = $request->last_name;
            $email = $request->email;
            $password = $this->generatePassword();
            $role_id = 2;

            try{
                User::create([
                    'first_name'=> $firstName,
                    'last_name'=> $lastName,
                    'email'=> $email,
                    'role_id'=>$role_id,
                    'password'=>Hash::make($password),
                ]);

                return response()->json(['status'=>'1', 'msg'=>'FA added successfully with password '.$password], 201);


            } catch(\Exception $e){
                return response()->json(['error'=>'FA not created', 'msg'=>$e]);
            }

            // call function to send the email
        }
        else{
            return response()->json(['msg'=>'qqqqqqq']);
        }



    }


     /**
      * This function generates a random password
      */
      public function generatePassword(){
          $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
          $pass = array();
          $alphaLength = strlen($alphabet) - 1;
          for ($i = 0; $i < 8; $i++) {
              $n = rand(0, $alphaLength);
              $pass[] = $alphabet[$n];
          }
          return implode($pass); //turn the array into a string

      }
}
