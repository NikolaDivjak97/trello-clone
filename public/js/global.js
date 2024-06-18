function getUrlParam(name) {
    let url = new URL(window.location.href);

    return url.searchParams.get(name);
}

function removeParam(key) {
    let rtn = document.location.toString().split("?")[0],
        param,
        params_arr = [],
        queryString = (document.location.toString().indexOf("?") !== -1) ? document.location.toString().split("?")[1] : "";

    if (queryString !== "") {
        params_arr = queryString.split("&");
        for (let i = params_arr.length - 1; i >= 0; i -= 1) {
            param = params_arr[i].split("=")[0];
            if (param === key) {
                params_arr.splice(i, 1);
            }
        }
        if (params_arr.length) rtn = rtn + "?" + params_arr.join("&");
    }

    window.history.pushState(null, null, rtn);
}

function updateFavicon() {
    let favicon = $('link[rel="icon"]').first();

    if(!favicon || favicon.attr('href').includes('-new')) return;

    favicon.attr('href', favicon.attr('href').replace('favicon', 'favicon-new'));
}

function notifyAndUpdate() {

    let updatePhases = true;

    updateFavicon();

    $.get(r_get_notifications, function (response) {
        if (response.success && response.data?.length) {
            let countNew = response.data.length;
            let notificationsTab = $('.notifications-tab > .card-body').html('');

            $('.bell').removeClass('viewed');
            $('.notification-number').text(countNew).show();

            response.data.forEach(function (notification) {

                if(updatePhases && notification.event === 'update_phase') {

                    if(typeof updatePhaseColumns != 'undefined') {
                        updatePhaseColumns();
                    }

                    updatePhases = !updatePhases;
                }

                notificationsTab.append(`
                    <div class="notification mb-4">
                        <a href="${notification.link}">
                            <div class="card bg-secondary text-white">
                                <div class="card-header">
                                    ${notification.message}
                                </div>
                            </div>
                        </a>
                    </div>
                    `);
            });
        }
    });
}


$(document).ready(function () {

    $('#team_members').select2({ width: '100%' });
    $('#members').select2({ width: '100%' });
    $('#labels').select2({ width: '100%' });
    $('#teams').select2({ width: '100%' });
    $('#selectMemberBoard').select2({ width: '100%' });

    $('.notifications-overlay').on('click', function() {
        $(this).toggle();
    });

    $('.bell').on('click', function () {
        $('.notifications-overlay').toggle();

        if ($(this).hasClass('viewed')) return;

        $(this).addClass('viewed');

        let url = $(this).data('url');

        $.post(url, {}, function (response) {
            if (response.success) {
                $('.notification-number').hide();
            }
        }).always(function () {
            $('.notification-number').hide();
        });
    });

    const output = $("#images-output");
    const background = $('#background');

    background.on('change', function (e) {
        const file = $(this).prop('files');
        let imageOutput = '';

        if (file.length) {
            imageOutput = `<div class="image">
            <img src="${URL.createObjectURL(file[0])}" alt="background">
          </div>`;
        }

        output.html(imageOutput);
    });
});
