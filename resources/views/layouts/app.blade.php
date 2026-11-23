<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Budget Tracker')</title>
    <style>
        :root {
            --bg:#fff7f0; --card:#ffffff; --ink:#3d2c2e; --muted:#8a7a76; --line:#f3e3d9;
            --primary:#e8685a; --primary-dark:#d4574a; --primary-soft:#ffe4df;
            --green:#2f9e6b; --green-soft:#e1f5ea;
            --amber:#c27a12; --amber-soft:#fff1d6;
            --teal:#2a8aa0; --teal-soft:#ddf1f6;
            --violet:#7461c4; --violet-soft:#ece8fb;
            --red:#cf4538; --red-soft:#fde4e1;
            --shadow:0 4px 14px rgba(120,70,50,.08);
        }
        * { box-sizing:border-box; }
        body {
            margin:0; min-height:100vh; color:var(--ink); font-size:16px; line-height:1.5;
            font-family:"Nunito","Segoe UI",system-ui,-apple-system,Arial,sans-serif;
            background:linear-gradient(180deg,#ffeee2 0,var(--bg) 280px);
        }
        a { color:var(--primary-dark); text-decoration:none; }
        h1, h2, h3 { margin:0; }

        /* top bar */
        .topbar { display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;
                  padding:14px 26px; background:#fff; box-shadow:var(--shadow); }
        .brand { font-size:1.3rem; font-weight:800; color:var(--primary); margin:0; }
        .navlinks { display:flex; gap:8px; flex-wrap:wrap; }
        .navlinks a { padding:8px 16px; border-radius:999px; color:var(--ink); font-weight:700; font-size:15px; }
        .navlinks a:hover { background:var(--primary-soft); color:var(--primary-dark); }
        .userbox { display:flex; align-items:center; gap:12px; flex-wrap:wrap; color:var(--muted); }
        .userbox b { color:var(--ink); }

        main { max-width:900px; margin:26px auto 60px; padding:0 16px; }

        /* cards */
        .card { background:var(--card); border-radius:20px; box-shadow:var(--shadow); padding:22px; margin-bottom:18px; }
        .card h3 { margin-bottom:6px; font-size:1.15rem; }

        /* hero */
        .hero { display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;
                background:linear-gradient(135deg,#ffe4df,#fff1d6); border-radius:22px; padding:24px; margin-bottom:18px; }
        .hero h1 { font-size:1.7rem; margin-bottom:6px; }
        .hero p { margin:0; color:#5b4648; font-size:1.05rem; }

        .monthbar { display:flex; justify-content:center; align-items:center; gap:14px; margin-bottom:18px; flex-wrap:wrap; }
        .monthbar strong { font-size:1.15rem; min-width:150px; text-align:center; }

        /* stat cards */
        .stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(190px,1fr)); gap:14px; margin-bottom:18px; }
        .stat { border-radius:20px; padding:18px 20px; box-shadow:var(--shadow); }
        .stat .num { font-size:1.6rem; font-weight:800; }
        .stat .lbl { font-size:14.5px; color:#5b4648; margin-top:2px; }
        .s-green { background:var(--green-soft); } .s-green .num { color:var(--green); }
        .s-red   { background:var(--red-soft); }   .s-red .num   { color:var(--red); }
        .s-teal  { background:var(--teal-soft); }  .s-teal .num  { color:var(--teal); }
        .s-amber { background:var(--amber-soft); } .s-amber .num { color:var(--amber); }

        /* bars */
        .bar-label { display:flex; justify-content:space-between; gap:10px; margin:16px 0 6px; font-weight:700; font-size:15px; }
        .bar-label span:last-child { color:var(--muted); font-weight:600; }
        .bar { height:14px; background:#f6ebe3; border-radius:999px; overflow:hidden; }
        .bar span { display:block; height:100%; border-radius:999px; }
        .bar-need { background:var(--teal); } .bar-want { background:var(--violet); } .bar-save { background:var(--green); }

        /* section title */
        .section-head { display:flex; justify-content:space-between; align-items:center; gap:10px; margin:26px 0 12px; flex-wrap:wrap; }
        .section-head h3 { font-size:1.15rem; }

        /* list items */
        .item { background:#fff; border-radius:18px; padding:16px 18px; margin-bottom:12px; box-shadow:var(--shadow);
                border-left:6px solid var(--line); }
        .item.k-need   { border-left-color:var(--teal); }
        .item.k-want   { border-left-color:var(--violet); }
        .item.k-income { border-left-color:var(--green); }
        .item.unpaid   { background:#fffaf0; }
        .item .title { font-size:1.05rem; font-weight:800; }
        .item .meta { color:var(--muted); font-size:14px; margin-top:4px; }
        .ico { width:46px; height:46px; border-radius:50%; display:flex; align-items:center; justify-content:center;
               font-size:22px; background:var(--primary-soft); flex-shrink:0; }
        .ico.k-need { background:var(--teal-soft); } .ico.k-want { background:var(--violet-soft); } .ico.k-income { background:var(--green-soft); }
        .money { font-weight:800; font-size:1.15rem; text-align:right; margin-bottom:8px; }
        .money.income { color:var(--green); } .money.expense { color:var(--red); }
        .row { display:flex; justify-content:space-between; align-items:flex-start; gap:14px; flex-wrap:wrap; }
        .actions { display:flex; gap:6px; flex-wrap:wrap; justify-content:flex-end; align-items:center; }
        .actions form { margin:0; }
        .actions .btn { padding:7px 14px; font-size:13.5px; }

        /* tags */
        .tags { margin-top:6px; }
        .tag { display:inline-block; font-size:12.5px; font-weight:700; padding:3px 11px; border-radius:999px; margin:0 6px 0 0; }
        .tag.pending, .tag.unpaid { background:var(--amber-soft); color:var(--amber); }
        .tag.done, .tag.paid, .tag.income { background:var(--green-soft); color:var(--green); }
        .tag.expense { background:var(--red-soft); color:var(--red); }
        .tag.need { background:var(--teal-soft); color:var(--teal); }
        .tag.want { background:var(--violet-soft); color:var(--violet); }

        /* buttons */
        .btn { display:inline-block; font-family:inherit; font-size:15px; font-weight:800; padding:11px 20px; border:0;
               border-radius:999px; cursor:pointer; background:var(--primary); color:#fff; transition:.15s; text-align:center; }
        .btn:hover { background:var(--primary-dark); color:#fff; }
        .btn-soft  { background:var(--primary-soft); color:var(--primary-dark); } .btn-soft:hover { background:#ffd3cc; color:var(--primary-dark); }
        .btn-green { background:var(--green-soft); color:var(--green); } .btn-green:hover { background:#c9ecd9; color:var(--green); }
        .btn-red   { background:var(--red-soft); color:var(--red); }     .btn-red:hover   { background:#f9cfca; color:var(--red); }
        .btn-ghost { background:#fff; color:var(--muted); border:2px solid var(--line); padding:9px 18px; }
        .btn-ghost:hover { background:#fff7f0; color:var(--ink); }
        .btn-block { width:100%; padding:14px; font-size:16px; }

        /* forms */
        label { display:block; margin-bottom:18px; font-weight:700; font-size:15px; }
        input, textarea, select {
            width:100%; margin-top:6px; padding:12px 14px; font-family:inherit; font-size:16px; color:var(--ink);
            background:#fffdfb; border:2px solid var(--line); border-radius:14px; outline:none;
        }
        input:focus, textarea:focus, select:focus { border-color:var(--primary); box-shadow:0 0 0 4px rgba(232,104,90,.15); }
        input[type="checkbox"] { width:20px; height:20px; margin:0 10px 0 0; vertical-align:middle; accent-color:var(--primary); }
        .grid2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
        .hint { color:var(--muted); font-size:14px; margin:0 0 16px; }

        /* messages */
        .alert { padding:12px 16px; border-radius:14px; margin-bottom:16px; font-weight:700; }
        .alert-ok  { background:var(--green-soft); color:#1d6b47; }  .alert-ok::before  { content:"✅ "; }
        .alert-err { background:var(--red-soft);  color:#a3342a; }  .alert-err::before { content:"⚠️ "; }

        .empty { background:#fff; border:2px dashed var(--line); border-radius:18px; padding:18px; color:var(--muted);
                 text-align:center; margin-bottom:12px; }
        .login-wrap { max-width:430px; margin:50px auto 0; }
        .center { text-align:center; }
        .big-emoji { font-size:3rem; margin-bottom:6px; }

        @media (max-width:600px) { .grid2 { grid-template-columns:1fr; } .topbar { padding:12px 16px; } }
    </style>
</head>
<body>
    @auth
    <header class="topbar">
        <p class="brand">💰 Budget Tracker</p>
        <nav class="navlinks">
            <a href="{{ route('dashboard') }}">🏠 Home</a>
            <a href="{{ route('entries.index') }}">📒 My Money</a>
            <a href="{{ route('requests.index') }}">📝 Requests</a>
            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin.users.index') }}">👥 Users</a>
            @endif
        </nav>
        <div class="userbox">
            <span>Hi, <b>{{ auth()->user()->name }}</b></span>
            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button type="submit" class="btn btn-ghost">Log out</button>
            </form>
        </div>
    </header>
    @endauth

    <main>
        @if (session('success') || session('notice'))
            <div class="alert alert-ok">{{ session('success') ?? session('notice') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-err">{{ $errors->first() }}</div>
        @endif
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>