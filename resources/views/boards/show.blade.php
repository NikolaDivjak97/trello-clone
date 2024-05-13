@extends('layouts.app')

@section('title', $board -> name . ' | TROLLO')
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/richtext.min.css') }}">

    <style>
    .lists-wrapper {
        background-image: url('{{ asset ( $board -> background ? "storage/boards/" . $board -> background : "images/default-board.jpg") }}');
    }

    .board-name {
        backdrop-filter: blur(10px);
    }

    #mentionDropdown {
        display: none;
        position: absolute;
        background-color: white;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        z-index: 1;
    }

    #mentionDropdown div {
        padding: 8px;
        cursor: pointer;
    }

    #mentionDropdown div:hover {
        background-color: #f2f2f2;
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
            <div class="board-options">
                @if(auth()->id() === $board -> user_id)
                    <a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addBoardMembers">Manage members</a>
                @endif
                <a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addLabelModal">Add label</a>
            </div>
        </div>

        <div class="container-xll h-100 overflow-auto p-3">
            <div class="row flex-nowrap ">

                @foreach ($board -> phases as $phase)
                    @include('phases.one-phase', ['phase' => $phase])
                @endforeach

                <div class="col">
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

<div id="mentionDropdown">
    @foreach($users as $user)
        <div class="mention">{{ $user -> name }}</div>
    @endforeach
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
