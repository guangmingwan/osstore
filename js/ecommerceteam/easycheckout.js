/*
 * Magento EsayCheckout Extension
 *
 * @copyright:	EcommerceTeam (http://www.ecommerce-team.com)
 * @version:	1.2
 *
 */
Event.observe(window, 'load',
	function(){
      	
		Event.observe($('billing:country_id'), 'change', billingAddressChanged);
		Event.observe($('billing:city'), 'change', billingAddressChanged);
		Event.observe($('billing:region'), 'change', billingAddressChanged);
		Event.observe($('billing:region_id'), 'change', billingAddressChanged);
		Event.observe($('billing:postcode'), 'change', billingAddressChanged);
      	
      	if($('shipping-address-form')){ // if enabled diferent shipping address
      	
      	Event.observe($('shipping:country_id'), 'change', shippingAddressChanged);
		Event.observe($('shipping:city'), 'change', shippingAddressChanged);
		Event.observe($('shipping:region'), 'change', shippingAddressChanged);
		Event.observe($('shipping:region_id'), 'change', shippingAddressChanged);
		Event.observe($('shipping:postcode'), 'change', shippingAddressChanged);
		
		if(e = $('billing_use_for_shipping_yes')){
			Event.observe(e, 'click', changeShippingAddressMode);
		}
		
		}
		
		if($('easycheckout-shippingmethod')){
		
		Event.observe($('easycheckout-shippingmethod'), 'click', function(e){
      		
      		if(e.target.nodeName == 'INPUT'){
      			
      			sendMethods();
      			
      		}
      		
      	});
      	
      	}
      	/*
      	Event.observe($('easycheckout-paymentmethod'), 'click', function(e){
      		
      		if(e.target.nodeName == 'INPUT'){
      			
      			sendMethods();
      			
      		}
      		
      	});
      	*/
      	
      	
      	
	}
);

function startLoadingData(only_review_block){
	
	if(only_review_block){
		
		checkoutoverlay.createOverlay('review', $('easycheckout-review'));
		
	}else{
		
		checkoutoverlay.createOverlay('review', $('easycheckout-review'));
		checkoutoverlay.createOverlay('methods', $('easycheckout-shipping-payment-step'));
	
	}
	
	
}

function stopLoadingData(){
	
	checkoutoverlay.hideOverlay();
	
}


function shippingAddressChanged(){
	
	if(!$('billing_use_for_shipping_yes').checked){
		sendShippingAddress();
	}
}

function billingAddressChanged(){
		sendBillingAddress();
}

function changeShippingAddressMode(){
	
	$flag = this.checked;
		
	if($flag){
		$('shipping-address-form').style.display = 'none';
		sendBillingAddress();
	}else{
		$('shipping-address-form').style.display = 'block';
		sendShippingAddress();
	};
	
}

function buildQueryString(elements){
	
	var q = '';
	
	for(var i = 0;i < elements.length;i++){
		if((elements[i].type == 'checkbox' || elements[i].type == 'radio') && !elements[i].checked){
			continue;
		}
		q += elements[i].name + '=' + encodeURIComponent(elements[i].value);
		
		if(i+1 < elements.length){
			q += '&';
		}
		
	}
	return q;
}

function update_coupon(remove){
	startLoadingData();
	if (remove){
		
		
        $('remove-coupone').value = "1";
		var q = buildQueryString($$('#coupon_code, #remove-coupone'));
	
		return updateFormData(checkoutCouponUrl, q);
	}
	else{
		
        $('remove-coupone').value = "0";
		var q = buildQueryString($$('#coupon_code, #remove-coupone'));
	
		return updateFormData(checkoutCouponUrl, q);
	}
}

