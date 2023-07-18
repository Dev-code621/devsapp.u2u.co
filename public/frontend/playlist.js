var current_playlist_url_id, playlist_type = 'general',
    current_tr, current_btn;
var flag;
$('.add_playlist_btn').click(function() {
    current_playlist_url_id = -1;
    current_tr = null;
    $(`#${playlist_type}-playlist-name`).val('');
    $(`#${playlist_type}-playlist-url`).val('');
    $('.invalid-feedback').slideUp();
    $(`#general-list-modal`).modal('show');
    $('#playlistbtn').css('display', 'none');
    $('#xtreamcode').css('display', 'block');
    flag = true;
});

function changeGeneralPlaylistProtect1() {
    if ($(`#playlist-protect1`).prop('checked')) {
        $(`#pin1`).removeAttr('disabled');
        $(`#pin1-confirm`).removeAttr('disabled');
    } else {
        $(`#pin1`).attr('disabled', true);
        $(`#pin1-confirm`).attr('disabled', true);
        $(`#pin1`).closest('div').find('.invalid-feedback').slideUp();
    }
}

function changeGeneralPlaylistProtect2() {
    if ($(`#playlist-protect2`).prop('checked')) {
        $(`#pin2`).removeAttr('disabled');
        $(`#pin2-confirm`).removeAttr('disabled');
    } else {
        $(`#pin2`).attr('disabled', true);
        $(`#pin2-confirm`).attr('disabled', true);
        $(`#pin2`).closest('div').find('.invalid-feedback').slideUp();
    }
}

$('.showXtream').click(function() {
    flag = true;
    $('#playlistbtn').css('display', 'none');
    $('#xtreamcode').css('display', 'block');
});

$('.showPlaylist').click(function() {
    flag = false;
    $('#xtreamcode').css('display', 'none');
    $('#playlistbtn').css('display', 'block');
})

