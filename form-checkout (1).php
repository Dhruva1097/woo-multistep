<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout custom-multistep" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<?php if ( $checkout->get_checkout_fields() ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

		<!-- Steps Navigation -->
		<ul class="checkout-steps-nav">
			<li id="nav-step-1" class="active">
				<span class="step-count" data-count="1">1</span>
				<span>Checkout</span>
			</li>
			<li id="nav-step-2">
				<span class="step-count" data-count="2">2</span>
				<span>Details</span>
			</li>
			<li id="nav-step-3">
				<span class="step-count" data-count="3">3</span>
				<span>Payment</span>
			</li>
		</ul>


		<div id="checkout-steps">
			<!-- Step 1: Billing Details -->
			<div id="step-1" class="checkout-step">
				<div class="woocommerce-notices">
					<div class="woocommerce-error"></div>
				</div>

				<?php do_action( 'woocommerce_checkout_billing' ); ?>
				<button type="button" id="next-step-1" class="button">Continue to delivery details</button>
			</div>

			<!-- Step 2: Shipping Details -->
			<div id="step-2" class="checkout-step" style="display: none;">
				<div class="woocommerce-notices">
				<div class="woocommerce-error"></div>
				</div>

				<?php 
					$checkout = WC()->checkout(); 
					function sanitize_field_value($value) {
						return $value === 'undefined' ? '' : $value;
					}
					// Display billing_country dropdown (required)
					woocommerce_form_field('billing_country', array(
						'type'        => 'select',
						'label'       => __('Country', 'woocommerce'),
						'required'    => true,
						'options'     => WC()->countries->get_allowed_countries(),
						'default'     => sanitize_field_value($checkout->get_value('billing_country')),
					), sanitize_field_value($checkout->get_value('billing_country')));
					// Display billing_address_1 (required)
					woocommerce_form_field('billing_address_1', array(
						'type'        => 'text',
						'label'       => __('Address Line 1', 'woocommerce'),
						'required'    => true,
						'default'     => sanitize_field_value($checkout->get_value('billing_address_1')),
					), sanitize_field_value($checkout->get_value('billing_address_1')));
					// Display billing_address_2 (optional)
					woocommerce_form_field('billing_address_2', array(
						'type'        => 'text',
						'label'       => __('Address Line 2', 'woocommerce'),
						'required'    => false,  // This field is optional
						'default'     => sanitize_field_value($checkout->get_value('billing_address_2')),
					), sanitize_field_value($checkout->get_value('billing_address_2')));
					// Display billing_city (required)
					woocommerce_form_field('billing_city', array(
						'type'        => 'text',
						'label'       => __('City', 'woocommerce'),
						'required'    => true,
						'default'     => sanitize_field_value($checkout->get_value('billing_city')),
					), sanitize_field_value($checkout->get_value('billing_city')));
					// Display billing_state dropdown (required)
					woocommerce_form_field('billing_state', array(
						'type'        => 'select',
						'label'       => __('State', 'woocommerce'),
						'required'    => true,
						'options'     => WC()->countries->get_states($checkout->get_value('billing_country')),
						'default'     => sanitize_field_value($checkout->get_value('billing_state')),
					), sanitize_field_value($checkout->get_value('billing_state')));
					// Display billing_postcode (required)
					woocommerce_form_field('billing_postcode', array(
						'type'        => 'text',
						'label'       => __('Postcode', 'woocommerce'),
						'required'    => true,
						'default'     => sanitize_field_value($checkout->get_value('billing_postcode')),
					), sanitize_field_value($checkout->get_value('billing_postcode')));
				?>
				<?php do_action( 'woocommerce_checkout_shipping' ); ?>
				<!-- <button type="button" id="prev-step-2" class="button">Previous</button> -->
				<button type="button" id="next-step-2" class="button">CONTINUE TO PAYMENT METHOD</button>
			</div>

			<!-- Step 3: Order Review -->
			<div id="step-3" class="checkout-step" style="display: none;">
				<div class="woocommerce-notices">
					<div class="woocommerce-error"></div>
				</div>

				<?php do_action( 'woocommerce_checkout_payment_hook' );?>
			</div>
		</div>
		<div class="checkout-payment-sidebar">
			<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
			<div id="order_review" class="woocommerce-checkout-review-order">
				<h3 id="order_review_heading"><?php esc_html_e( 'Order Summary', 'woocommerce' ); ?></h3>
				<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
				<?php do_action( 'woocommerce_checkout_order_review' ); ?>
				<?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>
			</div>
			<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
			<button type="button" id="prev-step-3" class="button">Previous</button>
			<button type="submit" id="submit-checkout" class="button">Place Order</button>
		</div>
		
		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

	<?php endif; ?>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nextButtons = document.querySelectorAll('[id^="next-step-"]');
    const prevButtons = document.querySelectorAll('[id^="prev-step-"]');
    const steps = document.querySelectorAll('.checkout-step');
    const navItems = document.querySelectorAll('.checkout-steps-nav li');

    function showStep(stepId) {
        steps.forEach(step => {
            step.style.display = step.id === stepId ? 'block' : 'none';
        });
        navItems.forEach(nav => {
            nav.classList.toggle('active', nav.id === `nav-${stepId}`);
        });

		// Dispatch custom event
		const event = new CustomEvent('woo_multistep_change', { detail: { stepId: stepId } });
        document.dispatchEvent(event);
    }

	function validateStep(stepId) {
        let isValid = true;
		console.log(stepId);
		if(!window.checked && stepId == "step-1"){
			var fields = document.querySelectorAll(`#${stepId} input:not(input[id*="shipping"])`);
		}else{
			var fields = document.querySelectorAll(`#${stepId} input`);
		}
		const notices = document.querySelector(`#${stepId} .woocommerce-notices .woocommerce-error`);
		if (notices) {
            notices.innerHTML = '';
        }

        fields.forEach(field => {
			var required = field.parentNode.parentNode.querySelector('abbr');
			var required = required?required.getAttribute('title'):false;
			var fieldName = field.parentNode.parentNode.querySelector('label').textContent.replace(/\*/g, '').trim();//innerHTML;

			console.log({fields,required})
            if (required && !field.value.trim()) {  //field.required && 
                isValid = false;
                field.parentNode.parentNode.classList.add('woocommerce-invalid');


				const notice = document.createElement('div');
                notice.innerHTML = `${fieldName} is a required field.`;
                if (notices) {
                    notices.appendChild(notice);
                }

				return isValid;
            } else {
                field.parentNode.parentNode.classList.remove('woocommerce-invalid');
            }
        });
		
		document.querySelector('.checkout-steps-nav').scrollIntoView({ behavior: 'smooth' });
        return isValid;
    } 

    nextButtons.forEach(button => {
        button.addEventListener('click', function() {
            const currentStepId = this.id.replace('next-step-', 'step-');
			if (validateStep(currentStepId)) {
				const nextStepId = `step-${parseInt(currentStepId.split('-')[1]) + 1}`;
				showStep(nextStepId);
			}
        });
    });

    prevButtons.forEach(button => {
        button.addEventListener('click', function() {
            const currentStepId = this.id.replace('prev-step-', 'step-');
            const prevStepId = `step-${parseInt(currentStepId.split('-')[1]) - 1}`;
            showStep(prevStepId);
			document.querySelector('.checkout-steps-nav').scrollIntoView({ behavior: 'smooth' });
        });
    });

    navItems.forEach(nav => {
        nav.addEventListener('click', function() {
            const stepId = this.id.replace('nav-', '');
			if (validateStep(`step-${parseInt(stepId.split('-')[1])}`)) {
            	showStep(stepId);
			}
        });
    });

    // Initialize first step
    showStep('step-1');
});
document.addEventListener('DOMContentLoaded', function() {
	var icon = `<i class="fa fa-check" aria-hidden="true"></i>`;
	document.addEventListener('woo_multistep_change', function(event) {
		var prevStep = event.detail.stepId;
		var stepNumber = parseInt(prevStep.split('-')[1], 10)
		for(i=0;i<=3;i++){
			if(i < stepNumber){
				let stepElement = document.getElementById(`nav-step-${i}`);
				if(stepElement){
					let stepCountElement = stepElement.querySelector('.step-count');
					stepCountElement.innerHTML = icon;
				}
			}else{
				let stepElement = document.getElementById(`nav-step-${i}`);
				if(stepElement){
					let stepCountElement = stepElement.querySelector('.step-count');
					stepCountElement.innerHTML = stepCountElement.dataset.count;
				}
			}
		}
	});

});
</script>

