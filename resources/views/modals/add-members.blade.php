@php
  $users = $card->board->users;
@endphp

<div class="dropdown-menu w-100 p-0 border border-gray" aria-labelledby="addMembers">
    <div class="form-group">
        <input type="text" id="memberSearch" class="form-control border-top-0" placeholder="Search members">
    </div>
    <div class="member-list selected-members d-flex flex-column justify-content-center mt-2">
        @foreach ($users->whereNotIn('id', $card->users()->pluck('users.id')) as $user)
        <div class="member ml-2">
            <label class="form-check mx-auto">
                <input type="checkbox" class="form-check-input align-middle" name="selectedMember"
                    value="{{ $user->id }}">
                <small class="form-check-label align-middle">{{ $user->name }}</small>
            </label>
        </div>
        @endforeach
    </div>

    <div class="modal-footer border-0">
        <button type="button" id="addMembersBtn" class="btn btn-sm btn-primary btn-block">Add</button>
    </div>
</div>

<style>
    .no-outline:focus {
        outline: none !important;
        box-shadow: none !important;
    }
</style>

<script>
    $('.member-list').on('click', function (e) {
        e.stopPropagation();
    });
</script>
