<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string $role
 * @property string $email
 * @property string|null $comment
 * @property \Cake\I18n\FrozenTime|null $last_logined
 * @property \Cake\I18n\FrozenTime|null $started
 * @property \Cake\I18n\FrozenTime|null $ended
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 *
 * @property \App\Model\Entity\Content[] $contents
 * @property \App\Model\Entity\Course[] $courses
 * @property \App\Model\Entity\Info[] $infos
 * @property \App\Model\Entity\Log[] $logs
 * @property \App\Model\Entity\Record[] $records
 * @property \App\Model\Entity\Group[] $groups
 */
class User extends Entity
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
        'username' => true,
        'password' => true,
        'name' => true,
        'role' => true,
        'email' => true,
        'comment' => true,
        'last_logined' => true,
        'started' => true,
        'ended' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'contents' => true,
        'courses' => true,
        'infos' => true,
        'logs' => true,
        'records' => true,
        'groups' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password',
    ];
}
