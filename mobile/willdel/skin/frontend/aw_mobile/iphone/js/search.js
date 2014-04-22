/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/LICENSE-M1.txt
 *
 * @category   AW
 * @package    AW_Mobile
 * @copyright  Copyright (c) 2010-2011 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-M1.txt
 */

/* [@pack@] */

var is_show = false;
function activateInput(input){   
    input.focus(function(){
        $j(this).select();  
        if (!is_show){
            showSearchForm();
        }
    });
    input.blur(function(){
        if (is_show){
            hideSearchForm();
        }
    });
    input.focus();
    input.keypress();
}


function setValue(element, value, type){
    if (type == 'search'){
        $(element.attr('id')).style.top = value + 'px';
    } else {
        $(element.attr('id')).style.opacity = value;
    }
}

/**
 * Make transition for element
 *
 * @param element jQuery Element to transition
 * @param type string Type of transition "search" or "overlay"
 * @param startVal string|integer
 * @param endVal string|integer
 * @param showAfter boolean
 */
function transitFor(element, type, startVal, endVal, showAfter, postback)
{    
    if (typeof WebKitTransitionEvent == "object"){
        var transClass = (type == 'search') ? 'trans-search' : 'trans-overlay';
        setValue(element, startVal, type);
        $(element.attr('id')).style.display = 'block';

        var callback = function(e){
            element.removeClass(transClass);
            $(element.attr('id')).style.display = showAfter ? 'block' : 'none';
            if (type == 'overlay' && !showAfter){                
                $j(document).trigger('hideoverlaycomplete');
            }
            if (typeof(postback) == 'function'){
                postback();
            }

        }
        element.addClass(transClass);           
        element.one('webkitTransitionEnd', callback);
        
        setValue(element, endVal, type);
    } else {
        setValue(element, endVal, type);
        $(element.attr('id')).style.display = showAfter ? 'block' : 'none';
        if (postback){
            postback();
        }
    }
    if (type == 'search' && showAfter){
        activateInput($j('#search'));
    }
}

function showSearchForm(a){
    is_show = true;
    showSearchBlock();
    showOverlay();    
    return false;
}

function hideSearchForm()
{
    is_show = false;
    hideSearchBlock();
    hideOverlay();
    return false;
}

function showOverlay(callback, isCart){
    if (isCart){
        $j('div.cart-loader').show();
    } else {
        $j('div.cart-loader').hide();
    }    
    $('topSearchOverlay').style.height = + ($j('body').height()) + 'px';
    transitFor($j('#topSearchOverlay'), 'overlay', 0, 0.5, true, callback);
}

function hideOverlay(callback, isCart){
    if (isCart){
        $j('div.cart-loader').hide();
    } else {
        $j('div.cart-loader').hide();
    }        
    transitFor($j('#topSearchOverlay'), 'overlay', 0.5, 0, false, callback);
}

function showSearchBlock(){
    transitFor($j('#topSearch'), 'search', -42, 0, true);
}

function hideSearchBlock(){
    transitFor($j('#topSearch'), 'search', 0, -42, false);
}

$j(document).ready(function(event){
    $j('#search_mini_form').submit(function(e){
        hideSearchForm();
    });
});