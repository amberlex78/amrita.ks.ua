<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Class Editor
 */
class Editor
{
	/**
	 * Imperavi WYSIWYG redactor
	 *
	 * @param $name
	 * @param $body
	 * @param $attributes
	 *
	 * @return string
	 */
	public static function imperavi($name, $body, array $attributes = NULL)
	{
		$attr = '';

		if (isset($attributes['height']))
		{
			$attr .= 'minHeight: '.$attributes['height'].',';
			unset($attributes['height']);
		}

		$language = Config::get('app.language');

		$attr .= ($language == 'en') ? '' : 'lang: "'.$language.'",';

		$attr = rtrim('{'.$attr.'}', ',');

		$script = '
			<script type="text/javascript">
				$(document).ready(
					function() {
						$("#'.$name.'").redactor('.$attr.');
					}
				);
			</script>
		';

		return $script.Form::textarea($name, $body, $attributes);
	}

	/**
	 * CKEditor WYSIWYG redactor
	 *
	 * @param        $name
	 * @param string $value
	 * @param array  $attributes
	 *
	 * @return string
	 */
	public static function ck($name, $value = '', array $attributes = NULL)
	{
		$url_base = URL::base();

		include_once DOCROOT.'media/vendor/ckeditor/ckeditor.php';
		include_once DOCROOT.'media/vendor/ckfinder/ckfinder.php';

		$CKEditor = new CKEditor();
		$CKEditor->basePath = $url_base . 'media/vendor/ckeditor/';

		if (isset($attributes['width']))
		{
			$CKEditor->config['width'] = $attributes['width'];
			unset($attributes['width']);
		}
		else
			$CKEditor->config['width'] = '97%';

		if (isset($attributes['height']))
		{
			$CKEditor->config['height'] = $attributes['height'];
			unset($attributes['height']);
		}
		else
			$CKEditor->config['height'] = 260;

		$CKEditor->config['filebrowserBrowseUrl']      = $url_base . 'media/vendor/ckfinder/ckfinder.html';
		$CKEditor->config['filebrowserImageBrowseUrl'] = $url_base . 'media/vendor/ckfinder/ckfinder.html?type=Images';
		$CKEditor->config['filebrowserFlashBrowseUrl'] = $url_base . 'media/vendor/ckfinder/ckfinder.html?type=Flash';
		$CKEditor->config['filebrowserUploadUrl']      = $url_base . 'media/vendor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
		$CKEditor->config['filebrowserImageUploadUrl'] = $url_base . 'media/vendor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
		$CKEditor->config['filebrowserFlashUploadUrl'] = $url_base . 'media/vendor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';

		$config['uiColor'] = '#efefef';
		$config['toolbar'] = array(
			array('Source','-', 'Maximize', 'ShowBlocks'),
			array('Cut','Copy','Paste','PasteText','PasteFromWord'),
			array('Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'),
			array('Link','Unlink','Anchor'),
			array('Image','Table','HorizontalRule','SpecialChar','PageBreak'),
			'/',
			array('Format','Font', 'Bold','Italic','Underline','Strike',),
			array('JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','NumberedList','BulletedList'),
			array('Outdent','Indent','-','TextColor','BGColor','-','Subscript','Superscript'),
			array('uiColor')
		);

		ob_start();
		$CKEditor->editor($name, $value, $config);
		return ob_get_clean();
	}
}
