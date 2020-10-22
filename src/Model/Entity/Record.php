<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Record Entity
 *
 * @property int $id
 * @property int $course_id
 * @property int $user_id
 * @property int $content_id
 * @property int|null $full_score
 * @property int|null $pass_score
 * @property int|null $score
 * @property int|null $is_passed
 * @property int|null $is_complete
 * @property int|null $progress
 * @property int|null $understanding
 * @property int|null $study_sec
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Course $course
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Content $content
 * @property \App\Model\Entity\RecordsQuestion[] $records_questions
 */
class Record extends Entity
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
        'content_id' => true,
        'full_score' => true,
        'pass_score' => true,
        'score' => true,
        'is_passed' => true,
        'is_complete' => true,
        'progress' => true,
        'understanding' => true,
        'study_sec' => true,
        'created' => true,
        'course' => true,
        'user' => true,
        'content' => true,
        'records_questions' => true,
    ];
}
