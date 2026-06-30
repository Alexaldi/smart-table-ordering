<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    @include('components.style')

    <style>
        body.sto-login-body {
            min-height: 100vh;
            background: #f4f7fb;
            color: #111827;
        }

        .sto-login-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 18px;
        }

        .sto-login-wrap {
            width: min(100%, 980px);
            display: grid;
            grid-template-columns: minmax(0, 1.05fr) minmax(360px, .95fr);
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, .08);
            overflow: hidden;
        }

        .sto-login-panel {
            padding: 42px;
        }

        .sto-brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 34px;
        }

        .sto-brand-mark {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }

        .sto-brand-mark i {
            font-size: 20px;
        }

        .sto-brand-title {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
            line-height: 1.2;
        }

        .sto-brand-subtitle {
            font-size: 12px;
            color: #6b7280;
            margin-top: 3px;
        }

        .sto-login-title {
            font-size: 26px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
            letter-spacing: 0;
        }

        .sto-login-lead {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.6;
            max-width: 420px;
            margin-bottom: 28px;
        }

        .sto-field {
            margin-bottom: 18px;
        }

        .sto-field-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #6b7280;
            margin-bottom: 7px;
        }

        .sto-input-wrap {
            position: relative;
        }

        .sto-input-wrap i {
            position: absolute;
            top: 50%;
            left: 13px;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 15px;
            pointer-events: none;
        }

        .sto-input {
            width: 100%;
            height: 43px;
            background: #f9fafb;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 0 14px 0 39px;
            color: #111827;
            font-size: 14px;
            outline: none;
            transition: border-color .15s ease, box-shadow .15s ease, background .15s ease;
        }

        .sto-input:focus {
            background: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .1);
        }

        .sto-input.is-invalid {
            border-color: #dc2626;
        }

        .sto-input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220, 38, 38, .1);
        }

        .sto-error {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #dc2626;
            font-size: 12px;
            margin-top: 7px;
        }

        .sto-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin: 3px 0 22px;
        }

        .sto-check {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #374151;
            font-size: 13px;
            user-select: none;
        }

        .sto-check input {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            accent-color: #2563eb;
        }

        .sto-help {
            color: #9ca3af;
            font-size: 12px;
            text-align: right;
        }

        .sto-submit {
            width: 100%;
            height: 43px;
            border: 0;
            border-radius: 8px;
            background: #2563eb;
            color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background .15s ease, transform .15s ease, box-shadow .15s ease;
            box-shadow: 0 10px 20px rgba(37, 99, 235, .18);
        }

        .sto-submit:hover,
        .sto-submit:focus {
            background: #1d4ed8;
            color: #ffffff;
        }

        .sto-submit:active {
            transform: scale(.99);
        }

        .sto-side {
            position: relative;
            padding: 42px;
            background: #f9fafb;
            border-left: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 28px;
        }

        .sto-side-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .sto-admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            padding: 7px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: #ffffff;
        }

        .sto-admin-badge i {
            color: #2563eb;
        }

        .sto-side-time {
            font-size: 12px;
            color: #9ca3af;
        }

        .sto-side-title {
            font-size: 20px;
            font-weight: 700;
            line-height: 1.35;
            color: #111827;
            margin-bottom: 10px;
            letter-spacing: 0;
        }

        .sto-side-copy {
            color: #6b7280;
            font-size: 13px;
            line-height: 1.7;
            margin-bottom: 22px;
        }

        .sto-mini-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .sto-mini-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 16px;
        }

        .sto-mini-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
            color: #9ca3af;
            margin-bottom: 8px;
        }

        .sto-mini-value {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #111827;
            font-size: 15px;
            font-weight: 700;
        }

        .sto-mini-value i {
            color: #2563eb;
            font-size: 17px;
        }

        .sto-side-note {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 14px;
            color: #1e40af;
            font-size: 12px;
            line-height: 1.55;
        }

        .sto-side-note i {
            margin-top: 2px;
            flex: 0 0 auto;
        }

        @media (max-width: 991.98px) {
            .sto-login-wrap {
                grid-template-columns: 1fr;
                max-width: 560px;
            }

            .sto-side {
                border-left: 0;
                border-top: 1px solid #e5e7eb;
            }
        }

        @media (max-width: 575.98px) {
            .sto-login-shell {
                align-items: flex-start;
                padding: 16px;
            }

            .sto-login-wrap {
                border-radius: 12px;
            }

            .sto-login-panel,
            .sto-side {
                padding: 24px 20px;
            }

            .sto-brand {
                margin-bottom: 26px;
            }

            .sto-login-title {
                font-size: 23px;
            }

            .sto-options,
            .sto-side-top {
                align-items: flex-start;
                flex-direction: column;
            }

            .sto-help {
                text-align: left;
            }

            .sto-mini-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="sto-login-body">
    <main class="sto-login-shell">
        <section class="sto-login-wrap" aria-label="Login admin">
            <div class="sto-login-panel">
                <div class="sto-brand">
                    <div class="sto-brand-mark" aria-hidden="true">
                        <i class="fe fe-grid"></i>
                    </div>
                    <div>
                        <div class="sto-brand-title">Cafe Admin</div>
                        <div class="sto-brand-subtitle">Smart Table Ordering</div>
                    </div>
                </div>

                <h1 class="sto-login-title">Masuk ke akun</h1>
                <p class="sto-login-lead">
                    Atur menu, meja, dan pesanan dengan lebih mudah.
                </p>

                <form method="POST" action="{{ route('login.process') }}" novalidate>
                    @csrf

                    <div class="sto-field">
                        <label class="sto-field-label" for="email">Email</label>
                        <div class="sto-input-wrap">
                            <i class="fe fe-mail"></i>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                class="sto-input @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                placeholder="admin@caffee.test"
                                autocomplete="email"
                                autofocus
                            >
                        </div>
                        @error('email')
                            <div class="sto-error">
                                <i class="fe fe-alert-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="sto-field">
                        <label class="sto-field-label" for="password">Password</label>
                        <div class="sto-input-wrap">
                            <i class="fe fe-lock"></i>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="sto-input @error('password') is-invalid @enderror"
                                placeholder="Masukkan password"
                                autocomplete="current-password"
                            >
                        </div>
                        @error('password')
                            <div class="sto-error">
                                <i class="fe fe-alert-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="sto-options">
                        <label class="sto-check" for="remember">
                            <input
                                id="remember"
                                type="checkbox"
                                name="remember"
                                value="1"
                                {{ old('remember') ? 'checked' : '' }}
                            >
                            <span>Ingat saya</span>
                        </label>
                        <span class="sto-help">Khusus staf yang sudah terdaftar.</span>
                    </div>

                    <button type="submit" class="sto-submit">
                        <i class="fe fe-log-in"></i>
                        <span>Masuk</span>
                    </button>
                </form>
            </div>

            <aside class="sto-side" aria-label="Ringkasan panel">
                <div>
                    <div class="sto-side-top">
                        <span class="sto-admin-badge">
                            <i class="fe fe-shield"></i>
                            Area Staf
                        </span>
                        <span class="sto-side-time">Siap digunakan hari ini</span>
                    </div>

                    <div class="mt-5">
                        <h2 class="sto-side-title">Masuk dulu untuk mulai mengelola pesanan.</h2>
                        <p class="sto-side-copy">
                            Semua kebutuhan harian restoran bisa diakses dari satu tempat.
                        </p>
                    </div>

                    <div class="sto-mini-grid">
                        <div class="sto-mini-card">
                            <div class="sto-mini-label">Menu</div>
                            <div class="sto-mini-value">
                                <i class="fe fe-book-open"></i>
                                <span>Siap diatur</span>
                            </div>
                        </div>
                        <div class="sto-mini-card">
                            <div class="sto-mini-label">Meja</div>
                            <div class="sto-mini-value">
                                <i class="fe fe-smartphone"></i>
                                <span>QR siap dipakai</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sto-side-note">
                    <i class="fe fe-info"></i>
                    <span>Gunakan akun staf yang sudah terdaftar untuk masuk ke halaman kerja yang sesuai.</span>
                </div>
            </aside>
        </section>
    </main>
</body>
</html>