function elogin(e, p, url){
	
	$('elogin-loading').style.display = 'block';
	$('elogin-buttons').style.display = 'none';
	
	var request = new Ajax.Request(url,
	  {
	    method:'post',
	    parameters:'username='+e+'&password='+p,
	    onSuccess: function(transport){ var response = eval('('+(transport.responseText || false)+')');
	      
	      if(response.error){
	      	  $('elogin-message').innerHTML = response.message;
	      	  $('elogin-loading').style.display = 'none';
			  $('elogin-buttons').style.display = 'block';
	      }else{
	      	  
	      	  location.reload();
	      	  
	      }
	      
	    },
	    onFailure: function(){ alert('Something went wrong...');stopLoadingData(); }
	  });
}

function updateFormData(url, q){
	
	var request = new Ajax.Request(url,
	  {
	    method:'post',
	    parameters:q,
	    onSuccess: function(transport){ var response = eval('('+(transport.responseText || false)+')');
	      
	      if(response.error){
			  if(response.review){
	      	  	$('easycheckout-review-info').update(response.review);
	      	  }
			  stopLoadingData();
			  alert(response.message);
	      	  //coming soon...
	      }else{
	      	  if(response.shipping_rates){
	      	  	$('easycheckout-shippingmethod-available').update(response.shipping_rates);
	      	  }
	      	  if(response.payments){
	      	  	$('easycheckout-paymentmethod-available').update(response.payments);
	      	  }
	      	  if(response.review){
	      	  	$('easycheckout-review-info').update(response.review);
	      	  }
			  if(response.coupon){
	      	  	$('easycheckout-coupon').update(response.coupon);
	      	  }
			stopLoadingData();	
	      }
	      
	    },
	    onFailure: function(){ alert('Something went wrong...');stopLoadingData(); }
	  });
	
}


function sendBillingAddress(){
	
	startLoadingData();
	
	var q = buildQueryString($$('#easycheckout-addressbilling input, #easycheckout-addressbilling select, #easycheckout-addressbilling textarea, #billing_use_for_shipping_yes'));
	
	if($('billing_use_for_shipping_yes') && $('billing_use_for_shipping_yes').checked){
		return updateFormData(checkoutDefaultUrl, q);
	}
	
	return updateFormData(checkoutBillingUrl, q);
	
	
}

function sendShippingAddress(){
	
	startLoadingData();
	
	var q = buildQueryString($$('#shipping-address-form input, #shipping-address-form select, #shipping-address-form textarea'));
	
	return updateFormData(checkoutShippingUrl, q);
	
}

function sendMethods(){
	
	startLoadingData(true);
	
	var q = '';
	
	q += buildQueryString($$('#easycheckout-shippingmethod input, #easycheckout-shippingmethod select, #easycheckout-shippingmethod textarea'));
	q += '&';
	q += buildQueryString($$('#easycheckout-paymentmethod input, #easycheckout-paymentmethod select, #easycheckout-paymentmethod textarea'));
	
	return updateFormData(checkoutTotalsUrl, q);
	
}

var checkoutoverlay = {
	overlay:{},
	hideOverlay:function(){
		for(i in this.overlay){
			this.overlay[i].style.display = 'none';
		}
	},
	createOverlay:function(id, container){
		
		if(this.overlay['sln-overlay-'+id]){
		
			var overlay = this.overlay['sln-overlay-'+id];
		
		}else{
		
			var overlay = document.createElement('div');
			overlay.id = 'sln-overlay-'+id;
			
			document.body.appendChild(overlay);
			
			this.overlay['sln-overlay-'+id] = overlay;
		}
		
		if(typeof SLN_IS_IE == 'boolean'){
			container.style.position = 'relative';
		}else{
			SLN_IS_IE = false;
		}
		
		overlay.style.top			= container.offsetTop + 'px';
		overlay.style.left			= container.offsetLeft - (SLN_IS_IE ? 1 : 0) + 'px';
		overlay.style.width			= container.offsetWidth + (SLN_IS_IE ? 1 : 0) + 'px';	
		overlay.style.height		= container.offsetHeight + 'px';
		overlay.style.display 		= 'block';
		overlay.style.background	= '#ffffff';
		overlay.style.position		= 'absolute';
		overlay.style.opacity		= '0.7';
		overlay.style.filter		= 'alpha(opacity: 70)';
		
	}
}




