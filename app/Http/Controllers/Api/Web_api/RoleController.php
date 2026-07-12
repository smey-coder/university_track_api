<?php

namespace App\Http\Controllers\Api\Web_api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    /**
     * Display all roles
     */
    public function index(Request $request)
    {
        try {

            $search = $request->search;


            $roles = Role::when($search, function($query) use ($search){

                $query->where('name','LIKE',"%{$search}%");

            })
            ->orderBy('id','desc')
            ->paginate(10);



            return response()->json([

                'success'=>true,

                'message'=>'Roles retrieved successfully',

                'data'=>$roles

            ]);


        } catch(\Exception $e){

            return response()->json([

                'success'=>false,

                'message'=>'Failed to get roles',

                'error'=>$e->getMessage()

            ],500);

        }
    }





    /**
     * Store new role
     */
    public function store(Request $request)
    {
        try {


            $validated = $request->validate([

                'name'=>[
                    'required',
                    'string',
                    'max:100',
                    'unique:roles,name'
                ],

                'guard_name'=>[
                    'nullable',
                    'string'
                ]

            ]);



            $role = Role::create([

                'name'=>$validated['name'],

                'guard_name'=>$validated['guard_name'] ?? 'sanctum'

            ]);



            return response()->json([

                'success'=>true,

                'message'=>'Role created successfully',

                'data'=>$role

            ],201);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>'Create role failed',

                'error'=>$e->getMessage()

            ],500);

        }
    }

    /**
     * Show single role
     */
    public function show($id)
    {

        try {


            $role = Role::find($id);



            if(!$role){

                return response()->json([

                    'success'=>false,

                    'message'=>'Role not found'

                ],404);

            }



            return response()->json([

                'success'=>true,

                'data'=>$role

            ]);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>'Get role failed',

                'error'=>$e->getMessage()

            ],500);


        }

    }

    /**
     * Update role
     */
    public function update(Request $request,$id)
    {

        try {


            $role = Role::find($id);



            if(!$role){


                return response()->json([

                    'success'=>false,

                    'message'=>'Role not found'

                ],404);

            }



            $validated=$request->validate([


                'name'=>[

                    'required',

                    'string',

                    'max:100',

                    Rule::unique('roles','name')
                    ->ignore($role->id)

                ]

            ]);



            $role->update([

                'name'=>$validated['name']

            ]);




            return response()->json([


                'success'=>true,

                'message'=>'Role updated successfully',

                'data'=>$role
            ]);
        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>'Update role failed',

                'error'=>$e->getMessage()

            ],500);


        }

    }

    /**
     * Delete role
     */
    public function destroy($id)
    {

        try {


            $role = Role::find($id);



            if(!$role){


                return response()->json([

                    'success'=>false,

                    'message'=>'Role not found'

                ],404);

            }



            $role->delete();



            return response()->json([


                'success'=>true,

                'message'=>'Role deleted successfully'


            ]);



        }catch(\Exception $e){


            return response()->json([

                'success'=>false,

                'message'=>'Delete role failed',

                'error'=>$e->getMessage()

            ],500);


        }

    }


}