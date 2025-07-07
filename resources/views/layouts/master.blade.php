<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Kerupuk 818 | @yield('title')</title>
    <link rel="icon" href="{{ asset('images/logo.jpg') }}" type="image/jpg">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE-2/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE-2/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE-2/dist/css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE-2/dist/css/skins/_all-skins.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE-2/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    
    <!-- Clean Modern Green Theme -->
    <style>
        
        /* Modern Green Color Variables */
        :root {
            --main-green: #10B981;          /* Emerald 500 - hijau utama yang fresh */
            --dark-green: #059669;          /* Emerald 600 - hijau gelap */
            --light-green: #D1FAE5;        /* Emerald 100 - hijau sangat terang */
            --medium-green: #34D399;       /* Emerald 400 - hijau medium */
            --text-green: #065F46;         /* Emerald 800 - hijau untuk text */
        }

        /* Reset dan Base Styling */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
            background-color: #F9FAFB;
        }

        /* Header Styling - Clean & Modern */
        .main-header .navbar {
            background: var(--text-green) !important;
            border: none !important;
            height: 60px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .main-header .navbar .nav > li > a {
            color: white !important;
            height: 60px;
            line-height: 60px;
            padding: 0 20px !important;
            font-size: 14px;
            font-weight: 500;
        }

        .main-header .navbar .nav > li > a:hover {
            background: var(--dark-green) !important;
        }

        .main-header .logo {
            background: var(--text-green) !important;
            color: white !important;
            height: 60px;
            line-height: 60px;
            font-size: 18px;
            font-weight: 600;
            text-align: center;
        }

        .main-header .logo:hover {
            background: var(--text-green) !important;
        }

        /* Sidebar - Minimalist Approach */
        .main-sidebar {
            background: white !important;
            border-right: 1px solid #E5E7EB;
            box-shadow: 1px 0 3px rgba(0, 0, 0, 0.05);
        }

        .sidebar-menu > li {
            border-bottom: 1px solid #F3F4F6;
        }

        .sidebar-menu > li > a {
            color: #6B7280 !important;
            padding: 15px 20px !important;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-menu > li > a:hover {
            background: var(--light-green) !important;
            color: var(--text-green) !important;
            border-left: 3px solid var(--text-green);
        }

        .sidebar-menu > li.active > a {
            background: var(--light-green) !important;
            color: var(--text-green) !important;
            border-left: 3px solid var(--text-green);
        }

        /* Menu aktif dengan background hijau soft */
        .sidebar-menu > li.active {
            background: var(--light-green) !important;
        }

        /* Submenu aktif */
        .sidebar-menu .treeview.active > a {
            background: var(--light-green) !important;
            color: var(--text-green) !important;
            border-left: 3px solid var(--text-green);
        }

        /* Menu item yang expand/collapse */
        .sidebar-menu .treeview.menu-open > a {
            background: var(--light-green) !important;
            color: var(--text-green) !important;
        }

        .sidebar-menu > li > a > .fa {
            color: var(--text-green) !important;
            width: 20px;
            margin-right: 10px;
        }

        /* Sidebar Section Headers */
        .sidebar-menu .header {
            background: white !important;
            color: var(--text-green) !important;
            padding: 10px 20px !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            margin: 0 !important;
            border-bottom: 1px solid #F3F4F6 !important;
        }

        /* Sidebar Treeview - Lebih terang */
        .sidebar-menu .treeview-menu {
            background: #F9FAFB !important;
        }

        .sidebar-menu .treeview-menu > li > a {
            background: transparent !important;
            color: #6B7280 !important;
            padding-left: 40px !important;
        }

        .sidebar-menu .treeview-menu > li > a:hover {
            background: var(--light-green) !important;
            color: var(--text-green) !important;
        }

        .sidebar-menu .treeview-menu > li.active > a {
            background: rgba(16, 185, 129, 0.15) !important;
            color: var(--text-green) !important;
            border-left: none !important;
            border-right: 3px solid var(--text-green) !important;
        }

        /* Content Wrapper - Clean Layout */
        .content-wrapper {
            background: #F9FAFB !important;
            margin-left: 230px;
            padding-top: 0;
        }

        .content-header {
            background: white;
            padding: 25px 30px;
            margin: 0;
            border-bottom: 1px solid #E5E7EB;
        }

        .content-header h1 {
            color: var(--text-green);
            font-size: 24px;
            font-weight: 600;
            margin: 0 0 10px 0;
        }

        /* Breadcrumb - Simple & Clean */
        .breadcrumb {
            background: transparent !important;
            padding: 0 !important;
            margin: 0 !important;
            font-size: 13px;
        }

        .breadcrumb > li {
            color: #9CA3AF;
        }

        .breadcrumb > li > a {
            color: var(--text-green);
            text-decoration: none;
        }

        .breadcrumb > li > a:hover {
            color: var(--dark-green);
        }

        .breadcrumb > li + li:before {
            content: "/";
            color: #D1D5DB;
            padding: 0 8px;
        }

        /* Main Content */
        .content {
            padding: 30px;
        }

        /* Cards/Boxes - Modern Card Design */
        .box {
            background: white;
            border: 1px solid #E5E7EB !important;
            border-radius: 8px !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
            margin-bottom: 25px;
            overflow: hidden;
        }

        .box-header {
            background: #F9FAFB !important;
            border-bottom: 1px solid #E5E7EB !important;
            padding: 20px 25px !important;
        }

        .box-header .box-title {
            color: var(--text-green) !important;
            font-size: 16px !important;
            font-weight: 600 !important;
            margin: 0 !important;
        }

        .box-body {
            padding: 25px !important;
        }

        /* Buttons - Modern Button Style */
        .btn {
            border-radius: 6px !important;
            font-weight: 500 !important;
            padding: 8px 16px !important;
            font-size: 14px !important;
            transition: all 0.2s ease !important;
        }

        .btn-success {
            background: var(--text-green) !important;
            border: 1px solid var(--text-green) !important;
            color: white !important;
        }

        .btn-success:hover {
            background: var(--dark-green) !important;
            border-color: var(--dark-green) !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-primary {
            background: var(--text-green) !important;
            border-color: var(--text-green) !important;
        }

        .label-success {
            background: var(--text-green) !important;
            border-color: var(--text-green) !important;
        }

        .btn-primary:hover {
            background: var(--dark-green) !important;
            border-color: var(--dark-green) !important;
        }

        /* Form Elements */
        .form-control {
            border: 1px solid #D1D5DB !important;
            border-radius: 6px !important;
            padding: 10px 12px !important;
            font-size: 14px !important;
            transition: all 0.2s ease !important;
        }

        .form-control:focus {
            border-color: var(--text-green) !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1) !important;
            outline: none !important;
        }

        /* Tables */
        .table {
            font-size: 14px !important;
        }

        .table > thead > tr > th {
            background: var(--text-green) !important;
            color: white !important;
            border: none !important;
            font-weight: 600 !important;
            padding: 15px 12px !important;
        }

        .table > tbody > tr > td {
            padding: 12px !important;
            border-top: 1px solid #F3F4F6 !important;
            vertical-align: middle !important;
        }

        .table-striped > tbody > tr:nth-of-type(odd) {
            background: #F9FAFB !important;
        }

        /* DataTables */
        .dataTables_wrapper {
            font-size: 14px !important;
        }

        /* Info Boxes */
        .info-box {
            border-radius: 8px !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
            border: 1px solid #E5E7EB !important;
            margin-bottom: 20px;
        }

        .info-box-icon {
            background: var(--text-green) !important;
            border-radius: 8px 0 0 8px !important;
        }

        .info-box-content {
            padding: 15px !important;
        }

        /* Footer */
        .main-footer {
            background: white !important;
            border-top: 1px solid #E5E7EB !important;
            color: #6B7280 !important;
            padding: 15px 30px !important;
            margin-left: 230px;
        }

        /* Alerts */
        .alert {
            border-radius: 6px !important;
            border: none !important;
            padding: 15px 20px !important;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1) !important;
            color: var(--text-green) !important;
            border-left: 4px solid var(--text-green) !important;
        }

        .alert-info {
            background: rgba(59, 130, 246, 0.1) !important;
            color: #1E40AF !important;
            border-left: 4px solid #3B82F6 !important;
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.1) !important;
            color: #92400E !important;
            border-left: 4px solid #F59E0B !important;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1) !important;
            color: #B91C1C !important;
            border-left: 4px solid #EF4444 !important;
        }

        /* Hover Effects */
        .box:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
            transition: all 0.2s ease;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .content-wrapper {
                margin-left: 0;
            }
            
            .main-footer {
                margin-left: 0;
            }
            
            .content {
                padding: 15px;
            }
            
            .content-header {
                padding: 20px 15px;
            }
        }
        /* Info Box Alignment CSS - Tambahkan ke master.blade.php */

        /* Container untuk info boxes */
        .info-boxes-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }

        /* Info box styling yang seragam */
        .info-box {
            flex: 1;
            min-width: 280px;
            height: 120px !important; /* Tinggi yang sama untuk semua */
            border-radius: 8px !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
            border: none !important;
            margin-bottom: 0 !important; /* Remove default margin */
            display: flex !important;
            align-items: center !important;
            overflow: hidden;
            position: relative;
            transition: all 0.3s ease;
        }

        /* Info box hover effect */
        .info-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15) !important;
        }

        /* Icon section */
        .info-box-icon {
            width: 80px !important;
            height: 120px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 35px !important;
            color: white !important;
            position: relative;
        }

        /* Icon background dengan pattern */
        .info-box-icon::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'%3E%3Ccircle cx='3' cy='3' r='3'/%3E%3Ccircle cx='13' cy='13' r='3'/%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.3;
        }

        /* Content section */
        .info-box-content {
            flex: 1 !important;
            padding: 10px 15px !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: center !important;
            background: white !important; /* GANTI dari transparent ke putih */
            height: 120px !important;
            color: white !important;
        }




        /* Number styling */
        .info-box-number {
            font-size: 28px !important;
            font-weight: 700 !important;
            color: #1F2937 !important; /* TEKS ANGKA WARNA GELAP */
            margin: 0 0 8px 0 !important;
        }

        .info-box-text {
            font-size: 14px !important;
            font-weight: 500 !important;
            color: #4B5563 !important; /* LABEL */
            margin: 0 0 8px 0 !important;
        }

        .info-box-more {
            font-size: 13px !important;
            color: #6B7280 !important; /* LINK "LIHAT" */
        }


        .info-box-more:hover {
            color: #4A5568 !important;
            text-decoration: none !important;
        }

        /* Specific color schemes */
        .info-box.bg-aqua .info-box-icon {
            background: linear-gradient(135deg, #00BCD4 0%, #0097A7 100%) !important;
        }

        .info-box.bg-blue .info-box-icon {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%) !important;
        }

        .info-box.bg-purple .info-box-icon {
            background: linear-gradient(135deg, #9C27B0 0%, #7B1FA2 100%) !important;
        }

        .info-box.bg-red .info-box-icon {
            background: linear-gradient(135deg, #F44336 0%, #D32F2F 100%) !important;
        }

        .info-box.bg-green .info-box-icon {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%) !important;
        }

        .info-box.bg-yellow .info-box-icon {
            background: linear-gradient(135deg, #FFC107 0%, #F57C00 100%) !important;
        }

        /* Responsive design */
        @media (max-width: 1200px) {
            .info-boxes-container {
                gap: 15px;
            }
            
            .info-box {
                min-width: 250px;
            }
        }

        @media (max-width: 768px) {
            .info-boxes-container {
                flex-direction: column;
                gap: 15px;
            }
            
            .info-box {
                min-width: 100%;
                height: 100px !important;
            }
            
            .info-box-icon {
                width: 70px !important;
                height: 100px !important;
                font-size: 28px !important;
            }
            
            .info-box-content {
                height: 100px !important;
                padding: 15px !important;
            }
            
            .info-box-number {
                font-size: 24px !important;
            }
            
            .info-box-text {
                font-size: 13px !important;
            }
        }

        @media (max-width: 480px) {
            .info-box {
                height: 90px !important;
            }
            
            .info-box-icon {
                width: 60px !important;
                height: 90px !important;
                font-size: 24px !important;
            }
            
            .info-box-content {
                height: 90px !important;
                padding: 12px !important;
            }
            
            .info-box-number {
                font-size: 20px !important;
            }
            
            .info-box-text {
                font-size: 12px !important;
            }
        }

        /* ===== FIX DROPDOWN DATATABLES YANG TERPOTONG ===== */

        /* 1. Fix untuk dropdown "Show entries" DataTables */
        .dataTables_wrapper .dataTables_length {
            position: relative !important;
            z-index: 1000 !important;
            margin-bottom: 15px !important;
        }

        .dataTables_wrapper .dataTables_length select {
            min-width: 60px !important;
            height: 34px !important;
            padding: 6px 12px !important;
            font-size: 14px !important;
            line-height: 1.42857143 !important;
            color: #555 !important;
            background-color: #fff !important;
            background-image: none !important;
            border: 1px solid #D1D5DB !important;
            border-radius: 4px !important;
            box-shadow: inset 0 1px 1px rgba(0,0,0,.075) !important;
            transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s !important;
            
            /* Pastikan dropdown bisa expand penuh */
            -webkit-appearance: menulist !important;
            -moz-appearance: menulist !important;
            appearance: menulist !important;
        }

        .dataTables_wrapper .dataTables_length select:focus {
            border-color: var(--text-green) !important;
            outline: 0 !important;
            box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(16, 185, 129, 0.3) !important;
        }

        /* 2. Fix untuk container yang membatasi dropdown */
        .dataTables_wrapper {
            overflow: visible !important;
            position: relative !important;
        }

        .dataTables_wrapper .row {
            overflow: visible !important;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            overflow: visible !important;
        }

        /* 3. Fix khusus untuk box container yang mungkin memotong */
        .box {
            overflow: visible !important; /* Ubah dari hidden ke visible */
        }

        .box-body {
            overflow: visible !important;
        }

        /* 4. Fix untuk dropdown yang keluar dari area parent */
        .dataTables_wrapper .dataTables_length select option {
            padding: 4px 8px !important;
            color: #333 !important;
            background-color: white !important;
        }

        /* 5. Alternative fix jika masih ada masalah - buat dropdown custom */
        .custom-length-wrapper {
            position: relative !important;
            display: inline-block !important;
            margin-bottom: 15px !important;
        }

        .custom-length-wrapper select {
            min-width: 70px !important;
            height: 34px !important;
            padding: 6px 25px 6px 12px !important;
            background: white url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3E%3Cpath fill='%23666' d='M2 0L0 2h4zm0 5L0 3h4z'/%3E%3C/svg%3E") no-repeat right 8px center !important;
            background-size: 8px 10px !important;
            border: 1px solid #D1D5DB !important;
            border-radius: 4px !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            cursor: pointer !important;
        }

        /* 6. Pastikan z-index yang cukup tinggi untuk dropdown menu */
        .dataTables_wrapper .dataTables_length select,
        .bootstrap-select .dropdown-menu {
            z-index: 9999 !important;
        }

        /* 7. Fix untuk mobile responsive */
        @media (max-width: 768px) {
            .dataTables_wrapper .dataTables_length {
                margin-bottom: 10px !important;
            }
            
            .dataTables_wrapper .dataTables_length select {
                width: 60px !important;
                font-size: 13px !important;
            }
        }

        /* 8. Fix untuk select2 jika digunakan */
        .select2-container {
            z-index: 9999 !important;
        }

        .select2-dropdown {
            z-index: 9999 !important;
        }

        /* 9. Pastikan tidak ada parent yang memotong dengan clip atau hidden */
        .content,
        .content-wrapper,
        .box,
        .box-body,
        .table-responsive {
            overflow: visible !important;
        }

        /* Khusus untuk table-responsive yang sering jadi masalah */
        .table-responsive {
            overflow-x: auto !important;
            overflow-y: visible !important;
        }

        /* 10. Fix untuk Bootstrap dropdown jika ada konflik */
        .dropdown-menu {
            z-index: 1000 !important;
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            display: none !important;
            float: left !important;
            min-width: 160px !important;
            padding: 5px 0 !important;
            margin: 2px 0 0 !important;
            list-style: none !important;
            font-size: 14px !important;
            text-align: left !important;
            background-color: #fff !important;
            border: 1px solid #ccc !important;
            border: 1px solid rgba(0,0,0,.15) !important;
            border-radius: 4px !important;
            box-shadow: 0 6px 12px rgba(0,0,0,.175) !important;
            background-clip: padding-box !important;
        }

        select.form-control {
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            
            /* Custom arrow */
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3E%3Cpath fill='%23666' d='M2 0L0 2h4zm0 5L0 3h4z'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 12px center !important;
            background-size: 12px !important;
            padding-right: 35px !important;
            
            /* Sizing */
            height: 40px !important;
            line-height: 1.42857143 !important;
            
            /* Cursor */
            cursor: pointer !important;
        }

        /* 2. Fix untuk container parent yang membatasi */
        .form-group {
            position: relative !important;
            overflow: visible !important;
            z-index: 1 !important;
        }

        .box-body .form-group {
            overflow: visible !important;
        }

        /* 3. Pastikan box container tidak memotong dropdown */
        .box {
            overflow: visible !important;
            position: relative !important;
        }

        .box-body {
            overflow: visible !important;
            position: relative !important;
        }

        /* 4. Fix khusus untuk dropdown options */
        select.form-control option {
            padding: 8px 12px !important;
            background-color: white !important;
            color: #333 !important;
            border: none !important;
        }

        select.form-control option:hover {
            background-color: var(--light-green) !important;
            color: var(--text-green) !important;
        }
        .modal-content{
            border-radius: 6px !important;
        }

        /* 5. Fix untuk modal jika dropdown ada di dalam modal */
        .modal-body .form-group {
            overflow: visible !important;
            z-index: 1050 !important;
        }

        .modal-body select.form-control {
            z-index: 1051 !important;
        }

        /* 6. Alternative - Custom Select dengan JavaScript enhance */
        .custom-select-wrapper {
            position: relative !important;
            display: block !important;
        }

        .custom-select-wrapper select {
            width: 100% !important;
            height: 40px !important;
            padding: 8px 35px 8px 12px !important;
            font-size: 14px !important;
            line-height: 1.42857143 !important;
            color: #555 !important;
            background-color: #fff !important;
            border: 1px solid #D1D5DB !important;
            border-radius: 4px !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            cursor: pointer !important;
            
            /* Custom dropdown arrow */
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
            background-position: right 8px center !important;
            background-repeat: no-repeat !important;
            background-size: 16px 12px !important;
        }

        .custom-select-wrapper select:focus {
            outline: none !important;
            border-color: var(--text-green) !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1) !important;
        }

        /* 7. Fix untuk content wrapper yang mungkin clip */
        .content-wrapper {
            overflow: visible !important;
        }

        .content {
            overflow: visible !important;
        }

        /* 8. Khusus untuk row dan col yang bisa membatasi */
        .row {
            overflow: visible !important;
        }

        .col-lg-12, .col-md-12, .col-sm-12, .col-xs-12,
        .col-lg-6, .col-md-6, .col-sm-6, .col-xs-6,
        .col-lg-4, .col-md-4, .col-sm-4, .col-xs-4,
        .col-lg-3, .col-md-3, .col-sm-3, .col-xs-3 {
            overflow: visible !important;
        }

        /* 9. Fix untuk select2 plugin jika digunakan */
        .select2-container {
            width: 100% !important;
            z-index: 9999 !important;
        }

        .select2-container .select2-selection--single {
            height: 40px !important;
            border: 1px solid #D1D5DB !important;
            border-radius: 4px !important;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            line-height: 38px !important;
            padding-left: 12px !important;
        }

        .select2-container .select2-selection--single .select2-selection__arrow {
            height: 38px !important;
            right: 8px !important;
        }

        .select2-dropdown {
            z-index: 9999 !important;
            border: 1px solid #D1D5DB !important;
            border-radius: 4px !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
        }

        /* 10. Mobile responsive */
        @media (max-width: 768px) {
            select.form-control {
                height: 45px !important;
                font-size: 16px !important; /* Prevent zoom on iOS */
            }
            
            .custom-select-wrapper select {
                height: 45px !important;
                font-size: 16px !important;
            }
        }

        /* 11. Fix untuk table responsive jika form ada dalam table */
        .table-responsive {
            overflow-x: auto !important;
            overflow-y: visible !important;
        }

        /* 12. Tambahan untuk memastikan z-index yang tepat */
        .form-control:focus {
            z-index: 2 !important;
        }

        /* 13. Fix untuk dropdown yang muncul di luar viewport */
        select.form-control {
            /* Pastikan dropdown muncul dalam viewport */
            position: relative !important;
        }

        /* 14. Alternative dengan Bootstrap Select jika diperlukan */
        .bootstrap-select {
            width: 100% !important;
        }

        .bootstrap-select .dropdown-toggle {
            height: 40px !important;
            border: 1px solid #D1D5DB !important;
            border-radius: 4px !important;
            text-align: left !important;
        }

        .bootstrap-select .dropdown-menu {
            z-index: 9999 !important;
            max-height: 200px !important;
            overflow-y: auto !important;
            border: 1px solid #D1D5DB !important;
            border-radius: 4px !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
        }

        .floating-notif-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 65px;
            height: 65px;
            background-color: #f59e0b; /* Amber */
            color: white;
            border: none;
            border-radius: 50%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            transition: all 0.2s ease-in-out;
        }

        .floating-notif-btn:hover {
            background-color: #d97706;
            transform: scale(1.1);
            cursor: pointer;
        }
        
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    @stack('css')
</head>
<body class="hold-transition skin-green sidebar-mini">
    <div class="wrapper">

        @includeIf('layouts.header')

        @includeIf('layouts.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    @yield('title')
                </h1>
                <ol class="breadcrumb">
                    @section('breadcrumb')
                        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                    @show
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">

                @yield('content')

            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        @includeIf('layouts.footer')
    </div>
    <!-- ./wrapper -->

    <!-- jQuery 3 -->
    <script src="{{ asset('AdminLTE-2/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('AdminLTE-2/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- Moment -->
    <script src="{{ asset('AdminLTE-2/bower_components/moment/min/moment.min.js') }}"></script>

    <!-- DataTables -->
    <script src="{{ asset('AdminLTE-2/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('AdminLTE-2/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('AdminLTE-2/dist/js/adminlte.min.js') }}"></script>
    <!-- Validator -->
    <script src="{{ asset('js/validator.min.js') }}"></script>

    @stack('scripts')
</body>
</html>

