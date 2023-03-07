$(function() {

    // HIDE PLACEHOLDER ON FOCUS
    $('[placeholder]').focus(function() {
        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    }).blur(function() {
        $(this).attr('placeholder', $(this).attr('data-text'));
    });


    $(".filter input").click(function() {
        $(this).addClass("btn-outline-success active").siblings().removeClass("btn-outline-success active");
    })
});


// $(function() {
//     $("#book-li").click(function() {
       
//         $("#pop1").text("book");
//         $("#all-li").text("book");
//     });


//     $("#book-li").click(function(){
//         alert("HTML: " + $("#pop1").html());
//       });
// });

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


$(function(){

    $("#about").hide();
        $("#less").hide();
        $("#more").click(function(){
            $("#about").show(400);
            $(this).hide();
            $("#less").show();
        })
        $("#less").click(function(){
            $("#about").hide(400);
            $(this).hide();
            $("#more").show();
    })
});


$(function() {


    
    $(".nav-link").click(function(e) {
        e.preventDefault();
        $("body, html").animate({
        scrollTop: $('#' + $(this).data("scroll")).offset().top + 1
        }, 50)
    })

    $(".nav-link").click(function() {
        
        $(this).addClass("active").parent().siblings().find('a').removeClass("active");
    });
    
    $(window).scroll(function() {
        $(".navbar .navbar-brand svg").css("color", "var(--second-color)");
        $(".navbar .navbar-brand svg").fadeOut(800);
        $(".navbar .navbar-brand svg").fadeIn(800);


        $(".section").each(function() {  
            if($(window).scrollTop() > $(this).offset().top) {
                var sectionId = $(this).attr("id");
                $(".nav-link").removeClass("active");
                $('.navbar-nav .nav-item .nav-link[data-scroll="'+ sectionId +'"]').addClass('active');

                if($(".services").attr("id") == sectionId) {
                    $(".services .use-a-lot svg").animate({
                        paddingLeft: '100px'
                    }, 1000);
                    $(".services .use-a-lot svg").animate({
                        paddingLeft: '0px'
                    }, 1000);
                    $(".services .use-a-lot svg").stop();
                    
                }
            }
        })

        // add scroll to top
        if($(window).scrollTop() >= 1000) {
            $(".scroll-up").fadeIn();
        }
        else
            $(".scroll-up").fadeOut();
    })


    $(".scroll-up").click(function() {
        $('body, html').animate({
            scrollTop: 0
        } ,50)
    })


    // add pop up
    $("#message").click(function() {
        $(".pop-up").fadeIn();
    })

    $("#cancel").click(function() {
        $(".pop-up").fadeOut();
    })

})

