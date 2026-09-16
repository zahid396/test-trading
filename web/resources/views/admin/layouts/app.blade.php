<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - {{ $storeSettings['store_name'] ?? 'Store' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --primary-light: #eef2ff;
            --success: #10b981;
            --success-light: #ecfdf5;
            --warning: #f59e0b;
            --warning-light: #fffbeb;
            --danger: #ef4444;
            --danger-light: #fef2f2;
            --info: #3b82f6;
            --info-light: #eff6ff;
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
            --sidebar-active: #4f46e5;
            --sidebar-text: #94a3b8;
            --sidebar-text-active: #ffffff;
            --sidebar-width: 260px;
            --topbar-height: 64px;
            --bg: #f1f5f9;
            --card-bg: #ffffff;
            --text: #1e293b;
            --text-secondary: #64748b;
            --border: #e2e8f0;
            --radius: 0.5rem;
            --radius-lg: 0.75rem;
            --shadow: 0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.08), 0 2px 4px -2px rgba(0,0,0,0.05);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.08), 0 4px 6px -4px rgba(0,0,0,0.04);
            --transition: all 0.2s ease;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
            min-height: 100vh;
        }
        a { color: var(--primary); text-decoration: none; }
        a:hover { color: var(--primary-hover); }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            z-index: 1000;
            transition: transform 0.3s ease;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }
        .sidebar-brand {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .sidebar-brand-icon {
            width: 36px;
            height: 36px;
            background: var(--primary);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.125rem;
        }
        .sidebar-brand-text {
            font-size: 1.125rem;
            font-weight: 700;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sidebar-nav {
            padding: 0.75rem 0;
            flex: 1;
        }
        .sidebar-section {
            padding: 0.5rem 1.5rem 0.25rem;
            font-size: 0.6875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: rgba(148,163,184,0.6);
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 1.5rem;
            color: var(--sidebar-text);
            font-size: 0.875rem;
            font-weight: 500;
            transition: var(--transition);
            border-left: 3px solid transparent;
        }
        .sidebar-link:hover {
            background: var(--sidebar-hover);
            color: var(--sidebar-text-active);
        }
        .sidebar-link.active {
            background: rgba(79,70,229,0.12);
            color: var(--sidebar-text-active);
            border-left-color: var(--primary);
        }
        .sidebar-link svg, .sidebar-link i {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            opacity: 0.7;
        }
        .sidebar-link.active svg, .sidebar-link.active i { opacity: 1; }
        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        /* Topbar */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: var(--card-bg);
            border-bottom: 1px solid var(--border);
            z-index: 900;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            transition: left 0.3s ease;
        }
        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .topbar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text);
            padding: 0.25rem;
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .topbar-user {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }
        .topbar-user-avatar {
            width: 32px;
            height: 32px;
            background: var(--primary);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.8125rem;
        }
        .topbar-logout {
            background: none;
            border: 1px solid var(--border);
            padding: 0.375rem 0.875rem;
            border-radius: var(--radius);
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--text-secondary);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }
        .topbar-logout:hover {
            background: var(--danger-light);
            color: var(--danger);
            border-color: var(--danger);
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-height);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
        .content-wrapper {
            padding: 1.5rem;
            max-width: 1400px;
        }
        .page-header {
            margin-bottom: 1.5rem;
        }
        .page-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text);
        }
        .page-header p {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        /* Cards */
        .card {
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }
        .card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-header h3 {
            font-size: 0.9375rem;
            font-weight: 600;
        }
        .card-body { padding: 1.25rem; }
        .card-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        /* Stat Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .stat-card {
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }
        a.stat-card {
            text-decoration: none;
            color: inherit;
            transition: var(--transition);
        }
        a.stat-card:hover {
            border-color: var(--primary);
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.12);
            transform: translateY(-2px);
        }
        .dash-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        .stat-icon.primary { background: var(--primary-light); color: var(--primary); }
        .stat-icon.success { background: var(--success-light); color: var(--success); }
        .stat-icon.warning { background: var(--warning-light); color: var(--warning); }
        .stat-icon.danger { background: var(--danger-light); color: var(--danger); }
        .stat-icon.info { background: var(--info-light); color: var(--info); }
        .stat-content h4 {
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1.2;
        }
        .stat-content p {
            font-size: 0.8125rem;
            color: var(--text-secondary);
            margin-top: 0.125rem;
        }

        /* Tables */
        .table-wrapper {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th {
            text-align: left;
            padding: 0.75rem 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-secondary);
            background: #f8fafc;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        table td {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }
        table tbody tr:hover { background: #f8fafc; }
        table tbody tr:last-child td { border-bottom: none; }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.375rem;
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            font-size: 0.875rem;
            font-weight: 500;
            font-family: inherit;
            cursor: pointer;
            transition: var(--transition);
            border: 1px solid transparent;
            text-decoration: none;
            white-space: nowrap;
        }
        .btn-primary {
            background: var(--primary);
            color: #fff;
        }
        .btn-primary:hover {
            background: var(--primary-hover);
            color: #fff;
        }
        .btn-success {
            background: var(--success);
            color: #fff;
        }
        .btn-success:hover {
            background: #059669;
            color: #fff;
        }
        .btn-warning {
            background: var(--warning);
            color: #fff;
        }
        .btn-warning:hover {
            background: #d97706;
            color: #fff;
        }
        .btn-danger {
            background: var(--danger);
            color: #fff;
        }
        .btn-danger:hover {
            background: #dc2626;
            color: #fff;
        }
        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-secondary);
        }
        .btn-outline:hover {
            background: #f8fafc;
            color: var(--text);
        }
        .btn-sm {
            padding: 0.25rem 0.625rem;
            font-size: 0.8125rem;
        }
        .btn-xs {
            padding: 0.125rem 0.5rem;
            font-size: 0.75rem;
        }
        .btn-icon {
            padding: 0.375rem;
            border-radius: var(--radius);
        }
        .btn-group {
            display: flex;
            gap: 0.375rem;
            flex-wrap: wrap;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.1875rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            white-space: nowrap;
        }
        .badge-success { background: var(--success-light); color: #065f46; }
        .badge-warning { background: var(--warning-light); color: #92400e; }
        .badge-danger { background: var(--danger-light); color: #991b1b; }
        .badge-info { background: var(--info-light); color: #1e40af; }
        .badge-primary { background: var(--primary-light); color: #3730a3; }
        .badge-secondary { background: #f1f5f9; color: var(--text-secondary); }

        /* Forms */
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text);
            margin-bottom: 0.375rem;
        }
        .form-group label .required {
            color: var(--danger);
            margin-left: 0.125rem;
        }
        .form-control {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 0.875rem;
            font-family: inherit;
            color: var(--text);
            background: var(--card-bg);
            transition: var(--transition);
            line-height: 1.5;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
        }
        .form-control::placeholder { color: #9ca3af; }
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            padding-right: 2.25rem;
        }
        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .form-check input[type="checkbox"],
        .form-check input[type="radio"] {
            width: 1rem;
            height: 1rem;
            accent-color: var(--primary);
        }
        .form-check label {
            font-size: 0.875rem;
            margin-bottom: 0;
        }
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        .form-text {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-top: 0.25rem;
        }
        .form-error {
            font-size: 0.75rem;
            color: var(--danger);
            margin-top: 0.25rem;
        }
        .form-control.is-invalid {
            border-color: var(--danger);
        }
        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239,68,68,0.15);
        }
        .form-section {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border);
        }
        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .form-section-title {
            font-size: 0.9375rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        /* Toggle Switch */
        .toggle {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 22px;
        }
        .toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .toggle-slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background: #cbd5e1;
            border-radius: 9999px;
            transition: var(--transition);
        }
        .toggle-slider::before {
            content: '';
            position: absolute;
            height: 16px;
            width: 16px;
            left: 3px;
            bottom: 3px;
            background: #fff;
            border-radius: 50%;
            transition: var(--transition);
        }
        .toggle input:checked + .toggle-slider {
            background: var(--primary);
        }
        .toggle input:checked + .toggle-slider::before {
            transform: translateX(18px);
        }

        /* Flash Messages */
        .flash-message {
            padding: 0.75rem 1rem;
            border-radius: var(--radius);
            margin-bottom: 1rem;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: slideDown 0.3s ease;
        }
        .flash-success {
            background: var(--success-light);
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .flash-error {
            background: var(--danger-light);
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .flash-close {
            margin-left: auto;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.125rem;
            line-height: 1;
            opacity: 0.6;
        }
        .flash-close:hover { opacity: 1; }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .modal-overlay.active { display: flex; }
        .modal {
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalIn 0.2s ease;
        }
        .modal-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .modal-header h3 {
            font-size: 1rem;
            font-weight: 600;
        }
        .modal-close {
            background: none;
            border: none;
            font-size: 1.25rem;
            cursor: pointer;
            color: var(--text-secondary);
            padding: 0.25rem;
        }
        .modal-close:hover { color: var(--text); }
        .modal-body { padding: 1.25rem; }
        .modal-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        /* Image Thumbnail */
        .thumb {
            width: 48px;
            height: 48px;
            border-radius: var(--radius);
            object-fit: cover;
            border: 1px solid var(--border);
            background: #f8fafc;
        }
        .thumb-sm {
            width: 36px;
            height: 36px;
        }
        .thumb-lg {
            width: 80px;
            height: 80px;
        }
        .thumb-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            color: #94a3b8;
            font-size: 1.25rem;
        }

        /* Image Upload */
        .upload-area {
            border: 2px dashed var(--border);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
        }
        .upload-area:hover {
            border-color: var(--primary);
            background: var(--primary-light);
        }
        .upload-area input[type="file"] {
            display: none;
        }
        .upload-area p {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }
        .upload-area .upload-icon {
            font-size: 2rem;
            color: #94a3b8;
            margin-bottom: 0.5rem;
        }
        .upload-preview {
            margin-top: 0.75rem;
        }
        .upload-preview img {
            max-width: 200px;
            max-height: 150px;
            border-radius: var(--radius);
            object-fit: cover;
            border: 1px solid var(--border);
        }

        /* Status Timeline */
        .timeline {
            display: flex;
            align-items: center;
            gap: 0;
            padding: 1rem 0;
        }
        .timeline-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
        }
        .timeline-step::after {
            content: '';
            position: absolute;
            top: 14px;
            left: 50%;
            width: 100%;
            height: 2px;
            background: var(--border);
        }
        .timeline-step:last-child::after { display: none; }
        .timeline-dot {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            color: #fff;
            position: relative;
            z-index: 1;
        }
        .timeline-step.active .timeline-dot { background: var(--primary); }
        .timeline-step.completed .timeline-dot { background: var(--success); }
        .timeline-step.rejected .timeline-dot { background: var(--danger); }
        .timeline-label {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-top: 0.5rem;
            text-align: center;
        }
        .timeline-step.active .timeline-label { color: var(--primary); font-weight: 600; }
        .timeline-step.completed .timeline-label { color: var(--success); font-weight: 600; }
        .timeline-step.rejected .timeline-label { color: var(--danger); font-weight: 600; }

        /* Stars */
        .stars { display: flex; gap: 0.125rem; }
        .stars .star { color: #d1d5db; }
        .stars .star.filled { color: #f59e0b; }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 0.75rem;
        }
        .quick-action {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1rem;
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            text-decoration: none;
            color: var(--text);
            font-size: 0.875rem;
            font-weight: 500;
            transition: var(--transition);
        }
        .quick-action:hover {
            border-color: var(--primary);
            color: var(--primary);
            box-shadow: var(--shadow-md);
        }
        .quick-action-icon {
            width: 36px;
            height: 36px;
            border-radius: var(--radius);
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Pagination */
        .pagination {
            display: flex;
            gap: 0.25rem;
            align-items: center;
            margin-top: 1rem;
        }
        .pagination a, .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            padding: 0 0.5rem;
            border-radius: var(--radius);
            font-size: 0.8125rem;
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
        }
        .pagination a {
            background: var(--card-bg);
            color: var(--text-secondary);
            border: 1px solid var(--border);
        }
        .pagination a:hover {
            background: #f8fafc;
            color: var(--text);
        }
        .pagination span.current {
            background: var(--primary);
            color: #fff;
        }
        .pagination .disabled {
            opacity: 0.4;
            pointer-events: none;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--text-secondary);
        }
        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.4;
        }
        .empty-state h3 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.25rem;
        }
        .empty-state p {
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }

        /* Filter Bar */
        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            align-items: center;
            margin-bottom: 1rem;
        }
        .filter-bar .form-control {
            width: auto;
            min-width: 160px;
        }
        .filter-tabs {
            display: flex;
            gap: 0.25rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
            padding: 0.25rem;
            background: #f1f5f9;
            border-radius: var(--radius);
        }
        .filter-tab {
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            font-size: 0.8125rem;
            font-weight: 500;
            text-decoration: none;
            color: var(--text-secondary);
            transition: var(--transition);
            border: none;
            background: none;
            cursor: pointer;
            font-family: inherit;
        }
        .filter-tab.active {
            background: var(--card-bg);
            color: var(--text);
            box-shadow: var(--shadow);
        }
        .filter-tab:hover:not(.active) {
            color: var(--text);
        }
        .filter-tab .count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            height: 20px;
            padding: 0 0.375rem;
            border-radius: 9999px;
            background: rgba(0,0,0,0.06);
            font-size: 0.6875rem;
            margin-left: 0.375rem;
        }

        /* Actions Column */
        .actions-cell {
            white-space: nowrap;
        }

        /* Note Card */
        .note-card {
            padding: 0.75rem 1rem;
            background: #f8fafc;
            border-radius: var(--radius);
            margin-bottom: 0.5rem;
            border: 1px solid var(--border);
        }
        .note-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.25rem;
        }
        .note-card-header .author {
            font-weight: 600;
            font-size: 0.8125rem;
        }
        .note-card-header .date {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }
        .note-card p {
            font-size: 0.8125rem;
            color: var(--text-secondary);
            margin: 0;
        }

        /* Overlay for mobile sidebar */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        .sidebar-overlay.active { display: block; }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .topbar {
                left: 0;
            }
            .main-content {
                margin-left: 0;
            }
            .topbar-toggle {
                display: block;
            }
        }
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            }
            .dash-grid {
                grid-template-columns: 1fr;
            }
            .form-row {
                grid-template-columns: 1fr;
            }
            .content-wrapper {
                padding: 1rem;
            }
            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }
            .filter-bar .form-control {
                width: 100%;
            }
            .card-body {
                overflow-x: auto;
            }
        }
        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
            }
            .stat-card {
                padding: 0.9rem;
                gap: 0.6rem;
            }
            .stat-icon {
                width: 38px;
                height: 38px;
                font-size: 1rem;
            }
            .stat-content h4 {
                font-size: 1.25rem;
            }
            .topbar-user span {
                display: none;
            }
            .topbar .topbar-page-title {
                font-size: 0.85rem;
            }
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon">&#9733;</div>
            <span class="sidebar-brand-text">{{ $storeSettings['store_name'] ?? 'Store' }}</span>
        </div>
        <nav class="sidebar-nav">
            <div class="sidebar-section">Main</div>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                &#9632; Dashboard
            </a>
            <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                &#9733; Products
            </a>
            <a href="{{ route('admin.jobs.index') }}" class="sidebar-link {{ request()->routeIs('admin.jobs.*') ? 'active' : '' }}">
                &#9679; Jobs
            </a>
            <a href="{{ route('admin.banners.index') }}" class="sidebar-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                &#9654; Banners
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="sidebar-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                &#9733; Reviews
            </a>
            <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                &#9993; Orders
            </a>

            <div class="sidebar-section">Configuration</div>
            <a href="{{ route('admin.payment-settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.payment-settings.*') ? 'active' : '' }}">
                &#9881; Payment Settings
            </a>
            <a href="{{ route('admin.social-links.index') }}" class="sidebar-link {{ request()->routeIs('admin.social-links.*') ? 'active' : '' }}">
                &#9742; Social Links
            </a>
            <a href="{{ route('admin.store-settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.store-settings.*') ? 'active' : '' }}">
                &#9881; Store Settings
            </a>
            <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                &#9776; Categories
            </a>
            <a href="{{ route('admin.legal-pages.index') }}" class="sidebar-link {{ request()->routeIs('admin.legal-pages.*') ? 'active' : '' }}">
                &#9998; Legal Pages
            </a>

            <div class="sidebar-section">Quick Link</div>
            <a href="{{ route('home') }}" target="_blank" class="sidebar-link">
                &#8599; View Website
            </a>
        </nav>
    </aside>

    <div class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <button class="topbar-toggle" onclick="toggleSidebar()">&#9776;</button>
                <span style="font-weight:600; font-size:0.9375rem;">{{ $storeSettings['store_name'] ?? 'Store' }} Admin</span>
            </div>
            <div class="topbar-right">
                <div class="topbar-user">
                    <div class="topbar-user-avatar">{{ substr($authAdmin->name ?? 'A', 0, 1) }}</div>
                    <span>{{ $authAdmin->name ?? 'Admin' }}</span>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="topbar-logout">Logout &#8594;</button>
                </form>
            </div>
        </header>

        <div class="content-wrapper">
            @if(session('success'))
                <div class="flash-message flash-success" id="flashSuccess">
                    &#10003; {{ session('success') }}
                    <button class="flash-close" onclick="this.parentElement.remove()">&times;</button>
                </div>
            @endif
            @if(session('error'))
                <div class="flash-message flash-error" id="flashError">
                    &#10007; {{ session('error') }}
                    <button class="flash-close" onclick="this.parentElement.remove()">&times;</button>
                </div>
            @endif
            @if($errors->any())
                <div class="flash-message flash-error">
                    &#10007; Please fix the following errors:
                    <button class="flash-close" onclick="this.parentElement.remove()">&times;</button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('active');
        }

        function toggleActive(el) {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const url = el.dataset.url;
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    el.checked = data.is_active;
                }
            });
        }

        function deleteConfirm(form) {
            if (confirm('Are you sure you want to delete this? This action cannot be undone.')) {
                form.submit();
            }
        }

        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }
        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-overlay.active').forEach(m => m.classList.remove('active'));
            }
        });

        setTimeout(function() {
            var flash = document.getElementById('flashSuccess');
            if (flash) flash.remove();
            flash = document.getElementById('flashError');
            if (flash) flash.remove();
        }, 5000);
    </script>
    @stack('scripts')
</body>
</html>
