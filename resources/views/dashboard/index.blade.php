@extends('layouts.dashboard')

@section('css')
@stop

@section('title', 'Dashboard | Trello clone')

@section('content')

    <h3 class="my-4">User activity</h3>

    <table id="user-events" class="table table-striped" style="width:100%">
        <thead>
        <tr>
            <th>User</th>
            <th>Event</th>
            <th>Time</th>
        </tr>
        </thead>
    </table>

    <h3 class="my-4">Notifications</h3>

    <form id="notifications-filter" action="" method="POST">
        <div class="row mb-4">

            <div class="col-12 col-md-3">
                <div class="form-group">
                    <label for="notification_type">Notification type</label>
                    <select name="notification_type" id="notification_type" class="form-control form-control-sm">
                        <option value="">All</option>
                        <option value="{{ \App\Board::class }}">Board</option>
                        <option value="{{ \App\Card::class }}">Card</option>
                        <option value="{{ \App\Comment::class }}">Comment</option>
                    </select>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="form-group">
                    <label for="owner">Notification owner</label>
                    <select name="owner" id="owner" class="form-control form-control-sm">
                        <option value="">All</option>
                        @foreach($users as $user)
                            <option value="{{ $user -> id }}">{{ $user -> name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="form-group">
                    <label for="event">Event</label>
                    <select name="event" id="event" class="form-control form-control-sm">
                        <option value="">All</option>
                        <option value="created">Created</option>
                        <option value="update_phase">Update Phase</option>
                        <option value="update_members">Update members</option>
                        <option value="new_attachment">New Attachment</option>
                        <option value="new_images">New images</option>
                        <option value="new_comment">New comment</option>
                    </select>
                </div>
            </div>

            <div class="col-12 col-md-2">
                <div class="form-group">
                    <button type="submit" style="margin-top: 32px;" class="btn btn-sm btn-block btn-primary"><i class="fas fa-filter"></i> Filter</button>
                </div>
            </div>

        </div>

    </form>

    <table id="user-activity" class="table table-striped" style="width:100%">
        <thead>
        <tr>
            <th>Created notification</th>
            <th>Notified user</th>
            <th>Message</th>
            <th>Event</th>
            <th>Link</th>
            <th>Time</th>
        </tr>
        </thead>
    </table>
@stop


@section('js')

    <script>

        const r_events_table = '{{ route('events.table') }}';
        const r_notifications_table = '{{ route('notifications.table') }}';

        $(document).ready(function() {

            const eventsTable = $('#user-events').DataTable({
                "pageLength": 25,
                "processing": true,
                "serverSide": true,
                "bFilter": false,
                "bInfo": false,
                "ajax":{
                    "url": r_events_table,
                    "dataType": "json",
                    "type": "POST",
                },
                "order": [[ 0, 'asc' ]],
                "columns": [
                    { "data": "user_id" },
                    { "data": "message" },
                    { "data": "created_at" },
                ]
            });

            const notificationsTable = $('#user-activity').DataTable({
                "pageLength": 25,
                "processing": true,
                "serverSide": true,
                "bFilter": false,
                "bInfo": false,
                "ajax":{
                    "url": r_notifications_table,
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d){
                        d.notification_type = $('#notification_type').val();
                        d.event = $('#event').val();
                        d.owner = $('#owner').val();
                    }
                },
                "order": [[ 0, 'asc' ]],
                "columns": [
                    { "data": "owner_id" },
                    { "data": "user_id" },
                    { "data": "message" },
                    { "data": "event" },
                    { "data": "link" },
                    { "data": "created_at" },
                ]
            });

            $('#notifications-filter').on('submit', function(e) {
                e.preventDefault();

                notificationsTable.ajax.reload();
            });

        });


    </script>

@stop
