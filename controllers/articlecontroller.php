<?php

class ArticleController extends Controller
{
	public function __construct($model, $action)
	{
		parent::__construct($model, $action);
		$this->_setModel($model);
	}
	
	public function index()
	{
		try {
			
			$articles = $this->_model->getArticles();
			
			$this->_view->set('articles', $articles);
			$this->_view->set('title', 'List Of Articles');
			
			return $this->_view->output();
			
		} catch (Exception $e) {
			echo '<h1>Application error:</h1>' . $e->getMessage();
		}
	}
	
	public function details($articleId)
	{
		try {
			
			$article = $this->_model->getArticleById((int)$articleId);
			
			if ($article)
			{
				$this->_view->set('id', $article['id']);
				$this->_view->set('title', $article['title']);
				$this->_view->set('intro', $article['intro']);
				$this->_view->set('content', $article['content']);
				$this->_view->set('datePublished', $article['date']);
				$this->_view->set('author', $article['full_name']);
				$this->_view->set('editable', $article['user_id'] == Auth::logged() );
			}
			else 
			{
				$this->_view->set('title', 'Invalid article ID');
				$this->_view->set('noArticle', true);
			}
			
			return $this->_view->output();
			 
		} catch (Exception $e) {
			echo '<h1>Application error:</h1>' . $e->getMessage();
		}
	}
	
	public function edit($articleId)
	{
		try {
				
			$article = $this->_model->getArticleById((int)$articleId);
				
			if ($article)
			{
				$this->_view->set('formData', $article);
			}
			else
			{
				$this->_view->set('title', 'New Article');
			}
				
			return $this->_view->output();
		
		} catch (Exception $e) {
			echo '<h1>Application error:</h1>' . $e->getMessage();
		}		
	}	

	public function save()
	{
		if (!isset($_POST['articleFormSubmit']))
		{
			header('Location: /article/index');
		}
	
		$errors = array();
		$check = true;
	
		$articleId = isset($_POST['article_id']) ? trim($_POST['article_id']) : NULL;
		$title = isset($_POST['title']) ? trim($_POST['title']) : NULL;
		$intro = isset($_POST['intro']) ? trim($_POST['intro']) : NULL;
		$content = isset($_POST['content']) ? trim($_POST['content']) : "";
		
		$article = new ArticleModel();
		
		if( $articleId != NULL )
		{
			$article->setId($articleId);
			
			if( $article->getUserId() != Auth::logged() )
			{
				$check = false;
				array_push($errors, "Only author is allowed to edit his article!");				
			}
		}
		elseif ( !Auth::logged() )
		{
			$check = false;
			array_push($errors, "Only registered users are allowed to add new articles!");
		}
		
		if (empty($title))
		{
			$check = false;
			array_push($errors, "Title is required!");
		}
		
		if (!$check)
		{
			$this->_setView('edit');
			$this->_view->set('title', 'Invalid form data!');
			$this->_view->set('errors', $errors);
			$this->_view->set('formData', $_POST);
			return $this->_view->output();
		}
		
		try {							
			$article->setTitle($title);
			$article->setIntro($intro);
			$article->setContent($content);
			$article->setUserId(Auth::logged());
			$article->store();				 
			header('Location: /article/details/'.$article->getId());
		} catch (Exception $e) {
			$this->_setView('index');
			$this->_view->set('errors', array('There was an error saving the data!'));
			$this->_view->set('formData', $_POST);
			$this->_view->set('saveError', $e->getMessage());
			$this->index();
		}
	
		return $this->_view->output();
	}	
	
}