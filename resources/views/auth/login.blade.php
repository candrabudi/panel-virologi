<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <title>Masuk | Panel Virologi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <link href="{{ asset('assets/css/vendors.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" />
</head>

<body>
    <div class="auth-box overflow-hidden align-items-center d-flex">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-md-6 col-sm-8">
                    <div class="auth-brand text-center mb-4">
                        <h4 class="fw-bold mt-3">Panel Virologi</h4>
                        <p class="text-muted">Autentikasi dua langkah</p>
                    </div>

                    <div class="card p-4">
                        <div id="alert-box"></div>

                        <form id="form-login">
                            <div id="step-login">
                                <div class="mb-3">
                                    <label class="form-label">Username atau Email</label>
                                    <input type="text" class="form-control" id="identity" required autofocus>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Kata Sandi</label>
                                    <input type="password" class="form-control" id="password" required>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary" id="btn-login">
                                        <span class="btn-text">Masuk</span>
                                        <span class="spinner-border spinner-border-sm d-none"></span>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <form id="form-otp" class="d-none">
                            <div class="mb-3">
                                <label class="form-label">Kode OTP</label>
                                <input type="text" class="form-control" id="otp" placeholder="6 digit OTP"
                                    inputmode="numeric" required>
                                <small class="text-muted">
                                    Kode OTP telah dikirim ke email Anda
                                </small>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success" id="btn-verify">
                                    <span class="btn-text">Verifikasi OTP</span>
                                    <span class="spinner-border spinner-border-sm d-none"></span>
                                </button>
                            </div>
                            <button type="button" class="btn btn-link mt-3 w-100" id="btn-back-login">
                                ← Kembali ke login
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
        axios.defaults.headers.common['X-CSRF-TOKEN'] =
            document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        axios.defaults.withCredentials = true

        const alertBox = document.getElementById('alert-box')
        const formLogin = document.getElementById('form-login')
        const formOtp = document.getElementById('form-otp')

        const showAlert = (type, message) => {
            alertBox.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `
        }

        const setLoading = (btn, loading) => {
            btn.disabled = loading
            btn.querySelector('.btn-text').classList.toggle('d-none', loading)
            btn.querySelector('.spinner-border').classList.toggle('d-none', !loading)
        }

        if (sessionStorage.getItem('otp_step') === '1') {
            formLogin.classList.add('d-none')
            formOtp.classList.remove('d-none')
        }

        formLogin.addEventListener('submit', async (e) => {
            e.preventDefault()
            const btn = document.getElementById('btn-login')
            setLoading(btn, true)

            try {
                await axios.post('{{ route('login.process') }}', {
                    identity: document.getElementById('identity').value.trim(),
                    password: document.getElementById('password').value
                })

                await axios.post('{{ route('login.sendOtp') }}')

                sessionStorage.setItem('otp_step', '1')

                showAlert('success', 'Login berhasil. Kode OTP telah dikirim ke email Anda.')
                formLogin.classList.add('d-none')
                formOtp.classList.remove('d-none')
                document.getElementById('otp').focus()
            } catch (e) {
                showAlert('danger', e.response?.data?.message || 'Login gagal')
            }

            setLoading(btn, false)
        })

        formOtp.addEventListener('submit', async (e) => {
            e.preventDefault()

            const otp = document.getElementById('otp').value.trim()
            if (otp.length !== 6) {
                showAlert('warning', 'Kode OTP harus 6 digit')
                return
            }

            const btn = document.getElementById('btn-verify')
            setLoading(btn, true)

            try {
                await axios.post('{{ route('login.verify') }}', {
                    otp
                })
                sessionStorage.removeItem('otp_step')
                showAlert('success', 'Verifikasi berhasil, mengalihkan ke dashboard...')
                setTimeout(() => {
                    window.location.href = '/dashboard'
                }, 800)
            } catch (e) {
                showAlert('danger', e.response?.data?.message || 'Kode OTP salah atau sudah kedaluwarsa')
            }

            setLoading(btn, false)
        })

        document.getElementById('btn-back-login').onclick = () => {
            sessionStorage.removeItem('otp_step')
            formOtp.classList.add('d-none')
            formLogin.classList.remove('d-none')
            alertBox.innerHTML = ''
            document.getElementById('identity').focus()
        }
    </script>
</body>

</html>
