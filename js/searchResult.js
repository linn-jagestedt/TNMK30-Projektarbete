const NUM_OF_COLORS = 3;

$('.item').each(function() {
    var partID = this.id;
    $(this).find('.scroll .colour-image img').each(function(index) {
        if (index == 0) {
            updatePreviewImage(partID, this.id);
        }

        if (index < NUM_OF_COLORS) {
            $(this).addClass('carousel');
        }
    });
});

function getIndexOfVisibleElements(partID) {
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
    var visibleElements = getIndexOfVisibleElements(partID);
    var num_of_colors = $('[id^=' + partID + '_color_]').length;

    $('[id^=' + partID + '_color_]').each(function(index) {     
        if (visibleElements.indexOf(index) > -1) {
            $(this).removeClass('carousel');
        }
    });

    if  (rotateLeft) {
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

    $('[id^=' + partID + '_color_]').each(function(index) {     
        if (visibleElements.indexOf(index) > -1) {
            $(this).addClass('carousel');
        }
    });
}

function updatePreviewImage(partID, imageID) 
{    
    // Update image link
    var preview_image = $('#' + partID + '_preview_image');
    var image_link = $('#' + imageID).attr('src');
    preview_image.attr("src", image_link);

    // Update link
    var link = $('#' + partID + '_link');
    var start_index = partID.length + "_color_".length;
    var color_value = imageID.substring(start_index, imageID.length)
    link.attr('href', 'selectedItem.php?PartID=' + partID + '&ColorID=' + color_value);
}