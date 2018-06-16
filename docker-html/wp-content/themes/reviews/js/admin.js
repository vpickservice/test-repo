jQuery(document).ready(function($){

	$(document).on( 'click', '.add-criteria', function(){
		$(this).before( '<div class="criteria-wrap"><input type="text" name="term_meta[review_criteria][]" value=""><a href="javascript:;" class="remove-criteria button">X</a></div>' );
	});	

	$(document).on( 'click', '.remove-criteria', function(){
		$(this).parent().remove();
	});

	$(document).on( 'change', '#review-categorychecklist input', function(){
		var $this = $(this);
		$.ajax({
			url: ajaxurl,
			method: 'POST',
			dataType: 'JSON',
			data: {
				term_id: $this.val(),
				action: 'category_criterias'
			},
			success: function( response ){
				if( response ){
					if( $this.prop('checked') ){
						for( var k=0; k<response.length; k++ ){
							criteria = response[k];
							if( criteria ){
								var $parent = $('label[for="reviews_score-cmb-field-0"]').parents('.cmb-row');
								$parent.find('.repeat-field').trigger('click');
								$parent.find('.field.repeatable > .field-item:not(.hidden):last input[name^="reviews_score"]').val( criteria );
							}
						}
					}
					else{
						for( var k=0; k<response.length; k++ ){
							criteria = response[k];
							if( criteria ){
								var $parent = $('label[for="reviews_score-cmb-field-0"]').parents('.cmb-row');
								$parent.find('.field.repeatable > .field-item:not(.hidden)').each(function(){
									var $this = $(this);
									if( $this.find('input[name^="reviews_score"]').val() == criteria ){
										$this.remove();
									}
								});
							}
						}
					}
				}
			}
		})
	});
});