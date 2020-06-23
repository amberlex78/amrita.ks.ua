//
//  jQuery Slug Plugin by Perry Trinier (perrytrinier@gmail.com)
//  MIT License: http://www.opensource.org/licenses/mit-license.php

jQuery.fn.slug = function(options) {
	var settings = {
		slug: 'slug', // Class used for slug destination input and span. The span is created on $(document).ready()
		hide: true    // Boolean - By default the slug input field is hidden, set to false to show the input field and hide the span.
	};

	if(options) {
		jQuery.extend(settings, options);
	}

	var $this = $(this);

	$(document).ready( function() {
		if (settings.hide) {
			$('input.' + settings.slug).after("<span class="+settings.slug+"></span>");
			$('input.' + settings.slug).hide();
		}
	});

	var transliterate = function(str) {
		var rExps=[
			{re: /ä|æ|ǽ/g, ch: 'ae'},
			{re: /ö|œ/g, ch: 'oe'},
			{re: /ü/g, ch: 'ue'},
			{re: /Ä/g, ch: 'Ae'},
			{re: /Ü/g, ch: 'Ue'},
			{re: /Ö/g, ch: 'Oe'},
			{re: /À|Á|Â|Ã|Ä|Å|Ǻ|Ā|Ă|Ą|Ǎ|А/g, ch: 'A'},
			{re: /à|á|â|ã|å|ǻ|ā|ă|ą|ǎ|ª|а/g, ch: 'a'},
			{re: /Б/g, ch: 'B'},
			{re: /б/g, ch: 'b'},
			{re: /Ç|Ć|Ĉ|Ċ|Č/g, ch: 'C'},
			{re: /ç|ć|ĉ|ċ|č/g, ch: 'c'},
			{re: /Ð|Ď|Đ|Д/g, ch: 'D'},
			{re: /ð|ď|đ|д/g, ch: 'd'},
			{re: /È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě|Е|Э|Є/g, ch: 'E'},
			{re: /è|é|ê|ë|ē|ĕ|ė|ę|ě|е|э|є/g, ch: 'e'},
			{re: /Ĝ|Ğ|Ġ|Ģ|Г|Ґ/g, ch: 'G'},
			{re: /ĝ|ğ|ġ|ģ|г|ґ/g, ch: 'g'},
			{re: /Ĥ|Ħ/g, ch: 'H'},
			{re: /ĥ|ħ/g, ch: 'h'},
			{re: /Ì|Í|Î|Ï|Ĩ|Ī|Ĭ|Ǐ|Į|İ|І|И/g, ch: 'I'},
			{re: /ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı|і|и/g, ch: 'i'},
			{re: /Ĵ|Й/g, ch: 'J'},
			{re: /ĵ|й/g, ch: 'j'},
			{re: /Ķ|К/g, ch: 'K'},
			{re: /ķ|к/g, ch: 'k'},
			{re: /Ĺ|Ļ|Ľ|Ŀ|Ł|Л/g, ch: 'L'},
			{re: /ĺ|ļ|ľ|ŀ|ł|л/g, ch: 'l'},
			{re: /М/g, ch: 'M'},
			{re: /м/g, ch: 'm'},
			{re: /Ñ|Ń|Ņ|Ň|Н/g, ch: 'N'},
			{re: /ñ|ń|ņ|ň|ŉ|н/g, ch: 'n'},
			{re: /Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ|О/g, ch: 'O'},
			{re: /ò|ó|ô|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º|о/g, ch: 'o'},
			{re: /П/g, ch: 'P'},
			{re: /п/g, ch: 'p'},
			{re: /Ŕ|Ŗ|Ř|Р/g, ch: 'R'},
			{re: /ŕ|ŗ|ř|р/g, ch: 'r'},
			{re: /Ś|Ŝ|Ş|Š|С/g, ch: 'S'},
			{re: /ś|ŝ|ş|š|ſ|с/g, ch: 's'},
			{re: /Ţ|Ť|Ŧ|Т/g, ch: 'T'},
			{re: /ţ|ť|ŧ|т/g, ch: 't'},
			{re: /Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ|У/g, ch: 'U'},
			{re: /ù|ú|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ|у/g, ch: 'u'},
			{re: /В/g, ch: 'V'},
			{re: /в/g, ch: 'v'},
			{re: /Ý|Ÿ|Ŷ|Ы/g, ch: 'Y'},
			{re: /ý|ÿ|ŷ|ы/g, ch: 'y'},
			{re: /Ŵ/g, ch: 'W'},
			{re: /ŵ/g, ch: 'w'},
			{re: /Ź|Ż|Ž|З/g, ch: 'Z'},
			{re: /ź|ż|ž|з/g, ch: 'z'},
			{re: /Æ|Ǽ/g, ch: 'AE'},
			{re: /ß/g, ch: 'ss'},
			{re: /Ĳ/g, ch: 'IJ'},
			{re: /ĳ/g, ch: 'ij'},
			{re: /Œ/g, ch: 'OE'},
			{re: /ƒ|ф/g, ch: 'f'},
			{re: /Ф/g, ch: 'F'},

			// multiple
			{re: /ё/g, ch: 'yo'},
			{re: /Ё/g, ch: 'Yo'},
			{re: /ж/g, ch: 'zh'},
			{re: /Ж/g, ch: 'Zh'},
			{re: /х/g, ch: 'kh'},
			{re: /Х/g, ch: 'Kh'},
			{re: /ц/g, ch: 'ts'},
			{re: /Ц/g, ch: 'Ts'},
			{re: /ч/g, ch: 'ch'},
			{re: /Ч/g, ch: 'Ch'},
			{re: /ш/g, ch: 'sh'},
			{re: /Ш/g, ch: 'Sh'},
			{re: /щ/g, ch: 'shch'},
			{re: /Щ/g, ch: 'Shch'},
			{re: /ю/g, ch: 'yu'},
			{re: /Ю/g, ch: 'Yu'},
			{re: /я/g, ch: 'ya'},
			{re: /Я/g, ch: 'Ya'},
			{re: /ї/g, ch: 'ji'},
			{re: /Ї/g, ch: 'Ji'},

			// special
			{re: /ъ|Ъ|ь|Ь/g, ch: ''},
			{re: /&/g, ch: 'and'},
			{re: /\'/g, ch: ''}
		];
		for (var i=0, len=rExps.length; i<len; i++) {
			str = str.replace(rExps[i].re, rExps[i].ch);
		}
		return str;
	}

	makeSlug = function() {
		var slug = transliterate(jQuery.trim($this.val()))
				.replace(/\s+/g,'-').replace(/[^a-zA-Z0-9\-]/g,'').toLowerCase()
				.replace(/\-{2,}/g,'-')
				.replace(/\-$/, '')
				.replace(/^\-/, '')
			;
		$('input.' + settings.slug).val(slug);
		$('span.' + settings.slug).text(slug);
	}

	$(this).keyup(makeSlug);

	return $this;
};