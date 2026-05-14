<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\PcBuilderSetup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Show the registration form
    public function showRegistrationForm()
    {
        $lang = getActiveLanguage();
        return view('auth.register',['lang' => $lang ]);
    }

    // Handle the registration logic
    public function register(Request $request)
    {
        $existingUser = User::where('email', $request->email)->first();
     
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required', 'regex:/^[0-9]+$/', 'min:10', 'max:15', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'regex:/[A-Z]/', 'regex:/[0-9]/','regex:/[@$!%*#?&]/'],
            'password_confirmation' => ['required_with:password', 'same:password'],
        ],[
            'password.regex' => 'Password must contain at least one uppercase letter, one number, and one special character.'
        ]);

       
        if ($validator->fails()) {
            return redirect('register')->withErrors($validator)->withInput();
        }

        if ($existingUser) {
            // If guest → convert to customer
            if ($existingUser->user_type == 'guest') {

                $existingUser->update([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'password' => Hash::make($request->password),
                    'user_type' => 'customer'
                ]);

                $user = $existingUser;

            } else {

                return redirect('register')
                    ->withErrors(['email' => 'This email is already registered. Please login.'])->withInput();
            }
        } else {
            // Create new user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'user_type' => 'customer',
                'password' => Hash::make($request->password),
            ]);
        }
        
        $details = [
            'name' => $request->name,
            'subject' => 'Registration Successful - Welcome to '.env('APP_NAME').'!',
            'body' => " <p> Congratulations and welcome to ".env('APP_NAME')."! We are delighted to inform you that your registration has been successfully completed. Thank you for choosing us as your trusted partner.</p><br>

            <p>We are committed to providing you with exceptional service and ensuring that your online shopping experience is smooth and hassle-free. If you have any questions or need assistance, our customer support team is here to help.</p><br>
            <p>Thank you for choosing ".env('APP_NAME').". </p>"
        ];
       
        Mail::to($request->email)->queue(new \App\Mail\SendMail($details));

        // Log the user in after registration
        Auth::guard('frontend')->login($user);

        return redirect()->route('home')->with('success', 'Welcome! Your registration was successful. Start shopping with us!');  // Redirect to home page after registration
    }

    // Show the login form
    public function showLoginForm(Request $request)
    {
        $lang = getActiveLanguage();
        $checkout = $request->checkout;
        $buildyourpc = $request->buildyourpc;

        return view('auth.login',[
            'lang' => $lang,
            'checkout' => $checkout,
            'buildyourpc' => $buildyourpc,
        ]);
    }

    // Handle the login logic
    public function login(Request $request)
    {
        // Validation for login form
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');
        // Attempt to log the user in
        if (Auth::guard('frontend')->attempt($credentials, $remember)) {
            if (Auth::guard('frontend')->user()->user_type === 'customer') {
                // Redirect to checkout if coming from checkout
                if ($request->checkout) {
                    return redirect()->route('checkout')
                        ->with('success', 'Login successful! Continue checkout.');
                }

                // Redirect to buildyourpc page if coming from buildyourpc page.
                if ($request->buildyourpc) {

                    $userId = Auth::guard('frontend')->id();
                    $guestToken = request()->cookie('guest_token');

                    Log::debug("Guest token: " . $guestToken);

                    // 1. Get existing user builder (BEFORE deleting)
                    $oldBuilder = PcBuilderSetup::where('user_id', $userId)->first();

                    Log::debug("Old builder: " . print_r($oldBuilder, true));

                    // 2. Remove PC builder cart items linked to OLD builder
                    if ($oldBuilder) {
                        Cart::where('user_id', $userId)
                            ->where('is_pc_builder', 1)
                            ->where('pc_builder_id', $oldBuilder->id)
                            ->delete();
                    }

                    // 3. Delete old builder
                    PcBuilderSetup::where('user_id', $userId)->delete();

                    // 4. Get guest builder
                    $guestBuilder = PcBuilderSetup::where('temp_user_id', $guestToken)->first();

                    Log::debug("Guest builder: " . print_r($guestBuilder, true));

                    // 5. Transfer guest builder to user
                    if ($guestBuilder) {
                        $guestBuilder->update([
                            'user_id' => $userId,
                            'temp_user_id' => null
                        ]);
                    }

                    return redirect()->route('buildyourpc')
                        ->with('success', 'Login successful! Continue configuring your PC.');
                }
                
                return redirect()->route('home')->with('success', 'Login successful! Welcome back.'); // Redirect to home for customers
            } else {
                Auth::guard('frontend')->logout(); // Log out non-customers
                return back()->with('error', 'Access restricted to customers only.');
            }
        }

        // If authentication fails
        if ($request->has('checkout')) {
            return redirect()->route('login', [
                    'checkout' => 1
                ])->withErrors(['password' => 'Invalid credentials'])->withInput();
        }

        if ($request->has('buildyourpc')) {
            return redirect()->route('login', [
                    'buildyourpc' => 1
                ])->withErrors([
                    'password' => 'Invalid credentials'
                ])->withInput();
        }
        return redirect()->route('login')->withErrors(['password' => 'Invalid credentials'])->withInput();
    }

    // Logout the user
    public function logout()
    {
        Auth::guard('frontend')->logout();
        return redirect()->route('home'); // Redirect to login page after logout
    }
}
