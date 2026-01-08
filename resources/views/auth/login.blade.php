<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" class="opacity-0" lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Virologi</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('dist/css/vendors/tippy.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/app.css') }}">
</head>

<body>
    <div
        class="container grid grid-cols-12 px-5 py-10 sm:px-10 sm:py-14 md:px-36 lg:h-screen lg:max-w-[1550px] lg:py-0 lg:pl-14 lg:pr-12 xl:px-24 2xl:max-w-[1750px]">
        <div
            class="relative z-50 h-full col-span-12 p-7 sm:p-14 bg-white rounded-2xl lg:bg-transparent lg:pr-10 lg:col-span-5 xl:pr-24 2xl:col-span-4 lg:p-0 before:content-[''] before:absolute before:inset-0 before:-mb-3.5 before:bg-white/40 before:rounded-2xl before:mx-5">
            <div class="relative z-10 flex flex-col justify-center w-full h-full py-2 lg:py-32">
                <div
                    class="flex h-[55px] w-[55px] items-center justify-center rounded-[0.8rem] border border-primary/30">
                    <div
                        class="relative flex h-[50px] w-[50px] items-center justify-center rounded-[0.6rem] bg-white bg-gradient-to-b from-theme-1/90 to-theme-2/90">
                        <div class="relative h-[26px] w-[26px] -rotate-45 [&_div]:bg-white">
                            <div class="absolute inset-y-0 left-0 my-auto h-[75%] w-[20%] rounded-full opacity-50">
                            </div>
                            <div class="absolute inset-0 m-auto h-[120%] w-[20%] rounded-full"></div>
                            <div class="absolute inset-y-0 right-0 my-auto h-[75%] w-[20%] rounded-full opacity-50">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-10">
                    <div class="text-2xl font-medium mb-5">Sign In</div>

                    <!-- Alert Box -->
                    <div id="alert-box"></div>

                    <!-- Form Login -->
                    <form id="form-login">
                        <div class="mt-6">
                            <label
                                class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                Email*
                            </label>
                            <input id="identity" type="text" placeholder="brad.pitt@left4code.com"
                                class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 block rounded-[0.6rem] border-slate-300/80 px-4 py-3.5">
                        </div>

                        <div class="mt-4">
                            <label
                                class="inline-block mb-2 group-[.form-inline]:mb-2 group-[.form-inline]:sm:mb-0 group-[.form-inline]:sm:mr-5 group-[.form-inline]:sm:text-right">
                                Password*
                            </label>
                            <input id="password" type="password" placeholder="************"
                                class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 block rounded-[0.6rem] border-slate-300/80 px-4 py-3.5">
                        </div>

                        <div class="mt-5 text-center xl:mt-8 xl:text-left">
                            <button id="btn-login" type="submit"
                                class="transition duration-200 border shadow-sm inline-flex items-center justify-center px-3 font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-primary border-primary text-white dark:border-primary rounded-full w-full bg-gradient-to-r from-theme-1/70 to-theme-2/70 py-3.5 xl:mr-3">
                                <span class="btn-text">Sign In</span>
                                <svg class="spinner-border hidden animate-spin h-5 w-5 text-white ml-2"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                            </button>
                        </div>
                    </form>

                    <!-- Form OTP -->
                    <form id="form-otp" class="hidden mt-6">
                        <label class="inline-block mb-2 font-medium">Enter OTP*</label>
                        <div class="flex justify-between gap-2 mt-auto">
                            <input type="text" maxlength="1"
                                class="otp-input w-14 h-14 text-center text-xl rounded-[0.6rem] border border-slate-300 dark:border-darkmode-700 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:outline-none" />
                            <input type="text" maxlength="1"
                                class="otp-input w-14 h-14 text-center text-xl rounded-[0.6rem] border border-slate-300 dark:border-darkmode-700 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:outline-none" />
                            <input type="text" maxlength="1"
                                class="otp-input w-14 h-14 text-center text-xl rounded-[0.6rem] border border-slate-300 dark:border-darkmode-700 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:outline-none" />
                            <input type="text" maxlength="1"
                                class="otp-input w-14 h-14 text-center text-xl rounded-[0.6rem] border border-slate-300 dark:border-darkmode-700 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:outline-none" />
                            <input type="text" maxlength="1"
                                class="otp-input w-14 h-14 text-center text-xl rounded-[0.6rem] border border-slate-300 dark:border-darkmode-700 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:outline-none" />
                            <input type="text" maxlength="1"
                                class="otp-input w-14 h-14 text-center text-xl rounded-[0.6rem] border border-slate-300 dark:border-darkmode-700 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:outline-none" />
                        </div>

                        <div class="mt-5 text-center xl:mt-8 xl:text-left">
                            <button id="btn-verify" type="submit"
                                class="transition duration-200 border shadow-sm inline-flex items-center justify-center px-3 font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 disabled:opacity-70 disabled:cursor-not-allowed bg-primary border-primary text-white dark:border-primary rounded-full w-full bg-gradient-to-r from-theme-1/70 to-theme-2/70 py-3.5 xl:mr-3">
                                <span class="btn-text">Verify OTP</span>
                                <svg class="spinner-border hidden animate-spin h-5 w-5 text-white ml-2"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                            </button>
                            <button id="btn-back-login" type="button"
                                class="transition duration-200 border shadow-sm inline-flex items-center justify-center px-3 font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none disabled:opacity-70 disabled:cursor-not-allowed border-secondary text-slate-500 dark:border-darkmode-100/40 dark:text-slate-300 [&:hover:not(:disabled)]:bg-secondary/20 [&:hover:not(:disabled)]:dark:bg-darkmode-100/10 rounded-full mt-3 w-full bg-white/70 py-3.5">
                                Back
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div
        class="container fixed inset-0 grid h-screen w-screen grid-cols-12 pl-14 pr-12 lg:max-w-[1550px] xl:px-24 2xl:max-w-[1750px]">
        <div
            class="relative h-screen col-span-12 lg:col-span-5 2xl:col-span-4 z-20 after:bg-white after:hidden after:lg:block after:content-[''] after:absolute after:right-0 after:inset-y-0 after:bg-gradient-to-b after:from-white after:to-slate-100/80 after:w-[800%] after:rounded-[0_1.2rem_1.2rem_0/0_1.7rem_1.7rem_0] before:content-[''] before:hidden before:lg:block before:absolute before:right-0 before:inset-y-0 before:my-6 before:bg-gradient-to-b before:from-white/10 before:to-slate-50/10 before:bg-white/50 before:w-[800%] before:-mr-4 before:rounded-[0_1.2rem_1.2rem_0/0_1.7rem_1.7rem_0]">
        </div>
        <div
            class="h-full col-span-7 2xl:col-span-8 lg:relative before:content-[''] before:absolute before:lg:-ml-10 before:left-0 before:inset-y-0 before:bg-gradient-to-b before:from-theme-1 before:to-theme-2 before:w-screen before:lg:w-[800%] after:content-[''] after:absolute after:inset-y-0 after:left-0 after:w-screen after:lg:w-[800%] after:bg-texture-white after:bg-fixed after:bg-center after:lg:bg-[25rem_-25rem] after:bg-no-repeat">
            <div class="sticky top-0 z-10 flex-col justify-center hidden h-screen ml-16 lg:flex xl:ml-28 2xl:ml-36">
                <div class="text-[2.6rem] font-medium leading-[1.4] text-white xl:text-5xl xl:leading-[1.2]">
                    Amankan Panel Anda <br> Bersama Virologi
                </div>
                <div class="mt-5 text-base leading-relaxed text-white/70 xl:text-lg">
                    Lindungi panel dan data Anda dengan solusi keamanan siber terkini.
                    Virologi memastikan dashboard Anda terlindungi dari ancaman,
                    memberikan akses yang aman, handal, dan efisien ke sistem penting.
                </div>
            </div>
        </div>

    </div>
    <!-- BEGIN: Vendor JS Assets-->
    <script src="{{ asset('dist/js/vendors/dom.js') }}"></script>
    <script src="{{ asset('dist/js/vendors/tailwind-merge.js') }}"></script>
    <script src="{{ asset('dist/js/vendors/lucide.js') }}"></script>
    <script src="{{ asset('dist/js/vendors/alert.js') }}"></script>
    <script src="{{ asset('dist/js/vendors/tippy.js') }}"></script>
    <script src="{{ asset('dist/js/vendors/modal.js') }}"></script>
    <script src="{{ asset('dist/js/components/base/theme-color.js') }}"></script>
    <script src="{{ asset('dist/js/components/base/lucide.js') }}"></script>
    <script src="{{ asset('dist/js/components/base/tippy.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
        axios.defaults.withCredentials = true;

        const alertBox = document.getElementById('alert-box');
        const formLogin = document.getElementById('form-login');
        const formOtp = document.getElementById('form-otp');
        const otpInputs = formOtp.querySelectorAll('.otp-input');

        const showAlert = (type, message) => {
            alertBox.innerHTML = `
            <div class="alert flex items-center rounded-lg border border-${type}/20 bg-${type}/5 px-4 py-3 text-${type} my-7 shadow-sm">
                <div class="ml-1 mr-8">${message}</div>
            </div>`;
        }

        const setLoading = (btn, loading) => {
            btn.disabled = loading;
            btn.querySelector('.btn-text').classList.toggle('hidden', loading);
            btn.querySelector('.spinner-border').classList.toggle('hidden', !loading);
        }

        // Logic Fokus OTP
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                if (input.value.length > 0 && index < otpInputs.length - 1) otpInputs[index + 1].focus();
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && input.value === '' && index > 0) otpInputs[index - 1].focus();
            });
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = e.clipboardData.getData('text').slice(0, 6).split('');
                pasteData.forEach((char, i) => { if (otpInputs[i]) otpInputs[i].value = char; });
                otpInputs[Math.min(pasteData.length, 5)].focus();
            });
        });

        if (sessionStorage.getItem('otp_step') === '1') {
            formLogin.classList.add('hidden');
            formOtp.classList.remove('hidden');
        }

        formLogin.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn-login');
            setLoading(btn, true);
            try {
                // STEP 1: Cek Login
                await axios.post('{{ route('login.process') }}', {
                    identity: document.getElementById('identity').value,
                    password: document.getElementById('password').value
                });
                
                // STEP 2: Kirim OTP
                await axios.post('{{ route('login.sendOtp') }}');
                
                sessionStorage.setItem('otp_step', '1');
                showAlert('primary', 'Kredensial valid, kode OTP dikirim.');
                formLogin.classList.add('hidden');
                formOtp.classList.remove('hidden');
                otpInputs[0].focus();
            } catch (err) {
                let msg = err.response?.data?.message || 'Terjadi kesalahan sistem';
                if (err.response?.status === 419) msg = 'Sesi berakhir, silakan refresh halaman.';
                showAlert('danger', msg);
            } finally { setLoading(btn, false); }
        });

        formOtp.addEventListener('submit', async (e) => {
            e.preventDefault();
            const otp = Array.from(otpInputs).map(i => i.value).join('');
            if (otp.length !== 6) return showAlert('warning', 'Input 6 digit OTP');
            
            const btn = document.getElementById('btn-verify');
            setLoading(btn, true);
            try {
                await axios.post('{{ route('login.verify') }}', { otp });
                sessionStorage.removeItem('otp_step');
                showAlert('success', 'Sukses, mengalihkan...');
                setTimeout(() => location.href = '/dashboard', 800);
            } catch (err) {
                showAlert('danger', err.response?.data?.message || 'OTP Salah');
            } finally { setLoading(btn, false); }
        });

        document.getElementById('btn-back-login').onclick = () => {
            sessionStorage.removeItem('otp_step');
            formOtp.classList.add('hidden');
            formLogin.classList.remove('hidden');
            alertBox.innerHTML = '';
        };
    </script>
</body>

</html>
