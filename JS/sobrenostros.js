


// Definir las configuraciones del slider
var sliderSettings = {
    infinite: true,
    autoplay: true,
    slidesToShow: 1,
    slidesToScroll: 1
};

// Inicializar el slider
$(".js-benefits-slider").slick(sliderSettings);

// Función para manejar el evento onMouseOver
function onMouseOver(degrees, color) {
    $(".js-arrow-wrap").stop().animate(
        { borderSpacing: degrees },
        {
            step: function(now, fx) {
                $(this).css("-webkit-transform", "rotate(" + now + "deg)");
                $(this).css("-moz-transform", "rotate(" + now + "deg)");
                $(this).css("transform", "rotate(" + now + "deg)");
            },
            duration: 350
        },
        "linear"
    );

    $(".desktop-info .js-" + color + "-info-text").stop().slideDown(550);
    $(".mobile-info ." + color + "-info").addClass("active");

    if ($(".mobile-info .info").hasClass("active")) {
        $(".mobile-info .info").removeClass("active");
        $(".mobile-info ." + color + "-info").addClass("active");
    }
}

// Función para manejar el evento onMouseOut
function onMouseOut(color) {
    $(".desktop-info .js-" + color + "-info-text").stop().slideUp(550);
}

// Manejar eventos de mouseenter y mouseleave para elementos con clase '.js-rotate'
$(".js-rotate").on("mouseenter", function() {
    var $width = $(window).width();
    var color = $(this).data("color");
    var degrees = ($width > 600) ? $(this).data("rotate") : $(this).data("rotate-mobile");
    onMouseOver(degrees, color);
});

$(".js-rotate").on("mouseleave", function() {
    var color = $(this).data("color");
    onMouseOut(color);
});

// Manejar eventos de mouseenter y mouseleave para elementos con clase '.info'
$(".info").on("mouseenter", function() {
    var degrees = $(this).data("rotate");
    var color = $(this).data("color");
    onMouseOver(degrees, color);
});

$(".info").on("mouseleave", function() {
    var color = $(this).data("color");
    onMouseOut(color);
});
