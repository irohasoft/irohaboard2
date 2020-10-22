<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Course Entity
 *
 * @property int $id
 * @property string $title
 * @property \Cake\I18n\FrozenTime|null $opened
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 * @property int $sort_no
 * @property string|null $comment
 * @property int $user_id
 *
 * @property \App\Model\Entity\User[] $users
 * @property \App\Model\Entity\Content[] $contents
 * @property \App\Model\Entity\Record[] $records
 */
class Course extends Entity
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
        'title' => true,
        'opened' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'sort_no' => true,
        'comment' => true,
        'user_id' => true,
        'users' => true,
        'contents' => true,
        'records' => true,
    ];
}
