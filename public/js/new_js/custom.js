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

var startTimeMS = 0;  // EPOCH Time of event count started
var timerSet = 0;
var timerStep=10000;

function getRemainingTime(){
    return  timerStep - ( (new Date()).getTime() - startTimeMS );
}


slidesCount = $('.ulCenterSlide li').length;
var currFadedOutImg = 7;
var imgCounter = 0;

var totalIdleTime = 0;
var animationsStarted = false;

var centerImageTransition = function() {
	var grt = getRemainingTime();
	var tzTimeRemaining = 0;
	if( timerSet )
	{
		tzTimeRemaining = grt;
	}
	if( ! animationsStarted )
	{
		animationsStarted = true;
		$('.ulCenterSlide li:nth-child(' + currFadedOutImg + ')').fadeOut(1200, function() {
			// console.log( "set " + totalIdleTime );
			totalIdleTime = 0;
			imgFadeIn();
		});
	}
	
	if( (parseInt(totalIdleTime) < parseInt("6000")) )
	{
		var funcReturn = true;
		totalIdleTime += parseInt(tzTimeRemaining);
		if( parseInt(totalIdleTime) >= parseInt("6000") || parseInt(totalIdleTime) >= parseInt("2200") )
		{
			funcReturn = false;
		}
		if( funcReturn )
		{
			return;
		}
	}
    $('.ulCenterSlide li:nth-child(' + currFadedOutImg + ')').fadeOut(1200, function() {
		totalIdleTime = 0;
		imgFadeIn();
    });
};

function imgFadeIn()
{
	imgCounter++;
	if( parseInt(imgCounter) > parseInt(slidesCount) )
	{
		imgCounter = 1;
	}
	currFadedOutImg = imgCounter;
	$('.ulCenterSlide li:nth-child(' + imgCounter + ')').fadeIn(1200, function() {
		startTimeMS = (new Date()).getTime()+2400;
		setTimeout(centerImageTransition, 10000);
		timerSet = 1;
	});
}
