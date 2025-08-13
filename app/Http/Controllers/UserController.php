<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\CourierCompany;
use App\Models\CourierWeightSlab;
use App\Models\User;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\UserCourierWeightSlab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $data['users'] = $query->orderBy('id', 'desc')->paginate(10);

        $data['users']->appends([
            'search' => $request->search,
            'status' => $request->status,
        ]);

        return view('admin.users.index', $data);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(CreateUserRequest $request)
    {
        // Validate and create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'website_url' => $request->website_url,
            'billing_address' => $request->billing_address,
            'zipcode' => $request->zipcode,
            'city' => $request->city,
            'state' => $request->state,
            'image_url' => $request->file('image_url') ? $request->file('image_url')->store('users', 'public') : null,
            'status' => $request->status,
            'cod_charges' => $request->cod_charges,
            'cod_percentage' => $request->cod_percentage,
            'chargeable_amount' => $request->chargeable_amount,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id); // Retrieve user or fail if not found
        return view('admin.users.edit', compact('user')); // Pass user data to the edit view
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        // Update user details
        $user->update([
            'name' => $request->name,
            'website_url' => $request->website_url,
            'billing_address' => $request->billing_address,
            'zipcode' => $request->zipcode,
            'city' => $request->city,
            'state' => $request->state,
            'status' => $request->status,
            'cod_charges' => $request->cod_charges,
            'cod_percentage' => $request->cod_percentage,
            'chargeable_amount' => $request->chargeable_amount
        ]);

        // If a new password is provided, update it
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Handle profile image upload
        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('users', 'public');
            $user->update(['image_url' => $imagePath]);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Show Update User Charge Update form
     */
    public function userCharges(Request $request)
    {
        $users = User::where('status', 1)->orderBy('id', 'desc')->paginate('10');
        return view('admin.users.user_charges', compact('users'));
    }


    /**
     * Update User Chargeable Amount
     */

    public function updateUserCharges(Request $request, $userId)
    {
        $request->validate([
            'chargeable_amount' => ['required', 'numeric'],
        ]);

        $user = User::findOrFail($userId);
        $user->update(['chargeable_amount' => $request->chargeable_amount]);

        return redirect()->back()->with('success', 'Chargeable Amount updated successfully.');
    }
 

public function user_exportCsv(Request $request)
{
    $ids = explode(',', $request->selected_user_ids);
    
    $users = \App\Models\User::whereIn('id', $ids)->get();
      $csvHeader = [
        'ID',
        'Name',
        'Email',
        'Billing Address',
        'Zipcode',
        'City',
        'State',
        'Image URL',
        'Status',
        'COD Charges',
        'COD Percentage',
        'Chargeable Amount',
        'Created At'
    ];

    $csvData = [];

    foreach ($users as $user) {
        $csvData[] = [
            $user->id,
            $user->name,
            $user->email,
            $user->billing_address,
            $user->zipcode,
            $user->city,
            $user->state,
            $user->image_url,
            $user->status ? 'Active' : 'Inactive',
            $user->cod_charges,
            $user->cod_percentage,
            $user->chargeable_amount,
            $user->created_at->format('d M Y'),
        ];
    }

    $filename = 'selected_users_' . now()->format('Ymd_His') . '.csv';

    $handle = fopen('php://temp', 'r+');
    fputcsv($handle, $csvHeader);
    foreach ($csvData as $line) {
        fputcsv($handle, $line);
    }
    rewind($handle);

    $content = stream_get_contents($handle);
    fclose($handle);

    return Response::make($content, 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename={$filename}",
    ]);
}
public function list(Request $request)
{
    $search = $request->search;

    // Fixed: Only last 1 month date range
    $from_date = now()->subMonth()->startOfDay()->format('Y-m-d');
    $to_date = now()->endOfDay()->format('Y-m-d');

    $baseQuery = Order::select([
            'id',
            'client_order_id',
            'invoice_number',
            'awb_number',
            'consignee_name',
            'order_amount',
            'payment_mode',
            'status',
            'created_at'
        ])
        ->whereBetween(DB::raw('DATE(created_at)'), [$from_date, $to_date]);

    if (!empty($search)) {
        $baseQuery->where('awb_number', 'LIKE', "%{$search}%");
    }

    // Clone base query for different tabs
    $bookedQuery = clone $baseQuery;
    $cancelledQuery = clone $baseQuery;
    $allOrdersQuery = clone $baseQuery;

    // Paginate results
    $data['bookedOrders'] = $bookedQuery->where('status', 221)->paginate(100, ['*'], 'booked_page');
    $data['cancelledOrders'] = $cancelledQuery->where('status', 229)->paginate(100, ['*'], 'cancelled_page');
    $data['allOrders'] = $allOrdersQuery->paginate(100, ['*'], 'all_page');

    return view('admin.shipments.index', $data);
}

