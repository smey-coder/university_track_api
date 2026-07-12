<?php

namespace App\Http\Controllers\Api\Web_api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;


class UserRoleController extends Controller
{


    /**
     * Get user roles
     */
    public function show($userId)
    {

        try {


            $user = User::find($userId);


            if(!$user){

                return response()->json([
                    'success'=>false,
                    'message'=>'User not found'
                ],404);

            }


            return response()->json([

                'success'=>true,

                'data'=>[

                    'user'=>$user,

                    'roles'=>$user
                        ->getRoleNames()

                ]

            ]);


        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>$e->getMessage()

            ],500);

        }

    }





    /**
     * Assign roles to user
     */
    public function store(Request $request,$userId)
    {

        try {


            $user = User::find($userId);


            if(!$user){

                return response()->json([
                    'success'=>false,
                    'message'=>'User not found'
                ],404);

            }



            $request->validate([

                'roles'=>'required|array',

                'roles.*'=>'exists:roles,name'

            ]);



            // Replace old roles
            $user->syncRoles(
                $request->roles
            );



            return response()->json([

                'success'=>true,

                'message'=>'Roles assigned successfully',

                'data'=>[

                    'user'=>$user->username,

                    'roles'=>$user->getRoleNames()

                ]

            ]);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>$e->getMessage()

            ],500);

        }

    }





    /**
     * Remove role from user
     */
    public function destroy(Request $request,$userId)
    {

        try {


            $user = User::find($userId);


            if(!$user){

                return response()->json([
                    'success'=>false,
                    'message'=>'User not found'
                ],404);

            }



            $request->validate([

                'role'=>'required|string'

            ]);



            $user->removeRole(
                $request->role
            );



            return response()->json([

                'success'=>true,

                'message'=>'Role removed successfully'

            ]);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>$e->getMessage()

            ],500);

        }

    }

}