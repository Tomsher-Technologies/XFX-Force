<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use App\Models\Upload;
use App\Models\User;
use App\Models\Wishlist;
use App\Models\OrderTracking;
use Illuminate\Http\Request;
use App\Models\Cart;
use Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Storage;
use Auth;

class ProfileController extends Controller
{
    public function counters()
    {
        return response()->json([
            'cart_item_count' => Cart::where('user_id', auth('frontend')->user()->id)->count(),
            'wishlist_item_count' => Wishlist::where('user_id', auth('frontend')->user()->id)->count(),
            'order_count' => Order::where('user_id', auth('frontend')->user()->id)->count(),
        ]);
    }

    public function getUserAccountInfo(){
        $user_id = (!empty(auth('frontend')->user())) ? auth('frontend')->user()->id : '';
        $user = User::find($user_id);
       
        return view('frontend.user.my-account',compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth('frontend')->user();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:12|unique:users,phone,' . $user->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->save();
    
        return response()->json([
            'success' => 'Profile updated successfully'
        ]);
    }

    public function updatePassword(){
        $lang = getActiveLanguage();
        return view('frontend.user.change-password',compact('lang'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'string', 'min:8', 'regex:/[A-Z]/', 'regex:/[0-9]/','regex:/[@$!%*#?&]/'],
        ]);

        $user = Auth::guard('frontend')->user();

        // Check if the current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            session()->flash('message', trans('messages.current_password_incorrect'));
            session()->flash('alert-type', 'error');
            return redirect()->back()->withInput(); 
        }

        // Update the password
        $user->password = Hash::make($request->new_password);
        $user->save();

        session()->flash('message', trans('messages.password_updated_successfully'));
        session()->flash('alert-type', 'success');
        return redirect()->back();
    }

    public function orderList(Request $request){
        $lang = getActiveLanguage();
        $user_id = (!empty(auth('frontend')->user())) ? auth('frontend')->user()->id : '';
        $user = User::find($user_id);
        $total_count = 0;
        $orderList = [];
        if($user){
            $sort_search = null;
            $delivery_status = null;

            $orders = Order::with(['orderDetails'])->select('id','code','delivery_status','payment_type','coupon_code','grand_total','created_at')->orderBy('id', 'desc')->where('user_id',$user_id);
            if ($request->has('search')) {
                $sort_search = $request->search;
                $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
            }
            if ($request->delivery_status != null) {
                $orders = $orders->where('delivery_status', $request->delivery_status);
                $delivery_status = $request->delivery_status;
            }
           
            $total_count = $orders->count();
            $orderList = $orders->get();
        }
        return view('pages.my-orders',compact('orderList','total_count','lang'));
    }
    
    public function orderReturnList(Request $request){
        $user_id = (!empty(auth('frontend')->user())) ? auth('frontend')->user()->id : '';
        $user = User::find($user_id);
        $total_count = 0;
        $orderList = [];
        if($user){
            $sort_search = null;
            $delivery_status = null;

            $orders = Order::with(['orderDetails'])->select('id','code','delivery_status','payment_type','coupon_code','grand_total','created_at')->orderBy('id', 'desc')->where('user_id',$user_id)->where('return_request',1);
           
            $orderList = $orders->get();
        }
        return view('frontend.order-returns',compact('orderList'));
    }
    public function orderDetails(Request $request){
        $order_code = $request->code ?? '';
        $user_id = (!empty(auth('frontend')->user())) ? auth('frontend')->user()->id : '';
        $track_list = [];
        $lang = getActiveLanguage();
        $order = [];

        if($order_code != ''){
            $order = Order::where('code',$order_code)->where('user_id',$user_id)->first();
            if($order){
                $tracks = OrderTracking::where('order_id', $order->id)->orderBy('id','ASC')->get();
                
                if ($tracks) {
                    foreach ($tracks as $key=>$value) {
                        $temp = array();
                        $temp['id'] = $value->id;
                        $temp['status'] = $value->status;
                        $temp['date'] = date("d-m-Y h:i A", strtotime($value->status_date));
                        $track_list[] = $temp;
                    }
                }    
            }
        }

        if(!empty($track_list)){
            $dataByStatus = $track_list;
        }else{
            $dataByStatus = [];
        }
        
        // echo '<pre>';
        // print_r($track_list);
        // print_r($dataByStatus);
        // die;

        return view('pages.order-details',compact('lang','order','track_list','dataByStatus'));
    }

    public function getUserAddressInfo(){
        $lang = getActiveLanguage();
        $addresses = Address::where('user_id', auth('frontend')->user()->id)->orderBy('id','desc')->get();
        return view('frontend.user.my-address', compact('addresses','lang'));
    }


    public function saveAddress(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[a-zA-Z\s]+$/u|max:100',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'phone' => ['required', 'regex:/^\+?[0-9]{7,15}$/']
        ], [
            'name.regex' => 'Only alphabets and spaces are allowed in the name field.',
            'phone.regex' => 'Please enter a valid phone number (numbers only, 7-15 digits).'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'=>'error',
                'errors'=>$validator->errors()
            ],200);
        }

        $user_id = (!empty(auth('frontend')->user())) ? auth('frontend')->user()->id : '';

        if($user_id != ''){
            if($request->address_id != 0){
                $address                = Address::find($request->address_id);
            }else{
                $address                = new Address;
            }
            $isDefault = $request->has('default') ? 1 : 0;

            if($isDefault){
                Address::where('user_id', $user_id)->where('id','!=',$request->address_id)->update(['set_default' => 0]);
            }

            $address->user_id       = $user_id;
            $address->address       = $request->address ?? null;
            $address->name          = $request->name ?? null;
            $address->city          = $request->city ?? null;
            $address->state_name    = $request->state ?? null;
            $address->country_name  = $request->country ?? null;
            $address->postal_code   = $request->zipcode ?? null;
            $address->type          = $request->address_type ?? null;
            $address->set_default   = $isDefault;
            $address->phone         = $request->phone;
            $address->latitude      = $request->latitude ?? null;
            $address->longitude     = $request->longitude ?? null;

            $address->save();
    
            return response()->json(['success'=> true ], 200);
        }else{
            return response()->json(['success'=> false ], 200);
        }
    }

    public function deleteAddress(Request $request)
    {
        $user_id = auth('frontend')->user()->id;

        $address = Address::where('id',$request->id)
            ->where('user_id',$user_id)
            ->first();

        if($address){
            $address->delete();
            return response()->json(['success'=>true]);
        }

        return response()->json(['success'=>false]);
    }

    public function editAddress($id){
        $lang = getActiveLanguage();
        $address = Address::find($id);
        return view('pages.add-address', compact('address','lang'));
    }
}
