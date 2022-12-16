const NUM_OF_COLORS = 3;

// Initiate the carousel
$('.item').each(function() {
    var partID = this.id;
    $(this).find('.scroll .colour-image img').each(function(index) {

        // Set the preview image to the first elements image
        if (index == 0) {
            updatePreviewImage(partID, this.id);
        }

        // Add the carousel class to the first 3 elements
        if (index < NUM_OF_COLORS) {
            $(this).addClass('carousel');
        }
    });
});

function getIndexOfVisibleElements(partID) 
{
    var result = [];
    $('[id^=' + partID + '_color_]').each(function(index) {  
        if ($(this).hasClass('carousel')) {
            result.push(index);
            return;
        }
    });
    return result;
}

function rotate(partID, rotateLeft) 
{
    // Store the indexes of visible elements in an array
    var visibleElements = getIndexOfVisibleElements(partID);
    var num_of_colors = $('[id^=' + partID + '_color_]').length;

    // Remove the carousel class from all elements
    $('[id^=' + partID + '_color_]').each(function(index) {     
        if (visibleElements.indexOf(index) > -1) {
            $(this).removeClass('carousel');
        }
    });

    // Shift the indexes of visible elements depending on direction
    if  (!rotateLeft) {
        for (var i = 0; i < visibleElements.length; i++) {
            visibleElements[i] += 1;

            if (visibleElements[i] > num_of_colors - 1) {
                visibleElements[i] -= num_of_colors;
            }
        }
    } else {
        for (var i = 0; i < visibleElements.length; i++) {
            visibleElements[i] -= 1;

            if (visibleElements[i] < 0) {
                visibleElements[i] += num_of_colors;
            }
        }
    }

    // Add the carousel class to the elements with the new indexes
    $('[id^=' + partID + '_color_]').each(function(index) {     
        if (visibleElements.indexOf(index) > -1) {
            $(this).addClass('carousel');
        }
    });
}

function updatePreviewImage(partID, imageID) 
{    
    // Get preview_image element of the selected part
    var preview_image = $('#' + partID + '_preview_image');

    // Get the source attribute of selected image
    var image_link = $('#' + imageID).attr('src');
    
    // Set the source atribute of the preview image
    preview_image.attr("src", image_link);

    // Get the a-tag from the selected part
    var link = $('#' + partID + '_link');

    // Retrieve the color value of from the selected image
    var start_index = partID.length + "_color_".length;
    var color_value = imageID.substring(start_index, imageID.length);

    // Set the href attribute of the selected part to the new value
    link.attr('href', 'selectedItem.php?PartID=' + partID + '&ColorID=' + color_value);
}