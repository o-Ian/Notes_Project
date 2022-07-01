function insertImage() {
    var link_image = $('#link-image').val();
    $('#htmlContent').append("<img contenteditable='false' src=" + link_image + " alt='' id='$(this).index()' name= 'image_add' srcset='' class='img_add'> <input contenteditable='false' id='range' class='range' name='range' onload='changeImageSize(value)' type='range' min='3' max='35' value=35><br>")
    setTimeout(function() {
        $('.range').css('opacity', '15%')
    }, 10000)
    setTimeout(function() {
        $('.range').css('opacity', '5%')
    }, 60000)
    $('.img_add').attr('id', function() {
        return $(this).index();
      });
      $('.range').attr('id', function() {
        return $(this).index();
      });
}

