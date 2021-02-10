<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Content Entity
 *
 * @property int $id
 * @property int $course_id
 * @property int $user_id
 * @property string $title
 * @property string|null $url
 * @property string $kind
 * @property string|null $body
 * @property int|null $timelimit
 * @property int|null $pass_rate
 * @property \Cake\I18n\FrozenTime|null $opened
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 * @property int $sort_no
 * @property string|null $comment
 *
 * @property \App\Model\Entity\Course $course
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\ContentsQuestion[] $contents_questions
 * @property \App\Model\Entity\Record[] $records
 */
class Content extends Entity
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
        'course_id' => true,
        'user_id' => true,
        'title' => true,
        'url' => true,
        'kind' => true,
        'body' => true,
        'timelimit' => true,
        'status' => true,
        'wrong_mode' => true,
        'pass_rate' => true,
        'opened' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'sort_no' => true,
        'comment' => true,
        'course' => true,
        'user' => true,
        'contents_questions' => true,
        'records' => true,
    ];
}
