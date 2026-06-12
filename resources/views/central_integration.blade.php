<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IAE - Central Console</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 Grid and Utilities -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --bg-dark: #0f172a;
            --panel-bg: #1e293b;
            --term-bg: #0b1329;
            --border-glow: rgba(99, 102, 241, 0.3);
            --accent-blue: #38bdf8;
            --accent-purple: #c084fc;
            --accent-green: #4ade80;
            --accent-amber: #fbbd23;
            --text-main: #f8fafc;
            --text-muted: #cbd5e1;
            --text-title: #38bdf8;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px 0;
        }

        .main-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .console-panel {
            background-color: var(--panel-bg);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        /* Form area */
        .control-side {
            padding: 30px;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        .compact-section-title {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-title);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .compact-section-title span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
        }

        /* Form components */
        .form-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 4px;
        }

        .form-control {
            background-color: rgba(5, 6, 10, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            color: #fff;
            font-size: 0.85rem;
            padding: 8px 12px;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            background-color: var(--term-bg);
            border-color: var(--accent-blue);
            box-shadow: 0 0 10px rgba(56, 189, 248, 0.15);
            color: #fff;
        }

        .btn-compact {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 8px 16px;
            border-radius: 8px;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-compact:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px currentColor;
        }

        .btn-compact:active {
            transform: translateY(1px);
        }

        .btn-blue { background-color: var(--accent-blue); color: #000; }
        .btn-amber { background-color: var(--accent-amber); color: #000; }
        .btn-green { background-color: var(--accent-green); color: #000; }

        /* Terminal screen */
        .terminal-side {
            background-color: var(--term-bg);
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .terminal-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .terminal-screen {
            flex-grow: 1;
            font-family: 'Fira Code', monospace;
            font-size: 0.8rem;
            line-height: 1.5;
            color: var(--accent-green);
            overflow-y: auto;
            max-height: 420px;
            white-space: pre-wrap;
            word-break: break-all;
        }

        .token-bar {
            background: rgba(255, 255, 255, 0.02);
            border: 1px dashed rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 0.75rem;
            font-family: 'Fira Code', monospace;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .terminal-status-success { color: var(--accent-green); }
        .terminal-status-info { color: var(--accent-blue); }
        .terminal-status-warning { color: var(--accent-amber); }

        /* Custom separator */
        .divider {
            height: 1px;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.05), transparent);
            margin: 20px 0;
        }

        .network-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 10px currentColor;
        }
    </style>
</head>
<body>

    <div class="container main-container">
        <!-- Title and Quick Stats -->
        <div class="row align-items-center mb-4">
            <div class="col-md-7">
                <h1 class="h3 fw-bold text-white mb-1">IAE INTEGRATION CONSOLE</h1>
                <p class="text-muted small mb-0">Microservice Tiket & Pembayaran (Bayu Samudera - {{ env('MY_NIM_KEY', '102022400251') }})</p>
            </div>
            <div class="col-md-5 text-md-end mt-2 mt-md-0">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                    <span class="network-dot" style="color: var(--accent-green); background-color: var(--accent-green);"></span>
                    <span class="small text-muted" style="font-size: 0.75rem; font-weight: 600;">GATEWAY: iae-sso.virtualfri.id</span>
                </div>
            </div>
        </div>

        <!-- Split Panel Console -->
        <div class="console-panel row g-0">
            <!-- Left inputs panel -->
            <div class="col-lg-5 control-side">
                
                <!-- 1. Central SSO Login -->
                <div class="compact-section-title">
                    <span style="background-color: var(--accent-blue);"></span> Central SSO Auth
                </div>
                <form action="/central/login" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">API Key Mahasiswa (M2M)</label>
                        <input type="text" class="form-control" name="api_key" value="KEY-MHS-264" placeholder="KEY-MHS-xxx" required>
                        <span style="font-size: 0.7rem; color: var(--text-muted);">Otentikasi terpusat menggunakan API Key Mahasiswa.</span>
                    </div>
                    <button type="submit" class="btn-compact btn-blue w-100">Ambil JWT Token</button>
                </form>

                <div class="divider"></div>

                <!-- 2. SOAP Audit Log -->
                <div class="compact-section-title">
                    <span style="background-color: var(--accent-amber);"></span> SOAP Audit Reporting
                </div>
                <form action="/central/audit" method="POST">
                    @csrf
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label">Order ID</label>
                            <input type="text" class="form-control" name="order_id" value="{{ $ticket->id ?? 'TKT-1234' }}" readonly>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Detail Kursi</label>
                            <input type="text" class="form-control" name="seat" value="{{ $ticket->seat_number ?? 'EXECUTIVE-A1' }}" required>
                        </div>
                    </div>
                    <button type="submit" class="btn-compact btn-amber w-100">Kirim SOAP XML</button>
                </form>

                <div class="divider"></div>

                <!-- 3. Message Broker (RabbitMQ) -->
                <div class="compact-section-title">
                    <span style="background-color: var(--accent-green);"></span> RabbitMQ Publisher
                </div>
                <form action="/central/publish" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Pesan Event (JSON String)</label>
                        <textarea class="form-control" name="message" rows="8" style="font-family: 'Fira Code', monospace; font-size: 0.75rem;" required>{
  "event_name": "ticket.purchased",
  "service_name": "Tickets-Service",
  "api_version": "v1",
  "occurred_at": "{{ now()->toIso8601String() }}",
  "ticket": {
    "id": "{{ $ticket->id ?? '019eb5d2-7bce-7304-8a4a-5ffeaa211dc9' }}",
    "schedule_id": "{{ $ticket->schedule_id ?? '102022400251' }}",
    "seat_number": "{{ $ticket->seat_number ?? 'A1' }}",
    "total_price": {{ $ticket->total_price ?? 100000 }},
    "status": "{{ $ticket->status ?? 'AUDITED' }}",
    "legacy_receipt_number": "{{ $ticket->receipt_number ?? 'IAE-LOG-2026-93003CC9' }}"
  },
  "published_by": {
    "api_key": "KEY-MHS-264",
    "team_id": "{{ env('MY_TEAM_ID', 'TEAM-12') }}"
  }
}</textarea>
                    </div>
                    <button type="submit" class="btn-compact btn-green w-100">Publish ke Event Bus</button>
                </form>

            </div>

            <!-- Right terminal panel -->
            <div class="col-lg-7 terminal-side">
                <div>
                    <div class="terminal-header d-flex justify-content-between align-items-center">
                        <span class="small text-muted fw-bold" style="letter-spacing: 0.05em;">LIVE RESPONSE STREAM</span>
                        <div class="d-flex gap-1.5">
                            <span style="width: 10px; height: 10px; border-radius:50%; background-color:#ff5f56; display:inline-block;"></span>
                            <span style="width: 10px; height: 10px; border-radius:50%; background-color:#ffbd2e; display:inline-block;"></span>
                            <span style="width: 10px; height: 10px; border-radius:50%; background-color:#27c93f; display:inline-block;"></span>
                        </div>
                    </div>

                    <!-- JWT Token session status -->
                    <div class="token-bar mb-3">
                        <span class="text-muted">ACTIVE_JWT_SESSION:</span>
                        @if(session('jwt_token'))
                            <span class="terminal-status-success">
                                {{ strtoupper(session('jwt_login_type', 'warga')) }} [{{ session('jwt_identifier') }}] - (ACTIVE)
                            </span>
                        @else
                            <span class="text-danger">NONE (UNAUTHORIZED)</span>
                        @endif
                    </div>
                </div>

                <!-- Terminal response monitor -->
                <div class="terminal-screen p-3 rounded" style="background-color: #030406; min-height: 280px; border: 1px solid rgba(255, 255, 255, 0.02);">
                    @if(session('response_data'))
                        @if(isset(session('response_data')['raw_response']))
                            <!-- SOAP Output style -->
                            <span class="terminal-status-info">system@audit-soap:~$ post /soap/v1/audit --payload="XML"</span><br>
                            <span class="text-white">&gt; Status Code: {{ session('response_data')['status_code'] }}</span><br>
                            <span class="text-white">&gt; Raw SOAP XML Response:</span><br>
                            <span class="terminal-status-warning">{{ session('response_data')['raw_response'] }}</span>
                        @else
                            <!-- REST JSON Output style -->
                            <span class="terminal-status-info">system@sso-rabbitmq:~$ post response --json</span><br>
                            <span class="text-white">{{ json_encode(session('response_data'), JSON_PRETTY_PRINT) }}</span>
                        @endif
                    @else
                        <span class="text-muted">// Awaiting input actions. Execute one of the forms on the left to trace integration logs...</span>
                    @endif
                </div>

                <div class="mt-3 text-end text-muted" style="font-size: 0.7rem; font-family: 'Fira Code', monospace;">
                    connection: secure_tls_1.3
                </div>
    </div>
</body>
</html>