function savePlaylist() {

    if (flag) {

        $('.invalid-feedback').slideUp();
        var url = site_url + '/device/savePlaylist';
        var valid;
        var required_inputs;
        var xtreamurl;

        xtreamurl = $('#schema').val() + "://" + $('#hostip').val() + ":" + $('#port').val() + "/get.php?username=" + $('#username').val() + "&password=" + $('#pwd').val() + "&type=m3u_plus&output=" + $('#output').val();

        required_inputs = [$('#general-playlist-name1'), $('#schema'), $('#hostip'), $('#port'), $('#username'), $('#pwd'), $('#output')];
        valid = checkInputValidate(required_inputs);
        // alert(xtreamurl);

        var protect = $(`#playlist-protect1`).prop('checked');
        if (protect) {
            if ($(`#pin1`).val() === '') {
                $(`#pin1`).closest('div').find('.invalid-feedback').text('Pin is required').slideDown();
                valid = false;
            }
            if ($(`#pin1`).val() != $(`#pin1-confirm`).val()) {
                $(`#pin1`).closest('div').find('.invalid-feedback').text('Pin code does not match').slideDown();
                valid = false;
            }
        }
        if (!valid)
            return;
        var data = {
            current_playlist_url_id: current_playlist_url_id,
            playlist_name: $(`#general-playlist-name1`).val(),
            playlist_url: xtreamurl,
            protect: protect,
            pin: $(`#pin1`).val(),
        };
        $('#device-page-loading-overlay').show();
        // alert(xtreamurl);
        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            data: data,
            success: result => {
                $('#device-page-loading-overlay').hide();
                if (result.status === 'success') {
                    showSuccessNotify(result.msg);
                    $('.modal').modal('hide');
                    var playlist_url = result.data;
                    var html = `
                        <td class="text-center">${playlist_url.name}</td>
                        <td class="text-center">${playlist_url.is_protected==1 ? 'This playlist is protected' : playlist_url.url}</td>
                        <td class="text-center">
                            <button class="playlist-action-btn-wrapper btn btn-outline-dark playlist-url-edit" data-current_id="${playlist_url.id}" data-protected="${playlist_url.is_protected}">
                                <i class="fa fa-edit playlist-icon"></i>
                                <span class="playlist-action-btn-txt">Edit</span>
                            </button>
                            <button class="playlist-action-btn-wrapper btn btn-outline-dark playlist-url-delete" data-current_id="${playlist_url.id}" data-protected="${playlist_url.is_protected}">
                                <i class="fa fa-trash playlist-icon"></i>
                                <span class="playlist-action-btn-txt">Delete</span>
                            </button>
                        </td>
                    `
                    if (current_tr == null) {
                        $('#playlist-table tbody').append(`<tr>${html}</tr>`);
                    } else {
                        $(current_tr).html(html);
                    }
                } else {
                    $('#device-page-loading-overlay').hide();
                    showErrorNotify(result.msg);
                    $('.modal').modal('hide');
                }
            },
            error: err => {
                $('#device-page-loading-overlay').show();
                showErrorNotify('Sorry, something is wrong, please try again later');
                $('.modal').modal('hide');
            }
        })

    } else {


        $('.invalid-feedback').slideUp();
        var url = site_url + '/device/savePlaylist';
        var valid;
        var required_inputs;

        required_inputs = [$('#general-playlist-name2'), $('#general-playlist-url')];
        valid = checkInputValidate(required_inputs);
        // alert($('#general-playlist-name2').val() + "   " + $('#general-playlist-url').val());
        var protect = $(`#playlist-protect2`).prop('checked');
        if (protect) {
            if ($(`#pin2`).val() === '') {
                $(`#pin2`).closest('div').find('.invalid-feedback').text('Pin is required').slideDown();
                valid = false;
            }
            if ($(`#pin2`).val() != $(`#pin2-confirm`).val()) {
                $(`#pin2`).closest('div').find('.invalid-feedback').text('Pin code does not match').slideDown();
                valid = false;
            }
        }
        if (!valid)
            return;
        var data = {
            current_playlist_url_id: current_playlist_url_id,
            playlist_name: $(`#general-playlist-name2`).val(),
            playlist_url: $(`#general-playlist-url`).val(),
            protect: protect,
            pin: $(`#pin2`).val(),
        };
        $('#device-page-loading-overlay').show();
        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            data: data,
            success: result => {
                $('#device-page-loading-overlay').hide();
                if (result.status === 'success') {
                    showSuccessNotify(result.msg);
                    $('.modal').modal('hide');
                    var playlist_url = result.data;
                    var html = `
                        <td class="text-center">${playlist_url.name}</td>
                        <td class="text-center">${playlist_url.is_protected==1 ? 'This playlist is protected' : playlist_url.url}</td>
                        <td class="text-center">
                            <button class="playlist-action-btn-wrapper btn btn-outline-dark playlist-url-edit" data-current_id="${playlist_url.id}" data-protected="${playlist_url.is_protected}">
                                <i class="fa fa-edit playlist-icon"></i>
                                <span class="playlist-action-btn-txt">Edit</span>
                            </button>
                            <button class="playlist-action-btn-wrapper btn btn-outline-dark playlist-url-delete" data-current_id="${playlist_url.id}" data-protected="${playlist_url.is_protected}">
                                <i class="fa fa-trash playlist-icon"></i>
                                <span class="playlist-action-btn-txt">Delete</span>
                            </button>
                        </td>
                    `
                    if (current_tr == null) {
                        $('#playlist-table tbody').append(`<tr>${html}</tr>`);
                    } else {
                        $(current_tr).html(html);
                    }
                } else {
                    $('#device-page-loading-overlay').hide();
                    showErrorNotify(result.msg);
                    $('.modal').modal('hide');
                }
            },
            error: err => {
                $('#device-page-loading-overlay').show();
                showErrorNotify('Sorry, something is wrong, please try again later');
                $('.modal').modal('hide');
            }
        })

    }
}

