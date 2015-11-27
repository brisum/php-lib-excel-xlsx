var urlAjaxParse = '/administrator/upload-excel2/parse.php';

function log(msg) {
    $('#out').prepend('<p>' + msg + '</p>');
}

function extract(resp) {
    var fileType = $("#bsm_fm_form input[type='radio']:checked").val();

    $.ajax({
        type: 'post',
        url: urlAjaxParse,
        data: {
            'filename': resp.filename,
            'fileType': fileType,
            'action': 'extract', 
            'dirTmp': new Date().getTime()
        },
        dataType: 'json',
        async: false,
        success: function(resp) {
            log(resp.msg);
            parse(resp, 1);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            log(jqXHR.responseText);
            // log('error run time1');
        }
    });
}

function parse(resp, step) {
    $.ajax({
        type: 'post',
        url: urlAjaxParse,
        data: {
            'filename': resp.filename,
            'fileType': resp.fileType,
            'dirTmp': resp.dirTmp,
            'action': 'parse', 
            'step': step
        },
        dataType: 'json',
        async: false,
        success: function(resp) {
            log(resp.msg);
            
            if ( 'parse' == resp.next ) {
                parse(resp, step + 1);
            } else if ( 'clear' == resp.next ) {
                clear(resp);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            log(jqXHR.responseText);
            // log('error run time2');
        }
    });
}

function clear(resp) {
    $.ajax({
        type: 'post',
        url: urlAjaxParse,
        data: {
            'filename': resp.filename,
            'fileType': resp.fileType,
            'dirTmp': resp.dirTmp,
            'action': 'clear',
        },
        dataType: 'json',
        async: false,
        success: function(resp) {
            log(resp.msg);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            log(jqXHR.responseText);
            // log('error run time3');
        }
    });
}