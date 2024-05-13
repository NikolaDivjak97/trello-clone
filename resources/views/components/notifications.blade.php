<style>

    .notifications-tab {
        position: absolute;
        top: 55px;
        right: 0;
        width: 30%;
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow-y: scroll;
        z-index: 100;
    }

</style>

<div class="notifications-tab card" style="display: none;">
    <card class="card-header">
        <h4 class="mb-0">Notifications</h4>
    </card>
    <div class="card-body">
        @forelse($notifications as $notification)
            <div class="notification mb-4">
                <a href="{{$notification -> link}}">
                    <div class="card bg-secondary text-white">
                        <div class="card-header">
                            {{ $notification -> message }}
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <h4>No New Notifications!</h4>
        @endforelse
    </div>
</div>
