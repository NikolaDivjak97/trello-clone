function renderCardDataModal() {
    $(".open-task[data-card='" + getUrlParam('card') + "']").trigger('click');
}

function updatePhaseColumns() {
    let phaseColumn = $(".phase_column");


    phaseColumn.each(function() {
        let phase = $(this);
        let phase_id = $(this).data('phase');

        if(!phase_id) return;

        $.get(r_get_phase_column, { phase_id }, function(response) {
            if(response.success && response.html) {
                phase.replaceWith(response.html);
            }
        }).always(function() {
            initDraggable();
        });
    });

}
//DRAG AND DROP
function initDraggable() {
    $('main').find(".draggable").draggable({
        revert: "invalid",
        helper: "clone",
        zIndex: 100,
        cursorAt: { left: 5, top: 5 }
    });

    $('main').find(".phase_column").droppable({
        accept: ".draggable",
        drop: function (event, ui) {
            var droppedItem = ui.draggable;
            var $droppableList = $(this).find("ul");
            var offset = ui.offset;
            var listItems = $droppableList.children();
            var index = -1;

            listItems.each(function (i, listItem) {
                var listItemOffset = $(listItem).offset();
                if (offset.top < listItemOffset.top) {
                    index = i;
                    return false;
                }
            });

            if (index === -1) {
                $droppableList.append(droppedItem);
            } else {
                $(listItems[index]).before(droppedItem);
            }

            $.post(r_update_phase, { order: index, card_id: droppedItem.data('card'), phase_id: $(this).data('phase') }, function (response) {

            });
        }
    });
}
//FINISH DRAG AND DROP

