<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Controllers\trait\common;

class purchase_cnt extends Controller
{
    use common;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $user = Auth::user();

        $pur_emp = $this->attd_index('Purchase');

        $men = DB::table('roles')->where('role_dept', 'Purchase')
            ->leftJoin('users as us', 'us.role_id', '=', 'roles.id')
            ->pluck('us.id')->toArray();

        //   dd($men);


        if (!empty($men)) {

            $totaltask = DB::table('tasks')
                ->whereIn('tasks.assign_to', $men)
                ->selectRaw("
                    SUM(CASE WHEN task_status = 'To Do' THEN 1 ELSE 0 END) AS todo,
                    SUM(CASE WHEN task_status = 'In Progress' THEN 1 ELSE 0 END) AS in_progress,
                    SUM(CASE WHEN task_status = 'On Hold' THEN 1 ELSE 0 END) AS on_hold,
                    SUM(CASE WHEN task_status = 'Completed' THEN 1 ELSE 0 END) AS completed
                ")
                ->first();

            $task = [
                'todo' => $totaltask->todo ?? 0,
                'in_progress' => $totaltask->in_progress ?? 0,
                'on_hold' => $totaltask->on_hold ?? 0,
                'completed' => $totaltask->completed ?? 0,
            ];
        } else {
            $task = [
                'todo' => 0,
                'in_progress' => 0,
                'on_hold' => 0,
                'completed' => 0,
            ];
        }

        //   dd($task);

        if (!empty($men)) {
            $teampertask = DB::table('tasks')
                ->join('users', 'tasks.assign_to', '=', 'users.id')
                ->whereIn('tasks.assign_to', $men)
                ->selectRaw("
                    users.name,
                    COUNT(*) AS total_tasks
                ")
                ->groupBy('users.name')
                ->get();
        } else {
            $teampertask = collect();
        }

        $staffNames = $teampertask->pluck('name')->toArray();
        $taskCounts = $teampertask->pluck('total_tasks')->toArray();

        //   dd($taskCounts);


        // category and subcategory charts.....

        $categoryTask = DB::table('tasks')
            ->join('categories', 'tasks.category_id', '=', 'categories.id')
            ->whereIn('tasks.assign_to', $men)
            ->where('tasks.task_status', 'Completed')
            ->select(
                'categories.category',
                DB::raw('COUNT(*) as total_tasks')
            )
            ->groupBy('categories.category')
            ->get();

        $categoryNames = $categoryTask->pluck('category')->toArray();
        $categorytaskCounts = $categoryTask->pluck('total_tasks')->toArray();

        $subcategoryTask = DB::table('tasks')
            ->join('sub_categories', 'tasks.subcategory_id', '=', 'sub_categories.id')
            ->join('categories', 'tasks.category_id', '=', 'categories.id')
            ->whereIn('tasks.assign_to', $men)
            ->where('tasks.task_status', 'Completed')
            ->select(
                'categories.category',
                'sub_categories.subcategory',
                DB::raw('COUNT(*) as subtotal_tasks')
            )
            ->groupBy('categories.category', 'sub_categories.subcategory')
            ->get();
        $subcategoryNames = $subcategoryTask->pluck('subcategory')->toArray();
        $subcategorytaskCounts = $subcategoryTask->pluck('subtotal_tasks')->toArray();


        // dd($categoryNames);

        if (!empty($men)) {
            $pendingLeaves = DB::table('leaves')
                ->leftJoin('users', 'leaves.user_id', '=', 'users.id')
                ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                ->select(
                    'leaves.*',
                    'users.name',
                    'users.profile_image',
                    'users.emp_code',
                    'users.store_id',
                    'leaves.request_to'
                )
                ->whereIn('users.id', $men)
                ->where('leaves.request_to',  $user->id)
                ->where('leaves.request_status', 'Pending')
                ->get();
        } else {
            $pendingLeaves = collect();
        }


        $task_ext = DB::table('task_ext')->where('request_for', Auth::id())
            ->leftJoin('tasks as tk', 'task_ext.task_id', '=', 'tk.id')
            ->leftJoin('users as us', 'task_ext.c_by', '=', 'us.id')

            ->select(
                'task_ext.id',
                'task_ext.extend_date',
                'task_ext.c_remarks',
                'task_ext.created_at',
                'task_ext.category',
                'task_ext.status',
                'tk.task_title',
                'task_ext.attach',
                'us.name'
            )
            ->whereIn('task_ext.status', ['Pending', 'Close Request'])
            ->orderBy('task_ext.created_at', 'desc')
            ->get();


        $tast_ext = DB::table('task_ext')
            ->select('task_id', 'status')
            ->orderBy('id', 'desc')
            ->get()
            ->unique('task_id')
            ->keyBy('task_id');

        return view('purchase.purchase_index', ['pur_emp' => $pur_emp, 'task' => $task, 'staffNames' => $staffNames, 'taskCounts' => $taskCounts, 'categoryNames' => $categoryNames, 'categorytaskCounts' => $categorytaskCounts, 'subcategoryNames' => $subcategoryNames, 'subcategorytaskCounts' => $subcategorytaskCounts, 'pendingLeaves' => $pendingLeaves, 'task_ext' => $task_ext,  'tast_ext' => $tast_ext]);
    }
    
     public function purchase_list()
    {
        $user_id = auth()->user()->id;
        $pur_list = DB::table('purchase_request as pm')
            ->leftJoin('users', 'pm.requst_to', '=', 'users.id')
            ->select('pm.*', 'users.name as name')
            ->where('c_by', $user_id)->get();

        return view('purchase.purchase_list', ['pur_list' =>  $pur_list]);
    }

     public function add_purchase()
    {
        // $userId = auth()->user()->id;
        $roleId = auth()->user()->role_id;

        $roles = [];

        if ($roleId == 12) {
            $roles = [10, 11, 37];
        } elseif (in_array($roleId, [1, 2, 37, 41])) {
            $roles = [41];
        }

        $pur_req = DB::table('users')
            ->select('id', 'name')
            ->whereIn('role_id', $roles)
            ->get();

        // dd($roles);

        return view('purchase.add_purchase', ['pur_req' => $pur_req]);
    }

    public function store_purchase(Request $request)
    {

        $po_id = rand(1000, 9999);

        $filename = null;

        if ($request->hasFile('pur_file')) {
            $image = $request->file('pur_file');

            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move('assets/images/Task', $filename);
        }


        DB::table('purchase_request')->insert([
            'purchase_id' => 'PO' . $po_id,
            'request_type' => $request->request_type,
            'pru_date' => $request->pru_date,
            'requst_to' => $request->requst_to,
            'pur_file' => $filename,
            'pur_des' => $request->pur_des,
            'c_by' => auth()->user()->id,
            'status' => 'Pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return to_route('p_request_list')->with([
            'status' => 'success',
            'message' => ' Created Successfully'
        ]);
    }
    public function vendor()
    {
        $ven_list = DB::table('vendors')
            ->leftJoin('users', 'vendors.c_by', '=', 'users.id')
            ->select('vendors.*', 'users.name as username')
            ->get();

        return view('purchase.vendor_list', ['ven_list' => $ven_list]);
    }
    public function add_vendor()
    {
        return view('purchase.add_vendor');
    }
    public function store_vendor(Request $request)
    {
        DB::table('vendors')->insert([
            'vendor_code' => $request->vendor_code,
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'opening_balance' => $request->opening_balance ?? 0,
            'balance_type' => $request->balance_type,
            'gstin_no' => $request->gstin_no,
            'pan_number' => $request->pan_number,
            'permanent_address' => $request->permanent_address,
            'shipping_address' => $request->shipping_address,
            'c_by' => auth()->user()->id,
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return to_route('purchase.vendor_list')->with([
            'status' => 'success',
            'message' => 'Vendor added successfully!'
        ]);
    }
    public function vendor_profile($id)
    {

        $ven_profile = DB::table('vendors')->where('id', $id)
            ->first();

        return view('purchase.vendor_profile', ['ven_profile' => $ven_profile]);
    }

    public function product()
    {
        $pro_list = DB::table('products')
            ->leftJoin('users', 'products.c_by', '=', 'users.id')
            ->select('products.*', 'users.name')
            ->get();

        return view('purchase.product_list', ['pro_list' => $pro_list]);
    }
    public function add_product()
    {
        return view('purchase.add_product');
    }
    public function store_product(Request $request)
    {
        $filename = null;

        if ($request->hasFile('product_file')) {
            $image = $request->file('product_file');

            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move('assets/images/Task', $filename);
        }

        // $file->move(public_path('uploads/products'), $filename);
        // $filePath = 'uploads/products/' . $filename;

        DB::table('products')->insert([
            'product_code' => $request->product_code,
            'product_name' => $request->product_name,
            'color' => $request->color,
            'measuring_unit' => $request->measuring_unit,
            'selling_price' => $request->selling_price,
            'selling_price_type' => $request->selling_price_type,
            'selling_gst_rate' => $request->selling_gst_rate,
            'opening_stock' => $request->opening_stock,
            'purchase_price' => $request->purchase_price,
            'purchase_price_type' => $request->purchase_price_type,
            'purchase_gst_rate' => $request->purchase_gst_rate,
            'file_path' => $filename,
            'description' => $request->description,
            'c_by' => auth()->user()->id,
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return to_route('purchase.product_list')->with([
            'status' => 'success',
            'message' => 'Product added successfully.'
        ]);
    }
    public function product_profile($id)
    {
        $pro_profile = DB::table('products')->where('id', $id)->first();

        return view('purchase.product_profile', ['pro_profile' => $pro_profile]);
    }
    public function po_list()
    {
        // $po_list = DB::table('purchase_order')
        //             ->leftJoin('purchase_order_item', 'product')
        // ->get();

              $po_list = DB::table('purchase_order as po')
            ->leftJoin('purchase_order_item as poi', 'poi.po_id', '=', 'po.id')
            ->leftJoin('vendors', 'po.vendor', '=', 'vendors.id')
            ->leftJoin('stores', 'po.store', '=', 'stores.id')
            ->select(
                'po.*',
                DB::raw('COUNT(poi.id) as product_count'),
                'vendors.name as vendor',
                'stores.store_name'
            )
            ->groupBy('po.id')
            ->orderByDesc('created_at')
            ->get();

        return view('purchase.purchase_order_list', ['po_list' => $po_list]);
    }
    public function po_add()
    {
             $roleId = auth()->user()->role_id;
        
        $vendors = DB::table('vendors')->select('name', 'id', 'contact', 'shipping_address')->get();

        $products = DB::table('products')->select('products.*')->get();
        
           $Stores = DB::table('stores')
            ->when(in_array($roleId, [10, 11]), function ($query) {
                $query->Leftjoin('cluster_store', 'stores.id', '=', 'cluster_store.store_id')
                    ->Leftjoin('m_cluster', 'cluster_store.cluster_id', '=', 'm_cluster.id')
                    ->Leftjoin('users', 'm_cluster.cl_name', '=', 'users.id')
                    ->where('users.id', '=', auth()->id());
            })
            ->select('stores.store_name', 'stores.id')
            ->get();

        return view('purchase.purchase_order', ['vendors' => $vendors, 'products' => $products, 'Stores' => $Stores]);
    }
    public function po_store(Request $request)
    {
        $po_no = rand(1000, 9999);


        $qtys = $request->qty;
        $sellings = $request->selling;
        $purchases = $request->purchase;

        // Totals
        $totalQty = array_sum($qtys);
        $totalSel = array_sum($sellings);
        $totalPur = array_sum($purchases);

        // $overallTotal = $totalQty * $totalPur;

        // Insert into purchase_order table
        $poId = DB::table('purchase_order')->insertGetId([
            'po_id' => 'PO' . $po_no,
            'store' => $request->store_id,
            'vendor' => $request->vendor,
            'contact' => $request->contact,
            'address' => $request->address,
            'date' => $request->date,
            'delivery_date' => $request->delivery_date,
            'advance_payment_date' => $request->advance_payment_date,
            'balance_payment_date' => $request->balance_payment_date,
            'total_qty' => $totalQty,
            'total_sel' => $totalSel,
            'total_pur' => $totalPur,
            'overall_total' => $request->overall_total,
            'req_status' => 'Pending',
            'c_by' => auth()->user()->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Prepare item insert data
        $items = [];
        for ($i = 0; $i < count($request->product_code); $i++) {
            $items[] = [
                'po_id' => $poId,
                'product_id' => $request->product_id[$i],
                'product_code' => $request->product_code[$i],
                'product' => $request->product[$i],
                'color' => $request->color[$i],
                'qty' => $qtys[$i],
                'selling_price' => $sellings[$i],
                'purchase_price' => $purchases[$i],
                'c_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        // Bulk insert items
        DB::table('purchase_order_item')->insert($items);

        return to_route('purchase.purchase_order_list')->with([
            'status' => 'success',
            'message' => 'Purchase Order saved successfully.'
        ]);
    }

    public function update_po(Request $request)
    {

        $update_po = DB::table('purchase_order')->where('id', $request->po_id)->update([
            'req_status' => $request->po_status,
            'updated_at' => now()
        ]);
        return redirect()->back()->with([
            'status' => 'success',
            'message' => 'Purchase Order updated successfully.'
        ]);
    }
    public function update_pofin(Request $request)
    {
        DB::table('purchase_order')->where('id', $request->apr_id)->update([
            'esc_status' => $request->apr_status,
            'req_status' => $request->apr_status,
            'updated_at' => now()
        ]);
        return redirect()->back()->with([
            'status' => 'success',
            'message' => 'Purchase Order approved successfully.'
        ]);
    }

    public function po_profile($id)
    {
        $po_view = DB::table('purchase_order')
            ->leftJoin('vendors', 'purchase_order.vendor', '=', 'vendors.id')
            ->where('purchase_order.id', $id)
            ->select('purchase_order.*', 'vendors.name as ven_name')
            ->first();

        $po_item = DB::table('purchase_order_item as poi')->where('po_id', $id)
            ->leftJoin('products as ps', 'poi.product_id', '=', 'ps.id')
            ->select('ps.*', 'poi.*')
            ->get();

        return view('purchase.purchase_order_profile', ['po_view' => $po_view, 'po_item' => $po_item]);
    }
}
