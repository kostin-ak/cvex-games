window.addEventListener('scroll', function() {
    const parallaxContainer = document.querySelector('body');

    var windowHeight = $(window).height();
    var windowWidth = $(window).width();

    var start_pose = $("body").css("background-position").split(" ")[1].replace("px", "");

    start_pose /= document.documentElement.clientHeight;

    //alert(start_pose);

    let scrollPosition = window.pageYOffset;

    var scroll_pose = scrollPosition/windowHeight;

    //alert(scroll_pose);

    parallaxContainer.style.backgroundPositionY = 25 - scroll_pose * windowWidth/45 + 'vh';
});