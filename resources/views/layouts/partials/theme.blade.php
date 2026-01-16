<style>
    :root {
        --brand-primary: #2563eb;
        --brand-primary-dark: #1e3a8a;
        --brand-accent: #10b981;
        --brand-warning: #f97316;
        --surface: #ffffff;
        --surface-muted: #f5f7fb;
        --surface-contrast: #0f172a;
        --text-color: #0f172a;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
         --sidebar-bg: #0f172a;
        --sidebar-link: rgba(255, 255, 255, 0.85);
        --sidebar-link-hover: #ffffff;
        --sidebar-muted: rgba(255, 255, 255, 0.6);
        --sidebar-border: rgba(255, 255, 255, 0.08);
        --sidebar-width: 280px;
    }

    * {
        font-family: 'Poppins', 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
    }

    body {
        background: var(--surface-muted);
        color: var(--text-color);
        line-height: 1.6;
    }

    h1, h2, h3, h4, h5, h6 {
        font-weight: 600;
        color: var(--surface-contrast);
    }

    .card,
    .modern-panel,
    .stat-card,
    .modal-content {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: 18px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
    }

    .btn-primary,
    .bg-primary {
        background-color: var(--brand-primary) !important;
        border-color: var(--brand-primary) !important;
        color: #fff !important;
    }

    .btn-primary:hover,
    .bg-primary:hover {
        background-color: var(--brand-primary-dark) !important;
        border-color: var(--brand-primary-dark) !important;
    }

    .btn-outline-primary {
        border-color: var(--brand-primary);
        color: var(--brand-primary);
    }

    .btn-outline-primary:hover {
        background: var(--brand-primary);
        color: #fff;
    }

    .form-control,
    .form-select {
        border-radius: 12px;
        border: 1px solid var(--border-color);
        padding: 0.65rem 0.9rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .navbar,
    .topbar,
    nav.navbar {
        background: var(--surface);
        border-bottom: 1px solid var(--border-color);
    }

    .table thead th {
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-size: 0.75rem;
        color: var(--text-muted);
        border-bottom: 1px solid var(--border-color);
        background: var(--surface-muted);
    }

    .table {
        border-collapse: separate !important;
        border-spacing: 0 14px !important;
        background: transparent;
    }

    .table tbody tr {
        border: none;
        box-shadow: inset 0 0 0 1px var(--border-color);
        border-radius: 18px;
        background: var(--surface);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .table tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: inset 0 0 0 1px rgba(37, 99, 235, 0.25), 0 10px 25px rgba(15, 23, 42, 0.08);
    }

    .table tbody td {
        border: none;
        padding: 1rem 1.25rem;
        vertical-align: middle;
        color: var(--text-color);
    }

    .table tbody tr td:first-child {
        border-top-left-radius: 18px;
        border-bottom-left-radius: 18px;
    }

    .table tbody tr td:last-child {
        border-top-right-radius: 18px;
        border-bottom-right-radius: 18px;
    }

    .table<tbody tr + tr {
        margin-top: 10px;
    }

    .table .avatar-chip {
        width: 40px;
        height: 40px;
        border-radius: 14px;
        background: var(--surface-muted);
        color: var(--brand-primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }

    .table-wrapper {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 16px 30px rgba(15, 23, 42, 0.08);
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        border-radius: 11px;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.45rem 0.95rem;
        border: 1px solid transparent;
        transition: background 0.2s ease, color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        text-decoration: none;
    }

    .action-btn i {
        font-size: 0.85rem;
    }

    .action-btn:focus-visible {
        outline: 2px solid rgba(37, 99, 235, 0.35);
        outline-offset: 2px;
    }

    .action-btn--view {
        background: rgba(37, 99, 235, 0.1);
        color: var(--brand-primary);
        border-color: rgba(37, 99, 235, 0.2);
    }

    .action-btn--edit {
        background: rgba(249, 179, 0, 0.12);
        color: #915400;
        border-color: rgba(250, 204, 21, 0.35);
    }

    .action-btn--delete {
        background: rgba(239, 68, 68, 0.12);
        color: #b91c1c;
        border-color: rgba(239, 68, 68, 0.25);
    }

    .action-btn--disabled {
        background: rgba(148, 163, 184, 0.2);
        color: #475569;
        border-color: rgba(148, 163, 184, 0.3);
        cursor: not-allowed;
    }

    .action-btn--view:hover:not(.action-btn--disabled),
    .action-btn--edit:hover:not(.action-btn--disabled),
    .action-btn--delete:hover:not(.action-btn--disabled) {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.12);
        color: #fff;
    }

    .action-btn--view:hover:not(.action-btn--disabled) {
        background: var(--brand-primary);
    }

    .action-btn--edit:hover:not(.action-btn--disabled) {
        background: #facc15;
        color: #1f2937;
    }

    .action-btn--delete:hover:not(.action-btn--disabled) {
        background: #ef4444;
    }

    .action-btn--icon {
        padding: 0.4rem;
        width: 38px;
        height: 38px;
    }

    .action-btn--compact {
        padding: 0.35rem 0.75rem;
        font-size: 0.8rem;
    }

    .status-pill {
        padding: 6px 14px;
        border-radius: 999px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .status-approved { background: rgba(16, 185, 129, 0.15); color: #047857; }
    .status-pending { background: rgba(249, 115, 22, 0.15); color: #9a3412; }
    .status-rejected { background: rgba(220, 53, 69, 0.15); color: #842029; }

    .has-sidebar #content-wrapper {
        margin-left: var(--sidebar-width);
        min-height: 100vh;
        transition: margin-left 0.3s ease;
    }

    @media (max-width: 991.98px) {
        .has-sidebar #content-wrapper {
            margin-left: 0;
        }
    }

    .sidebar-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(2px);
        z-index: 1040;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }

    .sidebar-overlay.show {
        opacity: 1;
        pointer-events: auto;
    }

    body.sidebar-open {
        overflow: hidden;
    }

    .sidebar-shell {
        position: fixed;
        inset: 0 auto 0 0;
        width: var(--sidebar-width);
        background: var(--brand-primary-dark);
        color: #fff;
        border-right: 1px solid rgba(37, 99, 235, 0.25);
        box-shadow: 0 25px 60px rgba(15, 23, 42, 0.55);
        z-index: 1050;
        display: flex;
        flex-direction: column;
        padding: 1.5rem 1.25rem;
        transition: transform 0.35s ease;
    }

    .sidebar-shell__inner {
        display: flex;
        flex-direction: column;
        height: 100%;
        gap: 1rem;
    }

    .sidebar-brand {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .sidebar-brand__icon {
        width: 46px;
        height: 46px;
        border-radius: 15px;
        background: rgba(255, 255, 255, 0.12);
        display: grid;
        place-items: center;
        font-size: 1.35rem;
    }

    .sidebar-brand__title {
        font-size: 1.1rem;
        margin: 0;
        line-height: 1.2;
        font-weight: 600;
    }

    .sidebar-scroll {
        flex: 1;
        overflow-y: auto;
        padding-right: 0.25rem;
    }

    .sidebar-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-scroll::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 999px;
    }

    .sidebar-section {
        margin-bottom: 1.65rem;
    }

    .sidebar-section__label {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        color: var(--sidebar-muted);
        margin-bottom: 0.85rem;
    }

    .sidebar-section__list {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }

    .sidebar-item {
        list-style: none;
    }

    .sidebar-link {
        width: 100%;
        border: none;
        background: transparent;
        color: var(--sidebar-link);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.65rem;
        padding: 0.9rem 0.95rem;
        border-radius: 14px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
    }

    .sidebar-link:hover {
        background: rgba(37, 99, 235, 0.18);
        color: #fff;
        text-decoration: none;
        transform: translateX(4px);
    }

    .sidebar-link.is-active {
        background: var(--brand-primary);
        color: #fff;
        box-shadow: 0 15px 35px rgba(37, 99, 235, 0.35);
        border: 1px solid rgba(255, 255, 255, 0.12);
    }

    .sidebar-link__icon {
        width: 34px;
        height: 34px;
        border-radius: 11px;
        background: rgba(37, 99, 235, 0.25);
        border: 1px solid rgba(37, 99, 235, 0.4);
        display: grid;
        place-items: center;
        font-size: 1rem;
        color: #fff;
    }

    .sidebar-link__chevron {
        margin-left: auto;
        font-size: 0.85rem;
        transition: transform 0.2s ease;
    }

    .sidebar-item.has-children .sidebar-link {
        width: 100%;
        justify-content: flex-start;
        text-align: left;
    }

    .sidebar-item.is-open .sidebar-link__chevron {
        transform: rotate(180deg);
    }

    .sidebar-subnav {
        margin-top: 0.4rem;
        margin-left: 0.6rem;
        padding-left: 0.85rem;
        border-left: 1px solid rgba(255, 255, 255, 0.12);
        display: none;
        flex-direction: column;
        gap: 0.35rem;
    }

    .sidebar-subnav.show {
        display: flex;
    }

    .sidebar-sublink {
        color: rgba(255, 255, 255, 0.75);
        font-size: 0.9rem;
        padding: 0.55rem 0.4rem;
        border-radius: 10px;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: color 0.2s ease, background 0.2s ease;
    }

    .sidebar-sublink:hover,
    .sidebar-sublink.is-active {
        color: #fff;
        background: rgba(37, 99, 235, 0.18);
        text-decoration: none;
    }

    .sidebar-footer {
        padding-top: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    .sidebar-footer__eyebrow {
        font-size: 0.7rem;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.65);
    }

    .sidebar-footer__text {
        font-size: 0.9rem;
        margin: 0.35rem 0 0;
        color: rgba(255, 255, 255, 0.9);
        opacity: 0.95;
    }

    @media (max-width: 991.98px) {
        .sidebar-shell {
            transform: translateX(-100%);
        }

        .sidebar-shell.is-open {
            transform: translateX(0);
        }
    }

    .dashboard-hero,
    .page-hero {
        background: linear-gradient(135deg, var(--brand-primary), var(--brand-primary-dark));
        color: #fff;
        border-radius: 24px;
        padding: 32px;
        margin-bottom: 32px;
        box-shadow: 0 20px 40px rgba(37, 99, 235, 0.35);
    }

    .chip {
        background: rgba(255, 255, 255, 0.18);
        border-radius: 999px;
        padding: 6px 14px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .bordered-section {
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 24px;
        background: var(--surface);
    }

    .page-heading {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        padding: 24px 28px;
        border-radius: 24px;
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.02), rgba(15, 23, 42, 0.08));
        border: 1px solid rgba(226, 232, 240, 0.7);
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
    }

    .page-heading__eyebrow {
        text-transform: uppercase;
        letter-spacing: 0.3em;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 0.35rem;
    }

    .page-heading__title {
        font-size: clamp(1.9rem, 2vw, 2.3rem);
        font-weight: 700;
        margin: 0;
        color: var(--surface-contrast);
    }

    .page-heading__meta {
        color: var(--text-muted);
        max-width: 520px;
        margin-top: 0.35rem;
        font-size: 1rem;
    }

    .page-heading__actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.6rem;
        align-items: center;
    }

    .section-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0 4px 0.5rem;
        border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        margin-bottom: 1.25rem;
    }

    .section-heading__title {
        display: flex;
        align-items: center;
        font-size: 1.05rem;
        font-weight: 600;
        gap: 0.6rem;
        position: relative;
        padding-left: 1rem;
    }

    .section-heading__title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 26px;
        border-radius: 999px;
        background: linear-gradient(180deg, var(--brand-primary), var(--brand-primary-dark));
    }

    .section-heading__meta {
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    @media (max-width: 768px) {
        .page-heading {
            padding: 20px;
            border-radius: 18px;
        }

        .page-heading__title {
            font-size: 1.75rem;
        }

        .section-heading {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
