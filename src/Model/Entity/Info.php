<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Info Entity
 *
 * @property int $id
 * @property string $title
 * @property string|null $body
 * @property \Cake\I18n\FrozenTime|null $opened
 * @property \Cake\I18n\FrozenTime|null $closed
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $user_id
 *
 * @property \App\Model\Entity\User $user
 */
class Info extends Entity
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
        'body' => true,
        'opened' => true,
        'closed' => true,
        'created' => true,
        'modified' => true,
        'user_id' => true,
        'user' => true,
        'groups' => true,
    ];
}
