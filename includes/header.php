<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sri Lanka Assets Complaint System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root { --primary: #0d6efd; --sidebar-width: 260px; --header-height: 60px; font-family: 'Inter', sans-serif; }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { background: #f0f2f5; font-family: var(--font); }
        .sidebar { position:fixed; top:0; left:0; width:var(--sidebar-width); height:100vh; background:#fff; border-right:1px solid rgba(0,0,0,0.05); z-index:1050; overflow-y:auto; padding-bottom:20px; transition:transform 0.3s; }
        .sidebar-brand { padding:18px 20px; border-bottom:1px solid #f0f0f0; display:flex; align-items:center; gap:12px; }
        .sidebar-brand i { font-size:28px; color:var(--primary); }
        .sidebar-brand span { font-weight:700; font-size:18px; }
        .sidebar-brand small { display:block; font-size:11px; color:#6c757d; }
        .sidebar-menu { padding:12px 16px; }
        .sidebar-menu .menu-label { font-size:11px; font-weight:600; text-transform:uppercase; color:#6c757d; padding:12px 12px 6px; }
        .sidebar-menu a { display:flex; align-items:center; gap:14px; padding:10px 14px; margin:2px 0; border-radius:10px; color:#4a5568; text-decoration:none; font-weight:500; font-size:14px; transition:0.2s; }
        .sidebar-menu a i { width:20px; color:#a0aec0; }
        .sidebar-menu a:hover { background:#eef2ff; color:var(--primary); }
        .sidebar-menu a:hover i { color:var(--primary); }
        .sidebar-menu a.active { background:var(--primary); color:#fff; box-shadow:0 4px 12px rgba(13,110,253,0.25); }
        .sidebar-menu a.active i { color:#fff; }
        .main-content { margin-left:var(--sidebar-width); min-height:100vh; }
        .top-nav { height:var(--header-height); background:#fff; border-bottom:1px solid rgba(0,0,0,0.04); padding:0 30px; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:1040; }
        .top-nav .toggle-sidebar { display:none; background:none; border:none; font-size:22px; }
        .page-content { padding:24px 30px 40px; }
        .stat-card { background:#fff; border-radius:16px; padding:20px 24px; border:1px solid rgba(0,0,0,0.04); height:100%; transition:0.2s; }
        .stat-card:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(0,0,0,0.06); }
        .stat-card .stat-number { font-size:28px; font-weight:700; }
        .stat-card .stat-label { font-size:14px; color:#6c757d; }
        .stat-card .stat-icon { width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; }
        .card-custom { background:#fff; border-radius:16px; border:1px solid rgba(0,0,0,0.04); overflow:hidden; }
        .card-custom .card-header { background:transparent; border-bottom:1px solid #f0f0f0; padding:18px 24px; font-weight:600; }
        .card-custom .card-body { padding:24px; }
        .badge-status { padding:5px 14px; border-radius:30px; font-weight:500; font-size:12px; }
        .badge-pending { background:#fef3c7; color:#92400e; }
        .badge-review { background:#dbeafe; color:#1e40af; }
        .badge-assigned { background:#e0e7ff; color:#3730a3; }
        .badge-progress { background:#fce4ec; color:#9a1f3c; }
        .badge-resolved { background:#d1fae5; color:#065f46; }
        .badge-rejected { background:#fee2e2; color:#991b1b; }
        .auth-wrapper { min-height:100vh; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg,#eef2ff,#f0f2f5); padding:20px; }
        .auth-card { background:#fff; border-radius:24px; padding:48px 40px; max-width:440px; width:100%; box-shadow:0 20px 60px rgba(0,0,0,0.08); }
        .form-control, .form-select { border-radius:10px; border:1px solid #e2e8f0; padding:10px 14px; font-size:14px; }
        .form-control:focus, .form-select:focus { border-color:var(--primary); box-shadow:0 0 0 4px rgba(13,110,253,0.12); }
        .btn { border-radius:10px; font-weight:600; padding:10px 22px; }
        .btn-primary { background:var(--primary); border-color:var(--primary); }
        .btn-primary:hover { background:#0a58ca; border-color:#0a58ca; transform:translateY(-1px); box-shadow:0 4px 12px rgba(13,110,253,0.3); }
        .image-preview { max-width:100%; max-height:200px; border-radius:10px; border:1px solid #e2e8f0; }
        .complaint-map { height:280px; border-radius:12px; overflow:hidden; border:1px solid #e2e8f0; }
        @media (max-width:992px) { .sidebar { transform:translateX(-100%); } .sidebar.show { transform:translateX(0); } .main-content { margin-left:0; } .top-nav .toggle-sidebar { display:block; } .page-content { padding:16px; } }
        @media (max-width:576px) { .auth-card { padding:30px 20px; } .stat-card .stat-number { font-size:22px; } }
    </style>
</head>
<body>