var paymentForm = Class.create();
paymentForm.prototype = {
	beforeInitFunc:$H({}),
    afterInitFunc:$H({}),
    beforeValidateFunc:$H({}),
    afterValidateFunc:$H({}),
    initialize: function(formId){
        this.form = $(this.formId = formId);
    },
    init : function () {
        //var elements = Form.getElements(this.form);
        
        var elements = $$('#easycheckout-paymentmethod-available input, #easycheckout-paymentmethod-available select, #easycheckout-paymentmethod-available textarea');
        
        /*if ($(this.form)) {
            $(this.form).observe('submit', function(event){this.save();Event.stop(event);}.bind(this));
        }*/
        var method = null;
        for (var i=0; i<elements.length; i++) {
            if (elements[i].name=='payment[method]') {
                if (elements[i].checked) {
                    method = elements[i].value;
                }
            }
            elements[i].setAttribute('autocomplete','off');
        }
        if (method) this.switchMethod(method);
    },
    
    switchMethod: function(method){
        if (this.currentMethod && $('payment_form_'+this.currentMethod)) {
        	
            var form = $('payment_form_'+this.currentMethod);
            form.style.display = 'none';
            var elements = form.getElementsByTagName('input');
            for (var i=0; i<elements.length; i++) elements[i].disabled = true;
            var elements = form.getElementsByTagName('select');
            for (var i=0; i<elements.length; i++) elements[i].disabled = true;
            

        }
        if ($('payment_form_'+method)){
            var form = $('payment_form_'+method);
            form.style.display = '';
            var elements = form.getElementsByTagName('input');
            for (var i=0; i<elements.length; i++) elements[i].disabled = false;
            var elements = form.getElementsByTagName('select');
            for (var i=0; i<elements.length; i++) elements[i].disabled = false;
            this.currentMethod = method;
        }
    }
}
var billing = Class.create();
billing = billing.prototype = {
	newAddress: function(isNew){
        if (isNew) {
        	
            $('billing-new-address-form').select('input[type=text], select, textarea').each(function(e){if(!e.getAttribute('disabled') && !e.getAttribute('readonly')){e.value = ''};});
            
            Element.show('billing-new-address-form');
        } else {
            Element.hide('billing-new-address-form');
        }
        
        billingAddressChanged();
    }
}
var shipping = Class.create();
shipping = billing.prototype = {
	newAddress: function(isNew){
        if (isNew) {
        	
            $('shipping-new-address-form').select('input[type=text], select, textarea').each(function(e){if(!e.getAttribute('disabled') && !e.getAttribute('readonly')){e.value = ''};});
            
            Element.show('shipping-new-address-form');
        } else {
            Element.hide('shipping-new-address-form');
        }
        
        shippingAddressChanged();
        
        //shipping.setSameAsBilling(false);
    }
}

/*Prototype fix for IE9*/
if (Prototype.Browser.IE) {
  Object.extend(Selector.handlers, {
        

    // IE improperly serializes _countedByPrototype in (inner|outer)HTML.
    unmark: (function(){

      var PROPERTIES_ATTRIBUTES_MAP = (function(){
        var el = document.createElement('div'),
            isBuggy = false,
            propName = '_countedByPrototype',
            value = 'x'
        el[propName] = value;
        isBuggy = (el.getAttribute(propName) === value);
        el = null;
        return isBuggy;
      })();

      return PROPERTIES_ATTRIBUTES_MAP ?
        function(nodes) {
          for (var i = 0, node; node = nodes[i]; i++)
            node.removeAttribute('_countedByPrototype');
          return nodes;
        } :
        function(nodes) {
          for (var i = 0, node; node = nodes[i]; i++)
            node._countedByPrototype = void 0;
          return nodes;
        }
    })()
  });
}