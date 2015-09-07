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

    /* DragDrop Plugin */
    // Grid stack initialization for customization page
  /*$('.grid-stack').gridstack({
        item_class: 'grid-stack-item',
        handle: '.grid-stack-item-content',
        // one cell height
        cell_height: 1,
        // vertical gap size
        vertical_margin: 20,
        horizontal_margin: 10
    });*/
    /* DragDrop Plugin End*/


//    Click handler for menuCollapse icon
    $('.mainContainerWrapper').on('click', '.divMobileMenuWrapper', function() {
        if ($('#divLeftSlideMenu').hasClass('show')) {
            hideMobileMenu();
        }
        else {
            showMobileMenu();
        }
    });
    $('html').niceScroll();

    $('.divNavLeftArrow').click(function() {
        var pos = $('#divCardsWrapper').scrollLeft() - (screen.width * 85) / 100;
        $('#divCardsWrapper').animate({scrollLeft: pos}, 300);
        return false;
    });

    $('.divNavRightArrow').click(function() {
        var pos = $('#divCardsWrapper').scrollLeft() + (screen.width * 85) / 100;
        $('#divCardsWrapper').animate({scrollLeft: pos}, 300);
    });

    $('.mainContainerWrapper').on('click', '.createCollection', function(e) {
        e.preventDefault();
        showModal();
    });

    $('.mainContainerWrapper').on('click', '.divModalBox', function(e) {
        e.stopPropagation();
        e.preventDefault();
    });

    $('.mainContainerWrapper').on('click', '.divModalBlurBackground', function() {
        hideModal();
    });

    $('html').mouseover(function() {
        $('html').getNiceScroll().resize();
    });

//    Include Header Partial
    //$('#divIncludeContent').load("header.html");

});
var hideMobileMenu = function() {
    $('#divLeftSlideMenu').removeClass('show');
    $('#divLeftSlideMenu').addClass('hide');
    $('#mainContainerWrapper').removeClass('hide');
    $('#mainContainerWrapper').addClass('show');
};
var showMobileMenu = function() {
    $('#divLeftSlideMenu').removeClass('hide');
    $('#divLeftSlideMenu').addClass('show');
    $('#mainContainerWrapper').removeClass('show');
    $('#mainContainerWrapper').addClass('hide');
};
var showModal = function() {
    $('#secModal').fadeIn(500, function() {
        $('.divModalContentWrapper').slideDown(200);
    });

};
var hideModal = function() {
    $('.divModalContentWrapper').slideUp(200, function() {
        $('#secModal').fadeOut(500);
    });
};

