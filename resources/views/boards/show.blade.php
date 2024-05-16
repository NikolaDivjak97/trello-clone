@extends('layouts.app')

@section('title', $board -> name . ' | Trello clone')
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/richtext.min.css') }}">

    <style>
    .lists-wrapper {
        background-image: url('{{ asset ( $board -> background ? "storage/boards/" . $board -> background : "images/default-board.jpg") }}');
    }

    .board-name {
        position: relative;
        z-index: 10;
        backdrop-filter: blur(10px);
    }

    .modal-xl {
        min-width: 80%;
    }
</style>
@endsection

@section('content')

<div class="">

    <div class="lists-wrapper bg-white text-dark">
        <div class="board-name p-3 bg-transparent text-white d-flex justify-content-between align-items-center">
            <h1 class="mb-0">{{ $board -> name }}</h1>
            <div class="board-options d-flex align-items-center">
                @if(auth()->id() === $board -> user_id)
                    <a class="btn btn-sm btn-primary mr-2" data-toggle="modal" data-target="#addBoardMembers">Manage members</a>
                @endif
                <a class="btn btn-sm btn-primary mr-2" data-toggle="modal" data-target="#addLabelModal">Add label</a>
                <div class="dropdown">
                    <button class="btn btn-sm btn-primary" type="button" data-toggle="dropdown">
                        <i class="fa-solid fa-ellipsis"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right text-center p-2">
                        @if(auth()->user()->id == $board -> owner -> id)
                            <form id="delete-board" action="{{route('boards.destroy', $board) }}" method="POST">

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-block btn-outline-danger" ><b>Delete board</b></button>
                            </form>
                        @else
                            <form id="leave-board" action="{{route('boards.leave', $board) }}" method="POST">

                                @csrf

                                <button type="submit" class="btn btn-block btn-outline-danger" ><b>Leave board</b></button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="container-xll h-100 phases_wrapper px-3 py-1">
            <div class="row flex-nowrap">

                @foreach ($board -> phases as $phase)
                    @include('phases.one-phase', ['phase' => $phase])
                @endforeach

                <div class="col min-h">
                    <div class="card card-board">
                        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center gap-2 cursor" data-toggle="modal" data-target="#addListModal">
                            <img width="24" height="24" src="{{ asset('images/plus.png') }}" alt="">
                            <p class="m-0">Add another list</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modals.add-new-list')
    @include('modals.add-new-label')
    @include('modals.add-members-board')
    @include('modals.add-new-task')

</div>

<div class="modal fade" id="taskDetailsModal" tabindex="-1" role="dialog" aria-labelledby="taskDetailsModalLabel"
    aria-hidden="true">
</div>
@endsection

@section('js')
    <script>
        const card_store_url = "{{ route ('cards.store') }}";
        const r_update_phase = "{{ route('cards.updatePhase') }}";
        const r_update_members = "{{ route('cards.updateCardMembers') }}";
        const r_add_comment = "{{ route('cards.addComment') }}";
        const r_add_images = "{{ route('cards.addImages') }}";
        const r_add_attachment = "{{ route('cards.addAttachment') }}";
        const r_get_phase_column = "{{ route('phases.getCardsComponent') }}";

    </script>

    <script src="{{ asset ('js/board.js') }}"></script>
    <script src="{{ asset('js/jquery.richtext.js') }}" defer></script>
@endsection
