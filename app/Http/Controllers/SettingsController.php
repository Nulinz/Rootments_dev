<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\{User,Asm_store};
use Exception;

class SettingsController extends Controller
{

    public function index()
    {
        $role = DB::table('users')->where('id', Auth::user()->id)->first();

        return view('settings',['role'=>$role->role_id]);
    }
    public function categorylist()
    {
        $cat=DB::table('categories')->get();

        // dd($cat);

        return view('settings.category',['cat'=> $cat]);
    }

    public function categorystore(Request $request)
    {
        $request->validate([
            'category' => 'required|unique:categories,category',
            'cat_tittle' => 'required',
        ]);


        $category = new Category();
        $category->category =$request->category;
        $category->cat_tittle =$request->cat_tittle;
        $category->cat_des =$request->cat_des;

        $category->save();

        return redirect()->back()->with([
            'status' => 'success',
            'message' => 'Category successfully!'
        ]);
    }

    public function updateStatus(Request $req)
    {
        $category = Category::find($req->id);

        if ($category) {
            $category->status = ($category->status == 1) ? 2 : 1;

            $category->save();

            return response()->json([
                'success' => true,
               'message' => 'Category Updated successfully!'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found!',
            ]);
        }
    }


    public function subcategoryList()
    {
        $subcat = DB::table('sub_categories')
            ->leftJoin('categories', 'sub_categories.cat_id', '=', 'categories.id')
            ->select('sub_categories.*', 'categories.category as cat')
            ->get();

        $cat=DB::table('categories')->select('id','category')->where('status',1)->get();

        return view('settings.subcategory',['subcat'=>$subcat,'cat'=>$cat]);
    }

    public function subcategorystore(Request $request)
    {
        $request->validate([
            'cat_id' =>'required',
            'subcategory' => 'required',
            'subcat_tittle' => 'required',
        ]);

        $subcategory = new SubCategory();
        $subcategory->cat_id =$request->cat_id;
        $subcategory->subcategory =$request->subcategory;
        $subcategory->subcat_tittle =$request->subcat_tittle;
        $subcategory->subcat_des =$request->subcat_des;

        $subcategory->save();

        return redirect()->back()->with([
            'status' => 'success',
            'message' => 'Sub-Category successfully!'
        ]);
    }

    public function subupdateStatus($id)
    {
        $subcategory = SubCategory::find($id);

        if ($subcategory) {
            $subcategory->status = ($subcategory->status == 1) ? 2 : 1;

            $subcategory->save();

            return response()->json([
                'success' => true,
               'message' => 'Sub-Category Updated successfully!'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Sub-Category not found!',
            ]);
        }
    }


    // public function permissionList()
    // {
    //     $this->data['roles'] = DB::table('roles')->get();

    // $this->data['filter'] = '';
    // $this->data['role'] = '';

    //     return view('settings.permission',$this->data);
    // }

    // public function permissionstore(Request $request)
    // {
    //     $requestData = json_decode($request->getContent(), true);


    //     $role = $requestData['role'];
    //     $permissions = $requestData['permissions'];

    //     DB::table('permissions')->where('role', $role)->delete();

    //     foreach ($permissions as $permission) {
    //         DB::table('permissions')->insert([
    //             'role' => $role,
    //             'module_name' => $permission['Module_name'] ?? '',
    //             'permission_view' => $permission['view_form'] ?? '0',
    //             'permission_add' => $permission['add_form'] ?? '0',
    //             'permission_edit' => $permission['edit_form'] ?? '0',
    //             'permission_delete' => $permission['delete_form'] ?? '0',
    //             'permission_recommend' => $permission['recommend_form'] ?? '0',
    //             'permission_verify' => $permission['verify_form'] ?? '0',
    //             'permission_approval' => $permission['approval_form'] ?? '0',
    //             'permission_show' => $permission['show_form'] ?? '0',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ]);
    //     }

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Permissions added successfully',
    //     ]);
    // }



    // public function filter($id)
    // {

