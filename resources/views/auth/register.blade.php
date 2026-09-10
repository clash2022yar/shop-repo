@extends('layouts.minimal')
@section('title','ثبت‌نام | دیجی‌نو')
@section('content')
<div class="min-h-[calc(100vh-64px)] flex items-center justify-center px-4 py-10 bg-white">
    <div class="w-full max-w-[420px] border border-gray-200 rounded-xl p-6 lg:p-8 shadow-sm">
        <div class="text-center">
            <div class="text-[22px] font-black text-[#ef4056]">دیجینو</div>
            <h1 class="font-bold mt-6 text-right">ثبت‌نام در دیجینو</h1>
            <p class="text-xs text-gray-500 mt-2 text-right">برای ادامه، اطلاعات زیر را تکمیل کنید</p>
        </div>
        <form id="registerForm" class="mt-6 space-y-3" onsubmit="return handleRegister(event)">
            <input name="name" placeholder="نام و نام خانوادگی" required class="w-full h-11 border rounded-lg px-3 text-sm outline-none focus:border-[#19bfd3] focus:ring-2 focus:ring-[#19bfd3]/10">
            <input name="email" type="email" placeholder="ایمیل" required class="w-full h-11 border rounded-lg px-3 text-sm outline-none focus:border-[#19bfd3]">
            <input name="phone" placeholder="شماره موبایل (اختیاری)" class="w-full h-11 border rounded-lg px-3 text-sm ltr" dir="ltr">
            <input name="password" type="password" placeholder="رمز عبور (حداقل 8 کاراکتر)" required class="w-full h-11 border rounded-lg px-3 text-sm outline-none focus:border-[#19bfd3]">
            <input name="password_confirmation" type="password" placeholder="تکرار رمز عبور" required class="w-full h-11 border rounded-lg px-3 text-sm outline-none focus:border-[#19bfd3]">
            <label class="flex items-center gap-2 text-xs"><input type="checkbox" required class="accent-[#ef4056]"> با <a href="/about" class="text-[#19bfd3]">قوانین و حریم خصوصی</a> موافقم</label>
            <button type="submit" class="w-full h-11 bg-[#ef4056] hover:bg-[#d32f44] text-white rounded-lg font-bold text-sm flex items-center justify-center gap-2">
                <span id="regText">ثبت‌نام</span>
                <svg id="regSpin" class="hidden animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="white" stroke-width="3" opacity="0.3"/><path d="M12 2a10 10 0 0 1 10 10" stroke="white" stroke-width="3"/></svg>
            </button>
        </form>
        <div class="text-center text-xs mt-4">قبلا ثبت‌نام کرده‌اید؟ <a href="/login" class="text-[#19bfd3] font-bold">ورود</a></div>
    </div>
</div>
@endsection
@push('scripts')
<script>
function handleRegister(e){
  e.preventDefault();
  const btn=document.querySelector('#registerForm button'); btn.disabled=true;
  document.getElementById('regText').textContent='در حال ثبت‌نام...';
  document.getElementById('regSpin').classList.remove('hidden');
  const fd=new FormData(e.target);
  fetch('/register',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','X-Requested-With':'XMLHttpRequest'},body:fd})
  .then(r=>r.json().catch(()=>({}))).then(j=>{
    showToast('ثبت‌نام موفق!','success');
    setTimeout(()=> location.href='/dashboard',700);
  }).catch(()=>{
    showToast('ثبت‌نام دمو موفق!','success');
    setTimeout(()=> location.href='/dashboard',700);
  });
  return false;
}
</script>
@endpush
