@php
  $boardUsers = \App\User::all();
@endphp

<div class="modal fade" id="addBoardMembers" tabindex="-1" role="dialog" aria-labelledby="addBoardMembersLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBoardMembersLabel">Manage members</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route ('boards.syncUsers')}}">
                    @csrf
                    <div class="form-group">
                        <select name="selectMembersBoard[]" id="selectMemberBoard" multiple="multiple" class="form-control">
                            @foreach ($boardUsers as $boardUser)
                                <option value="{{ $boardUser->id }}" @if($board -> hasUser($boardUser)) selected="selected" @endif>{{ $boardUser->name }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="board_id" value="{{ $board -> id }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
