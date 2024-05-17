@extends('layouts.dashboard')

@section('css')
@stop

@section('title', 'Boards | Trello clone')

@section('content')

    <h3 class="my-4">Boards</h3>

    <table id="boards-table" class="table table-striped table-bordered" style="width:100%">
        <thead>
        <tr>
            <th>Name</th>
            <th>Owner</th>
            <th>Description</th>
            <th>Members</th>
            <th>Created at</th>
        </tr>
        </thead>
    </table>

@stop

@section('js')

    <script>

        const r_boards_table = '{{ route('boards.table') }}';

        $(document).ready(function() {

            $('#boards-table').DataTable({
                "pageLength": 25,
                "processing": true,
                "serverSide": true,
                "bFilter": false,
                "bInfo": false,
                "ajax":{
                    "url": r_boards_table,
                    "dataType": "json",
                    "type": "POST",
                },
                "order": [[ 0, 'asc' ]],
                "columns": [
                    { "data": "name" },
                    { "data": "user_id" },
                    { "data": "description" },
                    { "data": "members" },
                    { "data": "created_at" },
                ]
            });

        });

    </script>

@stop
