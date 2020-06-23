(function($) {
	$.fn.extend({

		// Input and Textarea Character Limit Counter
		//
		limiter: function(limit, elem) {
			$(this).on("keyup focus", function() {
				setCount(this, elem);
			});
			function setCount(src, elem) {
				var chars = src.value.length;
				if (chars > limit) {
					src.value = src.value.substr(0, limit);
					chars = limit;
				}
				elem.html('You have <strong>' + (limit - chars) + '</strong> characters');
			}
			setCount($(this)[0], elem);
		}

	});
})(jQuery);

