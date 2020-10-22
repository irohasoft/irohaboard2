<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UsersCourse Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $course_id
 * @property \Cake\I18n\FrozenDate|null $started
 * @property \Cake\I18n\FrozenDate|null $ended
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property string|null $comment
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Course $course
 */
class UsersCourse extends Entity
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
        'user_id' => true,
        'course_id' => true,
        'started' => true,
        'ended' => true,
        'created' => true,
        'modified' => true,
        'comment' => true,
        'user' => true,
        'course' => true,
    ];
}
