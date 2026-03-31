@extends('frontend.layouts.app')

@section('title', 'Forgot Password - '.env('APP_NAME'))

@section('content')


<section class="bg-[#0F161B] max-w-6xl mx-auto py-[50px] pt-[100px] md:pt-[200px] md:pb[100px] flex flex-col gap-[30px] md:gap-[50px]">
    <div class="flex items-center justify-center p-6">
        <div class="w-full max-w-md bg-[#1C2228] rounded-[30px] border border-white/5 p-8 md:p-10 shadow-2xl">
            
            <div class="mb-10 text-center">
                <h2 class="text-white text-[20px] font-semibold mb-1 uppercase">Forgot Password</h2>
                <p class="text-gray-400 text-sm">Enter your email to receive a password reset link</p>
            </div>

            <form id="forgotPasswordForm" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="text-gray-500 text-[12px] font-medium uppercase mb-1 block tracking-wider">Email Address <span class="text-red-600">*</span></label>
                    <input type="email" name="email" id="email" placeholder="Enter your registered email.." class="w-full bg-[#0B0F13] border border-white/5 p-4 rounded-xl text-white outline-none focus:border-[#2A7CFF] transition-all placeholder:text-gray-700">
                </div>

                <button type="submit" id="resetButton"  class="cursor-pointer l duration-[600ms] w-full bg-[#2A7CFF] py-4 rounded-xl font-medium text-white flex items-center justify-center text-[14px] uppercase hover:bg-[#1A6BFF] transition-all shadow-lg shadow-[#2A7CFF]/20 active:scale-[0.98]">
                    <span id="resetText">Reset</span>
                    <svg id="loadingSpinner" class="hidden w-5 h-5 ml-2 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                </button>
            </form>

            <div class="mt-8 text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-gray-500 text-sm hover:text-white transition-colors group">
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Log In
                </a>
            </div>
        </div>
    </div>
</section>


@endsection

@section('script')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        $('#forgotPasswordForm').on('submit', function(e) {
            e.preventDefault();

            // Show loading effect
            $('#resetButton').prop('disabled', true);
            $('#resetText').text('Processing...');
            $('#loadingSpinner').removeClass('hidden');

            $.ajax({
                url: "{{ route('password.sendResetLink') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    toastr.success(response.success);
                    $('#forgotPasswordForm')[0].reset();
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    if (errors && errors.email) {
                        toastr.error(errors.email[0]);
                    } else if (xhr.responseJSON.error) {
                        toastr.error(xhr.responseJSON.error);
                    }
                },
                complete: function() {
                    // Hide loading effect
                    $('#resetButton').prop('disabled', false);
                    $('#resetText').text('Reset');
                    $('#loadingSpinner').addClass('hidden');
                }
            });
        });
    });
</script>
@endsection

