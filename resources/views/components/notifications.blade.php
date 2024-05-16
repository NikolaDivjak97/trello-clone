<style>

    .notifications-overlay {
        position: absolute;
        width: 100%;
        height: 100%;
        z-index: 99;
        background-color: rgba(0,0,0,0.08);
    }

    .notifications-tab {
        position: fixed;
        top: 55px;
        right: 0;
        width: 30%;
        display: flex;
        flex-direction: column;
        height: calc(100vh - 55px);
        overflow-y: scroll;
        z-index: 100;
    }

</style>

<div class="notifications-overlay" style="display: none;">
    <div class="notifications-tab card">
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
</div>
