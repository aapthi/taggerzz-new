/* 
 * Copyright (C) 2015 arun
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
$(function() {

//    imgUrl = $('.mainWrapper').css('background-image');
//    var rand = Math.floor(Math.random() * (7 - 1 + 1) + 1);
//    imgUrl = imgUrl.substring(4, imgUrl.length - 6) + rand + ".jpg";
//    $('.mainWrapper').css('background', "url('" + imgUrl + "')");
//    $('.mainWrapper').css('background-size', "cover");
//    
//    $(document).on('ready', '.mainWrapper', function(){
//        hideLoader();
//
//        $('.divCenter').css('opacity', '1');
//        centerImageTransition();
//    });

    $('<img>').attr('src', function() {
        imgUrl = $('.mainWrapper').css('background-image');
        var rand = Math.floor(Math.random() * (7 - 1 + 1) + 1);
        var isChrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
        if (isChrome) {
            imgUrl = imgUrl.substring(4, imgUrl.length - 6) + rand + ".jpg";
        }
        else {
            imgUrl = imgUrl.substring(5, imgUrl.length - 7) + rand + ".jpg";
        }
        return imgUrl;
    }).load(function() {
        $(this).remove();
        $('.mainWrapper').css('background', "url('" + imgUrl + "')");
        $('.mainWrapper').css('background-size', "cover");
        hideLoader();

        $('.divCenter').css('opacity', '1');
        centerImageTransition();
    });
    

});

var hideLoader = function() {
    $('.divLoaderWrapper').addClass("hidden");
    $('.divLoaderWrapper').css('z-index', '-1');
};

slidesCount = $('.ulCenterSlide li').length;

var centerImageTransition = function() {
    if (typeof counter === 'undefined' || counter == slidesCount) {
        counter = 0;
        prevCounter = slidesCount;
    }

    $('.ulCenterSlide li:nth-child(' + prevCounter + ')').fadeOut(1200, function() {
        counter++;
        $('.ulCenterSlide li:nth-child(' + counter + ')').fadeIn(1200, function() {
            prevCounter = counter;
            setTimeout(centerImageTransition, 7000);
        });
    });
};
