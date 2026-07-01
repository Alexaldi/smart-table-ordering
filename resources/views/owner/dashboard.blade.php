<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Owner Dashboard</title>
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            color: #111827;
        }

        .owner-shell {
            min-height: 100vh;
            padding: 32px;
        }

        .owner-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }

        .owner-title {
            margin: 0 0 6px;
            font-size: 28px;
        }

        .owner-subtitle {
            margin: 0;
            color: #6b7280;
        }

        .owner-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .06);
        }

        .owner-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .owner-button {
            border: 0;
            border-radius: 8px;
            background: #2563eb;
            color: #fff;
            cursor: pointer;
            font-weight: 700;
            padding: 10px 14px;
        }

        .owner-note {
            line-height: 1.6;
            margin: 0;
            color: #374151;
        }

        @media (max-width: 640px) {
            .owner-shell {
                padding: 20px;
            }

            .owner-header {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <main class="owner-shell">
        <header class="owner-header">
            <div>
                <h1 class="owner-title">Owner Dashboard</h1>
                <p class="owner-subtitle">Area laporan read-only untuk owner.</p>
            </div>

            <div class="owner-actions">
                @include('components.notification-bell')

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="owner-button" type="submit">Logout</button>
                </form>
            </div>
        </header>

        <section class="owner-card">
            <p class="owner-note">
                Role owner sekarang sudah bisa login dan diarahkan ke halaman ini.
                Langkah berikutnya adalah mengisi halaman ini dengan Owner Report, export Excel/PDF, dan grafik tren.
            </p>
        </section>
    </main>
</body>
</html>