function checkInputValidate(inputElements) {
    let result = true;
    inputElements.forEach(function(element) {
        if ($(element).val() == '') {
            $(element).closest('div').find('.invalid-feedback').slideDown('slow');
            result = false;
        }
    });
    return result;
}

function showOrHideLoader(show) {
    if (!show) {
        $('#device-page-loading-overlay').hide();
    } else {
        $('#device-page-loading-overlay').show();
    }
}

function getPlaylistDetail() {
    showOrHideLoader(true);
    $.ajax({
        method: 'get',
        url: site_url + '/device/getPlaylistUrlDetail/' + current_playlist_url_id,
        success: result => {
            showOrHideLoader(false);
            $(`#${playlist_type}-playlist-name`).val(result.name);
            $(`#${playlist_type}-playlist-url`).val(result.url);
            $(`#${playlist_type}-list-modal`).modal('show');
            if (result.is_protected == 1) {
                $(`#playlist-protect`).prop('checked', true);
                $(`#pin`).val(result.pin);
                $(`#pin-confirm`).val(result.pin);
            } else {
                $(`#playlist-protect`).prop('checked', false);
                $(`#pin`).val('');
                $(`#pin-confirm`).val('');
            }
        },
        error: err => {
            showOrHideLoader(false);
            showErrorNotify("Sorry, something is wrong, please try again later");
        }
    })
}

$(document).on('click', '.playlist-url-edit', function() {
    current_btn = this;
    current_playlist_url_id = $(current_btn).data('current_id');
    current_tr = $(current_btn).closest('td').closest('tr');
    if ($(this).data('protected') == 0) {
        getPlaylistDetail();
    } else {
        $('#pin-confirm-input').val('');
        $('#pin-confirm-modal').modal('show');
    }
})

function checkPincode() {
    $('.invalid-feedback').slideUp();
    var pin_code = $('#pin-confirm-input').val();
    showOrHideLoader(true);
    $.ajax({
        type: 'get',
        url: site_url + '/device/checkPlaylistPinCode/' + current_playlist_url_id + '/' + pin_code,
        success: result => {
            showOrHideLoader(false);
            if (result.status === 'error') {
                $('#pin-confirm-input').closest('div').find('.invalid-feedback').text(result.msg).slideDown()
            } else {
                $('#pin-confirm-modal').modal('hide');
                getPlaylistDetail();
            }
        },
        error: result => {
            showOrHideLoader(false);
            $('#pin-confirm-input').closest('div').find('.invalid-feedback')
                .text("Sorry something is wrong, please try again later").slideDown()
        }
    })
}


$(document).on('click', '.playlist-url-delete', function() {
    current_btn = this;
    current_playlist_url_id = $(current_btn).data('current_id');
    current_tr = $(current_btn).closest('td').closest('tr');
    var current_playlist_name = $(current_tr).find('td:eq(0)').text();
    $('#delete-confirm-title').text('Do you want to delete ' + current_playlist_name);
    if ($(this).data('protected') == 0) {
        $('#delete-pin-code-wrapper').hide();
    } else {
        $('#delete-pin-code-wrapper').show();
    }
    $('#delete-confirm-modal').modal('show')
})



$('#delete_playlist').click(function() {
    $('.invalid-feedback').slideUp();
    var pin_code = $('#delete-pin-confirm-input').val();
    var protected = $(current_btn).data('protected');
    if (protected == 1 && pin_code === '')
        return;
    $.ajax({
        method: 'delete',
        dataType: 'json',
        url: site_url + '/device/deletePlayListUrl',
        data: {
            playlist_url_id: current_playlist_url_id,
            pin_code: pin_code
        },
        success: result => {
            if (result.status == 'success') {
                showSuccessNotify('Playlist url removed successfully');
                $('#delete-confirm-modal').modal('hide');
                $(current_tr).remove();
            } else
                $('#delete-pin-confirm-input').closest('div').find('.invalid-feedback')
                .text(result.msg).slideDown();
        }
    })
})