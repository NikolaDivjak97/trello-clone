@extends('layouts.app')

@section('css')
    <style>

        output {
            width: 100%;
        }

        output img {
            width: 100%;
        }

    </style>
@endsection

@section('title', 'Boards | Trello Clone')


@section('content')
    <div class="container">
        <div class="row my-5">
            <div class="col-12 mb-2 text-light">
                <h3 class="p-3 bg-light-blue text-dark rounded w-100">Boards</h3>
            </div>
            @foreach(auth()->user()->boards as $board)
                <div class="col-3 mb-2">
                    <a href="{{ route('boards.show', $board) }}">
                        <div class="card w-100 h-75">
                            <img class="card-img object-fit-cover w-100 h-100" src="{{ asset($board -> background ? 'storage/boards/' . $board -> background : 'images/board.png') }}" alt="Card image">
                            <div class="card-img-overlay" style="background-color: rgba(0, 0, 0, 0.35);">
                                <h5 class="card-title text-white"><b>{{ $board -> name }}</b></h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-12 mb-2 text-light">
                <h3 class="p-3 bg-light-blue text-dark rounded w-100">My Boards</h3>
            </div>
            @foreach(auth()->user()->myBoards as $myBoard)
                <div class="col-3">
                    <a href="{{ route('boards.show', $myBoard) }}">
                        <div class="card w-100 h-75">
                            <img class="card-img object-fit-cover w-100 h-100" src="{{ asset($myBoard -> background ? 'storage/boards/' . $myBoard -> background : 'images/board.png') }}" alt="Card image">
                            <div class="card-img-overlay" style="background-color: rgba(0, 0, 0, 0.35);">
                                <h5 class="card-title text-white" ><b>{{ $myBoard -> name }}</b></h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    @include('modals.add-new-board')
    @include('modals.add-new-team')

@endsection
