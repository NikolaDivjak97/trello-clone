@extends('layouts.dashboard')

@section('css')
@stop

@section('title', 'Teams | Trello clone')

@section('content')

    <h3 class="my-4">Teams</h3>

    <table id="teams-table" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Users</th>
                <th>Created at</th>
            </tr>
        </thead>
    </table>

@stop

@section('js')

    <script>

        const r_teams_table = '{{ route('teams.table') }}';

        $(document).ready(function() {

            $('#teams-table').DataTable({
                "pageLength": 25,
                "processing": true,
                "serverSide": true,
                "bFilter": false,
                "bInfo": false,
                "ajax":{
                    "url": r_teams_table,
                    "dataType": "json",
                    "type": "POST",
                },
                "order": [[ 0, 'asc' ]],
                "columns": [
                    { "data": "name" },
                    { "data": "description" },
                    { "data": "users" },
                    { "data": "created_at" },
                ]
            });

        });

    </script>

@stop
