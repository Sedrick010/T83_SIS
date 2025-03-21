<div class="dropdown">
    <a href="#" class="nav-link" data-bs-toggle="dropdown" id="notificationsDropdown" role="button">
        <i class="material-icons">notifications</i>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <span class="notification">{{ auth()->user()->unreadNotifications->count() }}</span>
        @endif
    </a>
    <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="notificationsDropdown">
        @forelse(auth()->user()->notifications()->latest()->limit(5)->get() as $notification)
            <li class="mb-2">
                <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                        <div class="d-flex flex-column justify-content-center">
                            <h6 class="text-sm font-weight-normal mb-1">
                                <span class="font-weight-bold">{{ $notification->data['title'] }}</span>
                            </h6>
                            <p class="text-xs text-secondary mb-0">
                                {{ $notification->data['message'] }}
                            </p>
                            @if(isset($notification->data['student_id']))
                                <p class="text-xs text-primary mb-0">
                                    Student ID: {{ $notification->data['student_id'] }}
                                </p>
                            @endif
                            <p class="text-xs text-secondary mb-0">
                                {{ \Carbon\Carbon::parse($notification->data['time'])->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </a>
            </li>
        @empty
            <li class="mb-2">
                <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                        <div class="d-flex flex-column justify-content-center">
                            <p class="text-sm font-weight-normal mb-0">
                                No notifications
                            </p>
                        </div>
                    </div>
                </a>
            </li>
        @endforelse
        @if(auth()->user()->notifications->count() > 5)
            <li>
                <a class="dropdown-item border-radius-md text-center" href="#">
                    View all
                </a>
            </li>
        @endif
    </ul>
</div> 