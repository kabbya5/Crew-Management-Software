
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title>Maritime Unified HRMS — Crew Portal &amp; Office Operations</title>

    <link rel="stylesheet" href="{{ asset('asset/css/admin.css') }}">

    <script src="{{asset('asset/jquery/jquery-4.0.js')}}"> </script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    @yield('link')

    <style>
        /* Notification wrapper */
        .notification-wrapper {
            position: relative;
        }

        /* Notification button */
        .notification-btn {
            position: relative;
            width: 42px;
            height: 42px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #fff;
            color: #333;
            cursor: pointer;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: .2s;
        }

        .notification-btn:hover {
            background: #f1f5f9;
        }

        /* Red count badge */
        .notification-count {
            position: absolute;
            top: -7px;
            right: -7px;
            min-width: 19px;
            height: 19px;
            padding: 0 4px;
            background: #ef4444;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            border: 2px solid #fff;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

        /* Dropdown */
        .notification-dropdown {
            display: none;
            position: absolute;
            top: 52px;
            right: 0;
            width: 350px;
            max-width: calc(100vw - 30px);
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, .15);
            z-index: 99999;
            color: #222;
            overflow: hidden;
        }

        .notification-dropdown.show {
            display: block;
        }

        /* Header */
        .notification-header {
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #eee;
        }

        .notification-header strong {
            font-size: 15px;
        }

        .notification-header span {
            font-size: 12px;
            color: #64748b;
        }

        /* Notification list */
        .notification-list {
            max-height: 350px;
            overflow-y: auto;
        }

        .notification-item {
            display: flex;
            gap: 12px;
            padding: 14px;
            border-bottom: 1px solid #eee;
            transition: .2s;
        }

        .notification-item:hover {
            background: #f8fafc;
        }

        .notification-icon {
            width: 36px;
            height: 36px;
            flex-shrink: 0;
            background: #eff6ff;
            color: #2563eb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .notification-content {
            flex: 1;
            min-width: 0;
        }

        .notification-content strong {
            font-size: 13px;
            display: block;
        }

        .notification-content p {
            font-size: 12px;
            margin: 5px 0;
            color: #64748b;
            line-height: 1.5;
        }

        .notification-content small {
            font-size: 11px;
            color: #94a3b8;
        }

        /* Empty notification */
        .notification-empty {
            padding: 30px;
            text-align: center;
            color: #94a3b8;
        }

        .notification-empty i {
            font-size: 25px;
        }

        .notification-empty p {
            margin-bottom: 0;
            font-size: 13px;
        }

        @media (max-width: 600px) {
            .notification-dropdown {
                position: fixed;
                top: 75px;
                right: 12px;
                width: 350px;
            }
        }
    </style>
</head>

<body class="">

    <aside>

        <h2>
            MARITIME
            <span style="color:#62d4ce">HRMS</span>
        </h2>

        <p>UNIFIED CREW OPERATIONS • V2.0</p>

        <nav id="nav">

            <a class="btn" id="nav-dashboard" href="{{route('admin.dashboard')}}">
                Overview
            </a>

            <a class="btn" href="{{route('admin.crews.correction.list')}}">
                New submissions &amp; updates
            </a>

            <a class="btn" href="{{ route('crews.create', ['admin_create' => 1]) }}" id="nav-registration">
                Register crew — approved CV
            </a>

            <a id="nav-directory" class="btn" href="{{route('admin.crews.index')}}">
                Central crew database
            </a>

            {{-- <button id="nav-cadets"
                onclick="go('cadets')">
                Cadet program
            </button>

            <button id="nav-experienced"
                onclick="go('experienced')">
                Experienced recruitment
            </button>

            <button id="nav-vacancies"
                onclick="go('vacancies')">
                Vacancies &amp; matching
            </button>

            <button id="nav-applications"
                onclick="go('applications')">
                Recruitment pipeline
            </button>

            <button id="nav-onboard"
                onclick="go('onboard')">
                Onboard &amp; contracts
            </button>

            <button id="nav-payroll"
                onclick="go('payroll')">
                Payroll &amp; dispatch
            </button>

            <button id="nav-rejoining"
                onclick="go('rejoining')">
                Leave &amp; rejoining
            </button>

            <button id="nav-alerts"
                onclick="go('alerts')">
                Documents &amp; alerts
            </button>

            <button id="nav-tasks"
                onclick="go('tasks')">
                Next-action queue
            </button>

            <button id="nav-logs"
                onclick="go('logs')">
                Coordination log
            </button>

            <a id="nav-reports"
                onclick="go('reports')">
                Reports &amp; backup
            </a>

            <button id="nav-settings"
                onclick="go('settings')">
                Company &amp; roles
            </button>

            <button id="nav-developer"
                onclick="go('developer')">
                Developer handover
            </button>

            <button id="nav-guide"
                onclick="go('guide')">
                বাংলা ব্যবহার নির্দেশিকা
            </button> --}}

        </nav>

        <p style="margin-top:25px">
            DEVELOPER HANDOVER<br>
            17 November 2026<br>
            Fictional demonstration records
        </p>

    </aside>

    <main>

        <header>

            <div>
                <strong>Maritime HRMS • Office operations</strong><br>
                <small>Bangladesh · China · Dubai</small>
            </div>

            <div class="actions">

                <span class="badge warn">
                    LOCAL DEMO
                </span>

                <!-- Notification -->
                <div class="notification-wrapper">

                    <button type="button"
                        class="notification-btn"
                        id="notificationButton"
                        onclick="toggleNotifications()">

                        <i class="fa-regular fa-bell"></i>

                        @if($unreadCount > 0)
                            <span class="notification-count">
                                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                            </span>
                        @endif

                    </button>

                    <!-- Notification dropdown -->
                    <div class="notification-dropdown"
                        id="notificationDropdown">

                        <div class="notification-header">

                            <strong>
                                <i class="fa-regular fa-bell"></i>
                                Notifications
                            </strong>

                            <span>
                                {{ $unreadCount }} unread
                            </span>

                        </div>

                        <div class="notification-list">

                            @forelse($notifications as $notification)

                                <a href="{{route('admin.notifications.read', $notification->id)}}" class="notification-item">

                                    <div class="notification-icon">

                                        @if(($notification->data['type'] ?? '') === 'password_reset_requested')
                                            <i class="fa-solid fa-lock"></i>
                                        @else
                                            <i class="fa-solid fa-bell"></i>
                                        @endif

                                    </div>

                                    <div class="notification-content">

                                        <strong>
                                            {{ $notification->data['title'] ?? 'Notification' }}
                                        </strong>

                                        <p>
                                            {{ $notification->data['message'] ?? '' }}
                                        </p>

                                        @if(!empty($notification->data['phone_number']))
                                            <p>
                                                Phone:
                                                {{ $notification->data['phone_number'] }}
                                            </p>
                                        @endif

                                        <small>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </small>

                                    </div>

                                </a>

                            @empty

                                <div class="notification-empty">

                                    <i class="fa-regular fa-bell-slash"></i>

                                    <p>No new notifications</p>

                                </div>

                            @endforelse

                        </div>

                    </div>

                </div>
            </div>

        </header>

        @yield('content')

    </main>

    <script>
        function toggleNotifications() {

            const dropdown = document.getElementById(
                'notificationDropdown'
            );

            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {

            const wrapper = document.querySelector(
                '.notification-wrapper'
            );

            const dropdown = document.getElementById(
                'notificationDropdown'
            );

            if (wrapper && !wrapper.contains(event.target)) {
                dropdown.classList.remove('show');
            }

        });
    </script>

    @yield('js')

</body>

</html>
