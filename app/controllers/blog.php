<?php

class Blog extends Controller {
    public function index() {
        $data = [
            'articles' => $this->model('Article_model')->getByType('blog')
        ];
        $this->views('layouts/header');
        $this->views('blog/index', $data);
        $this->views('layouts/footer');
    }

    public function detail($id = null) {
        $data = [
            'article' => $this->model('Article_model')->getById($id)
        ];
        // Missing articles and drafts are not public (admins may preview drafts)
        if (empty($data['article']) || (($data['article']->status ?? 'published') !== 'published' && empty($_SESSION['admin_logged_in']))) {
            $this->notFound();
        }
        if (!empty($data['article'])) {
            $data['title'] = $data['article']->title_id;
            $data['meta_description'] = mb_substr(trim(preg_replace('/\s+/', ' ', strip_tags($data['article']->content_id ?? ''))), 0, 160);
        }
        $this->views('layouts/header', $data);
        $this->views('blog/detail', $data);
        $this->views('layouts/footer');
    }
}
