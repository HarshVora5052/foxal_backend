<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foxal Paris — Haute Parfumerie Admin Console</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-base: #0a0b0e;
            --bg-surface: #121318;
            --bg-elevated: #181920;
            --bg-hover: #21232c;
            --border: rgba(255, 255, 255, 0.07);
            --border-gold: rgba(212, 175, 55, 0.28);
            --gold: #d4af37;
            --gold-light: #f3e5ab;
            --gold-dark: #aa820a;
            --text-main: #f5f5f7;
            --text-muted: #9e9ea7;
            --text-dim: #60626c;
            --radius-sm: 8px;
            --radius-md: 14px;
            --radius-lg: 20px;
            --shadow-card: 0 10px 30px -10px rgba(0, 0, 0, 0.7);
            --font-sans: 'Plus Jakarta Sans', sans-serif;
            --font-serif: 'Playfair Display', Georgia, serif;
            --font-mono: 'JetBrains Mono', monospace;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: var(--font-sans);
            background: var(--bg-base);
            color: var(--text-main);
            min-height: 100vh;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        /* Utility classes */
        .hidden { display: none !important; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 12px; }
        .gap-4 { gap: 16px; }

        /* ========================================================
           SCREEN 1: LUXURY ADMIN LOGIN SCREEN
           ======================================================== */
        #login-screen {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: radial-gradient(circle at 50% 20%, rgba(212, 175, 55, 0.12) 0%, transparent 60%), #090a0f;
            position: relative;
            overflow: hidden;
        }
        #login-screen::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.05) 0%, transparent 70%);
            top: -200px;
            right: -100px;
            pointer-events: none;
        }
        .login-card {
            width: 100%;
            max-width: 440px;
            background: var(--bg-surface);
            border: 1px solid var(--border-gold);
            border-radius: var(--radius-lg);
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8), 0 0 40px rgba(212, 175, 55, 0.1);
            position: relative;
            z-index: 10;
        }
        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }
        .login-brand-logo {
            width: 56px;
            height: 56px;
            margin: 0 auto 16px;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-serif);
            font-size: 28px;
            font-weight: 700;
            color: #000;
            box-shadow: 0 0 25px rgba(212, 175, 55, 0.4);
        }
        .login-brand-title {
            font-family: var(--font-serif);
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 3px;
            color: #fff;
            margin-bottom: 4px;
        }
        .login-brand-subtitle {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--gold);
        }
        .login-desc {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }
        .input-wrapper {
            position: relative;
        }
        .form-input {
            width: 100%;
            background: var(--bg-elevated);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 12px 16px;
            color: #fff;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s ease;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.15);
            background: #1c202c;
        }
        .btn-gold {
            width: 100%;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: #000;
            border: none;
            border-radius: var(--radius-sm);
            padding: 14px 20px;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-gold:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.45);
            filter: brightness(1.05);
        }
        .btn-gold:active {
            transform: translateY(0);
        }
        .alert-error {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #f5f5f7;
            padding: 12px 14px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .quick-fill-box {
            margin-top: 24px;
            padding: 14px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px dashed var(--border);
            border-radius: var(--radius-sm);
            text-align: center;
        }
        .quick-fill-btn {
            background: none;
            border: 1px solid var(--border-gold);
            color: var(--gold-light);
            font-size: 12px;
            padding: 6px 14px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
        }
        .quick-fill-btn:hover {
            background: rgba(212, 175, 55, 0.15);
            color: #fff;
        }

        /* ========================================================
           SCREEN 2: ADMIN CONSOLE LAYOUT
           ======================================================== */
        #admin-panel {
            display: flex;
            min-height: 100vh;
        }

        /* Left Luxury Sidebar */
        .sidebar {
            width: 280px;
            background: var(--bg-surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            z-index: 40;
        }
        .sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .brand-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-serif);
            font-size: 22px;
            font-weight: 700;
            color: #000;
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.3);
            flex-shrink: 0;
        }
        .brand-text h2 {
            font-family: var(--font-serif);
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 2px;
            color: #fff;
            line-height: 1.2;
        }
        .brand-text span {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--gold);
        }

        /* Sidebar Navigation Menu */
        .nav-section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text-dim);
            padding: 20px 20px 8px;
        }
        .nav-menu {
            list-style: none;
            padding: 0 12px;
            flex: 1;
            overflow-y: auto;
        }
        .nav-item {
            margin-bottom: 4px;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            color: var(--text-muted);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }
        .nav-link:hover {
            background: var(--bg-elevated);
            color: #fff;
        }
        .nav-link.active {
            background: linear-gradient(90deg, rgba(212, 175, 55, 0.15), rgba(212, 175, 55, 0.05));
            color: var(--gold-light);
            font-weight: 600;
            border-left: 3px solid var(--gold);
        }
        .nav-link svg {
            width: 18px;
            height: 18px;
            stroke-width: 2;
            flex-shrink: 0;
        }
        .nav-badge {
            margin-left: auto;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 12px;
            background: var(--bg-elevated);
            color: var(--text-muted);
            border: 1px solid var(--border);
        }
        .nav-link.active .nav-badge {
            background: rgba(212, 175, 55, 0.2);
            color: var(--gold);
            border-color: var(--border-gold);
        }

        /* Sidebar Admin Profile Footer */
        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border);
            background: rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .admin-meta {
            display: flex;
            align-items: center;
            gap: 10px;
            overflow: hidden;
        }
        .admin-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #374151, #1f2937);
            border: 1.5px solid var(--gold);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: var(--gold);
            flex-shrink: 0;
        }
        .admin-info {
            overflow: hidden;
        }
        .admin-name {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .admin-role {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--gold);
        }
        .btn-logout {
            background: var(--bg-elevated);
            border: 1px solid var(--border);
            color: var(--text-muted);
            padding: 8px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-logout:hover {
            background: var(--bg-hover);
            color: var(--gold-light);
            border-color: var(--border-gold);
        }

        /* Main Workspace Container */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* Top Header */
        .topbar {
            height: 64px;
            padding: 0 32px;
            background: rgba(17, 19, 26, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 30;
        }
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-muted);
        }
        .breadcrumb-active {
            color: #fff;
            font-weight: 600;
        }
        .api-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(212, 175, 55, 0.06);
            border: 1px solid var(--border-gold);
            color: var(--gold-light);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-family: var(--font-mono);
        }
        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--gold);
            box-shadow: 0 0 8px rgba(212, 175, 55, 0.5);
        }

        /* Workspace Content Body */
        .content-body {
            padding: 32px;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
        }

        /* Section Title & Actions */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 16px;
        }
        .page-title {
            font-family: var(--font-serif);
            font-size: 26px;
            font-weight: 700;
            color: #fff;
        }
        .page-desc {
            font-size: 13.5px;
            color: var(--text-muted);
            margin-top: 4px;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: #000;
            border: none;
            border-radius: var(--radius-sm);
            padding: 10px 18px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(212, 175, 55, 0.25);
        }
        .btn-primary:hover {
            filter: brightness(1.08);
            transform: translateY(-1px);
        }
        .btn-secondary {
            background: var(--bg-elevated);
            color: var(--text-main);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 9px 16px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-secondary:hover {
            background: var(--bg-hover);
            border-color: rgba(255, 255, 255, 0.15);
        }

        /* Metrics Grid */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }
        .metric-card {
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-card);
            transition: all 0.2s;
        }
        .metric-card:hover {
            border-color: var(--border-gold);
            transform: translateY(-2px);
        }
        .metric-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            margin-bottom: 6px;
        }
        .metric-val {
            font-family: var(--font-serif);
            font-size: 28px;
            font-weight: 700;
            color: #fff;
        }
        .metric-icon-wrap {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(212, 175, 55, 0.1);
            color: var(--gold);
            border: 1px solid var(--border-gold);
        }

        /* Tables & Cards */
        .card-panel {
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-card);
            overflow: hidden;
            margin-bottom: 32px;
        }
        .card-panel-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        .card-panel-title {
            font-size: 16px;
            font-weight: 600;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .search-input-wrap {
            position: relative;
            width: 260px;
        }
        .search-input {
            width: 100%;
            background: var(--bg-elevated);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 8px 16px 8px 36px;
            color: #fff;
            font-size: 13px;
        }
        .search-input:focus {
            outline: none;
            border-color: var(--gold);
        }
        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 15px;
            height: 15px;
            color: var(--text-dim);
        }

        /* Custom Table */
        .custom-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .custom-table th {
            text-align: left;
            padding: 14px 20px;
            background: rgba(255, 255, 255, 0.02);
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.8px;
            border-bottom: 1px solid var(--border);
        }
        .custom-table td {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }
        .custom-table tr:hover td {
            background: rgba(255, 255, 255, 0.02);
        }
        .custom-table tr:last-child td {
            border-bottom: none;
        }

        /* Thumbnail with gallery trigger */
        .thumbnail-container {
            position: relative;
            width: 58px;
            height: 58px;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid var(--border-gold);
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
            flex-shrink: 0;
            background: #000;
        }
        .thumbnail-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .thumbnail-container:hover .thumbnail-img {
            transform: scale(1.1);
        }
        .thumbnail-badge {
            position: absolute;
            bottom: 2px;
            right: 2px;
            background: rgba(0, 0, 0, 0.75);
            color: var(--gold);
            font-size: 9px;
            font-weight: 700;
            padding: 1px 4px;
            border-radius: 4px;
            border: 1px solid var(--border-gold);
            backdrop-filter: blur(4px);
        }

        /* Luxury Unified Badges - Simple & Premium (No Multi-Color) */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11.5px;
            font-weight: 500;
            letter-spacing: 0.3px;
        }
        .badge-gold {
            background: rgba(212, 175, 55, 0.08);
            color: var(--gold-light);
            border: 1px solid rgba(212, 175, 55, 0.25);
        }
        .badge-green, .badge-stock {
            background: rgba(255, 255, 255, 0.04);
            color: #f5f5f7;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .badge-red, .badge-lowstock {
            background: rgba(212, 175, 55, 0.06);
            color: var(--gold-light);
            border: 1px solid rgba(212, 175, 55, 0.2);
        }
        .badge-slate {
            background: rgba(255, 255, 255, 0.04);
            color: var(--text-muted);
            border: 1px solid var(--border);
        }

        /* Price display */
        .price-original {
            font-size: 12px;
            text-decoration: line-through;
            color: var(--text-dim);
            margin-right: 6px;
        }
        .price-discount {
            font-size: 13.5px;
            font-weight: 600;
            color: #fff;
        }
        .discount-tag {
            font-size: 10.5px;
            color: var(--gold-light);
            font-weight: 600;
            margin-left: 6px;
            background: rgba(212, 175, 55, 0.08);
            padding: 1px 6px;
            border-radius: 4px;
            border: 1px solid rgba(212, 175, 55, 0.2);
        }

        /* Action buttons in row */
        .action-btn-group {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .btn-icon {
            background: var(--bg-elevated);
            border: 1px solid var(--border);
            color: var(--text-muted);
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-icon:hover {
            color: var(--gold-light);
            border-color: var(--border-gold);
            background: var(--bg-hover);
            transform: translateY(-1px);
        }
        .btn-icon-danger:hover {
            color: #fff;
            border-color: rgba(255, 255, 255, 0.25);
            background: var(--bg-hover);
            transform: translateY(-1px);
        }
        .btn-icon-gold:hover {
            color: var(--gold-light);
            border-color: var(--border-gold);
            background: var(--bg-hover);
            transform: translateY(-1px);
        }

        /* ========================================================
           MODALS (Gallery, Add/Edit Product, Delete)
           ======================================================== */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(8px);
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease;
        }
        .modal-overlay.open {
            opacity: 1;
            pointer-events: auto;
        }
        .modal-card {
            background: var(--bg-surface);
            border: 1px solid var(--border-gold);
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 680px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.8), 0 0 40px rgba(212, 175, 55, 0.15);
            transform: scale(0.95);
            transition: transform 0.25s ease;
        }
        .modal-overlay.open .modal-card {
            transform: scale(1);
        }
        .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .modal-title {
            font-family: var(--font-serif);
            font-size: 20px;
            font-weight: 700;
            color: #fff;
        }
        .modal-close-btn {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 4px;
            border-radius: 6px;
            transition: color 0.2s;
        }
        .modal-close-btn:hover {
            color: #fff;
        }
        .modal-body {
            padding: 24px;
        }
        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            background: rgba(0, 0, 0, 0.2);
        }

        /* Gallery Modal specifics */
        .gallery-main-view {
            width: 100%;
            height: 380px;
            border-radius: var(--radius-md);
            overflow: hidden;
            background: #000;
            border: 1px solid var(--border-gold);
            margin-bottom: 16px;
            position: relative;
        }
        .gallery-main-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: opacity 0.2s ease;
        }
        .gallery-strip {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        .gallery-thumb-item {
            height: 80px;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid transparent;
            cursor: pointer;
            background: #000;
            position: relative;
            transition: all 0.2s;
        }
        .gallery-thumb-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .gallery-thumb-item:hover {
            border-color: var(--gold-light);
        }
        .gallery-thumb-item.active {
            border-color: var(--gold);
            box-shadow: 0 0 12px rgba(212, 175, 55, 0.5);
        }
        .gallery-thumb-tag {
            position: absolute;
            bottom: 2px;
            left: 2px;
            background: rgba(0,0,0,0.8);
            font-size: 8px;
            padding: 1px 4px;
            border-radius: 3px;
            color: var(--gold);
            text-transform: uppercase;
        }

        /* Form Grid in Modal */
        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 16px;
        }
        .form-row-full {
            margin-bottom: 16px;
        }

        /* Live Code Display for Encrypted Feed */
        .feed-box {
            background: #08090d;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 16px;
            font-family: var(--font-mono);
            font-size: 12px;
            color: #93c5fd;
            max-height: 280px;
            overflow-y: auto;
            white-space: pre-wrap;
            word-break: break-all;
            margin-bottom: 16px;
        }

        /* Toast notification */
        #toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: var(--bg-surface);
            border: 1px solid var(--border-gold);
            border-radius: var(--radius-sm);
            padding: 12px 20px;
            color: #fff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.8);
            z-index: 200;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
            font-size: 13.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        #toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        /* Luxury Pagination Component */
        .pagination-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 24px;
            border-top: 1px solid var(--border);
            background: rgba(17, 19, 26, 0.6);
            flex-wrap: wrap;
            gap: 16px;
        }
        .pagination-info {
            font-size: 13px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .pagination-per-page {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12.5px;
            color: var(--text-dim);
        }
        .pagination-per-page select {
            background: var(--bg-elevated);
            border: 1px solid var(--border);
            color: var(--text-main);
            border-radius: var(--radius-sm);
            padding: 4px 8px;
            font-size: 12.5px;
            outline: none;
            cursor: pointer;
            transition: border-color 0.2s;
        }
        .pagination-per-page select:focus {
            border-color: var(--gold);
        }
        .pagination-controls {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .pagination-btn {
            background: var(--bg-elevated);
            border: 1px solid var(--border);
            color: var(--text-main);
            border-radius: var(--radius-sm);
            padding: 7px 14px;
            font-size: 12.5px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .pagination-btn:hover:not(:disabled) {
            background: var(--bg-hover);
            border-color: var(--border-gold);
            color: var(--gold-light);
        }
        .pagination-btn:disabled {
            opacity: 0.35;
            cursor: not-allowed;
            filter: grayscale(1);
        }
        .pagination-page-nums {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .pagination-page-num {
            min-width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-sm);
            background: var(--bg-elevated);
            border: 1px solid var(--border);
            color: var(--text-muted);
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .pagination-page-num:hover:not(.active) {
            color: #fff;
            border-color: var(--border-gold);
            background: var(--bg-hover);
        }
        .pagination-page-num.active {
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: #000;
            border-color: var(--gold);
            font-weight: 700;
            box-shadow: 0 0 12px rgba(212, 175, 55, 0.4);
        }

        /* Luxury Media Upload Component */
        .batch-upload-box {
            border: 2px dashed rgba(212, 175, 55, 0.45);
            background: rgba(212, 175, 55, 0.05);
            border-radius: var(--radius-sm);
            padding: 16px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            color: var(--gold);
        }
        .batch-upload-box:hover, .batch-upload-box.drag-over {
            border-color: var(--gold);
            background: rgba(212, 175, 55, 0.12);
            transform: translateY(-1px);
        }
        .upload-row {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .upload-preview-box {
            width: 60px;
            height: 60px;
            min-width: 60px;
            border-radius: var(--radius-sm);
            background: #08090d;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
        }
        .upload-preview-box:hover {
            border-color: var(--border-gold);
        }
        .preview-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            color: var(--text-dim);
            font-size: 10px;
        }
        .preview-media {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .upload-btn-badge {
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: #000;
            font-weight: 600;
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 6px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: filter 0.2s;
            white-space: nowrap;
        }
        .upload-btn-badge:hover {
            filter: brightness(1.1);
        }
    </style>
</head>
<body>

    <!-- ========================================================
         SCREEN 1: STRICT ADMIN LOGIN SCREEN
         ======================================================== -->
    <div id="login-screen">
        <div class="login-card">
            <div class="login-header">
                <div class="login-brand-logo">F</div>
                <h1 class="login-brand-title">FOXAL</h1>
                <div class="login-brand-subtitle">Haute Parfumerie Paris</div>
                <p class="login-desc">Enter administrator credentials to unlock the console.</p>
            </div>

            <div id="login-error" class="alert-error hidden">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span id="login-error-msg">Invalid email or password.</span>
            </div>

            <form id="admin-login-form">
                <div class="form-group">
                    <label class="form-label" for="login-email">Admin Email / Username</label>
                    <input type="email" id="login-email" class="form-input" placeholder="admin@foxal.com" required autocomplete="username">
                </div>

                <div class="form-group">
                    <label class="form-label" for="login-password">Master Password</label>
                    <input type="password" id="login-password" class="form-input" placeholder="••••••••••••" required autocomplete="current-password">
                </div>

                <button type="submit" id="btn-login-submit" class="btn-gold">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    <span>Sign In to Admin Panel</span>
                </button>
            </form>

            <div class="quick-fill-box">
                <div style="font-size: 11px; color: var(--text-dim);">Quick Admin Access Credentials</div>
                <button type="button" class="quick-fill-btn" onclick="fillAdminCreds()">
                    ⚡ Auto-Fill: admin@foxal.com / Admin@1234
                </button>
            </div>
        </div>
    </div>

    <!-- ========================================================
         SCREEN 2: ADMIN CONSOLE
         ======================================================== -->
    <div id="admin-panel" class="hidden">
        <!-- LEFT LUXURY SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="brand-icon">F</div>
                <div class="brand-text">
                    <h2>FOXAL PARIS</h2>
                    <span>Admin Console</span>
                </div>
            </div>

            <div class="nav-section-title">Navigation</div>
            <ul class="nav-menu">
                <!-- 1. Dashboard -->
                <li class="nav-item">
                    <a class="nav-link active" onclick="switchTab('dashboard')" id="nav-dashboard">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- 2. Customers / Users -->
                <li class="nav-item">
                    <a class="nav-link" onclick="switchTab('customers')" id="nav-customers">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>Customers</span>
                        <span class="nav-badge" id="badge-users-count">0</span>
                    </a>
                </li>

                <!-- 3. Section: Incense Cones -->
                <li class="nav-item">
                    <a class="nav-link" onclick="switchTab('incense-cones')" id="nav-incense-cones">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                        <span>Incense Cones</span>
                        <span class="nav-badge" id="badge-cones-count">0</span>
                    </a>
                </li>

                <!-- 4. Section: Incense Sticks -->
                <li class="nav-item">
                    <a class="nav-link" onclick="switchTab('incense-sticks')" id="nav-incense-sticks">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        <span>Incense Sticks</span>
                        <span class="nav-badge" id="badge-sticks-count">0</span>
                    </a>
                </li>

                <!-- 5. All Products -->
                <li class="nav-item">
                    <a class="nav-link" onclick="switchTab('all-products')" id="nav-all-products">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span>All Products</span>
                        <span class="nav-badge" id="badge-total-products">0</span>
                    </a>
                </li>

                <!-- 6. Section: Contact Messages -->
                <li class="nav-item">
                    <a class="nav-link" onclick="switchTab('contacts')" id="nav-contacts">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>Contact Messages</span>
                        <span class="nav-badge" id="badge-contacts-count">0</span>
                    </a>
                </li>


            </ul>

            <!-- Bottom Profile & Logout -->
            <div class="sidebar-footer">
                <div class="admin-meta">
                    <div class="admin-avatar" id="sidebar-avatar">A</div>
                    <div class="admin-info">
                        <div class="admin-name" id="sidebar-name">Foxal Admin</div>
                        <div class="admin-role">Administrator</div>
                    </div>
                </div>
                <button type="button" class="btn-logout" title="Lock & Sign Out" onclick="adminLogout()">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </div>
        </aside>

        <!-- MAIN WORKSPACE CONTENT -->
        <main class="main-content">
            <!-- Topbar -->
            <header class="topbar">
                <div class="breadcrumb">
                    <span>Admin</span>
                    <span>/</span>
                    <span class="breadcrumb-active" id="breadcrumb-title">Dashboard</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="api-pill">
                        <span class="status-dot"></span>
                        <span>API Online :8000</span>
                    </div>
                    <button class="btn-secondary" onclick="loadAllData()" title="Refresh Data">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Refresh</span>
                    </button>
                </div>
            </header>

            <div class="content-body">
                <!-- ================= TAB 1: DASHBOARD ================= -->
                <section id="tab-dashboard">
                    <div class="page-header">
                        <div>
                            <h2 class="page-title">Executive Dashboard</h2>
                            <p class="page-desc">Overview of boutique metrics, inventory health, and active customers.</p>
                        </div>
                        <div class="flex gap-2">
                            <button class="btn-primary" onclick="openAddProductModal('incense-cones')">+ New Incense Cone</button>
                            <button class="btn-secondary" onclick="openAddProductModal('incense-sticks')">+ New Incense Stick</button>
                        </div>
                    </div>

                    <div class="metrics-grid">
                        <div class="metric-card">
                            <div>
                                <div class="metric-label">Total Revenue</div>
                                <div class="metric-val" id="metric-revenue">₹0</div>
                            </div>
                            <div class="metric-icon-wrap">
                                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>

                        <div class="metric-card">
                            <div>
                                <div class="metric-label">Total Customers</div>
                                <div class="metric-val" id="metric-users">0</div>
                            </div>
                            <div class="metric-icon-wrap">
                                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                        </div>

                        <div class="metric-card">
                            <div>
                                <div class="metric-label">Fragrance Products</div>
                                <div class="metric-val" id="metric-products">0</div>
                            </div>
                            <div class="metric-icon-wrap">
                                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                        </div>

                        <div class="metric-card">
                            <div>
                                <div class="metric-label">Low Stock Alerts</div>
                                <div class="metric-val" id="metric-lowstock">0</div>
                            </div>
                            <div class="metric-icon-wrap">
                                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                        </div>

                        <div class="metric-card" onclick="switchTab('contacts')" style="cursor: pointer;">
                            <div>
                                <div class="metric-label">Inquiries Received</div>
                                <div class="metric-val" id="metric-contacts">0</div>
                            </div>
                            <div class="metric-icon-wrap">
                                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Categories Snapshot -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px;">
                        <div class="card-panel" style="margin-bottom: 0;">
                            <div class="card-panel-header">
                                <div class="card-panel-title">
                                    <span style="color: var(--gold);">🔥 Incense Cones Section</span>
                                </div>
                                <button class="btn-primary" onclick="openAddProductModal('incense-cones')">+ Add Cone</button>
                            </div>
                            <div style="padding: 20px;">
                                <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">
                                    Artisanal backflow and conical botanicals formulated with temple floral waste and pure frankincense.
                                </p>
                                <div class="flex items-center justify-between" style="border-top: 1px solid var(--border); padding-top: 14px;">
                                    <span style="font-size: 13px; color: var(--text-dim);">Products in section:</span>
                                    <span class="badge badge-gold" id="dash-cones-count">0 items</span>
                                </div>
                            </div>
                        </div>

                        <div class="card-panel" style="margin-bottom: 0;">
                            <div class="card-panel-header">
                                <div class="card-panel-title">
                                    <span style="color: var(--gold);">✨ Incense Sticks Section</span>
                                </div>
                                <button class="btn-primary" onclick="openAddProductModal('incense-sticks')">+ Add Stick</button>
                            </div>
                            <div style="padding: 20px;">
                                <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">
                                    Master-crafted bamboo & charcoal-free agarbatti sticks saturated with Mysore sandalwood and Oud.
                                </p>
                                <div class="flex items-center justify-between" style="border-top: 1px solid var(--border); padding-top: 14px;">
                                    <span style="font-size: 13px; color: var(--text-dim);">Products in section:</span>
                                    <span class="badge badge-gold" id="dash-sticks-count">0 items</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ================= TAB 2: CUSTOMERS / USERS ================= -->
                <section id="tab-customers" class="hidden">
                    <div class="page-header">
                        <div>
                            <h2 class="page-title">Registered Customers</h2>
                            <p class="page-desc">List of all boutique clients and administrators with decrypted details.</p>
                        </div>
                    </div>

                    <div class="card-panel">
                        <div class="card-panel-header">
                            <div class="card-panel-title">
                                <span>All Users</span>
                                <span class="badge badge-gold" id="table-users-count">0 Users</span>
                            </div>
                            <div class="search-input-wrap">
                                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <input type="text" class="search-input" id="search-users" placeholder="Search customer name/email..." oninput="filterUsersTable()">
                            </div>
                        </div>
                        <div style="overflow-x: auto;">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>User ID</th>
                                        <th>Client Name</th>
                                        <th>Email Address</th>
                                        <th>Phone Number</th>
                                        <th>Role</th>
                                        <th>Account Status</th>
                                        <th>Registered Date</th>
                                    </tr>
                                </thead>
                                <tbody id="users-table-body">
                                    <!-- Populated dynamically via JS -->
                                </tbody>
                            </table>
                        </div>
                        <!-- Customers Pagination -->
                        <div class="pagination-wrapper" id="pagination-users">
                            <div class="pagination-info">
                                <span id="users-page-info">Showing 0 to 0 of 0 customers</span>
                                <div class="pagination-per-page">
                                    <span>Rows:</span>
                                    <select id="users-per-page" onchange="changePerPage('users', this.value)">
                                        <option value="5" selected>5</option>
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                    </select>
                                </div>
                            </div>
                            <div class="pagination-controls">
                                <button type="button" class="pagination-btn" id="users-prev-btn" onclick="prevPage('users')">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                    Prev
                                </button>
                                <div class="pagination-page-nums" id="users-page-numbers"></div>
                                <button type="button" class="pagination-btn" id="users-next-btn" onclick="nextPage('users')">
                                    Next
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ================= TAB 3: INCENSE CONES ================= -->
                <section id="tab-incense-cones" class="hidden">
                    <div class="page-header">
                        <div>
                            <h2 class="page-title">Incense Cones</h2>
                            <p class="page-desc">Artisanal dhoop cones. Click any thumbnail to preview its 2–3 gallery images.</p>
                        </div>
                        <button class="btn-primary" onclick="openAddProductModal('incense-cones')">
                            + Add Incense Cone
                        </button>
                    </div>

                    <div class="card-panel">
                        <div class="card-panel-header">
                            <div class="card-panel-title">
                                <span>Incense Cones Catalog</span>
                                <span class="badge badge-gold" id="cones-catalog-count">0 Products</span>
                            </div>
                            <div class="search-input-wrap">
                                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <input type="text" class="search-input" id="search-cones" placeholder="Search incense cones..." oninput="filterConesTable()">
                            </div>
                        </div>
                        <div style="overflow-x: auto;">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>Thumbnail</th>
                                        <th>Product Title</th>
                                        <th>Fragrance Profile</th>
                                        <th>Original Price</th>
                                        <th>Discount Price</th>
                                        <th>Stock</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="cones-table-body">
                                    <!-- Populated dynamically via JS -->
                                </tbody>
                            </table>
                        </div>
                        <!-- Cones Pagination -->
                        <div class="pagination-wrapper" id="pagination-cones">
                            <div class="pagination-info">
                                <span id="cones-page-info">Showing 0 to 0 of 0 products</span>
                                <div class="pagination-per-page">
                                    <span>Rows:</span>
                                    <select id="cones-per-page" onchange="changePerPage('cones', this.value)">
                                        <option value="5" selected>5</option>
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                    </select>
                                </div>
                            </div>
                            <div class="pagination-controls">
                                <button type="button" class="pagination-btn" id="cones-prev-btn" onclick="prevPage('cones')">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                    Prev
                                </button>
                                <div class="pagination-page-nums" id="cones-page-numbers"></div>
                                <button type="button" class="pagination-btn" id="cones-next-btn" onclick="nextPage('cones')">
                                    Next
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ================= TAB 4: INCENSE STICKS ================= -->
                <section id="tab-incense-sticks" class="hidden">
                    <div class="page-header">
                        <div>
                            <h2 class="page-title">Incense Sticks</h2>
                            <p class="page-desc">Luxury hand-rolled agarbatti sticks. Click any thumbnail to preview its 2–3 gallery images.</p>
                        </div>
                        <button class="btn-primary" onclick="openAddProductModal('incense-sticks')">
                            + Add Incense Stick
                        </button>
                    </div>

                    <div class="card-panel">
                        <div class="card-panel-header">
                            <div class="card-panel-title">
                                <span>Incense Sticks Catalog</span>
                                <span class="badge badge-gold" id="sticks-catalog-count">0 Products</span>
                            </div>
                            <div class="search-input-wrap">
                                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <input type="text" class="search-input" id="search-sticks" placeholder="Search incense sticks..." oninput="filterSticksTable()">
                            </div>
                        </div>
                        <div style="overflow-x: auto;">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>Thumbnail</th>
                                        <th>Product Title</th>
                                        <th>Fragrance Profile</th>
                                        <th>Original Price</th>
                                        <th>Discount Price</th>
                                        <th>Stock</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="sticks-table-body">
                                    <!-- Populated dynamically via JS -->
                                </tbody>
                            </table>
                        </div>
                        <!-- Sticks Pagination -->
                        <div class="pagination-wrapper" id="pagination-sticks">
                            <div class="pagination-info">
                                <span id="sticks-page-info">Showing 0 to 0 of 0 products</span>
                                <div class="pagination-per-page">
                                    <span>Rows:</span>
                                    <select id="sticks-per-page" onchange="changePerPage('sticks', this.value)">
                                        <option value="5" selected>5</option>
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                    </select>
                                </div>
                            </div>
                            <div class="pagination-controls">
                                <button type="button" class="pagination-btn" id="sticks-prev-btn" onclick="prevPage('sticks')">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                    Prev
                                </button>
                                <div class="pagination-page-nums" id="sticks-page-numbers"></div>
                                <button type="button" class="pagination-btn" id="sticks-next-btn" onclick="nextPage('sticks')">
                                    Next
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ================= TAB 5: ALL PRODUCTS ================= -->
                <section id="tab-all-products" class="hidden">
                    <div class="page-header">
                        <div>
                            <h2 class="page-title">Master Product Inventory</h2>
                            <p class="page-desc">Comprehensive view of all fragrance creations across both sections.</p>
                        </div>
                        <div class="flex gap-2">
                            <button class="btn-primary" onclick="openAddProductModal()">+ Add Product</button>
                        </div>
                    </div>

                    <div class="card-panel">
                        <div class="card-panel-header">
                            <div class="card-panel-title">
                                <span>All Products</span>
                                <span class="badge badge-gold" id="all-catalog-count">0 Products</span>
                            </div>
                            <div class="search-input-wrap">
                                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <input type="text" class="search-input" id="search-products" placeholder="Search product title/fragrance..." oninput="filterAllProductsTable()">
                            </div>
                        </div>
                        <div style="overflow-x: auto;">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>Thumbnail</th>
                                        <th>Product Title</th>
                                        <th>Section / Category</th>
                                        <th>Original (MRP)</th>
                                        <th>Discount Price</th>
                                        <th>Stock</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="all-products-table-body">
                                    <!-- Populated dynamically via JS -->
                                </tbody>
                            </table>
                        </div>
                        <!-- All Products Pagination -->
                        <div class="pagination-wrapper" id="pagination-all">
                            <div class="pagination-info">
                                <span id="all-page-info">Showing 0 to 0 of 0 products</span>
                                <div class="pagination-per-page">
                                    <span>Rows:</span>
                                    <select id="all-per-page" onchange="changePerPage('all', this.value)">
                                        <option value="5" selected>5</option>
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                    </select>
                                </div>
                            </div>
                            <div class="pagination-controls">
                                <button type="button" class="pagination-btn" id="all-prev-btn" onclick="prevPage('all')">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                    Prev
                                </button>
                                <div class="pagination-page-nums" id="all-page-numbers"></div>
                                <button type="button" class="pagination-btn" id="all-next-btn" onclick="nextPage('all')">
                                    Next
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ========================================================
                     6. SECTION: CONTACT MESSAGES
                     ======================================================== -->
                <section id="tab-contacts" class="hidden">
                    <div class="page-header">
                        <div>
                            <h2 class="page-title">Customer Inquiries & Messages</h2>
                            <p class="page-desc">Real-time inquiries and messages submitted by devotees via the frontend Contact Us form.</p>
                        </div>
                        <button class="btn-secondary" onclick="loadContacts()">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span>Refresh Inquiries</span>
                        </button>
                    </div>

                    <div class="card-panel">
                        <div class="card-panel-header">
                            <div class="card-panel-title">
                                <span>All Contact Inquiries</span>
                                <span class="badge badge-gold" id="contacts-catalog-count">0 Inquiries</span>
                            </div>
                            <div class="search-input-wrap">
                                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <input type="text" class="search-input" id="search-contacts" placeholder="Search devotee name, email, phone..." oninput="filterContactsTable()">
                            </div>
                        </div>

                        <div style="overflow-x: auto;">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th style="min-width: 150px; width: 160px;">Date & Time</th>
                                        <th style="min-width: 160px; width: 180px;">Devotee Name</th>
                                        <th style="min-width: 200px; width: 230px;">Contact Details</th>
                                        <th style="min-width: 130px; width: 150px;">Subject</th>
                                        <th style="min-width: 280px;">Message</th>
                                        <th style="min-width: 90px; width: 100px;">Status</th>
                                        <th style="min-width: 90px; width: 110px; text-align: center;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="contacts-table-body">
                                    <!-- Populated dynamically via JS -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Contacts Pagination -->
                        <div class="pagination-wrapper" id="pagination-contacts">
                            <div class="pagination-info">
                                <span id="contacts-page-info">Showing 0 to 0 of 0 inquiries</span>
                                <div class="pagination-per-page">
                                    <span>Rows:</span>
                                    <select id="contacts-per-page" onchange="changePerPage('contacts', this.value)">
                                        <option value="5" selected>5</option>
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                    </select>
                                </div>
                            </div>
                            <div class="pagination-controls">
                                <button type="button" class="pagination-btn" id="contacts-prev-btn" onclick="prevPage('contacts')">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                    Prev
                                </button>
                                <div class="pagination-page-nums" id="contacts-page-numbers"></div>
                                <button type="button" class="pagination-btn" id="contacts-next-btn" onclick="nextPage('contacts')">
                                    Next
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                
            </div>
        </main>
    </div>

    <!-- ========================================================
         MODAL 1: GALLERY PREVIEW MODAL (Click Thumbnail -> Shows 2-3 Photos)
         ======================================================== -->
    <div id="gallery-modal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-header">
                <div>
                    <h3 class="modal-title" id="gallery-modal-title">Product Image Gallery</h3>
                    <p style="font-size: 12px; color: var(--text-muted);" id="gallery-modal-fragrance"></p>
                </div>
                <button type="button" class="modal-close-btn" onclick="closeModal('gallery-modal')">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="modal-body">
                <!-- Main High-Res Preview (Photo or Video) -->
                <div class="gallery-main-view">
                    <img id="gallery-active-img" class="gallery-main-img" src="" alt="Active View">
                    <video id="gallery-active-video" class="gallery-main-img hidden" controls autoplay muted loop></video>
                </div>

                <!-- 1 Thumbnail + 2-3 Related Photos Strip -->
                <div class="gallery-strip" id="gallery-strip-container">
                    <!-- Populated dynamically with thumbnail and related photos -->
                </div>

                <div class="flex items-center justify-between" style="border-top: 1px solid var(--border); padding-top: 16px;">
                    <div>
                        <span class="price-original" id="gallery-modal-mrp">₹0</span>
                        <span class="price-discount" id="gallery-modal-price">₹0</span>
                    </div>
                    <div id="gallery-modal-stock" class="badge badge-green">In Stock</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================
         MODAL 2: ADD / EDIT PRODUCT MODAL
         ======================================================== -->
    <div id="product-modal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-header">
                <h3 class="modal-title" id="product-modal-title">Add New Product</h3>
                <button type="button" class="modal-close-btn" onclick="closeModal('product-modal')">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="product-form" onsubmit="handleProductSubmit(event)">
                <input type="hidden" id="prod-id" value="">

                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Product Section / Category *</label>
                            <select id="prod-category" class="form-input" required>
                                <option value="">Select Section</option>
                                <option value="incense-cones">Incense Cones</option>
                                <option value="incense-sticks">Incense Sticks</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Product Title (Name) *</label>
                            <input type="text" id="prod-name" class="form-input" placeholder="e.g. Royal Oud Dhoop Cones" required>
                        </div>
                    </div>

                    <div class="form-row-full">
                        <label class="form-label">Description *</label>
                        <textarea id="prod-description" class="form-input" rows="3" placeholder="Artisanal botanicals, resins and pure essential oils..." required></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Original Price (MRP ₹) *</label>
                            <input type="number" id="prod-mrp" class="form-input" step="0.01" min="0" placeholder="899.00" required>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Discount Price (Selling Price ₹) *</label>
                            <input type="number" id="prod-price" class="form-input" step="0.01" min="0" placeholder="599.00" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Inventory Stock Units *</label>
                            <input type="number" id="prod-stock" class="form-input" min="0" placeholder="100" required>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Fragrance Notes</label>
                            <input type="text" id="prod-fragrance" class="form-input" placeholder="Woody Amber & Oud | Mysore Sandalwood">
                        </div>
                    </div>

                    <div style="border-top: 1px solid var(--border); margin: 20px 0 16px; padding-top: 16px;">
                        <div class="flex items-center justify-between" style="margin-bottom: 12px;">
                            <h4 style="font-size: 13px; font-weight: 700; color: var(--gold); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0;">
                                Product Imagery (Upload Photos / Videos to public/uploads)
                            </h4>
                            <span style="font-size: 11px; color: var(--text-dim);">Auto-saves to /uploads/products</span>
                        </div>

                        <!-- Drag and drop / Batch upload box -->
                        <div class="batch-upload-box" onclick="document.getElementById('batch-file-input').click()" ondragover="event.preventDefault(); this.classList.add('drag-over')" ondragleave="this.classList.remove('drag-over')" ondrop="event.preventDefault(); this.classList.remove('drag-over'); handleBatchUpload(event.dataTransfer.files)">
                            <input type="file" id="batch-file-input" accept="image/*,video/*" multiple onchange="handleBatchUpload(this.files)" style="display: none;">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            <div style="font-size: 12.5px; font-weight: 600; color: #fff;">Click or Drag & Drop Photos / Videos to Upload</div>
                            <div style="font-size: 11px; color: var(--text-muted);">Upload up to 4 media files at once (Stored directly in public/uploads/products)</div>
                        </div>

                        <!-- Select Number of Images to Upload -->
                        <div class="form-group" style="margin-bottom: 16px; background: rgba(200, 146, 42, 0.08); padding: 12px 14px; border-radius: 12px; border: 1px solid rgba(200, 146, 42, 0.3);">
                            <div class="flex items-center justify-between" style="margin-bottom: 6px;">
                                <label class="form-label" style="margin-bottom: 0; font-weight: 700; color: var(--gold);">
                                    📸 Kitne Images Upload Karne Hain? (Select Image Count)
                                </label>
                                <span style="font-size: 11px; color: var(--gold); font-weight: 600;">Photo 1 is Main Thumbnail</span>
                            </div>
                            <select id="prod-image-count" class="form-input" style="background: #1a1715; color: #fff; font-size: 13px;" onchange="updateImageSlotsCount(this.value)">
                                <option value="1">1 Image (Sirf 1 Thumbnail / Primary Cover)</option>
                                <option value="2">2 Images (1 Thumbnail + 1 Gallery Image)</option>
                                <option value="3">3 Images (1 Thumbnail + 2 Gallery Images)</option>
                                <option value="4" selected>4 Images (1 Thumbnail + 3 Gallery Images)</option>
                                <option value="5">5 Images (1 Thumbnail + 4 Gallery Images)</option>
                                <option value="6">6 Images (1 Thumbnail + 5 Gallery Images)</option>
                            </select>
                            <div style="font-size: 11px; color: var(--text-muted); margin-top: 5px;">
                                💡 Photo 1 hamesha main cover thumbnail rahegi. Niche utne hi upload fields dikhenge jitne aapne select kiye hain.
                            </div>
                        </div>

                        <!-- PHOTO 1 (THUMBNAIL) -->
                        <div class="form-group" id="photo-slot-0">
                            <div class="flex items-center justify-between" style="margin-bottom: 6px;">
                                <label class="form-label" style="margin-bottom: 0; color: #fff; font-weight: 600;">Photo 1 (Thumbnail / Primary Cover) *</label>
                                <span id="upload-status-0" style="font-size: 11px; color: var(--gold);"></span>
                            </div>
                            <div class="upload-row">
                                <div class="upload-preview-box" id="preview-box-0" onclick="document.getElementById('file-input-0').click()" title="Click to upload">
                                    <div class="preview-empty" id="preview-empty-0">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>Upload</span>
                                    </div>
                                    <img id="preview-img-tag-0" class="preview-media hidden" alt="Preview 1">
                                    <video id="preview-video-tag-0" class="preview-media hidden" controls></video>
                                </div>
                                <div style="flex: 1;">
                                    <div class="flex gap-2">
                                        <input type="file" id="file-input-0" accept="image/*,video/*" onchange="handleSingleUpload(0, this.files[0])" style="display: none;">
                                        <button type="button" class="btn-secondary" style="padding: 8px 12px; font-size: 12px; white-space: nowrap;" onclick="document.getElementById('file-input-0').click()">
                                            📁 Choose File
                                        </button>
                                        <input type="text" id="prod-img-0" class="form-input" placeholder="/uploads/products/... or image URL" required oninput="previewImageInput(0)" style="font-size: 12.5px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PHOTO 2 -->
                        <div class="form-group" id="photo-slot-1">
                            <div class="flex items-center justify-between" style="margin-bottom: 6px;">
                                <label class="form-label" style="margin-bottom: 0;">Photo 2 (Gallery Photo / Video 1)</label>
                                <span id="upload-status-1" style="font-size: 11px; color: var(--gold);"></span>
                            </div>
                            <div class="upload-row">
                                <div class="upload-preview-box" id="preview-box-1" onclick="document.getElementById('file-input-1').click()" title="Click to upload">
                                    <div class="preview-empty" id="preview-empty-1">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>Upload</span>
                                    </div>
                                    <img id="preview-img-tag-1" class="preview-media hidden" alt="Preview 2">
                                    <video id="preview-video-tag-1" class="preview-media hidden" controls></video>
                                </div>
                                <div style="flex: 1;">
                                    <div class="flex gap-2">
                                        <input type="file" id="file-input-1" accept="image/*,video/*" onchange="handleSingleUpload(1, this.files[0])" style="display: none;">
                                        <button type="button" class="btn-secondary" style="padding: 8px 12px; font-size: 12px; white-space: nowrap;" onclick="document.getElementById('file-input-1').click()">
                                            📁 Choose File
                                        </button>
                                        <input type="text" id="prod-img-1" class="form-input" placeholder="/uploads/products/... or image URL" oninput="previewImageInput(1)" style="font-size: 12.5px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PHOTO 3 -->
                        <div class="form-group" id="photo-slot-2">
                            <div class="flex items-center justify-between" style="margin-bottom: 6px;">
                                <label class="form-label" style="margin-bottom: 0;">Photo 3 (Gallery Photo / Video 2)</label>
                                <span id="upload-status-2" style="font-size: 11px; color: var(--gold);"></span>
                            </div>
                            <div class="upload-row">
                                <div class="upload-preview-box" id="preview-box-2" onclick="document.getElementById('file-input-2').click()" title="Click to upload">
                                    <div class="preview-empty" id="preview-empty-2">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>Upload</span>
                                    </div>
                                    <img id="preview-img-tag-2" class="preview-media hidden" alt="Preview 3">
                                    <video id="preview-video-tag-2" class="preview-media hidden" controls></video>
                                </div>
                                <div style="flex: 1;">
                                    <div class="flex gap-2">
                                        <input type="file" id="file-input-2" accept="image/*,video/*" onchange="handleSingleUpload(2, this.files[0])" style="display: none;">
                                        <button type="button" class="btn-secondary" style="padding: 8px 12px; font-size: 12px; white-space: nowrap;" onclick="document.getElementById('file-input-2').click()">
                                            📁 Choose File
                                        </button>
                                        <input type="text" id="prod-img-2" class="form-input" placeholder="/uploads/products/... or image URL" oninput="previewImageInput(2)" style="font-size: 12.5px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PHOTO 4 -->
                        <div class="form-group" id="photo-slot-3">
                            <div class="flex items-center justify-between" style="margin-bottom: 6px;">
                                <label class="form-label" style="margin-bottom: 0;">Photo 4 (Gallery Photo / Video 3)</label>
                                <span id="upload-status-3" style="font-size: 11px; color: var(--gold);"></span>
                            </div>
                            <div class="upload-row">
                                <div class="upload-preview-box" id="preview-box-3" onclick="document.getElementById('file-input-3').click()" title="Click to upload">
                                    <div class="preview-empty" id="preview-empty-3">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>Upload</span>
                                    </div>
                                    <img id="preview-img-tag-3" class="preview-media hidden" alt="Preview 4">
                                    <video id="preview-video-tag-3" class="preview-media hidden" controls></video>
                                </div>
                                <div style="flex: 1;">
                                    <div class="flex gap-2">
                                        <input type="file" id="file-input-3" accept="image/*,video/*" onchange="handleSingleUpload(3, this.files[0])" style="display: none;">
                                        <button type="button" class="btn-secondary" style="padding: 8px 12px; font-size: 12px; white-space: nowrap;" onclick="document.getElementById('file-input-3').click()">
                                            📁 Choose File
                                        </button>
                                        <input type="text" id="prod-img-3" class="form-input" placeholder="/uploads/products/... or image URL" oninput="previewImageInput(3)" style="font-size: 12.5px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PHOTO 5 -->
                        <div class="form-group" id="photo-slot-4" style="display: none;">
                            <div class="flex items-center justify-between" style="margin-bottom: 6px;">
                                <label class="form-label" style="margin-bottom: 0;">Photo 5 (Gallery Photo / Video 4)</label>
                                <span id="upload-status-4" style="font-size: 11px; color: var(--gold);"></span>
                            </div>
                            <div class="upload-row">
                                <div class="upload-preview-box" id="preview-box-4" onclick="document.getElementById('file-input-4').click()" title="Click to upload">
                                    <div class="preview-empty" id="preview-empty-4">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>Upload</span>
                                    </div>
                                    <img id="preview-img-tag-4" class="preview-media hidden" alt="Preview 5">
                                    <video id="preview-video-tag-4" class="preview-media hidden" controls></video>
                                </div>
                                <div style="flex: 1;">
                                    <div class="flex gap-2">
                                        <input type="file" id="file-input-4" accept="image/*,video/*" onchange="handleSingleUpload(4, this.files[0])" style="display: none;">
                                        <button type="button" class="btn-secondary" style="padding: 8px 12px; font-size: 12px; white-space: nowrap;" onclick="document.getElementById('file-input-4').click()">
                                            📁 Choose File
                                        </button>
                                        <input type="text" id="prod-img-4" class="form-input" placeholder="/uploads/products/... or image URL" oninput="previewImageInput(4)" style="font-size: 12.5px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PHOTO 6 -->
                        <div class="form-group" id="photo-slot-5" style="display: none; margin-bottom: 0;">
                            <div class="flex items-center justify-between" style="margin-bottom: 6px;">
                                <label class="form-label" style="margin-bottom: 0;">Photo 6 (Gallery Photo / Video 5)</label>
                                <span id="upload-status-5" style="font-size: 11px; color: var(--gold);"></span>
                            </div>
                            <div class="upload-row">
                                <div class="upload-preview-box" id="preview-box-5" onclick="document.getElementById('file-input-5').click()" title="Click to upload">
                                    <div class="preview-empty" id="preview-empty-5">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>Upload</span>
                                    </div>
                                    <img id="preview-img-tag-5" class="preview-media hidden" alt="Preview 6">
                                    <video id="preview-video-tag-5" class="preview-media hidden" controls></video>
                                </div>
                                <div style="flex: 1;">
                                    <div class="flex gap-2">
                                        <input type="file" id="file-input-5" accept="image/*,video/*" onchange="handleSingleUpload(5, this.files[0])" style="display: none;">
                                        <button type="button" class="btn-secondary" style="padding: 8px 12px; font-size: 12px; white-space: nowrap;" onclick="document.getElementById('file-input-5').click()">
                                            📁 Choose File
                                        </button>
                                        <input type="text" id="prod-img-5" class="form-input" placeholder="/uploads/products/... or image URL" oninput="previewImageInput(5)" style="font-size: 12.5px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('product-modal')">Cancel</button>
                    <button type="submit" id="btn-save-product" class="btn-primary">Save Product</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================
         MODAL 3: DELETE CONFIRMATION MODAL
         ======================================================== -->
    <div id="delete-modal" class="modal-overlay">
        <div class="modal-card" style="max-width: 440px;">
            <div class="modal-header">
                <h3 class="modal-title">Confirm Delete</h3>
                <button type="button" class="modal-close-btn" onclick="closeModal('delete-modal')">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="modal-body">
                <p style="font-size: 14px; color: var(--text-main); margin-bottom: 10px;">
                    Are you sure you want to delete this product?
                </p>
                <p style="font-size: 13px; font-weight: 600; color: var(--gold);" id="delete-prod-title"></p>
                <p style="font-size: 12px; color: var(--text-dim); margin-top: 8px;">
                    This will remove the product and its images from the boutique catalog.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('delete-modal')">Cancel</button>
                <button type="button" id="btn-confirm-delete" class="btn-primary" onclick="executeDeleteProduct()">
                    Delete Forever
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast">
        <svg width="18" height="18" fill="none" stroke="var(--gold)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span id="toast-msg">Operation completed successfully.</span>
    </div>

    <!-- ========================================================
         JAVASCRIPT LOGIC
         ======================================================== -->
    <script>
        const API_BASE = '/api';
        let adminToken = localStorage.getItem('foxal_admin_token') || '';
        let currentUser = null;
        let categoriesList = [];
        let productsList = [];
        let usersList = [];
        let productToDeleteId = null;

        // Document Ready Initialization
        document.addEventListener('DOMContentLoaded', () => {
            initAuth();

            // Login form listener
            document.getElementById('admin-login-form').addEventListener('submit', handleAdminLogin);
        });

        // ==========================================
        // AUTHENTICATION MANAGEMENT
        // ==========================================
        async function initAuth() {
            if (!adminToken) {
                showLoginScreen();
                return;
            }

            try {
                // Verify token with backend
                const res = await fetch(`${API_BASE}/auth/me`, {
                    headers: {
                        'Authorization': `Bearer ${adminToken}`,
                        'Accept': 'application/json'
                    }
                });

                if (res.ok) {
                    const data = await res.json();
                    if (data.data && data.data.role === 'admin') {
                        currentUser = data.data;
                        showAdminPanel();
                        loadAllData();
                    } else {
                        // User is not admin
                        adminLogout('Access denied. Only administrators are authorized.');
                    }
                } else {
                    // Invalid/expired token
                    adminLogout();
                }
            } catch (err) {
                console.warn('Auth check error:', err);
                showLoginScreen();
            }
        }

        async function handleAdminLogin(e) {
            e.preventDefault();
            const email = document.getElementById('login-email').value.trim();
            const password = document.getElementById('login-password').value;
            const btnSubmit = document.getElementById('btn-login-submit');
            const errBox = document.getElementById('login-error');
            const errMsg = document.getElementById('login-error-msg');

            errBox.classList.add('hidden');
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<span>Verifying Admin...</span>';

            try {
                const res = await fetch(`${API_BASE}/auth/admin/login`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await res.json();

                if (res.ok && data.success && data.data && data.data.token) {
                    adminToken = data.data.token;
                    currentUser = data.data.user;
                    localStorage.setItem('foxal_admin_token', adminToken);
                    showToast('Admin authentication verified.');
                    showAdminPanel();
                    loadAllData();
                } else {
                    errMsg.textContent = data.message || 'Invalid credentials or access denied.';
                    errBox.classList.remove('hidden');
                }
            } catch (err) {
                errMsg.textContent = 'Network error connecting to backend.';
                errBox.classList.remove('hidden');
            } finally {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = `
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    <span>Sign In to Admin Panel</span>
                `;
            }
        }

        function adminLogout(reason = null) {
            if (adminToken) {
                fetch(`${API_BASE}/auth/logout`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${adminToken}`,
                        'Accept': 'application/json'
                    }
                }).catch(() => {});
            }

            adminToken = '';
            currentUser = null;
            localStorage.removeItem('foxal_admin_token');
            showLoginScreen();

            if (reason) {
                const errBox = document.getElementById('login-error');
                const errMsg = document.getElementById('login-error-msg');
                errMsg.textContent = reason;
                errBox.classList.remove('hidden');
            }
        }

        function showLoginScreen() {
            document.getElementById('login-screen').classList.remove('hidden');
            document.getElementById('admin-panel').classList.add('hidden');
        }

        function showAdminPanel() {
            document.getElementById('login-screen').classList.add('hidden');
            document.getElementById('admin-panel').classList.remove('hidden');

            if (currentUser) {
                document.getElementById('sidebar-name').textContent = currentUser.name || 'Foxal Admin';
                document.getElementById('sidebar-avatar').textContent = (currentUser.name || 'A')[0].toUpperCase();
            }
        }

        function fillAdminCreds() {
            document.getElementById('login-email').value = 'admin@foxal.com';
            document.getElementById('login-password').value = 'Admin@1234';
        }

        // ==========================================
        // DATA LOADING & RENDERING
        // ==========================================
        async function loadAllData() {
            await Promise.all([
                loadCategories(),
                loadProducts(),
                loadUsers(),
                loadContacts(),
                loadDashboardStats(),
            ]);
            fetchLiveFeedPreview();
        }

        async function apiGet(endpoint) {
            const res = await fetch(`${API_BASE}${endpoint}`, {
                headers: {
                    'Authorization': `Bearer ${adminToken}`,
                    'Accept': 'application/json'
                }
            });
            if (res.status === 401 || res.status === 403) {
                adminLogout('Session expired or unauthorized.');
                throw new Error('Unauthorized');
            }
            return res.json();
        }

        async function loadCategories() {
            try {
                const res = await apiGet('/categories');
                if (res.success && res.data) {
                    categoriesList = res.data;
                    populateCategoryDropdown();
                }
            } catch (err) {
                console.error('Failed to load categories', err);
            }
        }

        function populateCategoryDropdown() {
            const select = document.getElementById('prod-category');
            select.innerHTML = '<option value="">Select Section / Category</option>';
            categoriesList.forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.slug;
                opt.textContent = c.name;
                opt.dataset.id = c.id;
                select.appendChild(opt);
            });
        }

        async function loadProducts() {
            try {
                const res = await apiGet('/admin/products?per_page=100');
                if (res.success && res.data) {
                    productsList = res.data;
                    renderProductsTables();
                }
            } catch (err) {
                console.error('Failed to load products', err);
            }
        }

        // ==========================================
        // PAGINATION CORE LOGIC
        // ==========================================
        const paginationState = {
            all: { page: 1, perPage: 5, filteredList: [] },
            cones: { page: 1, perPage: 5, filteredList: [] },
            sticks: { page: 1, perPage: 5, filteredList: [] },
            users: { page: 1, perPage: 5, filteredList: [] },
            contacts: { page: 1, perPage: 5, filteredList: [] }
        };

        function changePerPage(key, val) {
            if (!paginationState[key]) return;
            paginationState[key].perPage = parseInt(val, 10) || 5;
            paginationState[key].page = 1;
            renderPaginatedTable(key);
        }

        function prevPage(key) {
            if (!paginationState[key]) return;
            if (paginationState[key].page > 1) {
                paginationState[key].page--;
                renderPaginatedTable(key);
            }
        }

        function nextPage(key) {
            if (!paginationState[key]) return;
            const total = paginationState[key].filteredList.length;
            const totalPages = Math.max(1, Math.ceil(total / paginationState[key].perPage));
            if (paginationState[key].page < totalPages) {
                paginationState[key].page++;
                renderPaginatedTable(key);
            }
        }

        function gotoPage(key, pageNum) {
            if (!paginationState[key]) return;
            paginationState[key].page = pageNum;
            renderPaginatedTable(key);
        }

        function updatePaginationUI(key, total, startIdx, endIdx, totalPages) {
            const infoEl = document.getElementById(`${key}-page-info`);
            const prevBtn = document.getElementById(`${key}-prev-btn`);
            const nextBtn = document.getElementById(`${key}-next-btn`);
            const pageNumsEl = document.getElementById(`${key}-page-numbers`);
            const state = paginationState[key];

            if (infoEl) {
                if (total === 0) {
                    infoEl.textContent = 'No records found';
                } else {
                    const itemLabel = key === 'users' ? 'customers' : (key === 'contacts' ? 'inquiries' : 'products');
                    infoEl.textContent = `Showing ${startIdx + 1}–${endIdx} of ${total} ${itemLabel}`;
                }
            }

            if (prevBtn) prevBtn.disabled = (state.page <= 1);
            if (nextBtn) nextBtn.disabled = (state.page >= totalPages);

            if (pageNumsEl) {
                let buttonsHtml = '';
                for (let p = 1; p <= totalPages; p++) {
                    buttonsHtml += `<button type="button" class="pagination-page-num ${p === state.page ? 'active' : ''}" onclick="gotoPage('${key}', ${p})">${p}</button>`;
                }
                pageNumsEl.innerHTML = buttonsHtml;
            }
        }

        function renderPaginatedTable(key) {
            const state = paginationState[key];
            if (!state) return;

            const items = state.filteredList || [];
            const total = items.length;
            const totalPages = Math.max(1, Math.ceil(total / state.perPage));

            if (state.page > totalPages) state.page = totalPages;
            if (state.page < 1) state.page = 1;

            const startIdx = (state.page - 1) * state.perPage;
            const endIdx = Math.min(startIdx + state.perPage, total);
            const pageItems = items.slice(startIdx, endIdx);

            if (key === 'all') {
                renderAllProductsTableRows(pageItems);
            } else if (key === 'cones') {
                renderCategoryTableRows('cones-table-body', pageItems);
            } else if (key === 'sticks') {
                renderCategoryTableRows('sticks-table-body', pageItems);
            } else if (key === 'users') {
                renderUsersTableRows(pageItems);
            } else if (key === 'contacts') {
                renderContactsTableRows(pageItems);
            }

            updatePaginationUI(key, total, startIdx, endIdx, totalPages);
        }

        function renderProductsTables() {
            const cones = productsList.filter(p => (p.category && p.category.slug === 'incense-cones') || p.category_id === 3);
            const sticks = productsList.filter(p => (p.category && p.category.slug === 'incense-sticks') || p.category_id === 4);

            // Update badge counters
            document.getElementById('badge-cones-count').textContent = cones.length;
            document.getElementById('badge-sticks-count').textContent = sticks.length;
            document.getElementById('badge-total-products').textContent = productsList.length;

            document.getElementById('dash-cones-count').textContent = `${cones.length} items`;
            document.getElementById('dash-sticks-count').textContent = `${sticks.length} items`;
            document.getElementById('cones-catalog-count').textContent = `${cones.length} Products`;
            document.getElementById('sticks-catalog-count').textContent = `${sticks.length} Products`;
            document.getElementById('all-catalog-count').textContent = `${productsList.length} Products`;
            document.getElementById('metric-products').textContent = productsList.length;

            // Set lists for pagination
            paginationState.cones.filteredList = cones;
            paginationState.sticks.filteredList = sticks;
            paginationState.all.filteredList = productsList;

            renderPaginatedTable('cones');
            renderPaginatedTable('sticks');
            renderPaginatedTable('all');
        }

        function renderCategoryTableRows(tbodyId, items) {
            const tbody = document.getElementById(tbodyId);
            if (!tbody) return;

            if (items.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align: center; color: var(--text-dim); padding: 32px;">No products in this section yet. Click "+ Add" above to create one.</td></tr>`;
                return;
            }

            tbody.innerHTML = items.map(p => {
                const images = Array.isArray(p.images) ? p.images : [];
                const thumbnail = images[0] || 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=800';
                const isVid = /\.(mp4|webm|mov|mkv|avi)(\?.*)?$/i.test(thumbnail);
                const galleryCount = Math.max(0, images.length - 1);
                const mrp = parseFloat(p.mrp || 0).toFixed(2);
                const price = parseFloat(p.price || 0).toFixed(2);
                const discount = mrp > 0 ? Math.round(((mrp - price) / mrp) * 100) : 0;

                return `
                    <tr>
                        <td>
                            <div class="thumbnail-container" onclick="openGalleryModal(${p.id})" title="Click to view full image/video gallery">
                                ${isVid ? `<video src="${thumbnail}" class="thumbnail-img" muted></video>` : `<img src="${thumbnail}" class="thumbnail-img" alt="${p.name}">`}
                                <div class="thumbnail-badge">+${galleryCount}</div>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #fff;">${escapeHtml(p.name)}</div>
                            <div style="font-size: 11px; color: var(--text-dim); font-family: var(--font-mono);">${p.sku || 'FX-' + p.id}</div>
                        </td>
                        <td>
                            <div style="font-size: 12.5px; color: var(--text-muted); max-width: 240px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                ${escapeHtml(p.fragrance || 'Luxury Botanicals')}
                            </div>
                        </td>
                        <td>
                            <span class="price-original">₹${mrp}</span>
                        </td>
                        <td>
                            <span class="price-discount">₹${price}</span>
                            ${discount > 0 ? `<span class="discount-tag">-${discount}%</span>` : ''}
                        </td>
                        <td>
                            <span class="badge ${p.stock > 10 ? 'badge-green' : 'badge-red'}">
                                ${p.stock} units
                            </span>
                        </td>
                        <td>
                            <div class="action-btn-group">
                                <button type="button" class="btn-icon btn-icon-gold" title="View Photos / Videos" onclick="openGalleryModal(${p.id})">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </button>
                                <button type="button" class="btn-icon" title="Edit Product" onclick="openEditProductModal(${p.id})">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button type="button" class="btn-icon btn-icon-danger" title="Delete Product" onclick="confirmDeleteProduct(${p.id}, '${escapeHtml(p.name)}')">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function renderAllProductsTableRows(items) {
            const tbody = document.getElementById('all-products-table-body');
            if (!tbody) return;

            if (items.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align: center; color: var(--text-dim); padding: 32px;">No products found matching your search.</td></tr>`;
                return;
            }

            tbody.innerHTML = items.map(p => {
                const images = Array.isArray(p.images) ? p.images : [];
                const thumbnail = images[0] || 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=800';
                const isVid = /\.(mp4|webm|mov|mkv|avi)(\?.*)?$/i.test(thumbnail);
                const catName = p.category ? p.category.name : (p.category_id === 3 ? 'Incense Cones' : 'Incense Sticks');

                return `
                    <tr>
                        <td>
                            <div class="thumbnail-container" onclick="openGalleryModal(${p.id})">
                                ${isVid ? `<video src="${thumbnail}" class="thumbnail-img" muted></video>` : `<img src="${thumbnail}" class="thumbnail-img" alt="${p.name}">`}
                                <div class="thumbnail-badge">+${Math.max(0, images.length - 1)}</div>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #fff;">${escapeHtml(p.name)}</div>
                            <div style="font-size: 11px; color: var(--text-dim);">${escapeHtml(p.fragrance || '')}</div>
                        </td>
                        <td>
                            <span class="badge badge-gold">${catName}</span>
                        </td>
                        <td>₹${parseFloat(p.mrp || 0).toFixed(2)}</td>
                        <td><span class="price-discount">₹${parseFloat(p.price || 0).toFixed(2)}</span></td>
                        <td>
                            <span class="badge ${p.stock > 10 ? 'badge-green' : 'badge-red'}">
                                ${p.stock} units
                            </span>
                        </td>
                        <td>
                            <div class="action-btn-group">
                                <button type="button" class="btn-icon btn-icon-gold" title="Gallery" onclick="openGalleryModal(${p.id})">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </button>
                                <button type="button" class="btn-icon" title="Edit" onclick="openEditProductModal(${p.id})">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button type="button" class="btn-icon btn-icon-danger" title="Delete" onclick="confirmDeleteProduct(${p.id}, '${escapeHtml(p.name)}')">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        async function loadUsers() {
            try {
                const res = await apiGet('/admin/users');
                if (res.success && res.data) {
                    usersList = res.data;
                    paginationState.users.filteredList = usersList;
                    renderPaginatedTable('users');
                    document.getElementById('badge-users-count').textContent = usersList.length;
                    document.getElementById('metric-users').textContent = usersList.length;
                    document.getElementById('table-users-count').textContent = `${usersList.length} Users`;
                }
            } catch (err) {
                console.error('Failed to load users', err);
            }
        }

        function renderUsersTableRows(items) {
            const tbody = document.getElementById('users-table-body');
            if (!tbody) return;

            if (items.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align: center; color: var(--text-dim); padding: 32px;">No customers found.</td></tr>`;
                return;
            }

            tbody.innerHTML = items.map(u => {
                const dateStr = u.created_at ? new Date(u.created_at).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' }) : '—';
                return `
                    <tr>
                        <td style="font-family: var(--font-mono); color: var(--text-dim);">#USR-${u.id}</td>
                        <td style="font-weight: 600; color: #fff;">${escapeHtml(u.name || 'Anonymous')}</td>
                        <td>${escapeHtml(u.email || '—')}</td>
                        <td style="font-family: var(--font-mono);">${u.phone || '—'}</td>
                        <td>
                            <span class="badge ${u.role === 'admin' ? 'badge-gold' : 'badge-slate'}">
                                ${u.role ? u.role.toUpperCase() : 'CUSTOMER'}
                            </span>
                        </td>
                        <td>
                            <span class="badge ${u.is_active ? 'badge-green' : 'badge-red'}">
                                ${u.is_active ? 'Active' : 'Blocked'}
                            </span>
                        </td>
                        <td style="color: var(--text-muted); font-size: 12px;">${dateStr}</td>
                    </tr>
                `;
            }).join('');
        }

        // Backward compatibility aliases
        function renderCategoryTable(tbodyId, items) {
            renderCategoryTableRows(tbodyId, items);
        }
        function renderAllProductsTable(items) {
            renderAllProductsTableRows(items);
        }
        function renderUsersTable(items) {
            renderUsersTableRows(items);
        }

        async function loadDashboardStats() {
            try {
                const res = await apiGet('/admin/dashboard');
                if (res.success && res.data) {
                    const stats = res.data.stats || {};
                    document.getElementById('metric-revenue').textContent = `₹${parseFloat(stats.revenue || 0).toLocaleString('en-IN')}`;
                    document.getElementById('metric-lowstock').textContent = stats.low_stock || 0;
                }
            } catch (err) {
                console.warn('Dashboard stats fallback', err);
            }
        }

        // ==========================================
        // TAB NAVIGATION
        // ==========================================
        function switchTab(tabKey) {
            // Update nav links
            document.querySelectorAll('.nav-link').forEach(el => el.classList.remove('active'));
            const activeNav = document.getElementById(`nav-${tabKey}`);
            if (activeNav) activeNav.classList.add('active');

            // Hide all tab sections
            document.querySelectorAll('section[id^="tab-"]').forEach(sec => sec.classList.add('hidden'));

            // Show active tab section
            const activeSec = document.getElementById(`tab-${tabKey}`);
            if (activeSec) activeSec.classList.remove('hidden');

            if (tabKey === 'contacts') {
                loadContacts();
            }

            // Update breadcrumb
            const titles = {
                'dashboard': 'Dashboard',
                'customers': 'Customers & Users',
                'incense-cones': 'Incense Cones Section',
                'incense-sticks': 'Incense Sticks Section',
                'all-products': 'All Products Master Catalog',
                'contacts': 'Customer Inquiries & Messages',
                'encrypted-feed': 'Frontend Encrypted Feed API',
            };
            document.getElementById('breadcrumb-title').textContent = titles[tabKey] || 'Dashboard';
        }

        // ==========================================
        // MEDIA UPLOAD & PREVIEW LOGIC
        // ==========================================
        async function uploadMediaFile(file) {
            const formData = new FormData();
            formData.append('file', file);

            let res;
            try {
                res = await fetch(`${API_BASE}/admin/upload`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${adminToken}`,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
            } catch (err) {}

            if (!res || !res.ok) {
                res = await fetch(`${API_BASE}/upload`, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: formData
                });
            }

            const data = await res.json();
            if (!res.ok || !data.success) {
                throw new Error(data.message || 'File upload failed');
            }
            return data.data;
        }

        async function handleSingleUpload(index, file) {
            if (!file) return;
            const statusEl = document.getElementById(`upload-status-${index}`);
            if (statusEl) statusEl.textContent = '⏳ Uploading...';

            try {
                const uploaded = await uploadMediaFile(file);
                const inputEl = document.getElementById(`prod-img-${index}`);
                if (inputEl) {
                    inputEl.value = uploaded.path;
                    previewImageInput(index);
                }
                if (statusEl) statusEl.textContent = '✅ Uploaded to public/uploads!';
                showToast(`Saved to public/uploads: ${uploaded.filename}`);
            } catch (err) {
                alert('Upload failed: ' + err.message);
                if (statusEl) statusEl.textContent = '❌ Failed';
            }
        }

        async function handleBatchUpload(files) {
            if (!files || files.length === 0) return;
            showToast(`Uploading ${files.length} media file(s) to public/uploads...`);

            const targetCount = Math.min(6, Math.max(1, files.length));
            const countSelect = document.getElementById('prod-image-count');
            if (countSelect) countSelect.value = targetCount;
            updateImageSlotsCount(targetCount);

            for (let i = 0; i < targetCount; i++) {
                await handleSingleUpload(i, files[i]);
            }
        }

        function updateImageSlotsCount(count) {
            const total = parseInt(count, 10) || 1;
            for (let i = 0; i < 6; i++) {
                const slot = document.getElementById('photo-slot-' + i);
                if (!slot) continue;
                slot.style.display = i < total ? 'block' : 'none';
            }
        }

        function previewImageInput(index) {
            const input = document.getElementById(`prod-img-${index}`);
            const val = input ? input.value.trim() : '';
            const emptyEl = document.getElementById(`preview-empty-${index}`);
            const imgTag = document.getElementById(`preview-img-tag-${index}`);
            const videoTag = document.getElementById(`preview-video-tag-${index}`);

            if (!emptyEl || !imgTag || !videoTag) return;

            if (!val) {
                emptyEl.classList.remove('hidden');
                imgTag.classList.add('hidden');
                videoTag.classList.add('hidden');
                imgTag.src = '';
                videoTag.src = '';
                return;
            }

            const isVideo = /\.(mp4|webm|mov|mkv|avi)(\?.*)?$/i.test(val);
            if (isVideo) {
                emptyEl.classList.add('hidden');
                imgTag.classList.add('hidden');
                videoTag.classList.remove('hidden');
                videoTag.src = val;
            } else {
                emptyEl.classList.add('hidden');
                videoTag.classList.add('hidden');
                imgTag.classList.remove('hidden');
                imgTag.src = val;
            }
        }

        // ==========================================
        // GALLERY MODAL (Thumbnail + 2-3 Photos)
        // ==========================================
        function openGalleryModal(productId) {
            const product = productsList.find(p => p.id === productId);
            if (!product) return;

            const images = Array.isArray(product.images) && product.images.length > 0
                ? product.images
                : ['https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=800'];

            document.getElementById('gallery-modal-title').textContent = product.name;
            document.getElementById('gallery-modal-fragrance').textContent = product.fragrance || 'Haute Fragrance Formulation';
            document.getElementById('gallery-modal-mrp').textContent = `₹${parseFloat(product.mrp || 0).toFixed(2)}`;
            document.getElementById('gallery-modal-price').textContent = `₹${parseFloat(product.price || 0).toFixed(2)}`;

            const stockEl = document.getElementById('gallery-modal-stock');
            if (product.stock > 10) {
                stockEl.className = 'badge badge-green';
                stockEl.textContent = `In Stock (${product.stock} units)`;
            } else {
                stockEl.className = 'badge badge-red';
                stockEl.textContent = `Low Stock (${product.stock} units)`;
            }

            // Render gallery thumbnails strip (Thumbnail + 2-3 Related Photos / Videos)
            const strip = document.getElementById('gallery-strip-container');
            strip.innerHTML = images.map((imgUrl, idx) => {
                const isVid = /\.(mp4|webm|mov|mkv|avi)(\?.*)?$/i.test(imgUrl);
                return `
                <div class="gallery-thumb-item ${idx === 0 ? 'active' : ''}" onclick="selectGalleryPhoto(this, '${imgUrl}')">
                    ${isVid ? `<video src="${imgUrl}" muted></video>` : `<img src="${imgUrl}" alt="Gallery ${idx + 1}">`}
                    <span class="gallery-thumb-tag">${idx === 0 ? 'Thumbnail' : (isVid ? 'Video' : 'Photo ' + (idx + 1))}</span>
                </div>
            `;
            }).join('');

            selectGalleryPhoto(strip.children[0], images[0]);
            openModal('gallery-modal');
        }

        function selectGalleryPhoto(thumbEl, mediaUrl) {
            document.querySelectorAll('.gallery-thumb-item').forEach(el => el.classList.remove('active'));
            if (thumbEl) thumbEl.classList.add('active');
            const mainImg = document.getElementById('gallery-active-img');
            const mainVid = document.getElementById('gallery-active-video');
            const isVideo = /\.(mp4|webm|mov|mkv|avi)(\?.*)?$/i.test(mediaUrl);

            if (isVideo) {
                mainImg.classList.add('hidden');
                mainVid.classList.remove('hidden');
                mainVid.src = mediaUrl;
                mainVid.play().catch(() => {});
            } else {
                if (mainVid) {
                    mainVid.pause();
                    mainVid.classList.add('hidden');
                }
                mainImg.classList.remove('hidden');
                mainImg.style.opacity = '0';
                setTimeout(() => {
                    mainImg.src = mediaUrl;
                    mainImg.style.opacity = '1';
                }, 150);
            }
        }

        // ==========================================
        // ADD / EDIT / DELETE PRODUCT ACTIONS
        // ==========================================
        function openAddProductModal(defaultSectionSlug = '') {
            document.getElementById('product-modal-title').textContent = 'Add New Fragrance Product';
            document.getElementById('prod-id').value = '';
            document.getElementById('product-form').reset();

            // Clear photo preview indicators
            for (let i = 0; i < 6; i++) {
                const el = document.getElementById(`prod-img-${i}`);
                if (el) el.value = '';
                const st = document.getElementById(`upload-status-${i}`);
                if (st) st.textContent = '';
                previewImageInput(i);
            }

            // Pre-select category if passed
            if (defaultSectionSlug) {
                document.getElementById('prod-category').value = defaultSectionSlug;
            }

            const initCount = document.getElementById('prod-image-count') ? document.getElementById('prod-image-count').value : 1;
            updateImageSlotsCount(initCount);
            openModal('product-modal');
        }

        function openEditProductModal(productId) {
            const product = productsList.find(p => p.id === productId);
            if (!product) return;

            document.getElementById('product-modal-title').textContent = 'Edit Fragrance Product';
            document.getElementById('prod-id').value = product.id;
            document.getElementById('prod-name').value = product.name || '';
            document.getElementById('prod-description').value = product.description || '';
            document.getElementById('prod-mrp').value = product.mrp || '';
            document.getElementById('prod-price').value = product.price || '';
            document.getElementById('prod-stock').value = product.stock || '';
            document.getElementById('prod-fragrance').value = product.fragrance || '';

            // Set Category
            const catSlug = product.category ? product.category.slug : (product.category_id === 3 ? 'incense-cones' : 'incense-sticks');
            document.getElementById('prod-category').value = catSlug;

            // Set images and update previews
            const images = Array.isArray(product.images) ? product.images : [];
            const activeCount = Math.min(6, Math.max(1, images.length || 1));
            const countSelect = document.getElementById('prod-image-count');
            if (countSelect) countSelect.value = activeCount;
            updateImageSlotsCount(activeCount);

            for (let i = 0; i < 6; i++) {
                const input = document.getElementById(`prod-img-${i}`);
                if (input) input.value = images[i] || '';
                const st = document.getElementById(`upload-status-${i}`);
                if (st) st.textContent = '';
                previewImageInput(i);
            }

            openModal('product-modal');
        }

        async function handleProductSubmit(e) {
            e.preventDefault();
            const id = document.getElementById('prod-id').value;
            const catSlug = document.getElementById('prod-category').value;
            const category = categoriesList.find(c => c.slug === catSlug);
            const categoryId = category ? category.id : (catSlug === 'incense-cones' ? 3 : 4);

            const images = [];
            const maxSlots = parseInt(document.getElementById('prod-image-count')?.value || '6', 10);
            for (let i = 0; i < maxSlots; i++) {
                const val = (document.getElementById(`prod-img-${i}`)?.value || '').trim();
                if (val) images.push(val);
            }

            const payload = {
                name: document.getElementById('prod-name').value.trim(),
                category_id: categoryId,
                description: document.getElementById('prod-description').value.trim(),
                mrp: parseFloat(document.getElementById('prod-mrp').value),
                price: parseFloat(document.getElementById('prod-price').value),
                stock: parseInt(document.getElementById('prod-stock').value),
                fragrance: document.getElementById('prod-fragrance').value.trim(),
                images: images,
                is_active: true
            };

            const btnSave = document.getElementById('btn-save-product');
            btnSave.disabled = true;
            btnSave.textContent = 'Saving...';

            try {
                const url = id ? `${API_BASE}/admin/products/${id}` : `${API_BASE}/admin/products`;
                const method = id ? 'PUT' : 'POST';

                const res = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${adminToken}`
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    showToast(id ? 'Product updated successfully.' : 'Product created successfully.');
                    closeModal('product-modal');
                    await loadProducts();
                    fetchLiveFeedPreview();
                } else {
                    alert(data.message || 'Error saving product.');
                }
            } catch (err) {
                alert('Network error while saving product.');
            } finally {
                btnSave.disabled = false;
                btnSave.textContent = 'Save Product';
            }
        }

        function confirmDeleteProduct(id, title) {
            productToDeleteId = id;
            document.getElementById('delete-prod-title').textContent = title;
            openModal('delete-modal');
        }

        async function executeDeleteProduct() {
            if (!productToDeleteId) return;
            const btn = document.getElementById('btn-confirm-delete');
            btn.disabled = true;
            btn.textContent = 'Deleting...';

            try {
                const res = await fetch(`${API_BASE}/admin/products/${productToDeleteId}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${adminToken}`,
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    showToast('Product deleted from inventory.');
                    closeModal('delete-modal');
                    await loadProducts();
                    fetchLiveFeedPreview();
                } else {
                    alert(data.message || 'Error deleting product.');
                }
            } catch (err) {
                alert('Network error deleting product.');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Delete Forever';
                productToDeleteId = null;
            }
        }

        // ==========================================
        // ENCRYPTED FEED PREVIEW
        // ==========================================
        async function fetchLiveFeedPreview() {
            const encBox = document.getElementById('encrypted-feed-box');
            const decBox = document.getElementById('decrypted-feed-box');
            if (!encBox || !decBox) return;

            try {
                const res = await fetch(`${API_BASE}/feed`);
                const data = await res.json();
                if (data.success && data.data) {
                    encBox.textContent = data.data.encrypted_payload || 'No encrypted payload returned.';
                    decBox.textContent = JSON.stringify({
                        total_products: data.data.total_products,
                        encryption: data.data.encryption,
                        sections: data.data.sections,
                        products_sample: data.data.products.slice(0, 2)
                    }, null, 2);
                }
            } catch (err) {
                encBox.textContent = 'Error loading live feed.';
            }
        }

        // ==========================================
        // UTILITIES & MODAL HELPERS
        // ==========================================
        function openModal(id) {
            const m = document.getElementById(id);
            if (m) m.classList.add('open');
        }

        function closeModal(id) {
            const m = document.getElementById(id);
            if (m) m.classList.remove('open');
        }

        function showToast(msg) {
            const t = document.getElementById('toast');
            document.getElementById('toast-msg').textContent = msg;
            t.classList.add('show');
            setTimeout(() => t.classList.remove('show'), 3500);
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        function filterUsersTable() {
            const q = (document.getElementById('search-users')?.value || '').toLowerCase();
            paginationState.users.filteredList = usersList.filter(u => 
                (u.name && u.name.toLowerCase().includes(q)) || 
                (u.email && u.email.toLowerCase().includes(q)) || 
                (u.phone && u.phone.includes(q))
            );
            paginationState.users.page = 1;
            renderPaginatedTable('users');
        }

        function filterAllProductsTable() {
            const q = (document.getElementById('search-products')?.value || '').toLowerCase();
            paginationState.all.filteredList = productsList.filter(p => 
                (p.name && p.name.toLowerCase().includes(q)) || 
                (p.fragrance && p.fragrance.toLowerCase().includes(q)) ||
                (p.description && p.description.toLowerCase().includes(q))
            );
            paginationState.all.page = 1;
            renderPaginatedTable('all');
        }

        function filterConesTable() {
            const q = (document.getElementById('search-cones')?.value || '').toLowerCase();
            const cones = productsList.filter(p => (p.category && p.category.slug === 'incense-cones') || p.category_id === 3);
            paginationState.cones.filteredList = cones.filter(p => 
                (p.name && p.name.toLowerCase().includes(q)) || 
                (p.fragrance && p.fragrance.toLowerCase().includes(q)) ||
                (p.description && p.description.toLowerCase().includes(q))
            );
            paginationState.cones.page = 1;
            renderPaginatedTable('cones');
        }

        function filterSticksTable() {
            const q = (document.getElementById('search-sticks')?.value || '').toLowerCase();
            const sticks = productsList.filter(p => (p.category && p.category.slug === 'incense-sticks') || p.category_id === 4);
            paginationState.sticks.filteredList = sticks.filter(p => 
                (p.name && p.name.toLowerCase().includes(q)) || 
                (p.fragrance && p.fragrance.toLowerCase().includes(q)) ||
                (p.description && p.description.toLowerCase().includes(q))
            );
            paginationState.sticks.page = 1;
            renderPaginatedTable('sticks');
        }

        // ==========================================
        // CONTACT MESSAGES MANAGEMENT
        // ==========================================
        let contactsList = [];

        async function loadContacts() {
            try {
                const res = await apiGet('/admin/contact-messages?per_page=100');
                if (res.success && res.data) {
                    contactsList = res.data;
                    paginationState.contacts.filteredList = contactsList;
                    renderPaginatedTable('contacts');

                    const unread = contactsList.filter(c => !c.is_read).length;
                    const badge = document.getElementById('badge-contacts-count');
                    if (badge) badge.textContent = unread > 0 ? `${unread} New` : contactsList.length;
                    const countEl = document.getElementById('contacts-catalog-count');
                    if (countEl) countEl.textContent = `${contactsList.length} Inquiries`;
                    const metricEl = document.getElementById('metric-contacts');
                    if (metricEl) metricEl.textContent = contactsList.length;
                }
            } catch (err) {
                console.error('Failed to load contacts', err);
            }
        }

        function renderContactsTableRows(items) {
            const tbody = document.getElementById('contacts-table-body');
            if (!tbody) return;

            if (items.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align: center; color: var(--text-dim); padding: 32px;">No customer inquiries received yet.</td></tr>`;
                return;
            }

            tbody.innerHTML = items.map(c => {
                const dateStr = c.created_at ? new Date(c.created_at).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '—';
                const statusBadge = c.is_read
                    ? `<span class="badge badge-slate">Read</span>`
                    : `<span class="badge badge-gold">New</span>`;

                return `
                    <tr style="${!c.is_read ? 'background: rgba(212, 175, 55, 0.03);' : ''}">
                        <td style="color: var(--text-muted); font-size: 12px; white-space: nowrap;">
                            <div style="font-weight: 500; color: #fff;">${dateStr.split(',')[0] || dateStr}</div>
                            <div style="font-size: 11px; color: var(--text-dim);">${dateStr.split(',')[1] || ''}</div>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #fff; font-size: 13.5px;">${escapeHtml(c.name || 'Anonymous')}</div>
                        </td>
                        <td>
                            <div style="font-size: 13px; color: var(--text-main); font-weight: 500;">
                                <a href="mailto:${escapeHtml(c.email || '')}" style="color: var(--text-main); text-decoration: none;">${escapeHtml(c.email || '—')}</a>
                            </div>
                            ${c.phone ? `<div style="font-size: 11.5px; color: var(--gold); font-family: var(--font-mono); margin-top: 4px; display: flex; align-items: center; gap: 4px;"><svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>${escapeHtml(c.phone)}</div>` : ''}
                        </td>
                        <td>
                            <span class="badge badge-slate" style="font-size: 11.5px; font-weight: 500;">
                                ${escapeHtml(c.subject || 'Public Inquiry')}
                            </span>
                        </td>
                        <td>
                            <div style="font-size: 13px; color: var(--text-main); line-height: 1.5; word-break: break-word;">
                                ${escapeHtml(c.message || '')}
                            </div>
                        </td>
                        <td>${statusBadge}</td>
                        <td style="text-align: center;">
                            <div class="action-btn-group" style="justify-content: center;">
                                ${!c.is_read ? `
                                <button type="button" class="btn-icon btn-icon-gold" title="Mark as Read" onclick="markContactAsRead(${c.id})">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </button>` : ''}
                                <button type="button" class="btn-icon btn-icon-danger" title="Delete Inquiry" onclick="deleteContactMsg(${c.id})">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        async function markContactAsRead(id) {
            try {
                const res = await fetch(`${API_BASE}/admin/contact-messages/${id}/mark-read`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${adminToken}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                const data = await res.json();
                if (data.success) {
                    showToast('Message marked as read.');
                    loadContacts();
                }
            } catch (err) {
                console.error(err);
            }
        }

        async function deleteContactMsg(id) {
            if (!confirm('Are you sure you want to delete this contact message?')) return;
            try {
                const res = await fetch(`${API_BASE}/admin/contact-messages/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${adminToken}`,
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                if (data.success) {
                    showToast('Contact message deleted.');
                    loadContacts();
                }
            } catch (err) {
                console.error(err);
            }
        }

        function filterContactsTable() {
            const q = (document.getElementById('search-contacts')?.value || '').toLowerCase();
            paginationState.contacts.filteredList = contactsList.filter(c => 
                (c.name && c.name.toLowerCase().includes(q)) || 
                (c.email && c.email.toLowerCase().includes(q)) || 
                (c.phone && c.phone.toLowerCase().includes(q)) ||
                (c.subject && c.subject.toLowerCase().includes(q)) ||
                (c.message && c.message.toLowerCase().includes(q))
            );
            paginationState.contacts.page = 1;
            renderPaginatedTable('contacts');
        }
    </script>
</body>
</html>
