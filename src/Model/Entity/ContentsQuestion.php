<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ContentsQuestion Entity
 *
 * @property int $id
 * @property int $content_id
 * @property string $question_type
 * @property string $title
 * @property string $body
 * @property string|null $image
 * @property string|null $options
 * @property string $correct
 * @property int $score
 * @property string|null $explain
 * @property string|null $comment
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int $sort_no
 *
 * @property \App\Model\Entity\Content $content
 */
class ContentsQuestion extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'content_id' => true,
        'question_type' => true,
        'title' => true,
        'body' => true,
        'image' => true,
        'options' => true,
        'correct' => true,
        'score' => true,
        'explain' => true,
        'comment' => true,
        'created' => true,
        'modified' => true,
        'sort_no' => true,
        'content' => true,
    ];
}