$(document).ready(function () {
    var data;

    // Instantiate draggable class on components
    initDraggable();

    $('#taskDesc').richText({table: false, code: false});

    // rich text constructor and destructor on comments
    $('main').on('focus', '#comment', function() {
        $(this).richText({
            table: false,
            code: false,
        });

        $('.cancel-editor').show();
    })
    .on('click', '.cancel-editor', function(e) {
        e.preventDefault();

        if(confirm('If you exit editor everything you have done will be deleted. Are you sure you want to exit editor?')) {
            $('.richText-editor').trigger('destroy');
            $('#comment').val('');
            $(this).hide();
        }
    });
    // .on('keydown', '.richText-editor', function(e) {
    //     const inputValue = $(this).text();
    //     const mentionIndex = inputValue.lastIndexOf('@');
    //     const mentionDropdown = $('#mentionDropdown');
    //
    //     if (mentionIndex !== -1) {
    //         const mentionText = inputValue.substring(mentionIndex + 1);
    //
    //         mentionDropdown.find('div').each(function() {
    //             if($(this).text().toLowerCase().startsWith(mentionText.toLowerCase())) {
    //                 $(this).show();
    //
    //                 return;
    //             }
    //
    //             $(this).hide();
    //         });
    //
    //         if(!$(this).children().first().length) return;
    //
    //         // Position dropdown below input field
    //         const inputRect = $(this).children().first().getBoundingClientRect();
    //         mentionDropdown.css({
    //             top: inputRect.bottom + 'px',
    //             left: inputRect.left + 'px',
    //             zIndex: '1100',
    //             display: 'block'
    //         });
    //     } else {
    //         mentionDropdown.hide();
    //     }
    // });

    // Modal events
    $('#taskDetailsModal').on('hidden.bs.modal', function () {
        removeParam('card');
        $('div.modal-backdrop').replaceWith('');
    });

    $('main').on('click', '.add-task', function () {
        data = {
            phase_id: $(this).data('phaseid'),
            board_id: $(this).data('boardid'),
            user_id: $(this).data('userid'),
        };
    });

    $('#saveTask').click(function () {

        data.name = $('#taskName').val();
        data.description = $('#taskDesc').val();
        data.difficulty = $('#difficulty').val();
        data.members = $('#members').val();
        data.labels = $('#labels').val();

        $.post(card_store_url, data, function (response) {
            Swal.fire({
                title: "Good job!",
                text: response.message,
                icon: "success"
            });
            $('#addTaskModal').modal('hide');
            location.reload();

        }).fail(function (error) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Something went wrong!",
            });
            console.log("Cards Store Error:" + error)
        });
    });

    $('main').on('click', '.open-task', function (e) {
        e.preventDefault();

        let card_id = $(this).data('card');
        let url = $(this).data('url');

        $.get(url, { card_id }, function (response) {
            if (response.success) {
                $('#taskDetailsModal').html(response.html).modal('show');

                if (!getUrlParam('card')) {
                    const url = new URL(window.location.href);
                    url.searchParams.set('card', card_id);
                    window.history.replaceState(null, null, url);
                }
            }
        })
    });

    // Trigger open modal after event is registered
    if (getUrlParam('card')) {
        renderCardDataModal();
    }

    //  update card  members
    $('main').on('input', '#memberSearch', function () {
        var searchText = $(this).val().toLowerCase();

        $('.member-list .member').each(function () {
            var memberName = $(this).text().toLowerCase();
            if (memberName.indexOf(searchText) === -1) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });

    $('main').on('click', '#addMembersBtn', function () {

        let selectedCheckboxes = $('.selected-members input:checked');
        let members = [];

        selectedCheckboxes.each(function () { members.push($(this).val()) });

        $.post(r_update_members, { card_id: getUrlParam('card'), members }, function (response) {
            if(response.success) {
                renderCardDataModal();
            }
        });
    });
    //finish card memebers update

    $('#taskDetailsModal').on('click', '.add-comment', function() {
        let btn = $(this);
        let card_id = getUrlParam('card');
        let comment = $('#comment').val();

        if(!comment.length) return;

        btn.addClass('disabled').prop('disabled', true);

        $.post(r_add_comment, {card_id, comment}, function(response) {
            if(response.success) {
                renderCardDataModal();
            }
        }).always(function() {
            btn.removeClass('disabled').prop('disabled', false);
        });
    })
    .on('change', '.add-card-images', function (e) {
        let images = $(this).prop('files');
        let card_id = getUrlParam('card');
        let btn = $(this);

        btn.addClass('disabled').prop('disabled', true);

        let formData = new FormData();
        formData.append('card_id', card_id);

        for (let i = 0; i < images.length; i++) {
            formData.append('images[]', images[i]);
        }

        $.ajax({
            url: r_add_images,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.success) {
                    renderCardDataModal();
                }
            },
            error: function(xhr, status, error) {
            },
            complete: function() {
                btn.removeClass('disabled').prop('disabled', false);
            }
        });
    })
    .on('change', '.add-card-attachment', function (e) {
        let attachment = $(this).prop('files');
        let card_id = getUrlParam('card');
        let btn = $(this);

        btn.addClass('disabled').prop('disabled', true);

        let formData = new FormData();
        formData.append('card_id', card_id);

        if(attachment.length) {
            formData.append('attachment', attachment[0]);
        }

        $.ajax({
            url: r_add_attachment,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.success) {
                    renderCardDataModal();
                }
            },
            error: function(xhr, status, error) {
            },
            complete: function() {
                btn.removeClass('disabled').prop('disabled', false);
            }
        });
    })
    .on('click', '.add-new-label', function (e) {
        e.preventDefault();

        let card_id = getUrlParam('card');
        let label_id = $(this).data('label-id');
        let url = $(this).prop('href');

        $.post(url, { card_id, label_id }, function(response) {
            if(response.success) {
                renderCardDataModal();
            }
        });
    })
    .on('click', '.save-due-date', function (e) {
        e.preventDefault();

        let card_id = getUrlParam('card');
        let due_date = $('#due-date').val();
        let url = $(this).prop('href');

        $.post(url, { due_date, card_id }, function(response) {
            if(response.success) {
                renderCardDataModal();
            }
        });
    });

});
