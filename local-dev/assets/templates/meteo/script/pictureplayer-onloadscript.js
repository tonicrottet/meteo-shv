[[!preparePicturePlayer? &folder=`[[*folder]]` &filematch=`[[*filematch]]`]]

// params
var files = [ [[+pp-files]] ];
var numfiles = [[+pp-numfiles]];
var defaultfile = [[*stdpicture]];
var stdheight = [[*stdheight]];
var stdwidth = [[*stdwidth]];

if(files[defaultfile-1] === undefined) {
    defaultfile = 1;
}

// cache jquery objects
var image = $(".pp-picture-container img");
var container = $(".pp-picture-container");
var slider = $(".pp-slider");

slider.attr('max', numfiles);

// make image object array
var loadimages = new Array();

for (var i = 0; i < files.length; i++) {
    loadimages[i]=new Image();
}

// set container height
container.height(container.width()/stdwidth * stdheight);
container.css('max-height', stdheight);

// show loading
container.addClass('loading');

$(loadimages[defaultfile-1]).load(function() {
    container.removeClass('loading');
    image.attr('src', files[defaultfile-1]);

    //and now, autoheight, since the image give the size
    container.height('auto');

}).attr('src', files[defaultfile-1]);


if(numfiles > 1) {

    slider.val(defaultfile).slider("refresh");

    // change slider
    slider.change(function() {

        // not loaded images are different than already loaded
        // images
        // show 'loading' only when not loaded

        var index = $(this).val()-1;
        if (loadimages[index].src == '') {
            container.addClass('loading');
            loadimages[index].onload = function () {
                container.removeClass('loading');
                $(image).attr('src', loadimages[index].src);
            };
            loadimages[index].src = files[index];
        } else {
            if(loadimages[index].complete) {
                $(image).attr('src', loadimages[index].src);
            } else {
                container.addClass('loading');
                loadimages[index].onload = function () {
                    container.removeClass('loading');
                    $(image).attr('src', loadimages[index].src);
                };
            }

        }
    });

    var i;
    var j;

    // preload images from Default on
    for(i=defaultfile, j=files.length; i<j; i++) {
        loadimages[i].src = files[i];
    }

    // preload the rest
    for(i=0, j=defaultfile-2; i<j; i++) {
        loadimages[i].src = files[i];
    }


    // set events for navigation inside slider
    if('ontouchstart' in document.documentElement) {
        $(image).on('swipeleft', function(event){    
            if(event.handled !== true) // This will prevent event triggering more then once
            {
                var curval = parseInt(slider.val());
                if(curval < numfiles) slider.val(curval+1).change().slider("refresh");
                event.handled = true;
            }
            return false;         
        });
      
        $(image).on('swiperight', function(event){     
            if(event.handled !== true) // This will prevent event triggering more then once
            {   
                var curval = parseInt(slider.val());
                if(curval > 1) slider.val(curval-1).change().slider("refresh");
                event.handled = true;
            }
            return false;            
        });
    }

    $(document).keydown(function (event) {
        if(event.keyCode == 39) 
        {
            if(event.handled !== true) // This will prevent event triggering more then once
            {
                var curval = parseInt(slider.val());
                if(curval < numfiles) slider.val(curval+1).change().slider("refresh");
                event.handled = true;
            }
            return false;         
        }

        if(event.keyCode == 37) 
        {
            if(event.handled !== true) // This will prevent event triggering more then once
            {   
                var curval = parseInt(slider.val());
                if(curval > 1) slider.val(curval-1).change().slider("refresh");
                event.handled = true;
            }
            return false;      
        }
    });
}
else { // just one file
    $(".pp-controls").css('visibility', 'hidden');
}
