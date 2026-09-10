@extends('layouts.minimal')
@section('title','ورود | دیجی‌کالا')
@section('content')
<div class="min-h-[calc(100vh-64px)] flex items-center justify-center px-4 py-10 bg-white">
    <div class="w-full max-w-[400px] border border-gray-200 rounded-xl p-6 lg:p-8 shadow-sm">
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2">
                <div class="text-[24px] font-black text-[#ef4056]">دیجی‌کالا</div>
                <span class="w-10 h-6 bg-[#ef4056] rounded-full inline-block" style="clip-path: ellipse(60% 40% at 50% 0%);"></span>
            </a>
            <div class="mt-8 text-right">
                <h1 class="font-bold text-[16px]">ورود یا ثبت‌نام در دیجی‌کالا</h1>
                <p class="text-[12px] text-gray-500 mt-2">لطفا شماره موبایل یا ایمیل خود را وارد کنید</p>
            </div>
        </div>

        <form id="loginForm" class="space-y-4" onsubmit="return handleLogin(event)">
            <div>
                <input type="text" name="email" id="email" placeholder="شماره موبایل یا پست الکترونیک" required
                    class="w-full h-[48px] border border-gray-300 rounded-lg px-4 text-sm placeholder:text-gray-400 outline-none focus:border-[#19bfd3] focus:ring-2 focus:ring-[#19bfd3]/10 transition">
                <p id="emailError" class="text-xs text-[#ef4056] mt-1 hidden">این فیلد الزامی است</p>
            </div>
            <div id="passwordField" class="hidden">
                <input type="password" name="password" placeholder="رمز عبور" class="w-full h-[48px] border border-gray-300 rounded-lg px-4 text-sm outline-none focus:border-[#19bfd3] focus:ring-2 focus:ring-[#19bfd3]/10">
            </div>
            <button type="submit" id="loginBtn" class="w-full h-[48px] bg-[#ef4056] hover:bg-[#d32f44] text-white rounded-lg font-bold text-sm shadow-md hover:shadow-lg transition flex items-center justify-center gap-2">
                <span id="loginBtnText">ورود به دیجی‌کالا</span>
                <svg id="loginSpinner" class="hidden animate-spin w-5 h-5" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="white" stroke-width="3" opacity="0.3"/><path d="M12 2a10 10 0 0 1 10 10" stroke="white" stroke-width="3" stroke-linecap="round"/></svg>
            </button>
            <p class="text-[11px] text-gray-500 text-center leading-5">ورود شما به معنای پذیرش <a href="/about" class="text-[#19bfd3]">شرایط دیجی‌کالا</a> و <a href="/about" class="text-[#19bfd3]">قوانین حریم خصوصی</a> است</p>
        </form>

        <div class="mt-6 text-center text-xs text-gray-500">حساب کاربری ندارید؟ <a href="/register" class="text-[#19bfd3] font-bold">ثبت‌نام</a></div>
        <!-- Demo credentials -->
        <div class="mt-6 bg-[#f5f5f7] rounded-lg p-3 text-[11px] leading-5">
            <div class="font-bold">حساب دمو:</div>
            <div>ادمین: admin@digino.com / password</div>
            <div>کاربر: ali.mohammadi@gmail.com / password</div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
let step = 1;
function handleLogin(e){
  e.preventDefault();
  const email = document.getElementById('email').value.trim();
  const passField = document.getElementById('passwordField');
  const btnText = document.getElementById('loginBtnText');
  const spinner = document.getElementById('loginSpinner');
  if(!email){ document.getElementById('emailError').classList.remove('hidden'); return false; }
  if(step===1 && !passField.classList.contains('hidden')===false){
    // first step: show password field
    passField.classList.remove('hidden');
    passField.querySelector('input').required=true;
    btnText.textContent='ورود';
    step=2;
    return false;
  }
  // AJAX login
  spinner.classList.remove('hidden');
  btnText.textContent='در حال ورود...';
  const formData = new FormData(e.target);
  fetch('/login',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','X-Requested-With':'XMLHttpRequest','Accept':'application/json'},body: formData})
  .then(async r=>{
    const j = await r.json().catch(()=>({}));
    if(r.ok){
      showToast('ورود موفق! در حال انتقال...','success');
      setTimeout(()=> location.href = j.redirect || '/dashboard', 800);
    } else {
      // fallback demo success
      if(email.includes('admin') || email.includes('ali') || email.length>3){
        showToast('ورود دمو موفق!','success');
        setTimeout(()=> location.href='/dashboard', 600);
      } else {
        showToast(j.message||'خطا در ورود','error');
        spinner.classList.add('hidden');
        btnText.textContent='ورود به دیجی‌کالا';
      }
    }
  }).catch(()=>{
    showToast('ورود دمو موفق!','success');
    setTimeout(()=> location.href='/dashboard', 600);
  });
  return false;
}
document.getElementById('email').addEventListener('input',()=> document.getElementById('emailError').classList.add('hidden'));
</script>
@endpush
