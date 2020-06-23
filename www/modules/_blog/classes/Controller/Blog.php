<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class Controller_Blog
 */
class Controller_Blog extends Controller_Frontend
{
	public $module = 'blog';

	/**
	 * Rubric view
	 */
	public function action_rubric()
	{
		$rubric = ORM::factory('Blog_Rubric')
			->where('slug', '=', $this->request->param('slug'))
			->where('enabled', '=', 1)
			->find();

		if ( ! $rubric->loaded())
			throw new HTTP_Exception_404();

		$this->breadcrumbs = View::factory('blog/partials/v_breadcrumbs_rubric')
			->set('breadcrumbs', $rubric->parents(FALSE, TRUE));

//		$this->breadcrumbs = View::factory('app/partials/v_breadcrumbs')
//			->set('page_title', __('blog.rubrics_archive').': '.$rubric->title);

		// Sub-rubrics
		if ($rubric->show_subs)
			$subrubrics = $rubric->get_subs();

		$posts = ORM::factory('Blog_Post');

		// Query for get posts of rubrics
		if ($rubric->show_posts)
			$posts->rubric_posts($rubric->id);
		else
			$posts->rubric_posts($rubric->descendants_ids(TRUE));

		// Data for template
		$this->title       = $rubric->meta_t;
		$this->description = $rubric->meta_d;
		$this->keywords    = $rubric->meta_k;

		$this->content = View::factory('blog/v_rubric',
			array(
				'rubric'       => $rubric,
				'pagination'   => $pg = Pagination::get($posts->count_all(FALSE), Config::get('blog.per_page_frontend')),
				'posts'        => $posts->find_per_page($pg->offset, $pg->items_per_page, FALSE, $rubric->posts_orderby, $rubric->posts_orderto),
				'config_image' => Config::get('blog.post.image'),
			))
			->bind('subrubrics', $subrubrics);
	}

	/**
	 * Post view
	 * Путь вида http://post/rubric_slug/post_slug
	 */
	public function action_post()
	{
		$post = ORM::factory('Blog_Post')
			->where('slug', '=', $this->request->param('slug'))
			->where('enabled', '=', 1)
			->find();

		if ( ! $post->loaded())
			throw new HTTP_Exception_404();

		$rubric_anchors = $post->rubric_anchors();

		$this->breadcrumbs = View::factory('blog/partials/v_breadcrumbs_post')
			->set('rubrics', $rubric_anchors)
			->set('post_title', $post->title);

//		$this->breadcrumbs = View::factory('app/partials/v_breadcrumbs')
//			->set('page_title', __('blog.post').': '.$post->title);

		// Data for template
		$this->title       = $post->meta_t;
		$this->description = $post->meta_d;
		$this->keywords    = $post->meta_k;

		$this->content = View::factory('blog/v_post', array(
			'post'         => $post,
			'config_image' => Config::get('blog.post.image'),
		))
		->bind('rubric_anchors', $rubric_anchors);
	}
}