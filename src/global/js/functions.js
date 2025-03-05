window.addEventListener('scroll', function() {
    const parallaxContainer = document.querySelector('body');

    var windowHeight = $(window).height();
    var windowWidth = $(window).width();

    var start_pose = $("body").css("background-position").split(" ")[1].replace("px", "");

    start_pose /= document.documentElement.clientHeight;

    let scrollPosition = window.pageYOffset;

    var scroll_pose = scrollPosition/windowHeight;

    parallaxContainer.style.backgroundPositionY = 25 - scroll_pose * windowWidth/30 + 'vh';
});