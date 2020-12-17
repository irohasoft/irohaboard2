<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * ContentsQuestions Controller
 *
 * @property \App\Model\Table\ContentsQuestionsTable $ContentsQuestions
 * @method \App\Model\Entity\ContentsQuestion[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ContentsQuestionsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Contents'],
        ];
        $contentsQuestions = $this->paginate($this->ContentsQuestions);

        $this->set(compact('contentsQuestions'));
    }
}
