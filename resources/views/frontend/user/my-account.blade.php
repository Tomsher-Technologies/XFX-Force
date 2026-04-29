@extends('frontend.layouts.app')

@section('title', 'My Account')

@section('content')

<section class="bg-[#0F161B] w-full mx-auto px-[16px] md:px-[30px] xl:px-[140px] pt-[100px] xl:pt-[150px] pb-[50px] xl:pb-[100px] flex flex-col gap-[30px] md:gap-[50px]">
    <div class="text-white">
        <div class="w-full mx-auto flex flex-col xl:flex-row gap-[20px] md:gap-[50px] border-t-none xl:border-t border-[#252b31] pt-0 xl:pt-[80px]">
            
            @include('frontend.layouts.sidebar')

            <main class="flex-grow lg:pb-0">
                <div>
                    <div class="flex flex-col md:flex-row justify-between items-center md:items-center mb-10 pb-[30px] gap-4 border-b border-[#252B31] text-center md:text-left">
                        <div class="w-full">
                            <h2 class="text-[20px] font-medium mb-1 text-white uppercase">Account Details</h2>
                            <p class="text-gray-500">Manage your personal information and security.</p>
                        </div>
                        <button onclick="toggleEditMode()" class="hidden md:flex bg-[#252B31] border border-white/5 px-6 py-3 rounded-xl hover:bg-[#2A7CFF] hover:border-[#2A7CFF] transition-all items-center gap-2 font-medium cursor-pointer text-xs text-white whitespace-nowrap uppercase">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                            <span class="edit-text-sync">Edit Profile</span>
                        </button>
                    </div>
                    <div class="space-y-6">
                        <form id="account-details-form" action="{{ route('account.update') }}" method="POST" class="flex flex-col md:grid md:grid-cols-2 gap-3 md:gap-6 md:gap-8">
                            @csrf
                            <div class="transition-all focus-within:border-[#2A7CFF]/50 group text-center md:text-left">
                                <label class="text-gray-500 text-[12px] font-medium block mb-[5px] group-focus-within:text-[#2A7CFF]">Full Name</label>
                                <input type="text" value="{{ auth('frontend')->user()->name }}" readonly name="name"
                                    class="profile-input w-full bg-transparent p-0 pb-5 text-lg font-medium text-white outline-none border-b !border-0 !border-b-2 border-white/20 placeholder:text-gray-700 cursor-default focus:cursor-text read-only:opacity-70 text-center md:text-left" 
                                    placeholder="Enter your full name">
                                <div class="text-red-500 text-sm error-name mt-1"></div>
                            </div>

                            <div class="transition-all focus-within:border-[#2A7CFF]/50 group text-center md:text-left">
                                <label class="text-gray-500 text-[12px] font-medium block mb-[5px] group-focus-within:text-[#2A7CFF]">Email Address</label>
                                <input type="email" value="{{ auth('frontend')->user()->email }}" readonly name="email"
                                    class=" w-full bg-transparent p-0 pb-5 text-lg font-medium text-white outline-none border-b !border-0 !border-b-2 border-white/20 placeholder:text-gray-700 cursor-default focus:cursor-text read-only:opacity-70 text-center md:text-left" 
                                    placeholder="name@email.com">
                            </div>

                            <div class="transition-all focus-within:border-[#2A7CFF]/50 group text-center md:text-left">
                                <label class="text-gray-500 text-[12px] font-medium block mb-[5px] group-focus-within:text-[#2A7CFF]">Phone Number</label>
                                <input type="tel" value="{{ auth('frontend')->user()->phone }}" readonly
                                    class="profile-input w-full bg-transparent p-0 pb-5 text-lg font-medium text-white outline-none border-b !border-0 !border-b-2 border-white/20 placeholder:text-gray-700 cursor-default focus:cursor-text read-only:opacity-70 text-center md:text-left"  name="phone"
                                    placeholder="Enter phone..">
                                <div class="text-red-500 text-sm error-phone mt-1"></div>
                            </div>

                            {{-- <div class="opacity-50 select-none text-center md:text-left">
                                <label class="text-gray-500 text-[12px] font-medium block mb-1">Account Status</label>
                                <div class="flex items-center justify-center md:justify-between">
                                    <p class="text-lg font-medium text-green-500 flex items-center gap-2">
                                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Verified
                                    </p>
                                    <span class="hidden md:inline-block text-[10px] bg-white/5 px-2 py-1 rounded text-gray-500 italic">Locked</span>
                                </div>
                            </div> --}}

                            <div class="md:col-span-2 flex flex-col gap-4 mt-4">
                                <div id="save-button-container" class="hidden">
                                    <button type="submit" class="w-full md:w-fit md:ml-auto bg-[#2A7CFF] hover:bg-[#1A6BFF] text-white font-medium py-4 px-10 rounded-xl transition-all shadow-lg shadow-[#2A7CFF]/20 uppercase text-[14px] cursor-pointer active:scale-95 flex justify-center items-center">
                                        Update Profile
                                    </button>
                                </div>

                                <button type="button" onclick="toggleEditMode()" class="md:hidden w-full bg-[#252B31] border border-white/5 px-6 py-4 rounded-xl hover:bg-[#2A7CFF] transition-all flex items-center justify-center gap-2 font-medium cursor-pointer text-sm text-white">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    <span class="edit-text-sync">EDIT PROFILE</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
            
        </div>
    </div>
</section>

@endsection

@section('script')
    <script>
        const phoneInput = document.querySelector('input[name="phone"]');

        if (phoneInput) {
            phoneInput.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9()+\-\s]/g, '');
            });
        }

        document.getElementById('account-details-form').addEventListener('submit', function(e) {
            e.preventDefault();

            let form = this;
            let formData = new FormData(form);

            document.querySelectorAll('[class*="error-"]').forEach(el => el.innerHTML = '');

            fetch(form.action, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {

                if(data.errors){
                    Object.keys(data.errors).forEach(function(key){
                        let errorDiv = document.querySelector('.error-'+key);
                        if(errorDiv){
                            errorDiv.innerHTML = data.errors[key][0];
                        }
                    });
                }
                if(data.success){
                    var name = $('input[name="name"]').val();
                    $('#userNameSidebar').text(name);
                    $('#userName').text(name);

                    toastr.success(data.success);

                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }
            });
        });
    </script>
@endsection