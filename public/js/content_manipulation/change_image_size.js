function changeImageSize(value) {
    $('.img_add').css("width", value + "vw");
    }
    $(document).on('input', '.range', function() {
        $(this).prev().css("width", $(this).val() + "vw");
    })

    $(document).on('mouseover', '.range', function() {
        $(this).css('opacity', '100%');
    })

    $(document).on('mouseout', '.range', function() {
        $(this).css('opacity', '15%');
        setTimeout(function() {
            $('.range').css('opacity', '5%')
        }, 30000)
    $(this).attr("value",$(this).val());
})