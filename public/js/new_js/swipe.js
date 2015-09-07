
window.addEventListener('load', function() {

    var touchsurface = document.getElementsByTagName('body')[0],
            startX,
            startY,
            dist,
            threshold = 150, //required min distance traveled to be considered swipe
            allowedTime = 500, // maximum time allowed to travel that distance
            elapsedTime,
            startTime

    function handleswipe(isrightswipe, isleftswipe) {
        if (isleftswipe || isrightswipe)
            mraid.close();
    }

    touchsurface.addEventListener('touchstart', function(e) {
        var touchobj = e.changedTouches[0]
        dist = 0
        startX = touchobj.pageX
        startY = touchobj.pageY
        startTime = new Date().getTime() // record time when finger first makes contact with surface
        e.preventDefault()

    }, false)

    touchsurface.addEventListener('touchmove', function(e) {
        e.preventDefault() // prevent scrolling when inside DIV
    }, false)

    touchsurface.addEventListener('touchend', function(e) {
        var touchobj = e.changedTouches[0]
        dist = touchobj.pageX - startX // get total dist traveled by finger while in contact with surface

        elapsedTime = new Date().getTime() - startTime // get time elapsed
        // check that elapsed time is within specified, horizontal dist traveled >= threshold, and vertical dist traveled <= 100
        if (touchobj.pageX > startX)
        {
            var swiperightBol = (elapsedTime <= allowedTime && dist >= threshold && Math.abs(touchobj.pageY - startY) <= 100);
        }
        else if (touchobj.pageX < startX)
        {
            dist = (dist) * (-1);
            var swipeleftBol = (elapsedTime <= allowedTime && dist >= threshold && Math.abs(touchobj.pageY - startY) <= 100);
        }
        else
        {
            hitTracker();
        }

        handleswipe(swiperightBol, swipeleftBol)
        e.preventDefault()
    }, false)

}, false)