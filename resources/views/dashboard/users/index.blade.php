@extends('layouts.dashboard')

@section('css')
@stop

@section('title', 'Users | Trello clone')

@section('content')

    <h3 class="my-4">Users</h3>

    <table id="users" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Is admin</th>
                <th>Team</th>
                <th>Email</th>
                <th>Registered at</th>
            </tr>
        </thead>
    </table>

@stop


@section('js')

    <script>

        const r_users_table = '{{ route('users.table') }}';

        $(document).ready(function() {

            $('#users').DataTable({
                "pageLength": 25,
                "processing": true,
                "serverSide": true,
                "bFilter": false,
                "bInfo": false,
                "ajax":{
                    "url": r_users_table,
                    "dataType": "json",
                    "type": "POST",
                },
                "order": [[ 0, 'asc' ]],
                "columns": [
                    { "data": "name" },
                    { "data": "is_admin" },
                    { "data": "team_id" },
                    { "data": "email" },
                    { "data": "created_at" },
                ]
            });

        });


    </script>

@stop