    //     $this->data['uniqueId']=$id;
    //     $this->data['roles'] = DB::select("
    //     SELECT a.*, b.designation,b.id as role_id
    //     FROM users AS a
    //     LEFT JOIN destinations AS b
    //     ON a.role_id = b.id
    //     GROUP BY b.designation
    // ");
    //     $this->data['filter']=DB::SELECT("SELECT * FROM permissions WHERE role='$id'");

    //     return view('permission.list',$this->data);

    // }


    public function roleList()
    {
        $role=DB::table('roles')->get();

        return view('settings.role',['role'=>$role]);
    }



    public function rolestore(Request $request)
    {
        $request->validate([
            'role' =>'required',
            'role_des' => 'required',
            'role_dept' => 'required',
        ]);

        $role = new Role();
        $role->role_dept =$request->role_dept;
        $role->role =$request->role;
        $role->role_des =$request->role_des;

        $role->save();

        return redirect()->back()->with([
            'status' => 'success',
            'message' => 'Role Added successfully!'
        ]);
    }

    public function roleupdateStatus($id)
    {
        $role = Role::find($id);

        if ($role) {
            $role->status = ($role->status == 1) ? 2 : 1;

            $role->save();

            return response()->json([
                'success' => true,
               'message' => 'Role Updated successfully!'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Role not found!',
            ]);
        }
    }
    public function themeList()
    {
        return view('settings.theme');
    }

    public function passwordList()
    {
        $auth = Auth::user();


        $user = User::find($auth->id);

        return view('settings.password',['user'=>$user]);
    }


    public function passwordupdate(Request $request)
    {
        $request->validate([
            'emp_code'=>'required',
            'password' =>'required'
        ]);

        $emp_code = $request->emp_code;
        $password = $request->password;

        $user = User::where('emp_code', $emp_code)->first();

        if ($user) {
            if ($password) {
                if (Hash::check($password, $user->password)){
                    return redirect()->back()->with
                    (['status' => 'error', 'message' => 'You Entered Old Password']);
                }
            }

            if($password) {
                $user->password = Hash::make($password);
            }
            $user->save();

            return redirect()->back()-> with
            (['status' => 'success', 'message' => 'Password Updated Successfully']);

        }

        return redirect()->back()->with(['status' => 'error', 'message' => 'Unauthroized Profile']);
    }

    public function assign_asm()
    {
    //   /  $user = Auth::user();

        $stores = DB::table('stores')->pluck('store_name','id');

         $asm_assign = DB::table('asm_store as as')
         ->leftJoin('users as us','us.id','=','as.emp_id')
         ->leftJoin('stores as st','st.id','=','as.store_id')
         ->select('us.name','st.store_name')
         ->get();



        // $stores = DB::table('stores')->get();

         //  return $stores;

        //  return view('settings.assing_asm', compact('stores'));

         return view('settings.assing_asm',['stores'=>$stores,'asm_assigned'=>$asm_assign]);

        // return response()->json([
        //     'store' => $stores,
        // ]);

        //
    }



    public function get_asm(Request $req)
    {
    //   /  $user = Auth::user();

        $asm = DB::table('users')->where('store_id',$req->store_id)->where('role_id',13)->select('users.id','users.name')->get();

        return response()->json($asm,200);

        //
    }

    public function insert_asm(Request $req)
    {
    //   /  $user = Auth::user();

            $asm = new Asm_store();

            //  dd($req);

             try{

            $asm->store_id = $req->store;
            $asm->emp_id = $req->asm;
            $asm->c_by = Auth::user()->id;
            $asm->save();

             }
             catch(\Exception $e){

                // Log::error('Error saving ASM: ' . $e->getMessage());


             }



            if($asm){
                return  redirect()->back()->with(['status'=>'success', 'message'=>'Asm Assigned Successfully']);
                //  return response()->json(
                //     [
                //         'status'=>true,
                //         'message'=>'Asm Assigned Successfully',
                //     ]);
            }



        // $asm = DB::table('users')->where('store_id',$req->store_id)->where('role_id',13)->select('users.id','users.name')->get();

        // return response()->json($asm,200);

        //
    }
}