public function exportCsv(Request $request)
{
    $awbNumbers = explode(',', $request->input('selected_awbs'));
    $orders = Order::with('productsData')->whereIn('awb_number', $awbNumbers)->get();

    $headers = [
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename=selected_orders.csv",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0"
    ];

    $columns = [
        'Order ID',
        'AWB Number',
        'Order Amount',
        'Payment Mode',
        'Consignee Name',
        'Consignee Email',
        'Consignee Phone',
        'Consignee Address',
        'Pincode',
        'Tax',
        'Status',
        'Created At',
        'Product SKU',
        'Product Name',
        'Product Quantity',
        'Courier_name'
    ];

    $callback = function () use ($orders, $columns) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        foreach ($orders as $order) {
            foreach ($order->productsData as $product) {
                fputcsv($file, [
                    $order->client_order_id,
                    $order->awb_number,
                    $order->order_amount,
                    $order->payment_mode,
                    $order->consignee_name,
                    $order->consignee_emailid,
                    $order->consignee_mobile ?: $order->consignee_phone,
                    $order->consignee_address1,
                    $order->consignee_pincode,
                    $order->tax_amount,
                    $order->status == 221 ? 'Booked' : '',
                    $order->created_at->format('d M Y'),
                    $product->product_sku ?? '',
                    $product->product_name ?? '',
                    $product->product_quantity ?? '',
                    $order->courier_name
                ]);
            }
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
   public function view($id)
    {
        $order = Order::where(['id' => $id])->with('productsData')->first();
        return view('users.orders.view', compact('order'));
    }

    /**Cancel Order */

    public function cancelOrder(CancelOrderRequest $request)
    {
        $awbNumber = $request->awb_number;
        $order = Order::where('awb_number', $awbNumber)->first();
        $user = Auth::user();

        // Define the API URL for cancellation
        $url = 'https://api.ekartlogistics.com/v3/shipments/rto/create';

        // Prepare the data for the request
        $apiData = [
            'request_details' => [
                'tracking_id' => $awbNumber,
                'reason' => 'Test Cancellation' // You can modify this reason as needed
            ]
        ];

        // Send the request using a helper function
        $response = EkartApiService::sendRequest($url, $apiData, 'PUT');
        $responseData = $response->json(); // Get response as an array

        // Debugging output (optional)
        // dd($responseData);

        if ($response->successful()) {
            $user->logActivity($user, 'Order canceled successfully', 'order_canceled');

            // Update the order status in the database
            $order->update(['status' => '229']);
            return response()->json(['success' => true, 'message' => 'Order canceled successfully']);
        } else {
            $user->logActivity($user, 'Exception: Order cancel Failed', 'order_failed');

            $errorMsg = $responseData['responsemsg'] ?? 'Failed to cancel order';
            return response()->json(['success' => false, 'message' => $errorMsg], 400);
        }
    }

    /**
     * Order Label Data
     * */
    public function orderLabelData(Request $request)
    {     Log::info('🚀 orderLabelData called!');
    Log::info('AWBs: ', $request->input('awb_numbers', []));
        $awbNumbers = $request->input('awb_numbers', []);

        if (empty($awbNumbers)) {
            return response()->json(['error' => 'No AWB numbers provided.'], 400);
        }

        if (!is_array($awbNumbers)) {
            $awbNumbers = [$awbNumbers];
        }

        $orders = DB::table('shiparcel_orders')
            ->join('users', 'shiparcel_orders.user_id', '=', 'users.id')
            ->join('shiparcel_warehouses as pickup_warehouse', 'shiparcel_orders.pick_address_id', '=', 'pickup_warehouse.id')
            ->join('shiparcel_warehouses as return_warehouse', 'shiparcel_orders.return_address_id', '=', 'return_warehouse.id')
            ->select(
                'shiparcel_orders.id',
                    'shiparcel_orders.client_order_id',
                    'shiparcel_orders.consignee_emailid',
                    'shiparcel_orders.consignee_pincode',
                    'shiparcel_orders.consignee_mobile',
                    'shiparcel_orders.consignee_phone',
                    'shiparcel_orders.consignee_address1',
                    'shiparcel_orders.consignee_address2',
                    'shiparcel_orders.consignee_name',
                    'shiparcel_orders.invoice_number',
                    'shiparcel_orders.express_type',
                    'shiparcel_orders.pick_address_id',
                    'shiparcel_orders.return_address_id',
                    'shiparcel_orders.cod_amount',
                    'shiparcel_orders.tax_amount',
                    'shiparcel_orders.order_amount',
                    'shiparcel_orders.payment_mode',
                    'shiparcel_orders.courier_type',
                    'shiparcel_orders.awb_number',
                    'shiparcel_orders.order_number',
                    'shiparcel_orders.partner_display_name',
                    'shiparcel_orders.courier_code',
                    'shiparcel_orders.pickup_id',
                    'shiparcel_orders.courier_name',
                    'shiparcel_orders.user_id',
                    'shiparcel_orders.status',
                    'shiparcel_orders.created_at',
                    'shiparcel_orders.shipment_weight',

                    // Pickup address fields (aliased)
                    'pickup_warehouse.address_title as pickup_address_title',
                    'pickup_warehouse.sender_name as pickup_sender_name',
                    'pickup_warehouse.full_address as pickup_full_address',
                    'pickup_warehouse.phone as pickup_phone',
                    'pickup_warehouse.pincode as pickup_pincode',
                    'pickup_warehouse.state as pickup_state',
                    'pickup_warehouse.city as pickup_city',

                    // Return address fields (aliased)
                    'return_warehouse.address_title as return_address_title',
                    'return_warehouse.sender_name as return_sender_name',
                    'return_warehouse.full_address as return_full_address',
                    'return_warehouse.phone as return_phone',
                    'return_warehouse.pincode as return_pincode',
                    'return_warehouse.state as return_state',
                    'return_warehouse.city as return_city',

                    // User fields
                    'users.name as customer_name',
                    'users.email as customer_email'
            )
            ->whereIn('shiparcel_orders.awb_number', $awbNumbers)
            ->get();

        if ($orders->isEmpty()) {
            abort(404, 'No orders found for the selected AWB numbers.');
        }

        return view('admin.shipments.print_label', [
            'orders' => $orders,
            'awbNumbers' => $awbNumbers
        ]);
    }
     public function pincode()
    {
        return view('admin.pincode.index',['couriers' => CourierCompany::all()]); 
    }


    public function uploadPincode(Request $request)
    {
        $request->validate([
            'multiple_shipment' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('multiple_shipment');
        $fileHandle = fopen($file, 'r');

        $header = fgetcsv($fileHandle);

        $insertData = [];

        while (($row = fgetcsv($fileHandle)) !== false) {
            $pincode = trim($row[0]);
            $courierId = trim($row[1]);

            if ($pincode && $courierId) {
                $exists = DB::table('pincode_couriers')
                    ->where('pincode', $pincode)
                    ->where('courier_id', $courierId)
                    ->exists();

                if (!$exists) {
                    $insertData[] = [
                        'pincode' => $pincode,
                        'courier_id' => $courierId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        fclose($fileHandle);

        if (!empty($insertData)) {
            DB::table('pincode_couriers')->insert($insertData);
        }

        return back()->with('success', 'Pincode-Courier data uploaded successfully.');
    }
    public function showwallet($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.wallet', compact('user'));
    }


  public function updateWallet(Request $request, $id)
{
    $request->validate([
        'wallet_amount' => 'required|numeric',
    ]);

    DB::beginTransaction();

    try {
        $user = User::findOrFail($id);

        $wallet = Wallet::where('user_id', $user->id)->first();
        if ($wallet) {
            // Update existing wallet
            $wallet->amount = $wallet->amount + $request->wallet_amount;
            $wallet->save();
        } else {
            // Create new wallet
            $wallet = Wallet::create([
                'user_id'    => $user->id,
                'amount'     => $request->wallet_amount,
                'promo_code' => null,
                'status'     => 1
            ]);
        }

        // Create wallet transaction
        WalletTransaction::create([
            'user_id'        => $user->id,
            'name'           => 'admin',
            'amount'         => $request->wallet_amount,
            'user_role'      => 'admin',
            'issued_date'    => now()->toDateString(),
            'invoice_number' => NULL
        ]);

        DB::commit();

        return redirect()
            ->route('users.wallet', $user->id)
            ->with('success', 'Wallet updated successfully. Total balance: ' . $wallet->amount);
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()
            ->back()
            ->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}


}