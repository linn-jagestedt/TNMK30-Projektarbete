function updatePreviewImage(imageID) 
{    
    // Update image link
    var preview_image = $('#preview_image');
    var image_link = $('#' + imageID).attr('src');
    preview_image.attr("src", image_link);
}