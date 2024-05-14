<div class="col flex-grow-0 flex-shrink-1 phase_column pb-4" data-phase="{{ $phase -> id }}">
    <div class="card card-board">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <span>{{ $phase -> name }}</span>
            <button type="button" class="btn btn-sm btn-primary add-task" data-phaseId="{{ $phase -> id }}"
                data-boardId="{{ $phase -> board -> id }}" data-userId="{{ Auth::user() -> id }}" data-toggle="modal"
                data-target="#addTaskModal">
                Add Task
            </button>
        </div>
        <ul class="list-group list-group-flush overflow-auto max-h sortable">
            @foreach ($phase -> cards() -> orderBy('order', 'asc')->orderBy('updated_at', 'asc')->get() as $card)
                <li class=" list-group-item cursor open-task draggable li-width" data-card="{{ $card -> id }}"
                    data-url="{{ route ('cards.getData') }}">{{$card -> name }} <br>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <span class="">{!! $card -> difficulty !!} </span>
                        <div>
                            @foreach($card->users as $member)
                                <span class="badge rounded-pill bg-orange p-2 m-03">{{ $member -> initials}}</span>
                            @endforeach
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
