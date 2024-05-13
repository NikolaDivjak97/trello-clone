<div class="modal-xl modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <div class="d-flex flex-column">
                <h5 class="modal-title" id="taskDetailsModalLabel">{{ $card->name }}</h5>
                <p>in list: <span class="capitalize-underline">{{ $card -> phase -> name }}</span></p>

                <div class="row align-items-center">
                    <div class="d-flex flex-column px-3">
                        <b class="small">Members</b>
                        <div class="members d-flex">
                            @foreach($card->users as $member)
                                <div class="badge rounded-pill bg-orange p-3 cursor" title="{{ $member -> name }}" @if($member -> icon) style="background-image: url('{{ $member -> icon }}'); background-size: contain" @endif>
                                    <p class="m-0">{{ $member -> initials }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if($card -> labels -> count())
                        <div class="d-flex flex-column px-3">
                            <b class="small">Labels</b>
                            <div class="members d-flex">
                                @foreach($card->labels as $label)
                                    <span class="badge p-2 text-white" style="background-color: {{ $label -> color }}"><b>{{
                                    $label -> name }}</b></span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="d-flex flex-column px-3">
                        <b class="small">Last Updated</b>
                        <div class="members d-flex">
                            {{ $card -> updated_at -> format('d.m.Y H:i') }}
                        </div>
                    </div>

                    @if($card -> due_date)
                        <div class="d-flex flex-column px-3">
                            <b class="small">Due date</b>
                            <div class=" d-flex">
                            <span class="badge badge-danger p-2 text-white">
                                {{ $card -> due_date -> format('d.m.Y') }}
                            </span>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-9">
                    <div class="d-flex justify-content-between">
                        <p class="mb-1"><strong>Description:</strong></p>
                        @if(auth()->id() === $card -> owner -> id)
                            <div class="d-flex">
                                <label class="btn btn-sm btn-primary mr-2">
                                    Add Images <input type="file" class="add-card-images"
                                                      accept="image/png, image/gif, image/jpeg" multiple="multiple"
                                                      style="display: none;">
                                </label>
                                <label class="btn btn-sm btn-primary">
                                    Add Attachment <input type="file" class="add-card-attachment" style="display: none;">
                                </label>
                            </div>
                        @endif
                    </div>

                    <p>{!! $card->description  !!}</p>

                    @if($card->images->count())
                        <div class="images">
                            <small class="mt-2">Images</small>
                            <div class="d-flex justify-content-start flex-wrap">
                                @foreach($card->images as $image)
                                    <a href="{{ $image -> full_path }}" class="m-2" target="_blank"><img
                                            src="{{ $image->full_path }}" alt="" width="64" height="64"></a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($card->attachments->count())
                        <div class="attachments">
                            <small class="mt-2">Attachments</small> <br>
                            @foreach($card->attachments as $index => $attachment)
                                <a href="{{ $attachment -> full_path }}" class="mr-1" target="_blank">
                                    {{ 'Attachment ' . ($index + 1) }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <div class="form-group mt-3">
                        <label for="comment"></label>
                        <textarea class="form-control" name="comment" id="comment" rows="2" placeholder="Add your comment"></textarea>
                    </div>
                    <a class="btn btn-sm btn-primary add-comment">Add Comment</a>
                    <a class="btn btn-sm btn-secondary cancel-editor" style="display: none;">Cancel</a>

                    @if($card->comments()->count())
                        <div class="container mt-2">
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="comments">
                                        @foreach ($card->comments()->orderBy('created_at', 'desc')->get() as $comment)
                                            <div class="comment mb-4">
                                                <div class="d-flex align-items-center mb-1">
                                                    <div class="user-info px-2">
                                                        <small class="text-capitalize font-weight-bold">{{ $comment->user->name }}</small>
                                                    </div>
                                                    <div class="comment-time">
                                                        <small>{{ $comment->created_at->format('d.m.Y H:i') }}</small>
                                                    </div>
                                                </div>
                                                <div class="comment-content p-3 rounded shadow-sm bg-light">
                                                    <p class="mb-0">{!! $comment->description !!}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
                <div class="col-3">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-secondary btn-block mb-1" type="button" id="addMembers" data-toggle="dropdown">
                            Add Members
                        </button>
                        @include('modals.add-members')
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-secondary btn-block mb-1" type="button" id="addLabels" data-toggle="dropdown">
                            Add labels
                        </button>
                        <div class="dropdown-menu w-100 p-0" aria-labelledby="addLabels">
                            <label for="" class="mx-1">Labels</label>
                            @forelse($card->board->labels->whereNotIn('id', $card->labels()->pluck('labels.id')) as $label)
                                <a href="{{ route('cards.addNewLabel') }}" data-label-id="{{ $label->id }}" class="badge p-2 text-white w-100 my-1 add-new-label" style="background-color: {{ $label->color }}">{{ $label->name }}</a>
                            @empty
                                <p>No labels left</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-secondary btn-block mb-1" type="button" id="dueDate" data-toggle="dropdown">
                            {{ $card->due_date ? 'Change' : 'Add' }} Due Date
                        </button>
                        <div class="dropdown-menu w-100 px-2 py-2" aria-labelledby="dueDate">
                            <label for="due-date">Due date</label>
                            <input type="date" id="due-date" name="due-date" class="form-control" @if($card->due_date) value="{{ $card->due_date->format('Y-m-d') }}" @endif>
                            <a href="{{ route('cards.addDueDate') }}" class="btn btn-sm btn-block btn-primary save-due-date mt-3">Save</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('modals.add-members')
