$(function() {

    // HIDE PLACEHOLDER ON FOCUS
    $('[placeholder]').focus(function() {
        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    }).blur(function() {
        $(this).attr('placeholder', $(this).attr('data-text'));
    });
});

$(function() {
        // MAKE STRAR FOR THE REQUIRED INPUT
        $('input').each(function() {
            if($(this).attr('required')==='required') {
                $(this).after("<span class=after>* </span>");
            }
        });
});

$(function() {
    // MAKE CONFIRM FOR THE DELETION
    $('.confirm').click(function() {
        return confirm('Are Sure For Deletion?!');
    });
});

$(function() {
    $('.panel-heading').click(function() {
        $(this).next().slideToggle(400);
    });
});