<script>
jQuery(document).ready(function($) {
    // Function to copy billing data to shipping fields
    function copyBillingToShipping() {
        if ($('#ship-to-different-address-checkbox').prop('checked') === false) {
            $('#shipping_first_name').val($('#billing_first_name').val());
            $('#shipping_last_name').val($('#billing_last_name').val());
            $('#shipping_address_1').val($('#billing_address_1').val());
            $('#shipping_address_2').val($('#billing_address_2').val());
            $('#shipping_city').val($('#billing_city').val());
            $('#shipping_postcode').val($('#billing_postcode').val());
            $('#shipping_country').val($('#billing_country').val()).trigger('change');
            $('#shipping_state').val($('#billing_state').val()).trigger('change');
        }
    }
    $(document).ready(function() {
        copyBillingToShipping();
    });
    $('#ship-to-different-address-checkbox, #step-2 > p input').change(function() {
		window.checked = document.querySelector('#ship-to-different-address-checkbox').checked;
        if ($(this).prop('checked') === false) {
            copyBillingToShipping();
        } else {
            // Clear shipping fields if "Ship to a different address" is checked
            $('#shipping_first_name').val('');
            $('#shipping_last_name').val('');
            $('#shipping_address_1').val('');
            $('#shipping_address_2').val('');
            $('#shipping_city').val('');
            $('#shipping_postcode').val('');
            $('#shipping_country').val('').trigger('change');
            $('#shipping_state').val('');
        }
    });
	var back = `<a href="<?php echo wc_get_cart_url();?>"><i class="fa fa-angle-left" aria-hidden="true"></i> Cart</a>`;
	var text = `<span><i class="fa fa-lock" aria-hidden="true"></i><span>Secure Checkout</span></span>`;
	$('.header-col-3').html(text);
	$('.header-col-2').html(back);
});

</script>