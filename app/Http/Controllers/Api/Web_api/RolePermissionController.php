<?php

namespace App\Http\Controllers\Api\Web_api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{


    /**
     * Get permissions of role
     */
    public function show($roleId)
    {
        try {

            $role = Role::find($roleId);


            if(!$role){

                return response()->json([
                    'success'=>false,
                    'message'=>'Role not found'
                ],404);
            }
            return response()->json([
                'success'=>true,
                'data'=>[
                    'role'=>$role,
                    'permissions'=>$role
                        ->permissions
                        ->pluck('name')
                ]

            ]);


        } catch(\Exception $e){

            return response()->json([

                'success'=>false,
                'message'=>$e->getMessage()

            ],500);

        }
    }
    /**
     * Assign permissions to role
     */
    public function store(Request $request,$roleId)
    {
        try {
            $role = Role::find($roleId);
            if(!$role){
                return response()->json([
                    'success'=>false,
                    'message'=>'Role not found'
                ],404);
            }
            $request->validate([
                'permissions'=>'required|array',
                'permissions.*'=>'exists:permissions,name'
            ]);
            $role->syncPermissions(
                $request->permissions
            );
            return response()->json([

                'success'=>true,

                'message'=>'Permissions assigned successfully',

                'data'=>[
                    'role'=>$role->name,

                    'permissions'=>
                        $role->permissions
                        ->pluck('name')
                ]

            ]);
        } catch(\Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>$e->getMessage()
            ],500);
        }
    }
    /**
     * Remove permission from role
     */
    public function destroy(Request $request,$roleId)
    {
        try {
            $role = Role::find($roleId);
            if(!$role){
                return response()->json([
                    'success'=>false,

                    'message'=>'Role not found'

                ],404);

            }
            $request->validate([

                'permission'=>'required|string'

            ]);
            $role->revokePermissionTo(
                $request->permission
            );
            return response()->json([

                'success'=>true,

                'message'=>'Permission removed successfully'

            ]);

        } catch(\Exception $e){

            return response()->json([

                'success'=>false,

                'message'=>$e->getMessage()

            ],500);


        }

    }


}