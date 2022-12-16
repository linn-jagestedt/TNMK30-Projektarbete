function updatePreviewImage(imageID) 
{    
    // Get preview_image element
    var preview_image = $('#preview_image');

    // Get the source attribute of selected image
    var image_link = $('#' + imageID).attr('src');

    // Set the source atribute of the preview image
    preview_image.attr("src", image_link);
}