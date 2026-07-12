<?php

namespace App\Http\Controllers\Api\Web_api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{

    /**
     * Display all permissions
     */
    public function index(Request $request)
    {
        try {

            $search = $request->search;

            // Create query builder
            $query = Permission::when($search, function ($query) use ($search) {

                $query->where('name', 'LIKE', "%{$search}%");

            })->orderBy('id', 'desc');


            // Check all=true or pagination
            if ($request->boolean('all')) {

                $permissions = $query->get();

            } else {
                $permissions = $query->paginate(10);
            }
            return response()->json([

                'success' => true,

                'message' => 'Permissions retrieved successfully',

                'data' => $permissions
            ]);
        } catch(\Exception $e) {

            return response()->json([

                'success' => false,

                'message' => 'Failed to retrieve permissions',

                'error' => $e->getMessage()

            ],500);

        }
    }
    /**
     * Store new permission
     */
    public function store(Request $request)
    {
        try {


            $validated = $request->validate([

                'name'=>[
                    'required',
                    'string',
                    'max:100',
                    'unique:permissions,name'
                ],

                'guard_name'=>[
                    'nullable',
                    'string'
                ]

            ]);



            $permission = Permission::create([

                'name'=>$validated['name'],

                'guard_name'=>$validated['guard_name'] ?? 'sanctum'

            ]);



            return response()->json([

                'success'=>true,

                'message'=>'Permission created successfully',

                'data'=>$permission

            ],201);



        } catch(\Exception $e) {


            return response()->json([

                'success'=>false,

                'message'=>'Create permission failed',

                'error'=>$e->getMessage()

            ],500);

        }
    }





    /**
     * Display one permission
     */
    public function show($id)
    {

        try {


            $permission = Permission::find($id);



            if(!$permission){

                return response()->json([

                    'success'=>false,

                    'message'=>'Permission not found'

                ],404);

            }



            return response()->json([

                'success'=>true,

                'data'=>$permission

            ]);



        } catch(\Exception $e) {


            return response()->json([

                'success'=>false,

                'message'=>'Get permission failed',

                'error'=>$e->getMessage()

            ],500);

        }

    }





    /**
     * Update permission
     */
    public function update(Request $request,$id)
    {

        try {


            $permission = Permission::find($id);



            if(!$permission){

                return response()->json([

                    'success'=>false,

                    'message'=>'Permission not found'

                ],404);

            }



            $validated = $request->validate([


                'name'=>[

                    'required',

                    'string',

                    'max:100',

                    Rule::unique('permissions','name')
                    ->ignore($permission->id)

                ]

            ]);



            $permission->update([

                'name'=>$validated['name']

            ]);



            return response()->json([

                'success'=>true,

                'message'=>'Permission updated successfully',

                'data'=>$permission

            ]);



        } catch(\Exception $e) {


            return response()->json([

                'success'=>false,

                'message'=>'Update permission failed',

                'error'=>$e->getMessage()

            ],500);

        }

    }





    /**
     * Delete permission
     */
    public function destroy($id)
    {

        try {


            $permission = Permission::find($id);



            if(!$permission){

                return response()->json([

                    'success'=>false,

                    'message'=>'Permission not found'

                ],404);

            }



            $permission->delete();



            return response()->json([

                'success'=>true,

                'message'=>'Permission deleted successfully'

            ]);



        } catch(\Exception $e) {


            return response()->json([

                'success'=>false,

                'message'=>'Delete permission failed',

                'error'=>$e->getMessage()

            ],500);

        }

    }

}