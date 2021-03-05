$(function(){
    Profile.load();
});

Profile = {
    load:function(){
        this.links();
        this.social();
        this.accordion();
    },
    links:function(){
        $('a[href="#"]').click(function(e){
            e.preventDefault();
        });
    },
    social:function(){
        $('.accordion .about-me .photo .photo-overlay .plus').click(function(){
            $('.social-link').toggleClass('active');
            $('.about-me').toggleClass('blur');
        });
        $('.social-link').click(function(){
            $(this).toggleClass('active');
            $('.about-me').toggleClass('blur');
        });
    },
    accordion:function(){
        var subMenus = $('.accordion .sub-nav').hide();
        $('.accordion > a').each(function(){
            if($(this).hasClass('active')){
                $(this).next().slideDown(100);
            }
        });
    }
}